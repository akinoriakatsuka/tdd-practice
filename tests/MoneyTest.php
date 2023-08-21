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
        $this->assertTrue($five->equals(new Dollar(5)));
        $this->assertFalse($five->equals(new Dollar(6)));

        $five_franc = new Franc(5);
        $this->assertTrue($five_franc->equals(new Franc(5)));
        $this->assertFalse($five_franc->equals(new Franc(6)));
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
