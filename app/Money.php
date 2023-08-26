<?php

namespace App;

class Money
{
    protected $amount;
    protected $currency;

    public function __construct(int $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function times(int $multiplier): Money
    {
        return new Money(0, '');
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function equals(object $object): bool
    {
        $money = $object; // TODO: castできないか調べる
        return $this->amount === $money->amount and get_class($this) === get_class($money);
    }

    public static function dollar(int $amount): Money
    {
        return new Dollar($amount, 'USD');
    }

    public static function franc(int $amount): Money
    {
        return new Franc($amount, 'CHF');
    }
}
