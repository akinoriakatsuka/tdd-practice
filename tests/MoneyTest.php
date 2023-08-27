<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Money;
use App\Dollar;
use App\Franc;

final class MoneyTest extends TestCase
{
    /**
     * @test
     */
    public function testMultiplication(): void
    {
        $five = Money::dollar(5);
        $product = $five->times(2);
        $this->assertEquals($product, Money::dollar(10));
        $product = $five->times(3);
        $this->assertEquals($product, Money::dollar(15));
    }

    /**
     * @test
     */
    public function testEquality(): void
    {
        $this->assertTrue(Money::dollar(5)->equals(Money::dollar(5)));
        $this->assertFalse(Money::dollar(5)->equals(Money::dollar(6)));

        $this->assertTrue(Money::franc(5)->equals(Money::franc(5)));
        $this->assertFalse(Money::franc(5)->equals(Money::franc(6)));

        $this->assertFalse(Money::franc(5)->equals(Money::dollar(5)));
    }

    /**
     * @test
     */
    public function testFrancMultiplication(): void
    {
        $five = Money::franc(5);
        $product = $five->times(2);
        $this->assertEquals($product, Money::franc(10));
        $product = $five->times(3);
        $this->assertEquals($product, Money::franc(15));
    }

    public function testCurrency(): void
    {
        $this->assertEquals('CHF', Money::franc(1)->currency());
        $this->assertEquals('USD', Money::dollar(1)->currency());
    }
}
