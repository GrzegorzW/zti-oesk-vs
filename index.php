<?php
declare(strict_types = 1);

use App\Manager\MemcachedManager;
use App\Manager\RedisManager;
use App\Tester\MemoryTester;

require __DIR__ . '/vendor/autoload.php';


$host = 'localhost';
$memcachedPort = 11211;
$redisPort = 6379;

$managers = [new RedisManager($host, $redisPort), new MemcachedManager($host, $memcachedPort)];

$tester = new MemoryTester($managers, 1000);

$tester->uniqueVisitorsCounterTest();


var_dump($tester->getAllTestsResults());exit;

/** @var \App\Result\VisitorsCounterTestResult $item */
foreach ($tester->getAllTestsResults() as $item) {
    var_dump($item->getStorageName());
    var_dump($item->getDiff());
    var_dump($item->getVisitorsCountError());
}