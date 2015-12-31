<?php

namespace hanneskod\readmetester\PHPUnit;

use hanneskod\readmetester\ReadmeTester;

class ReadmeTestCase extends \PHPUnit_Framework_TestCase
{
    public function assertReadme($filename)
    {
        $tester = new ReadmeTester;
        $this->addToAssertionCount(1);
        $result = $this->getTestResultObject();

        foreach ($tester->test(new \SplFileObject($filename)) as $line) {
            $result->addFailure($this, new \PHPUnit_Framework_AssertionFailedError($line), 0.0);
        }
    }
}
