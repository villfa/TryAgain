TryAgain
========

[![Build Status](https://secure.travis-ci.org/villfa/TryAgain.png?branch=master)](http://travis-ci.org/villfa/TryAgain)


Server Requirements
-------------------

- PHP version 5.3.3 or newer

Installation
------------

TryAgain may be installed using Composer.
You can read more about Composer and its main repository at
[http://packagist.org](http://packagist.org "Packagist"). To install
TryAgain using Composer, first install Composer for your project using the instructions on the
Packagist home page. You can then define your development dependency on TryAgain using the
suggested parameters below.

    {
        "require": {
            "villfa/tryagain": "dev-master"
        }
    }

To install, you then may call:

    $ composer.phar install

Tests
-----

To run the test suite, you need [composer](http://getcomposer.org) and
[PHPUnit](https://github.com/sebastianbergmann/phpunit).

    $ cd path/to/TryAgain
    $ composer.phar install --dev
    $ phpunit