<?php
declare(strict_types = 1);

use App\MemcachedManager;
use App\RedisManager;
use App\Tester;

require __DIR__ . '/vendor/autoload.php';


$host = 'localhost';
$memcachedPort = 11211;
$redisPort = 6379;

$managers = [new RedisManager($host, $redisPort), new MemcachedManager($host, $memcachedPort)];

$tester = new Tester($managers, 1000000);

$result1 = $tester->getValueTest();
$result2 = $tester->incrementationTest();

var_dump($result1);
var_dump($result2);
