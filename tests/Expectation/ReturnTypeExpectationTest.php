<?php

namespace hanneskod\readmetester\Expectation;

use hanneskod\readmetester\Result;

class ReturnTypeExpectationTest extends \PHPUnit_Framework_TestCase
{
    public function testGettypeNoMatch()
    {
        $expectation = new ReturnTypeExpectation('integer');
        $this->assertInstanceOf(
            'hanneskod\readmetester\Expectation\ReturnObj\Failure',
            $expectation->validate(new Result('string', ''))
        );
    }

    public function testClassNoMatch()
    {
        $expectation = new ReturnTypeExpectation('integer');
        $this->assertInstanceOf(
            'hanneskod\readmetester\Expectation\ReturnObj\Failure',
            $expectation->validate(new Result('string', ''))
        );
    }

    public function typeProvider()
    {
        return array(
            array('BOOLEAN', true),
            array('integer', 123),
            array('double', 1.1),
            array('string', '123'),
            array('array', array()),
            array('null', null),
            array('Exception', new \Exception),
        );
    }

    /**
     * @dataProvider typeProvider
     */
    public function testInteger($expected, $value)
    {
        $expectation = new ReturnTypeExpectation($expected);
        $this->assertInstanceOf(
            'hanneskod\readmetester\Expectation\ReturnObj\Success',
            $expectation->validate(new Result($value, ''))
        );
    }
}