<?php

declare(strict_types=1);

namespace SpomkyLabs\Pki\X509\Certificate\Extension;

use SpomkyLabs\Pki\ASN1\Element;
use SpomkyLabs\Pki\ASN1\Type\UnspecifiedType;
use SpomkyLabs\Pki\X509\GeneralName\GeneralNames;

/**
 * Implements 'Subject Alternative Name' certificate extension.
 *
 * @see https://tools.ietf.org/html/rfc5280#section-4.2.1.6
 */
final class SubjectAlternativeNameExtension extends Extension
{
    public function __construct(
        bool $critical, /**
     * Names.
     */
    protected GeneralNames $_names
    ) {
        parent::__construct(self::OID_SUBJECT_ALT_NAME, $critical);
    }

    public function names(): GeneralNames
    {
        return $this->_names;
    }

    protected static function _fromDER(string $data, bool $critical): Extension
    {
        return new self($critical, GeneralNames::fromASN1(UnspecifiedType::fromDER($data)->asSequence()));
    }

    protected function _valueASN1(): Element
    {
        return $this->_names->toASN1();
    }
}
