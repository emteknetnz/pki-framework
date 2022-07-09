<?php

declare(strict_types=1);

namespace SpomkyLabs\Pki\Test\ASN1\Tagging;

use PHPUnit\Framework\TestCase;
use SpomkyLabs\Pki\ASN1\Exception\DecodeException;
use SpomkyLabs\Pki\ASN1\Type\Tagged\DERTaggedType;
use SpomkyLabs\Pki\ASN1\Type\TaggedType;

/**
 * @internal
 */
final class IndefiniteTaggedTest extends TestCase
{
    /**
     * @test
     */
    public function decodeIndefinite()
    {
        $el = TaggedType::fromDER(hex2bin('a0800201010000'));
        static::assertInstanceOf(DERTaggedType::class, $el);
        return $el;
    }

    /**
     * @depends decodeIndefinite
     *
     * @test
     */
    public function encodeIndefinite(TaggedType $el)
    {
        $der = $el->toDER();
        static::assertEquals(hex2bin('a0800201010000'), $der);
    }

    /**
     * @test
     */
    public function primitiveFail()
    {
        $this->expectException(DecodeException::class);
        $this->expectExceptionMessage('Primitive type with indefinite length is not supported');
        TaggedType::fromDER(hex2bin('80800201010000'));
    }
}
