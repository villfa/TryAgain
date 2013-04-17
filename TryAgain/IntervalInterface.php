<?php

namespace TryAgain;

interface IntervalInterface
{
    /**
     * @param TryAgain\Handler $handler
     */
    public function process(Handler $handler);
}
