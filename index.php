<?php
declare(strict_types = 1);

use App\Manager\MemcachedManager;
use App\Manager\RedisManager;
use App\MemoryTester;

require __DIR__ . '/vendor/autoload.php';


$host = 'localhost';
$memcachedPort = 11211;
$redisPort = 6379;

$managers = [new RedisManager($host, $redisPort), new MemcachedManager($host, $memcachedPort)];

$tester = new MemoryTester($managers, 100000);

//$managers[0]->getUsedMemory();


//$result1 = $tester->getValueTest();
//$result2 = $tester->incrementationTest();
//$result2 = $tester->getMultiTest(1);

$tester->uniqueVisitorsCounterTest();

var_dump($tester->getAllTestsResults());

//var_dump($result1);
//var_dump($result2);
