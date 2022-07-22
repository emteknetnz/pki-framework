<?php

declare(strict_types=1);

namespace SpomkyLabs\Pki\X501\ASN1\AttributeValue;

use SpomkyLabs\Pki\X501\ASN1\AttributeType;
use SpomkyLabs\Pki\X501\ASN1\AttributeValue\Feature\DirectoryString;

/**
 * 'localityName' attribute value.
 *
 * @see https://www.itu.int/ITU-T/formal-language/itu-t/x/x520/2012/SelectedAttributeTypes.html#SelectedAttributeTypes.localityName
 */
final class LocalityNameValue extends DirectoryString
{
    /**
     * @param string $value String value
     * @param int $string_tag Syntax choice
     */
    public function __construct(string $value, int $string_tag = DirectoryString::UTF8)
    {
        $this->_oid = AttributeType::OID_LOCALITY_NAME;
        parent::__construct($value, $string_tag);
    }
}
