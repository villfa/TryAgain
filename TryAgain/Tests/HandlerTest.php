<?php

namespace TryAgain\Tests;

use TryAgain\Handler;

class HandlerTest extends \PHPUnit_Framework_TestCase
{
    private function getHandler()
    {
        return new Handler();
    }

    public function testSimpliestCase()
    {
        $add = function ($a, $b) {
            return $a + $b;
        };

        $h = $this->getHandler();
        $this->assertEquals(5, $h->execute($add, array(2, 3)));
        $this->assertEquals(5, $h->getLastResult());
        $this->assertEquals(1, $h->getNbTries());
        $this->assertNull($h->getLastException());
    }
}