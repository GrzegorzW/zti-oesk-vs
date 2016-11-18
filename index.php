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

$iterations = 100000;

printf("ITERATIONS: %d\n", $iterations);

echo "INCREMENTATION:\n";
$memcachedResult = $memcachedTester->incrementationTest($iterations);
echo $memcachedResult->getDuration() . "\n";
$redisResult = $redisTester->incrementationTest($iterations);
echo $redisResult->getDuration() . "\n";

$diff = $memcachedResult->getDuration() - $redisResult->getDuration();

if ($diff === 0) {
    $result = 'REMIS';
} else if ($diff < 0) {
    $result = 'MEMECACHED WYGRAŁ';
} else {
    $result = 'REDIS WYGRAŁ';
}

echo $result . "\n";



echo "GET: \n";
$memcachedResult = $memcachedTester->getValueTest($iterations);
echo $memcachedResult->getDuration() . "\n";
$redisResult = $redisTester->getValueTest($iterations);
echo $redisResult->getDuration() . "\n";

$diff = $memcachedResult->getDuration() - $redisResult->getDuration();

if ($diff === 0) {
    $result = 'REMIS';
} else if ($diff < 0) {
    $result = 'MEMECACHED WYGRAŁ';
} else {
    $result = 'REDIS WYGRAŁ';
}

echo $result . "\n";

//=================================

echo "INCREMENTATION:\n";
$memcachedResult = $memcachedTester->incrementationTest($iterations);
echo $memcachedResult->getDuration() . "\n";
$redisResult = $redisTester->incrementationTest($iterations);
echo $redisResult->getDuration() . "\n";

$diff = $memcachedResult->getDuration() - $redisResult->getDuration();

if ($diff === 0) {
    $result = 'REMIS';
} else if ($diff < 0) {
    $result = 'MEMECACHED WYGRAŁ';
} else {
    $result = 'REDIS WYGRAŁ';
}

echo $result . "\n";
