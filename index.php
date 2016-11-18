<?php
declare(strict_types = 1);

use App\MemcachedManager;
use App\RedisManager;
use App\Tester;

require __DIR__ . '/vendor/autoload.php';


$host = 'localhost';
$memcachedPort = 11211;
$redisPort = 6379;

$memcachedManager = new MemcachedManager($host, $memcachedPort);
$redisManager = new RedisManager($host, $redisPort);

$memcachedTester = new Tester($memcachedManager);
$redisTester = new Tester($redisManager);

$iterations = 1000000;

var_dump('GET:');
$memcachedResult = $memcachedTester->getValueTest($iterations);
var_dump($memcachedResult->getDuration());
$redisResult = $redisTester->getValueTest($iterations);
var_dump($redisResult->getDuration());

$diff = $memcachedResult->getDuration() - $redisResult->getDuration();

if ($diff === 0) {
    $result = 'REMIS';
} else if ($diff < 0) {
    $result = 'MEMECACHED WYGRAŁ';
} else {
    $result = 'REDIS WYGRAŁ';
}

var_dump($result);

//=================================

var_dump('INCREMENTATION:');
$memcachedResult = $memcachedTester->incrementationTest($iterations);
var_dump($memcachedResult->getDuration());
$redisResult = $redisTester->incrementationTest($iterations);
var_dump($redisResult->getDuration());

$diff = $memcachedResult->getDuration() - $redisResult->getDuration();

if ($diff === 0) {
    $result = 'REMIS';
} else if ($diff < 0) {
    $result = 'MEMECACHED WYGRAŁ';
} else {
    $result = 'REDIS WYGRAŁ';
}

var_dump($result);
