<?php

declare(strict_types=1);

namespace SpomkyLabs\Pki\CryptoTypes\AlgorithmIdentifier\Signature;

/**
 * RSA with SHA-1 signature algorithm identifier.
 *
 * @see https://tools.ietf.org/html/rfc3279#section-2.2.1
 */
final class SHA1WithRSAEncryptionAlgorithmIdentifier extends RFC3279RSASignatureAlgorithmIdentifier
{
    public function __construct()
    {
        $this->_oid = self::OID_SHA1_WITH_RSA_ENCRYPTION;
    }

    public function name(): string
    {
        return 'sha1-with-rsa-signature';
    }
}
