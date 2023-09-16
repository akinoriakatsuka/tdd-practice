# tdd-practice

## 全体

### テスト駆動開発の流れ
1. テストを書く
2. コンパイラを通す（インタプリタ言語の場合は、静的解析など？）
3. テストを走らせ、失敗することを確認する
4. テストを通す
5. 重複を排除する

## 第1部

### 1章

#### 環境

- PHP 8.2.7 (cli)
- Composer version 2.5.8

#### phpunitのインストール

composerを使ってインストール。

```comopser.json```

```json
{
  "require-dev": {
      "phpunit/phpunit": "10.0.*"
  }
}
```

```bash
$ composer install
```

#### ディレクトリ構成

`app/`と`test/`を作成

```
$ tree -L 1
.
├── README.md
├── app
├── composer.json
├── composer.lock
├── tests
├── todo.md
└── vendor
```

#### autoloadの設定

`composer.json`に以下を追記

```json:composer.json
{
  "autoload": {
    "psr-4": {
      "App\\": "app/"
    }
  }
}
```

以下を実行

```bash
$ composer dump-autoload
```

#### テストの実行

`/tests`にテストファイル、`/app`にテスト対象のファイルを作成し、以下を実行

```bash
$ ./vendor/bin/phpunit tests --color
```

### 2章

### 3章

#### Value Objectパターン

- オブジェクトを値として使う（参照との対比）
- 別名参照を気にする必要がなくなる

### 4章

#### amountをprivateにする

3章の終了時点では、テストする時にDollarのamountにアクセスする必要があった。
これは実装の詳細なので、private変数にすることで、外部から隠蔽する。そうすることで、テストコードがDollarの内部実装に依存しなくなる。

### 5章

まずは、テストを書いて、テストを通すように実装するのがTDDの手順なので、重複したコードがあっても良い。
後で、重複を排除する。

### 6章

#### `Fatal error: Cannot declare class Money, because the name is already in use in /path_to_dir/tdd-practice/app/Money.php on line 3` の解消

原因：namespaceの書き忘れ

```php:Money.php
<?php

namespace App; // これを書かないとエラーになる

class Money {

}
```

### 8章
重複を消すために、DollarとFrancのtimesメソッドを一致させる。（完全に一致させられれば、Moneyクラスに移動できる）
シグネチャを合わせたり、Moneyクラスをabstractにしてtimesメソッドを宣言したりして、徐々に近づけていく。
その間も、常にテストが通ることを確認しながら進める。

### 9章
各々のコンストラクタの実装を合わせて、Moneyクラスに移動した。

`Money.php`
```php:Money.php
    public function __construct(int $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }
```

`Dollar.php`
```php:Dollar.php
    public function __construct(int $amount, string $currency)
    {
        parent::__construct($amount, $currency);
    }
```

### 10章
timesをMoneyクラスに移動するために、各々の子クラスでtimesメソッドの実装を合わせる。

1. timesメソッドの中でMoneyをインスタンス化する
2. Moneyをインスタンス化するために、Moneyクラスを具象クラスにする
3. Moneyクラスを具象クラスにするためには、timesのabstractを削除する
4. timesは各々の子クラスで実装しているため、Moneyクラスでは仮実装で良い（オーバーロードされる）
    ```
    public function times(int $multiplier): Money
    {
        return new Money(0, '');
    }
    ```

#### equalsとtoString
junitでは、プロダクションコードのequalsメソッドを使って、テスト時のオブジェクトの等価性を比較している（？）
phpunitでは、特にそういう機能はないので、型があっていないオブジェクトの等価性比較をすると、falseになる。

toStringもおそらくjunitのみだと思われる。

phpunitでも、対応するようなものがないか調べる。

### 11章

サブクラスを削除したことによって、不要になったテストを削除した。
そのテストが不要になったかどうかは、それぞれが何を検証しているかを考慮して判断する。

### 12章

