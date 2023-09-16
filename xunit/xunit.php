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
