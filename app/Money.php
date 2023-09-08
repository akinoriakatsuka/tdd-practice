<?php

declare(strict_types=1);

namespace App;

class Money implements Expression
{
    public int $amount;
    protected string $currency;

    public function __construct(int $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function times(int $multiplier): Expression
    {
        return new self($this->amount * $multiplier, $this->currency);
    }

    public function plus(Expression $addend): Sum
    {
        return new Sum($this, $addend);
    }

    public function reduce(Bank $bank, string $to): self
    {
        $rate = $bank->rate($this->currency, $to);
        return new self($this->amount / $rate, $to);
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function equals(Expression $money): bool
    {
        return $this->amount === $money->amount && $this->currency === $money->currency;
    }

    public static function dollar(int $amount): self
    {
        return new self($amount, 'USD');
    }

    public static function franc(int $amount): self
    {
        return new self($amount, 'CHF');
    }
}