テストが整備されていると、新しいアイデアを試しやすくなる。

### 13章

#### 疑問点
p.94 の中断あたりで、Sumにreduceを持ってくると、グリーンバーになると書いてあるが、実際はprotectedプロパティにアクセスしてしまっているため、グリーンにならなかった。JavaとPHPの違いだろうか？
~~（とりあえず、不本意ながらpublicにして先に進む）~~

Javaのprotectedは同一パッケージであればアクセスできる仕様らしい。今回は、MoneyのamountにSumからアクセスさせたいため、phpではpublicで、妥当であると考えられるのでそのまま進むことにする。

### 14章

PHPは配列のキーにオブジェクトを入れることはできないので、仕方なく通貨ペアのオブジェクトをシリアライズしたものをキーとして値を保存した。
Piarの等価性比較をしないので、ハッシュコードなどはいらないかもしれない…（次章以降で確認する）

set側
```php
    public function addRate(string $from, string $to, int $rate): void
    {
        $this->rates[serialize(new Pair($from, $to))] = $rate;
    }
```

get側
```php
    public function rate(string $from, string $to): int
    {
        if ($from === $to) return 1;
        return $this->rates[serialize(new Pair($from, $to))];
    }
```

### 17章

#### 振り返り
- テストを綺麗に機能させるには、仮実装、三角測量、明白な実装の方法がある

`tests/TriangulationSampleTest.php --color` を参照

#### 仮実装
ベタ書きでテストを通す。テストを通した後に、テストケースを追加して本物の実装を追加する。
テストケースを1+3のものだけを用意し、4を返すようにすればテストは通る。

```php:TriangulationSampleTest.php
final class SampleTest extends TestCase
{
    public function testSum():void
    {
        $this->assertSame(4, $this->plus(3,1));
    }

    private function plus(int $augend, int $addend): int
    {
        return 4; // 仮実装
    }

}
```

#### 三角測量
2つ以上の例があるときのみ、一般化を行う。

Step1: 一つの例しかないので、一般化は行わない。（仮実装と同じ）

```php:TriangulationSampleTest.php
final class SampleTest extends TestCase
{
    public function testSum():void
    {
        $this->assertSame(4, $this->plus(3,1));
    }

    private function plus(int $augend, int $addend): int
    {
        return 4; // 仮実装のまま
    }

}
```

Step2: 2つの例があるので、一般化を行う。

```php:TriangulationSampleTest.php
final class SampleTest extends TestCase
{
    public function testSum():void
    {
        $this->assertSame(4, $this->plus(3,1));
        $this->assertSame(7, $this->plus(3,4));
    }

    private function plus(int $augend, int $addend): int
    {
        return $augend + $addend; // 一般化
    }

}

```

#### 明白な実装

シンプルな操作をそのまま実装すること。上記のplusメソッドのようなシンプルな実装では、仮実装や三角測量をする必要は基本的にない。レッドバーが出て驚いた時などは、仮実装などのスモールステップに戻るのが良い。

仮実装などの中間地点は、あくまで手段なので、すぐに書けそうなら明白な実装にする。

## 第2部 xUnit

### 18章

まず、単純なテストとして、あるメソッドが呼ばれたがどうかを検証するテストを書く。（書籍にはpythonで書かれているが、phpで書いてみる）

WasRunというクラスを作成して、メソッドが呼ばれたかどうかをフラグを使って返すようにする。
メソッド名については、WasRunのインスタンス化時に名前を指定できるようにして、呼ぶときにはvariable functionを使った。

途中で、WasRunクラスが、「メソッドが起動されたか記録する仕事」と「テストメソッドを動的に呼び出す仕事」をするようになったので、後者の仕事をTestCaseクラスに分離した。

#### 検証の仕方

`xunit/xunit.php`を作り、以下のコマンドで実行する。

```bash
php xunit/xunit.php
```

一章終了時のコードは以下の通り。

