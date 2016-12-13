<?php
declare(strict_types = 1);

namespace App\Tester;

use App\Manager\KeyValueManagerInterface;
use App\Result\TimeTestResult;
use Symfony\Component\Stopwatch\Stopwatch;

class TimeTester extends Tester
{
    /**
     * @var Stopwatch
     */
    private $stopwatch;

    /**
     * Tester constructor.
     * @param array $managers
     * @param Stopwatch $stopwatch
     * @throws \InvalidArgumentException
     */
    public function __construct(array $managers, Stopwatch $stopwatch)
    {
        parent::__construct($managers);
        $this->stopwatch = $stopwatch;
    }

    /**
     * @param int $iterations
     * @param string $testName
     * @return array
     * @throws \InvalidArgumentException
     * @throws TestException
     */
    public function getValueTest(int $iterations, $testName = 'getValue'): array
    {
        $this->checkIterations($iterations);
        $testingKey = $this->getRandomChars();
        $testingValue = $this->getRandomChars();

        /** @var KeyValueManagerInterface $manager */
        foreach ($this->getManagers() as $manager) {
            $manager->flush();
            $manager->set($testingKey, $testingValue);

            $stopwatchName = $this->getRandomChars();
            $this->stopwatch->start($stopwatchName);
            for ($i = 0; $i < $iterations; $i++) {
                $manager->get($testingKey);
            }
            $this->stopwatch->stop($stopwatchName);

            if ($manager->get($testingKey) !== $testingValue) {
                throw new TestException('Invalid test result.');
            }

            $result = new TimeTestResult($iterations, $manager, $this->stopwatch->getEvent($stopwatchName));
            $this->addTestResult($testName, $result);

            $manager->flush();
        }

        return $this->getTestResults($testName);
    }

    /**
     * @param int $iterations
     * @param string $testName
     * @return array
     * @throws \InvalidArgumentException
     * @throws TestException
     */
    public function incrementationTest(int $iterations, $testName = 'incrementation'): array
    {
        $this->checkIterations($iterations);
        $testingKey = $this->getRandomChars();

        /** @var KeyValueManagerInterface $manager */
        foreach ($this->getManagers() as $manager) {
            $manager->flush();
            $manager->set($testingKey, '0');

            $stopwatchName = $this->getRandomChars();
            $this->stopwatch->start($stopwatchName);
            for ($i = 0; $i < $iterations; $i++) {
                $manager->increment($testingKey);
            }
            $this->stopwatch->stop($stopwatchName);

            if ((int)$manager->get($testingKey) !== $iterations) {
                throw new TestException('Invalid test result.');
            }

            $result = new TimeTestResult($iterations, $manager, $this->stopwatch->getEvent($stopwatchName));
            $this->addTestResult($testName, $result);

            $manager->flush();
        }

        return $this->getTestResults($testName);
    }

    /**
     * @param int $iterations
     * @param int $keysAmount
     * @param string $testName
     * @return array
     * @throws \InvalidArgumentException
     * @throws TestException
     */
    public function getMultiTest(int $iterations, int $keysAmount, $testName = 'multiGet'): array
    {
        $this->checkIterations($iterations);

        if ($keysAmount < 1) {
            throw new \InvalidArgumentException('Keys amount cannot be lest than 1.');
        }

        $items = [];
        for ($i = 0; $i < $keysAmount; $i++) {
            $items[$this->getRandomChars()] = $this->getRandomChars();
        }

        /** @var KeyValueManagerInterface $manager */
        foreach ($this->getManagers() as $manager) {
            $manager->flush();
            $manager->setMulti($items);
            $keys = array_keys($items);

            $stopwatchName = $this->getRandomChars();
            $this->stopwatch->start($stopwatchName);
            for ($i = 0; $i < $iterations; $i++) {
                $manager->getMulti($keys);
            }
            $this->stopwatch->stop($stopwatchName);

            if (array_values($manager->getMulti($keys)) !== array_values($items)) {
                throw new TestException('Invalid test result.');
            }

            $result = new TimeTestResult($iterations, $manager, $this->stopwatch->getEvent($stopwatchName));
            $this->addTestResult($testName, $result);

            $manager->flush();
        }

        return $this->getTestResults($testName);
    }

    /**
     * @param int $iterations
     * @param string $testName
     * @return array
     * @throws \InvalidArgumentException
     * @throws TestException
     */
    public function getRandomValuesTest(int $iterations, $testName = 'getRandomValues'): array
    {
        $this->checkIterations($iterations);

        $testingArray = [];
        for ($i = 0; $i < $iterations; $i++) {
            $testingArray[$this->getRandomChars()] = $this->getRandomChars();
        }

        /** @var KeyValueManagerInterface $manager */
        foreach ($this->getManagers() as $manager) {
            $manager->flush();
            $manager->setMulti($testingArray);

            $stopwatchName = $this->getRandomChars();
            $this->stopwatch->start($stopwatchName);
            foreach ($testingArray as $key => $value) {
                if ($manager->get($key) !== $value) {
                    throw new TestException('Invalid result');
                }
            }
            $this->stopwatch->stop($stopwatchName);

            $result = new TimeTestResult($iterations, $manager, $this->stopwatch->getEvent($stopwatchName));
            $this->addTestResult($testName, $result);

            $manager->flush();
        }

        return $this->getTestResults($testName);
    }
}
