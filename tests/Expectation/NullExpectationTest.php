<?php

declare(strict_types = 1);

namespace hanneskod\readmetester\Expectation;

use hanneskod\readmetester\Runner\Result;

class NullExpectationTest extends \PHPUnit\Framework\TestCase
{
    public function testMatch()
    {
        $expectation = new NullExpectation;
        $this->assertInstanceOf(
            'hanneskod\readmetester\Expectation\ReturnObj\Success',
            $expectation->validate(new Result('', ''))
        );
    }
}
