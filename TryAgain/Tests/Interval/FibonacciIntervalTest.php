<?php

namespace TryAgain\Tests\Interval;

use TryAgain\Interval\FibonacciInterval;
use \Mockery as m;

class FibonacciIntervalTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementsInterface()
    {
        $this->assertInstanceOf(
            'TryAgain\IntervalInterface',
            new FibonacciInterval()
        );
    }

    public function testFibonacciSuite()
    {
        $method = new \ReflectionMethod(
            'TryAgain\Interval\FibonacciInterval',
            'getFibonacciNumber'
        );
        $method->setAccessible(true);
        $interval = new FibonacciInterval();
        $this->assertEquals(0, $method->invoke($interval));
        $this->assertEquals(1, $method->invoke($interval));
        $this->assertEquals(1, $method->invoke($interval));
        $this->assertEquals(2, $method->invoke($interval));
        $this->assertEquals(3, $method->invoke($interval));
        $this->assertEquals(5, $method->invoke($interval));
        $this->assertEquals(8, $method->invoke($interval));
        $this->assertEquals(13, $method->invoke($interval));
        $this->assertEquals(21, $method->invoke($interval));
        $this->assertEquals(34, $method->invoke($interval));
    }

    public function testSleepOneSecond()
    {
        $handler = m::mock('TryAgain\Handler');
        $interval = new FibonacciInterval();
        $startTime = time();
        $interval->process($handler);
        $interval->process($handler);
        $this->assertGreaterThanOrEqual(1, time() - $startTime);
    }
}
