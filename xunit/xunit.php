<?php

declare(strict_types=1);

$test = new WasRun('testMethod');
assert(!$test->wasRun);
$test->run();
assert($test->wasRun);

class WasRun
{
    public $wasRun;
    public $name;
    public function __construct($name)
    {
        $this->wasRun = null;
        $this->name = $name;
    }
    public function testMethod()
    {
        $this->wasRun = 1;
    }
    public function run()
    {
        $func = $this->name;
        $this->$func();
    }
}
