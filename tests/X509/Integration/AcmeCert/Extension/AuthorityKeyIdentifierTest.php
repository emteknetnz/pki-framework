<?php

declare(strict_types=1);

namespace Sop\Test\X509\Integration\AcmeCert\Extension;

use Sop\CryptoEncoding\PEM;
use Sop\CryptoTypes\Asymmetric\RSA\RSAPrivateKey;
use Sop\X509\Certificate\Extension\AuthorityKeyIdentifierExtension;
use Sop\X509\GeneralName\GeneralName;

/**
 * @group certificate
 * @group extension
 * @group decode
 *
 * @internal
 */
class AuthorityKeyIdentifierTest extends RefExtTestHelper
{
    /**
     * @param Extensions $extensions
     *
     * @return AuthorityKeyIdentifierExtension
     */
    public function testAuthorityKeyIdentifier()
    {
        $ext = self::$_extensions->authorityKeyIdentifier();
        $this->assertInstanceOf(AuthorityKeyIdentifierExtension::class, $ext);
        return $ext;
    }

    /**
     * @depends testAuthorityKeyIdentifier
     *
     * @param AuthorityKeyIdentifierExtension $aki
     */
    public function testAuthorityKeyIdentifierKey(
        AuthorityKeyIdentifierExtension $aki
    ) {
        $pem = PEM::fromFile(
            TEST_ASSETS_DIR . '/certs/keys/acme-interm-rsa.pem'
        );
        $keyid = RSAPrivateKey::fromPEM($pem)->publicKey()
            ->publicKeyInfo()
            ->keyIdentifier();
        $this->assertEquals($keyid, $aki->keyIdentifier());
    }

    /**
     * @depends testAuthorityKeyIdentifier
     *
     * @param AuthorityKeyIdentifierExtension $aki
     */
    public function testAuthorityKeyIdentifierIssuer(
        AuthorityKeyIdentifierExtension $aki
    ) {
        $issuer_dn = $aki->issuer()
            ->firstOf(GeneralName::TAG_DIRECTORY_NAME)
            ->dn()
            ->toString();
        $this->assertEquals('o=ACME Ltd.,c=FI,cn=ACME Root CA', $issuer_dn);
        $this->assertEquals(1, $aki->serial());
    }
}
