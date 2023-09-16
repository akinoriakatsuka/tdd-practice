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
    {
    }
    public function run(): void
    {
        $this->setUp();
        $func = $this->name;
        $this->$func();
    }
}

class WasRun extends TestCase
{
    public string $log;

    public function setUp(): void
    {
        $this->wasRun = null;
        $this->log = 'setUp ';
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
        assert($this->test->log === 'setUp ');
    }
}
