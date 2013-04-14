<?php

namespace TryAgain\Tests\Interval;

use TryAgain\Interval\ConstantInterval;

class ConstantIntervalTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementsInterface()
    {
        $this->assertInstanceOf(
            'TryAgain\IntervalInterface',
            new ConstantInterval()
        );
    }
}