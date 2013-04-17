<?php

namespace TryAgain\Interval;

use TryAgain\IntervalInterface;
use TryAgain\Handler;

class FibonacciInterval implements IntervalInterface
{
    /** @var int */
    protected $delay = 0;

    /** @var int */
    protected $previousDelay = 0;

    /**
     * Make the script sleep during a given delay
     *
     * @param TryAgain\Handler $handler
     */
    public function process(Handler $handler)
    {
        $delay = $this->getFibonacciNumber();

        if ($delay > 0) {
            time_sleep_until(microtime(true) + $delay);
        }
    }

    /**
     * Return the Fibonacci suite number by number
     *
     * @return int
     */
    protected function getFibonacciNumber()
    {
        $delay = $this->delay;

        if ($this->delay > 0) {
            list($this->delay, $this->previousDelay) = array(
                $this->previousDelay + $this->delay,
                $this->delay
            );
        } else {
            $this->delay = 1;
        }

        return $delay;
    }
}