```php:xunit/xunit.php
<?php

declare(strict_types=1);

$testCaseTest = new TestCaseTest('testRunning');
$testCaseTest->run();

class TestCase
{
    public $name;
    public function __construct($name)
    {
        $this->name = $name;
    }
    public function run()
    {
        $func = $this->name;
        $this->$func();
    }
}

class WasRun extends TestCase
{
    public $wasRun;
    public function __construct($name)
    {
        $this->wasRun = null;
        parent::__construct($name);
    }
    public function testMethod()
    {
        $this->wasRun = 1;
    }
}

class TestCaseTest extends TestCase
{
    public function testRunning()
    {
        $test = new WasRun('testMethod');
        assert(!$test->wasRun);
        $test->run();
        assert($test->wasRun);
    }
}

```

### 19章

#### 終了時点のコード

```php:xunit/xunit.php
<?php

declare(strict_types=1);

(new TestCaseTest('testRunning'))->run();
(new TestCaseTest('testSetUp'))->run();

class TestCase
{
    public string $name;
    public ?int $wasRun;
    public ?int $wasSetUp;
    public function __construct(string $name)
    {
        $this->name = $name;
    }
    public function setUp(): void
    {}
    public function run(): void
    {
        $this->setUp();
        $func = $this->name;
        $this->$func();
    }
}

class WasRun extends TestCase
{
    public function setUp(): void
    {
        $this->wasRun = null;
        $this->wasSetUp = 1;
    }
    public function testMethod(): void
    {
        $this->wasRun = 1;
    }
}

class TestCaseTest extends TestCase
{
    public WasRun $test;

    public function setUp(): void
    {
        $this->test = new WasRun('testMethod');
    }

    public function testRunning(): void
    {
        $this->test->run();
        assert($this->test->wasRun === 1);
    }

    public function testSetUp(): void
    {
        $this->test->run();
        assert($this->test->wasSetUp === 1);
    }
}

```

### 20章

> なんとWasRunのインスタンスを使う部分は１つになってしまった。気の利いたsetUpメソッドだったが、元に戻そう。

この部分の意味がわからなかった。
→使う部分が１つなので、testTemplateMethodの中でWasRunをインスタンス化すれば良いということだった。

```
 class TestCaseTest extends TestCase
 {
-    public WasRun $test;
-
-    public function setUp(): void
-    {
-        $this->test = new WasRun('testMethod');
-    }
-
     public function testTemplateMethod(): void
     {
-        $this->test->run();
-        assert($this->test->log === 'setUp testMethod ');
+        $test = new WasRun('testMethod');
+        $test->run();
+        assert($test->log === 'setUp testMethod ');
     }
 }
```

#### 終了時のコード

```php:xunit/xunit.php
<?php

declare(strict_types=1);

(new TestCaseTest('testTemplateMethod'))->run();

class TestCase
{
    public string $name;
    public ?int $wasRun;
    public ?int $wasSetUp;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
    public function setUp(): void
    {
    }
    public function tearDown(): void
    {
    }
    public function run(): void
    {
        $this->setUp();
        $func = $this->name;
        $this->$func();
        $this->tearDown();
    }
}

class WasRun extends TestCase
{
    public string $log;

    public function setUp(): void
    {
        $this->log = 'setUp ';
    }
    public function testMethod(): void
    {
        $this->log = $this->log . 'testMethod ';
    }
    public function tearDown(): void
    {
        $this->log = $this->log . 'tearDown ';
    }
}

class TestCaseTest extends TestCase
{
    public function testTemplateMethod(): void
    {
        $test = new WasRun('testMethod');
        $test->run();
        assert($test->log === 'setUp testMethod tearDown ');
    }
}

```

### 21章

終了前に、テストを一度コメントアウトして、棚上げした。より小さいテストを追加する。

#### 終了時のコード

