<?php

namespace TryAgain;

interface IntervalInterface
{
    /**
     * @param Handler $handler
     */
    public function process(Handler $handler);
}
