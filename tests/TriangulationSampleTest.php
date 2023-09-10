<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class TriangulationSampleTest extends TestCase
{
    public function testSum(): void
    {
        $this->assertSame(4, $this->plus(3, 1));
        $this->assertSame(7, $this->plus(3, 4));
    }

    private function plus(int $augend, int $addend): int
    {
        return $augend + $addend;
    }

}
