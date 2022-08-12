<?php

declare(strict_types=1);

namespace SpomkyLabs\Pki\Test\CryptoTypes\Unit\AlgoId;

use PHPUnit\Framework\TestCase;
use SpomkyLabs\Pki\ASN1\Type\Constructed\Sequence;
use SpomkyLabs\Pki\ASN1\Type\Primitive\Integer;
use SpomkyLabs\Pki\ASN1\Type\UnspecifiedType;
use SpomkyLabs\Pki\CryptoTypes\AlgorithmIdentifier\GenericAlgorithmIdentifier;

/**
 * @internal
 */
final class GenericAlgorithmIdentifierTest extends TestCase
{
    /**
     * @test
     */
    public function create(): GenericAlgorithmIdentifier
    {
        $ai = GenericAlgorithmIdentifier::create('1.3.6.1.3', UnspecifiedType::create(Integer::create(42)));
        static::assertInstanceOf(GenericAlgorithmIdentifier::class, $ai);
        return $ai;
    }

    /**
     * @depends create
     *
     * @test
     */
    public function name(GenericAlgorithmIdentifier $ai): void
    {
        static::assertEquals('1.3.6.1.3', $ai->name());
    }

    /**
     * @depends create
     *
     * @test
     */
    public function parameters(GenericAlgorithmIdentifier $ai): void
    {
        static::assertInstanceOf(UnspecifiedType::class, $ai->parameters());
    }

    /**
     * @depends create
     *
     * @test
     */
    public function encode(GenericAlgorithmIdentifier $ai): void
    {
        static::assertInstanceOf(Sequence::class, $ai->toASN1());
    }
}
