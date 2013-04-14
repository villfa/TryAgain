<?php

namespace TryAgain\Tests\Validator;

use TryAgain\Validator\AnonymousValidator;
use \Mockery as m;

class AnonymousValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementsInterface()
    {
        $this->assertInstanceOf(
            'TryAgain\ValidatorInterface',
            new AnonymousValidator(
                function () {}
            )
        );
    }

    public function testCallbackReturnsTrue()
    {
        $validator = new AnonymousValidator(
            function () {
                return true;
            }
        );
        $handler = m::mock('TryAgain\Handler');
        $this->assertTrue($validator->mustRetry($handler));
    }

    public function testCallbackReturnsFalse()
    {
        $validator = new AnonymousValidator(
            function () {
                return false;
            }
        );
        $handler = m::mock('TryAgain\Handler');
        $this->assertFalse($validator->mustRetry($handler));
    }

    public function testCallbackReceivesHandler()
    {
        $validator = new AnonymousValidator(
            function ($handler) {
                return $handler->getNbTries() > 1;
            }
        );
        $handler = m::mock('TryAgain\Handler');
        $handler->shouldReceive('getNbTries')->times(2)->andReturn(0, 2);
        $this->assertFalse($validator->mustRetry($handler));
        $this->assertTrue($validator->mustRetry($handler));
    }
}