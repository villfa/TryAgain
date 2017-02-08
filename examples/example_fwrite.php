<?php

namespace TryAgain\Examples;

use TryAgain\Handler;
use TryAgain\ValidatorInterface;
use TryAgain\Interval\AnonymousInterval;

require_once __DIR__.'/../vendor/autoload.php';

class FWriteValidator implements ValidatorInterface
{
    private $written = 0;
    private $nbErrors = 0;

    public function mustRetry(Handler $handler)
    {
        $mustRetry = false;
        $written = $handler->getLastResult();
        list($fh, $text) = $handler->getArguments();
        
        if ($written === false) {
            // we try 3 times before abandoning
            $mustRetry = ++$this->nbErrors <= 3;
        } else {
            $this->written += $written;
            if ($written !== strlen($text)) {
                $mustRetry = true;
                $handler->setArguments(array($fh, substr($text, $written)));
            } else {
                // we force the result with the total written length
                $handler->setResult($this->written);
            }
        }
        
        return $mustRetry;
    }
}

$handler = new Handler(
    new FWriteValidator,
    new AnonymousInterval(
        function ($handler) {
            if (false === $handler->getLastResult()) {
                // if there is an error we wait 1 second before trying again
                sleep(1);
            }
        }
    )
);

$file = __DIR__.'/../build/logs/fwrite_example_file.txt';
$text = 'Here the text I want to write in my file.';

$fh = fopen($file, 'w+');
$isWritten = false !== $handler->execute('fwrite', array($fh, $text));
fclose($fh);

echo $isWritten ? 'OK' : 'KO';
