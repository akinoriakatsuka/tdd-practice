<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Dollar;
use App\Franc;

final class MoneyTest extends TestCase
{
    /**
     * @test
     */
    public function testMultiplication(): void
    {
        $five = new Dollar(5);
        $product = $five->times(2);
        $this->assertEquals($product, new Dollar(10));
        $product = $five->times(3);
        $this->assertEquals($product, new Dollar(15));
    }

    /**
     * @test
     */
    public function testEquality(): void
    {
        $five = new Dollar(5);
        $another_five = new Dollar(5);
        $six = new Dollar(6);

        $this->assertTrue($five->equals($another_five));
        $this->assertFalse($five->equals($six));
    }

    /**
     * @test
     */
    public function testFrancMultiplication(): void
    {
        $five = new Franc(5);
        $product = $five->times(2);
        $this->assertEquals($product, new Franc(10));
        $product = $five->times(3);
        $this->assertEquals($product, new Franc(15));
    }
}