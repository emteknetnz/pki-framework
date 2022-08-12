<?php

declare(strict_types=1);

namespace SpomkyLabs\Pki\CryptoTypes\Asymmetric\RSA;

use SpomkyLabs\Pki\ASN1\Type\Constructed\Sequence;
use SpomkyLabs\Pki\ASN1\Type\Primitive\Integer;
use SpomkyLabs\Pki\ASN1\Type\UnspecifiedType;
use SpomkyLabs\Pki\CryptoEncoding\PEM;
use SpomkyLabs\Pki\CryptoTypes\AlgorithmIdentifier\Asymmetric\RSAEncryptionAlgorithmIdentifier;
use SpomkyLabs\Pki\CryptoTypes\AlgorithmIdentifier\Feature\AlgorithmIdentifierType;
use SpomkyLabs\Pki\CryptoTypes\Asymmetric\PrivateKey;
use SpomkyLabs\Pki\CryptoTypes\Asymmetric\PublicKey;
use function strval;
use UnexpectedValueException;

/**
 * Implements PKCS #1 RSAPrivateKey ASN.1 type.
 *
 * @see https://tools.ietf.org/html/rfc2437#section-11.1.2
 */
final class RSAPrivateKey extends PrivateKey
{
    /**
     * Modulus as a base 10 integer.
     */
    protected string $_modulus;

    /**
     * Public exponent as a base 10 integer.
     */
    protected string $_publicExponent;

    /**
     * Private exponent as a base 10 integer.
     */
    protected string $_privateExponent;

    /**
     * First prime factor as a base 10 integer.
     */
    protected string $_prime1;

    /**
     * Second prime factor as a base 10 integer.
     */
    protected string $_prime2;

    /**
     * First factor exponent as a base 10 integer.
     */
    protected string $_exponent1;

    /**
     * Second factor exponent as a base 10 integer.
     */
    protected string $_exponent2;

    /**
     * CRT coefficient of the second factor as a base 10 integer.
     */
    protected string $_coefficient;

    /**
     * @param int|string $n Modulus
     * @param int|string $e Public exponent
     * @param int|string $d Private exponent
     * @param int|string $p First prime factor
     * @param int|string $q Second prime factor
     * @param int|string $dp First factor exponent
     * @param int|string $dq Second factor exponent
     * @param int|string $qi CRT coefficient of the second factor
     */
    public function __construct($n, $e, $d, $p, $q, $dp, $dq, $qi)
    {
        $this->_modulus = strval($n);
        $this->_publicExponent = strval($e);
        $this->_privateExponent = strval($d);
        $this->_prime1 = strval($p);
        $this->_prime2 = strval($q);
        $this->_exponent1 = strval($dp);
        $this->_exponent2 = strval($dq);
        $this->_coefficient = strval($qi);
    }

    /**
     * Initialize from ASN.1.
     */
    public static function fromASN1(Sequence $seq): self
    {
        $version = $seq->at(0)
            ->asInteger()
            ->intNumber();
        if ($version !== 0) {
            throw new UnexpectedValueException('Version must be 0.');
        }
        // helper function get integer from given index
        $get_int = fn ($idx) => $seq->at($idx)
            ->asInteger()
            ->number();
        $n = $get_int(1);
        $e = $get_int(2);
        $d = $get_int(3);
        $p = $get_int(4);
        $q = $get_int(5);
        $dp = $get_int(6);
        $dq = $get_int(7);
        $qi = $get_int(8);
        return new self($n, $e, $d, $p, $q, $dp, $dq, $qi);
    }

    /**
     * Initialize from DER data.
     */
    public static function fromDER(string $data): self
    {
        return self::fromASN1(UnspecifiedType::fromDER($data)->asSequence());
    }

    /**
     * @see PrivateKey::fromPEM()
     */
    public static function fromPEM(PEM $pem): self
    {
        $pk = parent::fromPEM($pem);
        if (! ($pk instanceof self)) {
            throw new UnexpectedValueException('Not an RSA private key.');
        }
        return $pk;
    }

    /**
     * Get modulus.
     *
     * @return string Base 10 integer
     */
    public function modulus(): string
    {
        return $this->_modulus;
    }

    /**
     * Get public exponent.
     *
     * @return string Base 10 integer
     */
    public function publicExponent(): string
    {
        return $this->_publicExponent;
    }

    /**
     * Get private exponent.
     *
     * @return string Base 10 integer
     */
    public function privateExponent(): string
    {
        return $this->_privateExponent;
    }

    /**
     * Get first prime factor.
     *
     * @return string Base 10 integer
     */
    public function prime1(): string
    {
        return $this->_prime1;
    }

    /**
     * Get second prime factor.
     *
     * @return string Base 10 integer
     */
    public function prime2(): string
    {
        return $this->_prime2;
    }

    /**
     * Get first factor exponent.
     *
     * @return string Base 10 integer
     */
    public function exponent1(): string
    {
        return $this->_exponent1;
    }

    /**
     * Get second factor exponent.
     *
     * @return string Base 10 integer
     */
    public function exponent2(): string
    {
        return $this->_exponent2;
    }

    /**
     * Get CRT coefficient of the second factor.
     *
     * @return string Base 10 integer
     */
    public function coefficient(): string
    {
        return $this->_coefficient;
    }

    public function algorithmIdentifier(): AlgorithmIdentifierType
    {
        return RSAEncryptionAlgorithmIdentifier::create();
    }

    /**
     * @return RSAPublicKey
     */
    public function publicKey(): PublicKey
    {
        return new RSAPublicKey($this->_modulus, $this->_publicExponent);
    }

    /**
     * Generate ASN.1 structure.
     */
    public function toASN1(): Sequence
    {
        return Sequence::create(
            Integer::create(0),
            Integer::create($this->_modulus),
            Integer::create($this->_publicExponent),
            Integer::create($this->_privateExponent),
            Integer::create($this->_prime1),
            Integer::create($this->_prime2),
            Integer::create($this->_exponent1),
            Integer::create($this->_exponent2),
            Integer::create($this->_coefficient)
        );
    }

    public function toDER(): string
    {
        return $this->toASN1()
            ->toDER();
    }

    public function toPEM(): PEM
    {
        return PEM::create(PEM::TYPE_RSA_PRIVATE_KEY, $this->toDER());
    }
}
