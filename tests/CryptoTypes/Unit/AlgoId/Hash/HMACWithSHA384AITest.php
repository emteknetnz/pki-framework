<?php

declare(strict_types=1);

namespace Sop\Test\CryptoTypes\Unit\AlgoId\Hash;

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Type\Constructed\Sequence;
use Sop\CryptoTypes\AlgorithmIdentifier\AlgorithmIdentifier;
use Sop\CryptoTypes\AlgorithmIdentifier\Hash\HMACWithSHA384AlgorithmIdentifier;

/**
 * @internal
 */
final class HMACWithSHA384AITest extends TestCase
{
    /**
     * @return Sequence
     */
    public function testEncode()
    {
        $ai = new HMACWithSHA384AlgorithmIdentifier();
        $seq = $ai->toASN1();
        $this->assertInstanceOf(Sequence::class, $seq);
        return $seq;
    }

    /**
     * @depends testEncode
     */
    public function testDecode(Sequence $seq)
    {
        $ai = AlgorithmIdentifier::fromASN1($seq);
        $this->assertInstanceOf(HMACWithSHA384AlgorithmIdentifier::class, $ai);
        return $ai;
    }

    /**
     * @depends testDecode
     */
    public function testName(AlgorithmIdentifier $algo)
    {
        $this->assertIsString($algo->name());
    }
}
