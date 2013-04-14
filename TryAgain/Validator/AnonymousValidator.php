<?php

namespace TryAgain\Validator;

use TryAgain\ValidatorInterface;
use TryAgain\Handler;

class AnonymousValidator implements ValidatorInterface
{
    /** @var callable */
    protected $callback;

    /**
     * @param callable $callback
     */
    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param TryAgain\Handler $handler
     * @return bool
     */
    public function mustRetry(Handler $handler)
    {
        return (bool) call_user_func($this->callback, $handler);
    }
}