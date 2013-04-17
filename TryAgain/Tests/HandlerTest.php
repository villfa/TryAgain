<?php

namespace TryAgain\Tests;

use TryAgain\Handler;
use \Mockery as m;

class HandlerTest extends \PHPUnit_Framework_TestCase
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
        $h->execute(function () {});
        $this->assertEquals(3, $h->getNbTries());
    }
}