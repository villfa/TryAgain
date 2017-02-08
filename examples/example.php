<?php
/**
 * This example shows how it can handle exceptions.
 */

namespace TryAgain\Examples;

use TryAgain\Handler;
use TryAgain\Validator\AnonymousValidator;

require_once __DIR__.'/../vendor/autoload.php';

function divide($a, $b)
{
    if ($b == 0) {
        throw new \InvalidArgumentException('Cannot divide by zero');
    }
    return $a/$b;
}

$handler = new Handler(
    new AnonymousValidator(
        function ($handler) {
            if ($handler->getLastException() instanceof \InvalidArgumentException) {
                $handler->setResult($handler->getLastException()->getMessage());
            }
            
            return false;
        }
    )
);

header("Content-Type: text/plain");

$a = 6;
for ($b = -3; $b <= 3; $b++) {
    echo sprintf(
        '%d / %d = %s',
        $a,
        $b,
        $handler->execute('TryAgain\Examples\divide', array($a, $b))
    ),
    PHP_EOL;
}
