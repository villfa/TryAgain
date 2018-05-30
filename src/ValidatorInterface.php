<?php

namespace TryAgain;

interface ValidatorInterface
{
    /**
     * @param  Handler $handler
     * @return bool
     */
    public function mustRetry(Handler $handler);
}
