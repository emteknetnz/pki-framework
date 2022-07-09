<?php

declare(strict_types=1);

namespace SpomkyLabs\Pki\X509\AttributeCertificate\Validation;

use function count;
use SpomkyLabs\Pki\CryptoBridge\Crypto;
use SpomkyLabs\Pki\X509\AttributeCertificate\AttributeCertificate;
use SpomkyLabs\Pki\X509\AttributeCertificate\Validation\Exception\ACValidationException;
use SpomkyLabs\Pki\X509\Certificate\Certificate;
use SpomkyLabs\Pki\X509\Certificate\Extension\Extension;
use SpomkyLabs\Pki\X509\Certificate\Extension\Target\Targets;
use SpomkyLabs\Pki\X509\Certificate\Extension\TargetInformationExtension;
use SpomkyLabs\Pki\X509\CertificationPath\Exception\PathValidationException;
use SpomkyLabs\Pki\X509\CertificationPath\PathValidation\PathValidationConfig;

/**
 * Implements attribute certificate validation conforming to RFC 5755.
 *
 * @see https://tools.ietf.org/html/rfc5755#section-5
 */
final class ACValidator
{
    /**
     * Crypto engine.
     */
    private readonly Crypto $_crypto;

    /**
     * Constructor.
     *
     * @param AttributeCertificate $_ac Attribute certificate to validate
     * @param ACValidationConfig $_config Validation configuration
     * @param null|Crypto          $crypto Crypto engine, use default if not set
     */
    public function __construct(
        protected AttributeCertificate $_ac,
        protected ACValidationConfig $_config,
        ?Crypto $crypto = null
    ) {
        $this->_crypto = $crypto ?? Crypto::getDefault();
    }

    /**
     * Validate attribute certificate.
     *
     * @return AttributeCertificate Validated AC
     */
    public function validate(): AttributeCertificate
    {
        $this->_validateHolder();
        $issuer = $this->_verifyIssuer();
        $this->_validateIssuerProfile($issuer);
        $this->_validateTime();
        $this->_validateTargeting();
        return $this->_ac;
    }

    /**
     * Validate AC holder's certification.
     *
     * @return Certificate Certificate of the AC's holder
     */
    private function _validateHolder(): Certificate
    {
        $path = $this->_config->holderPath();
        $config = PathValidationConfig::defaultConfig()
            ->withMaxLength(count($path))
            ->withDateTime($this->_config->evaluationTime());
        try {
            $holder = $path->validate($config, $this->_crypto)
                ->certificate();
        } catch (PathValidationException $e) {
            throw new ACValidationException("Failed to validate holder PKC's certification path.", 0, $e);
        }
        if (! $this->_ac->isHeldBy($holder)) {
            throw new ACValidationException("Name mismatch of AC's holder PKC.");
        }
        return $holder;
    }

    /**
     * Verify AC's signature and issuer's certification.
     *
     * @return Certificate Certificate of the AC's issuer
     */
    private function _verifyIssuer(): Certificate
    {
        $path = $this->_config->issuerPath();
        $config = PathValidationConfig::defaultConfig()
            ->withMaxLength(count($path))
            ->withDateTime($this->_config->evaluationTime());
        try {
            $issuer = $path->validate($config, $this->_crypto)
                ->certificate();
        } catch (PathValidationException $e) {
            throw new ACValidationException("Failed to validate issuer PKC's certification path.", 0, $e);
        }
        if (! $this->_ac->isIssuedBy($issuer)) {
            throw new ACValidationException("Name mismatch of AC's issuer PKC.");
        }
        $pubkey_info = $issuer->tbsCertificate()
            ->subjectPublicKeyInfo();
        if (! $this->_ac->verify($pubkey_info, $this->_crypto)) {
            throw new ACValidationException('Failed to verify signature.');
        }
        return $issuer;
    }

    /**
     * Validate AC issuer's profile.
     *
     * @see https://tools.ietf.org/html/rfc5755#section-4.5
     */
    private function _validateIssuerProfile(Certificate $cert): void
    {
        $exts = $cert->tbsCertificate()
            ->extensions();
        if ($exts->hasKeyUsage() && ! $exts->keyUsage()->isDigitalSignature()) {
            throw new ACValidationException(
                "Issuer PKC's Key Usage extension doesn't permit" .
                     ' verification of digital signatures.'
            );
        }
        if ($exts->hasBasicConstraints() && $exts->basicConstraints()->isCA()) {
            throw new ACValidationException('Issuer PKC must not be a CA.');
        }
    }

    /**
     * Validate AC's validity period.
     */
    private function _validateTime(): void
    {
        $t = $this->_config->evaluationTime();
        $validity = $this->_ac->acinfo()
            ->validityPeriod();
        if ($validity->notBeforeTime()->diff($t)->invert) {
            throw new ACValidationException('Validity period has not started.');
        }
        if ($t->diff($validity->notAfterTime())->invert) {
            throw new ACValidationException('Attribute certificate has expired.');
        }
    }

    /**
     * Validate AC's target information.
     */
    private function _validateTargeting(): void
    {
        $exts = $this->_ac->acinfo()
            ->extensions();
        // if target information extension is not present
        if (! $exts->has(Extension::OID_TARGET_INFORMATION)) {
            return;
        }
        $ext = $exts->get(Extension::OID_TARGET_INFORMATION);
        if ($ext instanceof TargetInformationExtension &&
            ! $this->_hasMatchingTarget($ext->targets())) {
            throw new ACValidationException("Attribute certificate doesn't have a matching target.");
        }
    }

    /**
     * Check whether validation configuration has matching targets.
     *
     * @param Targets $targets Set of eligible targets
     */
    private function _hasMatchingTarget(Targets $targets): bool
    {
        foreach ($this->_config->targets() as $target) {
            if ($targets->hasTarget($target)) {
                return true;
            }
        }
        return false;
    }
}
