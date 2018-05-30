<?php

namespace TryAgain;

class Handler
{
    /** @var null|ValidatorInterface */
    public $validator;

    /** @var null|IntervalInterface */
    public $interval;

    /** @var callable */
    protected $callback;

    /** @var array */
    protected $arguments = array();

    /** @var int */
    protected $nbTries = 0;

    /** @var mixed */
    protected $result;

    /** @var null|\Exception */
    protected $exception;

    /**
     * @param ValidatorInterface $validator
     * @param IntervalInterface  $interval
     */
    public function __construct(
        ValidatorInterface $validator = null,
        IntervalInterface $interval = null
    ) {
        $this->validator = $validator;
        $this->interval = $interval;
    }

    /**
     * @param  callable           $callback
     * @param  mixed              $arguments
     * @param  ValidatorInterface $validator
     * @param  IntervalInterface  $interval
     * @return mixed
     */
    public function execute(
        $callback,
        $arguments = array(),
        ValidatorInterface $validator = null,
        IntervalInterface $interval = null
    ) {
        $this->nbTries = 0;
        $this->setCallback($callback);
        $this->setArguments($arguments);
        if ($validator !== null) {
            $this->validator = $validator;
        }
        if ($interval !== null) {
            $this->interval = $interval;
        }

        do {
            $this->result = null;
            $this->exception = null;
            $this->nbTries++;

            try {
                $this->result = call_user_func_array($this->callback, $this->arguments);
            } catch (\Exception $exception) {
                $this->exception = $exception;
            }

            if ($this->validator === null || !$this->validator->mustRetry($this)) {
                break;
            }
            if ($this->interval !== null) {
                $this->interval->process($this);
            }
        } while (true);

        if ($this->exception !== null) {
            throw $this->exception;
        }

        return $this->result;
    }

    /**
     * @return int
     */
    public function getNbTries()
    {
        return $this->nbTries;
    }

    /**
     * @param  mixed   $result
     * @return Handler
     */
    public function setResult($result)
    {
        $this->result = $result;
        $this->exception = null;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastResult()
    {
        return $this->result;
    }

    /**
     * @return null|\Exception
     */
    public function getLastException()
    {
        return $this->exception;
    }

    /**
     * Set the validator to use
     * Can be handy for method chaining
     *
     * @param  ValidatorInterface $validator
     * @return Handler
     */
    public function setValidator(ValidatorInterface $validator = null)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Set the interval to use
     * Can be handy for method chaining
     *
     * @param  IntervalInterface $interval
     * @return Handler
     */
    public function setInterval(IntervalInterface $interval = null)
    {
        $this->interval = $interval;

        return $this;
    }

    /**
     * @param  callable $callback
     * @return Handler
     */
    public function setCallback($callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'First parameter of %s must be callable instead of %s', __METHOD__,
                    gettype($callback)
                )
            );
        }

        $this->callback = $callback;

        return $this;
    }

    /**
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param  mixed   $arguments
     * @return Handler
     */
    public function setArguments($arguments)
    {
        if (!is_array($arguments)) {
            $arguments = array($arguments);
        }

        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }
}
