<?php

declare(strict_types=1);

namespace App;

interface Expression
{
    public function reduce(Bank $bank, string $to): Money;
}
