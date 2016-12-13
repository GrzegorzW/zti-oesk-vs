<?php
declare(strict_types = 1);

use App\Manager\MemcachedManager;
use App\Manager\RedisManager;
use App\Printer\CSVPrinter;
use App\Result\TesterResultsHandler;
use App\Tester\MemoryTester;
use App\Tester\TimeTester;
use Symfony\Component\Stopwatch\Stopwatch;

require __DIR__ . '/vendor/autoload.php';

$host = 'localhost';
$memcachedPort = 11211;
$redisPort = 6379;
$resultDir = __DIR__ . '/result';

$managers = [new RedisManager($host, $redisPort), new MemcachedManager($host, $memcachedPort)];

$stopwatch = new Stopwatch();
$timeTester = new TimeTester($managers, $stopwatch);
$memoryTester = new MemoryTester($managers, $stopwatch);

$memoryTester->uniqueVisitorsCounterTest(1000000);

$resultHandler = new TesterResultsHandler(new CSVPrinter());
$resultHandler->printResults($timeTester, $resultDir);
