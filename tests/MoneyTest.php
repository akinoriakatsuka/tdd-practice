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
        $five = Money::dollar(5);
        $this->assertTrue($five->equals(Money::dollar(5)));
        $this->assertFalse($five->equals(Money::dollar(6)));

        $five_franc = Money::franc(5);
        $this->assertTrue($five_franc->equals(Money::franc(5)));
        $this->assertFalse($five_franc->equals(Money::franc(6)));

        $this->assertFalse($five_franc->equals($five));
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
}
