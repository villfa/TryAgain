<?php

namespace TryAgain\Tests\Interval;

use TryAgain\Interval\AnonymousInterval;
use \Mockery as m;

class AnonymousIntervalTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementsInterface()
    {
        $this->assertInstanceOf(
            'TryAgain\IntervalInterface',
            new AnonymousInterval(
                function () {}
            )
        );
    }

    public function testCallbackIsCalled()
    {
        $test = (object) array('called' => false);
        $interval = new AnonymousInterval(
            function () use ($test) {
                $test->called = true;
            }
        );
        $handler = m::mock('TryAgain\Handler');
        $interval->process($handler);
        $this->assertTrue($test->called);
    }
}
