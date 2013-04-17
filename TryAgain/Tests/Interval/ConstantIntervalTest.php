<?php

namespace TryAgain\Tests\Interval;

use TryAgain\Interval\ConstantInterval;
use \Mockery as m;

class ConstantIntervalTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementsInterface()
    {
        $this->assertInstanceOf(
            'TryAgain\IntervalInterface',
            new ConstantInterval()
        );
    }

    public function testErrorWithWrongDelay()
    {
        $this->setExpectedException('\InvalidArgumentException');
        new ConstantInterval('not a valid delay');
    }

    public function testValidCase()
    {
        $interval = new ConstantInterval(0.2);
        $startTime = microtime(true);
        $interval->process(m::mock('TryAgain\Handler'));
        $this->assertGreaterThanOrEqual(0.2, microtime(true) - $startTime);
    }
}
