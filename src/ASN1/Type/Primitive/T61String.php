<?php

declare(strict_types=1);

namespace SpomkyLabs\Pki\ASN1\Type\Primitive;

use SpomkyLabs\Pki\ASN1\Type\PrimitiveString;
use SpomkyLabs\Pki\ASN1\Type\UniversalClass;

/**
 * Implements *T61String* type.
 */
final class T61String extends PrimitiveString
{
    use UniversalClass;

    public function __construct(string $string)
    {
        $this->_typeTag = self::TYPE_T61_STRING;
        parent::__construct($string);
    }

    protected function _validateString(string $string): bool
    {
        // allow everything since there's literally
        // thousands of allowed characters (16 bit composed characters)
        return true;
    }
}
