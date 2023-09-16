<?php

declare(strict_types=1);

echo (new TestCaseTest('testTemplateMethod'))->run()->summary() . PHP_EOL;
echo (new TestCaseTest('testResult'))->run()->summary() . PHP_EOL;
echo (new TestCaseTest('testFailedResult'))->run()->summary() . PHP_EOL;
echo (new TestCaseTest('testFailedTestFormatting'))->run()->summary() . PHP_EOL;
echo (new TestCaseTest('testSuite'))->run()->summary() . PHP_EOL;

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

    public function testSuite(): void
    {
        $suite = new TestSuite();
        $suite->add(new WasRun('testMethod'));
        $suite->add(new WasRun('testBrokenMethod'));
        $result = $suite->run();
        assert($result->summary() === '2 run, 1 failed');
    }
}
