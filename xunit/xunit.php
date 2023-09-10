<?php

declare(strict_types=1);

(new TestCaseTest('testRunning'))->run();
$testCaseTest->run();

class TestCase
{
    public string $name;
    public function __construct(string $name)
    {
        $this->name = $name;
    }
    public function run(): void
    {
        $func = $this->name;
        $this->$func();
    }
}

class WasRun extends TestCase
{
    public ?int $wasRun;
    public function __construct(string $name)
    {
        $this->wasRun = null;
        parent::__construct($name);
    }
    public function testMethod(): void
    {
        $this->wasRun = 1;
    }
}

class TestCaseTest extends TestCase
{
    public function testRunning(): void
    {
        $test = new WasRun('testMethod');
        assert(is_null($test->wasRun));
        $test->run();
        assert($test->wasRun === 1);
    }
}
