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
