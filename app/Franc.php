<?php

namespace App;

class Franc extends Money
{
    public function __construct(int $amount, string $currency)
    {
        parent::__construct($amount, $currency);
    }
    
    public function times(int $multiplier): Money
    {
        return new Franc($this->amount * $multiplier, $this->currency);
    }
}
