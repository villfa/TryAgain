<?php

namespace TryAgain\Interval;

use TryAgain\IntervalInterface;
use TryAgain\Handler;

class AnonymousInterval implements IntervalInterface
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
     * @param Handler $handler
     */
    public function process(Handler $handler)
    {
        call_user_func($this->callback, $handler);
    }
}
