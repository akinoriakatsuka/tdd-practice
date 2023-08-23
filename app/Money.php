<?php

namespace App;

class Money
{
    protected $amount;

    public function equals(object $object): bool
    {
        $money = $object; // TODO: castできないか調べる
        return $this->amount === $money->amount and get_class($this) === get_class($money);
    }
}
