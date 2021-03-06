<?php

declare(strict_types = 1);

namespace hanneskod\readmetester\Expectation;

use hanneskod\readmetester\Annotations;
use hanneskod\readmetester\Parser\Annotation;

/**
 * Create expectations from annotation data
 */
class ExpectationFactory
{
    public function createExpectation(Annotation $annotation): ?ExpectationInterface
    {
        if ($annotation->isNamed(Annotations::ANNOTATION_EXPECT_OUTPUT)) {
            return new OutputExpectation(new Regexp($annotation->getArgument() ?: '//'));
        }

        if ($annotation->isNamed(Annotations::ANNOTATION_EXPECT_ERROR)) {
            return new ErrorExpectation(new Regexp($annotation->getArgument() ?: '//'));
        }

        return null;
    }
}
