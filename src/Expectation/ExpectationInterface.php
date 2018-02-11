<?php

namespace hanneskod\readmetester\Expectation;

use hanneskod\readmetester\Runner\Result;

/**
 * Validate the result of an executed code block
 */
interface ExpectationInterface
{
    /**
     * Validate result object
     */
    public function validate(Result $result): ReturnObj\ReturnObj;
}
