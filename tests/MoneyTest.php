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
        $five->times(2);
        $this->assertEquals(10, $five->amount);
    }
}