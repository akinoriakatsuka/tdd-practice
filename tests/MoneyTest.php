<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Dollar;

final class MoneyTest extends TestCase
{
    /**
     * @test
     */
    public function testMultiplication(): void
    {
        $five = new Dollar(5);
        $product = $five->times(2);
        $this->assertEquals(10, $product->amount);
        $product = $five->times(3);
        $this->assertEquals(15, $product->amount);
    }

    /**
     * @test
     */
    public function testEquality(): void
    {
        $five = new Dollar(5);
        $another_five = new Dollar(5);
        $this->assertTrue($five->equals($another_five));
    }
}