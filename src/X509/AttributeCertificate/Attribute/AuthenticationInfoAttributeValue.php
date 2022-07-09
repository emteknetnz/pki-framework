<?php

declare(strict_types=1);

namespace SpomkyLabs\Pki\X509\AttributeCertificate\Attribute;

use SpomkyLabs\Pki\X509\GeneralName\GeneralName;

/**
 * Implements value for 'Service Authentication Information' attribute.
 *
 * @see https://tools.ietf.org/html/rfc5755#section-4.4.1
 */
final class AuthenticationInfoAttributeValue extends SvceAuthInfo
{
    final public const OID = '1.3.6.1.5.5.7.10.1';

    public function __construct(GeneralName $service, GeneralName $ident, ?string $auth_info = null)
    {
        parent::__construct($service, $ident, $auth_info);
        $this->_oid = self::OID;
    }
}
