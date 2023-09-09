<?php

declare(strict_types=1);

use App\Bank;
use App\Money;
use App\Sum;
use PHPUnit\Framework\TestCase;

final class MoneyTest extends TestCase
{
    public function testMultiplication(): void
    {
        $five = Money::dollar(5);
        $this->assertEquals($five->times(2), Money::dollar(10));
        $this->assertEquals($five->times(3), Money::dollar(15));
    }

    public function testEquality(): void
    {
        $this->assertTrue(Money::dollar(5)->equals(Money::dollar(5)));
        $this->assertFalse(Money::dollar(5)->equals(Money::dollar(6)));

        $this->assertFalse(Money::franc(5)->equals(Money::dollar(5)));
    }

    public function testCurrency(): void
    {
        $this->assertSame('CHF', Money::franc(1)->currency());
        $this->assertSame('USD', Money::dollar(1)->currency());
    }

    public function testSimpleAddition(): void
    {
        $five = Money::dollar(5);
        $sum = $five->plus($five);
        $bank = new Bank();
        $reduced = $bank->reduce($sum, "USD");
        $this->assertTrue(Money::dollar(10)->equals($reduced));
    }

    public function testPlusReturnsSum(): void
    {
        $five = Money::dollar(5);
        $result = $five->plus($five);
        $sum = $result; // 本当はここでExpressionからSumへのキャストを書きたい
        $this->assertEquals($five, $sum->augend);
        $this->assertEquals($five, $sum->addend);
    }

    public function testReduceSum(): void
    {
        $sum = new Sum(Money::dollar(3), Money::dollar(4));
        $bank = new Bank();
        $result = $bank->reduce($sum, 'USD');
        $this->assertTrue(Money::dollar(7)->equals($result));
    }

    public function testReduceMoney(): void
    {
        $bank = new Bank();
        $result = $bank->reduce(Money::dollar(1), 'USD');
        $this->assertTrue(Money::dollar(1)->equals($result));
    }

    public function testReduceMoneyDifferentCurrency(): void
    {
        $bank = new Bank();
        $bank->addRate('CHF', 'USD', 2);
        $result = $bank->reduce(Money::franc(2), 'USD');
        $this->assertTrue(Money::dollar(1)->equals($result));
    }

    public function testIdentityRate(): void
    {
        $bank = new Bank();
        $this->assertSame(1, $bank->rate('USD', 'USD'));
    }

    public function testAddRate(): void
    {
        $bank = new Bank();
        $bank->addRate('CHF', 'USD', 2);
        $this->assertSame(2, $bank->rate('CHF', 'USD'));
    }

    public function testMixedAddtion(): void
    {
        $fiveBucks = Money::dollar(5);
        $tenFrancs = Money::franc(10);
        $bank = new Bank();
        $bank->addRate('CHF', 'USD', 2);
        $result = $bank->reduce($fiveBucks->plus($tenFrancs), 'USD');
        $this->assertTrue(Money::dollar(10)->equals($result));
    }

    public function testSumPlusMoney(): void
    {
        $fiveBucks = Money::dollar(5);
        $tenFrancs = Money::franc(10);
        $bank = new Bank();
        $bank->addRate('CHF', 'USD', 2);
        $sum = (new Sum($fiveBucks, $tenFrancs))->plus($fiveBucks);
        $result = $bank->reduce($sum, 'USD');
        $this->assertTrue($result->equals(Money::dollar(15)));
    }

    public function testSumTimes(): void
    {
        $fiveBucks = Money::dollar(5);
        $tenFrancs = Money::franc(10);
        $bank = new Bank();
        $bank->addRate('CHF', 'USD', 2);
        $sum = (new Sum($fiveBucks, $tenFrancs))->times(2);
        $result = $bank->reduce($sum, 'USD');
        $this->assertTrue($result->equals(Money::dollar(20)));
    }

}
