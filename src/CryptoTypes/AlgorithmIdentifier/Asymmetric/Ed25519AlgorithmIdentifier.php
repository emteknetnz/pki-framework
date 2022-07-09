<?php

declare(strict_types=1);

namespace SpomkyLabs\Pki\CryptoTypes\AlgorithmIdentifier\Asymmetric;

use SpomkyLabs\Pki\CryptoTypes\AlgorithmIdentifier\AlgorithmIdentifier;

/**
 * Algorithm identifier for the Edwards-curve Digital Signature Algorithm (EdDSA) with curve25519.
 *
 * Same algorithm identifier is used for public and private keys as well as for signatures.
 *
 * @see http://oid-info.com/get/1.3.101.112
 * @see https://tools.ietf.org/html/rfc8420#appendix-A.1
 */
final class Ed25519AlgorithmIdentifier extends RFC8410EdAlgorithmIdentifier
{
    public function __construct()
    {
        $this->_oid = self::OID_ED25519;
    }

    public function name(): string
    {
        return 'id-Ed25519';
    }

    public function supportsKeyAlgorithm(AlgorithmIdentifier $algo): bool
    {
        return $algo->oid() === self::OID_ED25519;
    }
}