```php:xunit/xunit.php
<?php

declare(strict_types=1);

(new TestCaseTest('testTemplateMethod'))->run();
(new TestCaseTest('testResult'))->run();
// (new TestCaseTest('testBrokenResult'))->run();

class TestCase
{
    public string $name;
    public ?int $wasRun;
    public ?int $wasSetUp;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
    public function setUp(): void
    {
    }
    public function tearDown(): void
    {
    }
    public function run(): TestResult
    {
        $result = new TestResult();
        $result->testStarted();
        $this->setUp();
        $func = $this->name;
        $this->$func();
        $this->tearDown();
        return $result;
    }
}

class WasRun extends TestCase
{
    public string $log;

    public function setUp(): void
    {
        $this->log = 'setUp ';
    }
    public function testMethod(): void
    {
        $this->log = $this->log . 'testMethod ';
    }
    public function testBrokenMethod(): void
    {
        throw new Exception();
    }
    public function tearDown(): void
    {
        $this->log = $this->log . 'tearDown ';
    }
}

class TestResult
{
    public int $runCount;
    public function __construct()
    {
        $this->runCount = 0;
    }
    public function testStarted(): void
    {
        $this->runCount++;
    }
    public function summary(): string
    {
        return sprintf('%b run, 0 faild',$this->runCount);
    }
}

class TestCaseTest extends TestCase
{
    public function testTemplateMethod(): void
    {
        $test = new WasRun('testMethod');
        $test->run();
        assert($test->log === 'setUp testMethod tearDown ');
    }

    public function testResult(): void
    {
        $test = new WasRun('testMethod');
        $result = $test->run();
        assert($result->summary() === '1 run, 0 faild');
    }

    public function testFaildResult(): void
    {
        $test = new WasRun('testBrokenMethod');
        $result = $test->run();
        assert($result->summary() === '1 run, 1 faild');
    }
}

```

### 22章

#### 終了時のコード

```php:xunit/xunit.php
<?php

declare(strict_types=1);

echo (new TestCaseTest('testTemplateMethod'))->run()->summary() . PHP_EOL;
echo (new TestCaseTest('testResult'))->run()->summary() . PHP_EOL;
echo (new TestCaseTest('testFailedResult'))->run()->summary() . PHP_EOL;
echo (new TestCaseTest('testFailedTestFormatting'))->run()->summary() . PHP_EOL;

class TestCase
{
    public string $name;
    public ?int $wasRun;
    public ?int $wasSetUp;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
    public function setUp(): void
    {
    }
    public function tearDown(): void
    {
    }
    public function run(): TestResult
    {
        $result = new TestResult();
        $result->testStarted();
        $this->setUp();
        try {
            $func = $this->name;
            $this->$func();
        } catch (Exception $e) {
            $result->testFailed();
        }
        $this->tearDown();
        return $result;
    }
}

class WasRun extends TestCase
{
    public string $log;

    public function setUp(): void
    {
        $this->log = 'setUp ';
    }
    public function testMethod(): void
    {
        $this->log = $this->log . 'testMethod ';
    }
    public function testBrokenMethod(): void
    {
        throw new Exception();
    }
    public function tearDown(): void
    {
        $this->log = $this->log . 'tearDown ';
    }
}

class TestResult
{
    public int $runCount;
    public int $errorCount;
    public function __construct()
    {
        $this->runCount = 0;
        $this->errorCount = 0;
    }
    public function testStarted(): void
    {
        $this->runCount++;
    }
    public function testFailed(): void
    {
        $this->errorCount++;
    }
    public function summary(): string
    {
        return sprintf('%b run, %b failed', $this->runCount, $this->errorCount);
    }
}

class TestCaseTest extends TestCase
{
    public function testTemplateMethod(): void
    {
        $test = new WasRun('testMethod');
        $test->run();
        assert($test->log === 'setUp testMethod tearDown ');
    }

    public function testResult(): void
    {
        $test = new WasRun('testMethod');
        $result = $test->run();
        assert($result->summary() === '1 run, 0 failed');
    }

    public function testFailedResult(): void
    {
        $test = new WasRun('testBrokenMethod');
        $result = $test->run();
        assert($result->summary() === '1 run, 1 failed');
    }

    public function testFailedTestFormatting(): void
    {
        $result = new TestResult();
        $result->testStarted();
        $result->testFailed();
        assert($result->summary() === '1 run, 1 failed');
    }
}

```

