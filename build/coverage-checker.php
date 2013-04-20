<?php

/**
 * Code from: https://github.com/RWOverdijk/AssetManager/blob/master/coverage-checker.php
 *
 * Copyright (c) 2012, Wesley Overdijk <r.w.overdijk@gmail.com>
 * All rights reserved.
 */

$inputFile = $argv[1];
$percentage = min(100, max(0, (int) $argv[2]));

if (!file_exists($inputFile)) {
    throw new InvalidArgumentException('Invalid input file provided: '.$inputFile);
}

if (!$percentage) {
    throw new InvalidArgumentException('An integer checked percentage must be given as second parameter');
}

$xml = new SimpleXMLElement(file_get_contents($inputFile));

/* @var $classes SimpleXMLElement[] */
$classes = $xml->xpath('//class');

foreach ($classes as $class) {
    if (!isset($class->metrics) || !isset($class->metrics[0])) {
        continue;
    }
    $metric = $class->metrics[0];
    if (intval($metric['elements']) == intval($metric['coveredelements'])) {
        continue;
    }
    $classname = $class['name'];
    if (isset($class['namespace'])) {
        $classname = $class['namespace'].'\\'.$classname;
    }
    $coverage = round(($metric['coveredelements'] / $metric['elements']) * 100);
    echo sprintf('Coverage of class %s is %s%%', $classname, $coverage).PHP_EOL;
}

/* @var $metrics SimpleXMLElement[] */
$metrics = $xml->xpath('//metrics');

$totalElements = 0;
$checkedElements = 0;

foreach ($metrics as $metric) {
    $totalElements   += (int) $metric['elements'];
    $checkedElements += (int) $metric['coveredelements'];
}

$coverage = round(($checkedElements / $totalElements) * 100);

if ($coverage < $percentage) {
    echo 'Code coverage is ' . $coverage . '%, which is below the accepted ' . $percentage . '%' . PHP_EOL;
    exit(1);
}

echo 'Code coverage is ' . $coverage . '% - OK!' . PHP_EOL;
