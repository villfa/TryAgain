<?php

namespace TryAgain\Tests\Validator;

use TryAgain\Validator\CollectionValidator;
use \Mockery as m;

class CollectionValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementsInterface()
    {
        $this->assertInstanceOf(
            'TryAgain\ValidatorInterface',
            new CollectionValidator()
        );
    }

    public function testExtendsSplObjectStorage()
    {
        $this->assertInstanceOf(
            '\SplObjectStorage',
            new CollectionValidator()
        );
    }

    public function testReturnsFalseByDefault()
    {
        $validator = new CollectionValidator();
        $this->assertFalse($validator->mustRetry(m::mock('TryAgain\Handler')));
    }

    public function testReturnsSameResultThanHisSon()
    {
        $son = m::mock('TryAgain\ValidatorInterface');
        $son->shouldReceive('mustRetry')->times(2)->andReturn(true, false);
        $validator = new CollectionValidator(array($son));
        $this->assertTrue($validator->mustRetry(m::mock('TryAgain\Handler')));
        $this->assertFalse($validator->mustRetry(m::mock('TryAgain\Handler')));
    }

    public function testTrueIsStrongerThanFalse()
    {
        $son1 = m::mock('TryAgain\ValidatorInterface');
        $son1->shouldReceive('mustRetry')->andReturn(false, false);
        $son2 = m::mock('TryAgain\ValidatorInterface');
        $son2->shouldReceive('mustRetry')->times(2)->andReturn(false, true);
        $son3 = m::mock('TryAgain\ValidatorInterface');
        $son3->shouldReceive('mustRetry')->andReturn(false, false);
        $validator = new CollectionValidator(array($son1, $son2, $son3));
        $this->assertFalse($validator->mustRetry(m::mock('TryAgain\Handler')));
        $this->assertTrue($validator->mustRetry(m::mock('TryAgain\Handler')));
    }

    public function testSonsReceiveHandler()
    {
        $handler = m::mock('TryAgain\Handler');
        $handler->shouldReceive('getNbTries')->andReturn(0, 0, 1, 1);

        $son1 = m::mock('TryAgain\ValidatorInterface');
        $son1->shouldReceive('mustRetry')->andReturnUsing(
            function ($handler) {
                return $handler->getNbTries() > 0;
            }
        );

        $son2 = m::mock('TryAgain\ValidatorInterface');
        $son2->shouldReceive('mustRetry')->andReturnUsing(
            function ($handler) {
                return $handler->getNbTries() > 1;
            }
        );

        $validator = new CollectionValidator(array($son1, $son2));

        $this->assertFalse($validator->mustRetry($handler));
        $this->assertTrue($validator->mustRetry($handler));
    }

    public function testIsRecursive()
    {
        $son = m::mock('TryAgain\ValidatorInterface');
        $son->shouldReceive('mustRetry')->times(2)->andReturn(true, false);
        $validator = new CollectionValidator(
            array(
                new CollectionValidator(array($son))
            )
        );
        $this->assertTrue($validator->mustRetry(m::mock('TryAgain\Handler')));
        $this->assertFalse($validator->mustRetry(m::mock('TryAgain\Handler')));
    }
}