### 23章

一つひとつのテストを逐次呼んでいて、汚い実装になっているのでスイートにまとめる。

#### Collecting Parameterパターン
処理のパラメータに、結果格納用のオブジェクトを渡すパターン。

#### 終了時のコード

```php:xunit/xunit.php
<?php

declare(strict_types=1);

$suite = new TestSuite();
$suite->add(new TestCaseTest('testTemplateMethod'));
$suite->add(new TestCaseTest('testResult'));
$suite->add(new TestCaseTest('testFailedResult'));
$suite->add(new TestCaseTest('testFailedResultFormatting'));
$suite->add(new TestCaseTest('testSuite'));
$result = new TestResult();
$suite->run($result);
echo $result->summary() . PHP_EOL;

class TestCase
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
    public function setUp(): void
    {
    }
    public function tearDown(): void
    {
    }
    public function run(TestResult $result): void
    {
        $result->testStarted();
        $this->setUp();
        try {
            $func = $this->name;
            $this->$func();
        } catch (Exception $e) {
            $result->testFailed();
        }
        $this->tearDown();
    }
}

class WasRun extends TestCase
{
    public string $log;

    public function setUp(): void
    {
        $this->log = 'setUp ';
    }
    public function testMethod(): void
    {
        $this->log = $this->log . 'testMethod ';
    }
    public function testBrokenMethod(): void
    {
        throw new Exception();
    }
    public function tearDown(): void
    {
        $this->log = $this->log . 'tearDown ';
    }
}

class TestResult
{
    public int $runCount;
    public int $errorCount;

    public function __construct()
    {
        $this->runCount = 0;
        $this->errorCount = 0;
    }
    public function testStarted(): void
    {
        $this->runCount++;
    }
    public function testFailed(): void
    {
        $this->errorCount++;
    }
    public function summary(): string
    {
        return sprintf('%d run, %d failed', $this->runCount, $this->errorCount);
    }
}

class TestSuite
{
    /** @var TestCase[] */
    public $tests;

    public function __construct()
    {
        $this->tests = [];
    }
    public function add(TestCase $test): void
    {
        $this->tests[] = $test;
    }
    public function run(TestResult $result): void
    {
        foreach ($this->tests as $test) {
            $test->run($result);
        }
    }
}

class TestCaseTest extends TestCase
{
    public TestResult $result;

    public function setUp(): void
    {
        $this->result = new TestResult();
    }
    public function testTemplateMethod(): void
    {
        $test = new WasRun('testMethod');
        $test->run($this->result);
        assert($test->log === 'setUp testMethod tearDown ');
    }
    public function testResult(): void
    {
        $test = new WasRun('testMethod');
        $test->run($this->result);
        assert($this->result->summary() === '1 run, 0 failed');
    }
    public function testFailedResult(): void
    {
        $test = new WasRun('testBrokenMethod');
        $test->run($this->result);
        assert($this->result->summary() === '1 run, 1 failed');
    }
    public function testFailedResultFormatting(): void
    {
        $this->result->testStarted();
        $this->result->testFailed();
        assert($this->result->summary() === '1 run, 1 failed');
    }
    public function testSuite(): void
    {
        $suite = new TestSuite();
        $suite->add(new WasRun('testMethod'));
        $suite->add(new WasRun('testBrokenMethod'));
        $suite->run($this->result);
        assert($this->result->summary() === '2 run, 1 failed');
    }
}

```