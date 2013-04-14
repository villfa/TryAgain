<?php

namespace TryAgain\Tests\Interval;

use TryAgain\Interval\FibonacciInterval;

class FibonacciIntervalTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementsInterface()
    {
        $this->assertInstanceOf(
            'TryAgain\IntervalInterface',
            new FibonacciInterval()
        );
    }
}