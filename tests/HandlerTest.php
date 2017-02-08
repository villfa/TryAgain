<?php

namespace TryAgain\Tests;

use PHPUnit\Framework\TestCase;
use TryAgain\Handler;
use Mockery as m;

class HandlerTest extends TestCase
{
    public function testSimpliestCase()
    {
        $add = function ($a, $b) {
            return $a + $b;
        };

        $h = new Handler;
        $this->assertEquals(0, $h->getNbTries());
        $this->assertEquals(5, $h->execute($add, array(2, 3)));
        $this->assertEquals(5, $h->getLastResult());
        $this->assertEquals(1, $h->getNbTries());
        $this->assertNull($h->getLastException());
    }

    public function testThrowExceptionAfterFewTries()
    {
        $func = function () {
            throw new \RuntimeException();
        };

        $validator = m::mock('TryAgain\ValidatorInterface');
        $validator->shouldReceive('mustRetry')->andReturnUsing(
            function ($handler) {
                return $handler->getNbTries() < 3;
            }
        );

        $h = new Handler($validator);

        try {
            $h->execute($func);
        } catch (\Exception $exception) {
            $this->assertInstanceOf('\RuntimeException', $exception);
            $this->assertNull($h->getLastResult());
            $this->assertEquals(3, $h->getNbTries());

            return;
        }

        $this->fail('No exception thrown');
    }

    public function testForcedResult()
    {
        $validator = m::mock('TryAgain\ValidatorInterface');
        $validator->shouldReceive('mustRetry')->andReturnUsing(
            function ($handler) {
                $handler->setResult('FORCED_RESULT');

                return false;
            }
        );

        $func = function () {
            throw new \Exception();
        };

        $h = new Handler($validator);
        $this->assertEquals('FORCED_RESULT', $h->execute($func));
        $this->assertEquals('FORCED_RESULT', $h->getLastResult());
    }

    public function testIntervalIsCalled()
    {
        $validator = m::mock('TryAgain\ValidatorInterface');
        $validator->shouldReceive('mustRetry')->andReturn(true, true, false);

        $testcase = $this;

        $interval = m::mock('TryAgain\IntervalInterface');
        $interval->shouldReceive('process')->times(2)->andReturnUsing(
            function ($handler) use ($testcase) {
                $testcase->assertInstanceOf('TryAgain\Handler', $handler);
            }
        );

        $h = new Handler($validator, $interval);
        $h->execute(function () {
        });
        $this->assertEquals(3, $h->getNbTries());
    }

    public function testExecuteOverridesValidator()
    {
        $h = new Handler();
        $this->assertNull($h->validator);

        $validator = m::mock('TryAgain\ValidatorInterface');
        $validator->shouldReceive('mustRetry')->andReturn(false);

        $h->execute(function () {
        }, array(), $validator);

        $this->assertEquals($validator, $h->validator);
    }

    public function testExecuteOverridesInterval()
    {
        $h = new Handler();
        $this->assertNull($h->interval);

        $interval = m::mock('TryAgain\IntervalInterface');
        $interval->shouldReceive('process');

        $h->execute(function () {
        }, array(), null, $interval);

        $this->assertEquals($interval, $h->interval);
    }

    public function testSetValidatorIsChainable()
    {
        $h = new Handler();

        $this->assertEquals($h, $h->setValidator());
        $this->assertNull($h->validator);

        $validator = m::mock('TryAgain\ValidatorInterface');
        $this->assertEquals($h, $h->setValidator($validator));
        $this->assertEquals($validator, $h->validator);
    }

    public function testSetIntervalIsChainable()
    {
        $h = new Handler();

        $this->assertEquals($h, $h->setInterval());
        $this->assertNull($h->interval);

        $interval = m::mock('TryAgain\IntervalInterface');
        $this->assertEquals($h, $h->setInterval($interval));
        $this->assertEquals($interval, $h->interval);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionWithWrongCallback()
    {
        $h = new Handler();

        $h->setCallback('not a valid callback');
    }

    public function testSetCallbackIsChainable()
    {
        $h = new Handler();
        $callback = function () {
        };
        $this->assertEquals($h, $h->setCallback($callback));
        $this->assertEquals($callback, $h->getCallback());
    }

    public function testArgumentsAreInArray()
    {
        $h = new Handler();
        $this->assertEquals(array(), $h->getArguments());

        $array = array('foo' => 'bar');
        $this->assertEquals($h, $h->setArguments($array));
        $this->assertEquals($array, $h->getArguments());

        $string = 'not an array';
        $this->assertEquals($h, $h->setArguments($string));
        $this->assertEquals(array($string), $h->getArguments());
    }
}
