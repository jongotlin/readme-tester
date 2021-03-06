<?php

declare(strict_types = 1);

namespace hanneskod\readmetester\Runner;

/**
 * Output from an executed code block
 */
class ErrorOutcome implements OutcomeInterface
{
    /**
     * @var string
     */
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function getType(): string
    {
        return self::TYPE_ERROR;
    }

    public function mustBeHandled(): bool
    {
        return true;
    }

    public function getContent(): string
    {
        return $this->message;
    }

    public function __tostring(): string
    {
        return $this->message;
    }
}
