<?php

namespace TryAgain;

interface ValidatorInterface
{
    /**
     * @param TryAgain\Handler $handler
     * @return bool
     */
    public function mustRetry(Handler $handler);
}