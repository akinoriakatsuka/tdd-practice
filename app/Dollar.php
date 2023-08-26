<?php

namespace App;

class Dollar extends Money
{
    public function __construct(int $amount, string $currency)
    {
        parent::__construct($amount, $currency);
    }
    public function times(int $multiplier): Money
    {
        return parent::dollar($this->amount * $multiplier);
    }
}
