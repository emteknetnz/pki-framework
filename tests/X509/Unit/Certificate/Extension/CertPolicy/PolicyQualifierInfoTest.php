<?php

declare(strict_types=1);

namespace Sop\Test\X509\Unit\Certificate\Extension\CertPolicy;

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Type\Constructed\Sequence;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\Primitive\ObjectIdentifier;
use Sop\X509\Certificate\Extension\CertificatePolicy\PolicyQualifierInfo;
use UnexpectedValueException;

/**
 * @internal
 */
final class PolicyQualifierInfoTest extends TestCase
{
    /**
     * @test
     */
    public function fromASN1UnknownTypeFail()
    {
        $seq = new Sequence(new ObjectIdentifier('1.3.6.1.3'), new NullType());
        $this->expectException(UnexpectedValueException::class);
        PolicyQualifierInfo::fromASN1($seq);
    }
}
