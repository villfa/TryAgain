<?php

namespace TryAgain\Interval;

use TryAgain\IntervalInterface;
use TryAgain\Handler;

class ConstantInterval implements IntervalInterface
{
    /** @var float */
    protected $delay;

    /**
     * @param float $delay
     */
    public function __construct($delay = 0)
    {
        if (!is_numeric($delay) || $delay < 0) {
            throw new \InvalidArgumentException('delay must be a number greater than or equal to zero');
        }

        $this->delay = (float) $delay;
    }

    /**
     * Make the script sleep during a given delay
     *
     * @param TryAgain\Handler $handler
     */
    public function process(Handler $handler)
    {
        if ($this->delay > 0) {
            time_sleep_until(microtime(true) + $this->delay);
        }
    }
}
