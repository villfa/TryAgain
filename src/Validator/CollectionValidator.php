<?php

namespace TryAgain\Validator;

use TryAgain\ValidatorInterface;
use TryAgain\Handler;

class CollectionValidator extends \SplObjectStorage implements ValidatorInterface
{
    /**
     * @param ValidatorInterface[] $validators
     */
    public function __construct(array $validators = array())
    {
        foreach ($validators as $validator) {
            $this->attach($validator);
        }
    }

    /**
     * @param  TryAgain\Handler $handler
     * @return bool
     */
    public function mustRetry(Handler $handler)
    {
        $mustRetry = false;
        foreach ($this as $validator) {
            if ($mustRetry = $validator->mustRetry($handler)) {
                break;
            }
        }

        return (bool) $mustRetry;
    }
}
