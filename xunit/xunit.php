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
