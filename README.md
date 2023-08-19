# tdd-practice

## 1章

### 環境

- PHP 8.2.7 (cli)
- Composer version 2.5.8

### phpunitのインストール

composerを使ってインストール。

```json:comopser.json
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
