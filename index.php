<?php
declare(strict_types = 1);

use App\Manager\MemcachedManager;
use App\Manager\RedisManager;
use App\Tester\TimeTester;

require __DIR__ . '/vendor/autoload.php';


$host = 'localhost';
$memcachedPort = 11211;
$redisPort = 6379;

$managers = [new RedisManager($host, $redisPort), new MemcachedManager($host, $memcachedPort)];

$tester = new TimeTester($managers, 100000);

$tester->getRandomValuesTest('randomValues');

/** @var \App\Result\TimeTestResult $item */
foreach ($tester->getTestResults('randomValues') as $item) {
    var_dump($item);
}