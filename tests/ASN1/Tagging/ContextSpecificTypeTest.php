<?php

declare(strict_types=1);

namespace Sop\Test\ASN1\Tagging;

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Tagged\ContextSpecificType;

/**
 * @internal
 */
final class ContextSpecificTypeTest extends TestCase
{
    public function testExplicitType()
    {
        $el = Element::fromDER(hex2bin('a1020500'));
        $this->assertInstanceOf(ContextSpecificType::class, $el);
    }

    public function testImplicitType()
    {
        $el = Element::fromDER(hex2bin('8100'));
        $this->assertInstanceOf(ContextSpecificType::class, $el);
    }
}
