<?php

declare(strict_types=1);

namespace App;

class Bank
{
    /** @var int[] */
    private array $rates = [];

    public function reduce(Expression $source, string $to): Money
    {
        return $source->reduce($this, $to);
    }

    public function addRate(string $from, string $to, int $rate): void
    {
        $this->rates[serialize(new Pair($from, $to))] = $rate;
    }

    public function rate(string $from, string $to): int
    {
        if ($from === $to) {
            return 1;
        }
        return $this->rates[serialize(new Pair($from, $to))];
    }
}
