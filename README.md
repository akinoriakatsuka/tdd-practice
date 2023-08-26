# tdd-practice

## 全体

### テスト駆動開発の流れ
1. テストを書く
2. コンパイラを通す（インタプリタ言語の場合は、静的解析など？）
3. テストを走らせ、失敗することを確認する
4. テストを通す
5. 重複を排除する

## 1章

### 環境

- PHP 8.2.7 (cli)
- Composer version 2.5.8

### phpunitのインストール

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

### ディレクトリ構成

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

### autoloadの設定

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

### テストの実行

`/tests`にテストファイル、`/app`にテスト対象のファイルを作成し、以下を実行

```bash
$ ./vendor/bin/phpunit tests --color
```

## 2章

## 3章

### Value Objectパターン

- オブジェクトを値として使う（参照との対比）
- 別名参照を気にする必要がなくなる

## 4章

### amountをprivateにする

3章の終了時点では、テストする時にDollarのamountにアクセスする必要があった。
これは実装の詳細なので、private変数にすることで、外部から隠蔽する。そうすることで、テストコードがDollarの内部実装に依存しなくなる。

## 5章

まずは、テストを書いて、テストを通すように実装するのがTDDの手順なので、重複したコードがあっても良い。
後で、重複を排除する。

## 6章

### `Fatal error: Cannot declare class Money, because the name is already in use in /path_to_dir/tdd-practice/app/Money.php on line 3` の解消

原因：namespaceの書き忘れ

```php:Money.php
<?php

namespace App; // これを書かないとエラーになる

class Money {

}
```

## 8章
重複を消すために、DollarとFrancのtimesメソッドを一致させる。（完全に一致させられれば、Moneyクラスに移動できる）
シグネチャを合わせたり、Moneyクラスをabstractにしてtimesメソッドを宣言したりして、徐々に近づけていく。
その間も、常にテストが通ることを確認しながら進める。

## 9章
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

## 10章
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