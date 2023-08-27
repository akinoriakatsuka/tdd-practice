<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Money;

final class MoneyTest extends TestCase
{
    /**
     * @test
     */
    public function testMultiplication(): void
    {
        $five = Money::dollar(5);
        $this->assertEquals($five->times(2), Money::dollar(10));
        $this->assertEquals($five->times(3), Money::dollar(15));
    }

    /**
     * @test
     */
    public function testEquality(): void
    {
        $this->assertTrue(Money::dollar(5)->equals(Money::dollar(5)));
        $this->assertFalse(Money::dollar(5)->equals(Money::dollar(6)));

        $this->assertFalse(Money::franc(5)->equals(Money::dollar(5)));
    }

    public function testCurrency(): void
    {
        $this->assertEquals('CHF', Money::franc(1)->currency());
        $this->assertEquals('USD', Money::dollar(1)->currency());
    }
}
