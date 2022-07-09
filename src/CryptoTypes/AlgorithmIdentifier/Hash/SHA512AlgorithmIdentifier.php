<?php

declare(strict_types=1);

namespace SpomkyLabs\Pki\CryptoTypes\AlgorithmIdentifier\Hash;

/**
 * SHA-512 algorithm identifier.
 *
 * @see http://oid-info.com/get/2.16.840.1.101.3.4.2.3
 * @see https://tools.ietf.org/html/rfc4055#section-2.1
 * @see https://tools.ietf.org/html/rfc5754#section-2.4
 */
final class SHA512AlgorithmIdentifier extends SHA2AlgorithmIdentifier
{
    public function __construct()
    {
        $this->_oid = self::OID_SHA512;
        parent::__construct();
    }

    public function name(): string
    {
        return 'sha512';
    }
}
