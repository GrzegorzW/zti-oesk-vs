<?php
declare(strict_types = 1);

namespace App;

use App\Manager\KeyValueManagerInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use App\Result\TimeTestResult;


class TimeTester extends Tester
{
    /**
     * @var Stopwatch
     */
    private $stopwatch;

    /**
     * Tester constructor.
     * @param array $managers
     * @param $iterations
     * @throws \InvalidArgumentException
     */
    public function __construct(array $managers, $iterations)
    {
        parent::__construct($managers, $iterations);
        $this->stopwatch = new Stopwatch();
    }

    /**
     * @param string $testName
     * @return array
     * @throws TestException
     */
    public function getValueTest($testName = 'getValue'): array
    {
        $testingKey = $this->getRandomChars();
        $testingValue = $this->getRandomChars();

        /** @var KeyValueManagerInterface $manager */
        foreach ($this->getManagers() as $manager) {
            $manager->set($testingKey, $testingValue);

            $stopwatchName = $this->getRandomChars();
            $this->stopwatch->start($stopwatchName);
            for ($i = 0; $i < $this->getIterations(); $i++) {
                $manager->get($testingKey);
            }
            $this->stopwatch->stop($stopwatchName);

            if ($manager->get($testingKey) !== $testingValue) {
                throw new TestException('Invalid test result.');
            }

            $result = new TimeTestResult($testName, $this->getIterations(), $manager, $this->stopwatch->getEvent($stopwatchName));
            $this->addTestResult($result);

            $manager->delete($testingKey);
        }

        return $this->getTestResults($testName);
    }

    /**
     * @param string $testName
     * @return array
     * @throws TestException
     */
    public function incrementationTest($testName = 'incrementation'): array
    {
        $testingKey = $this->getRandomChars();

        /** @var KeyValueManagerInterface $manager */
        foreach ($this->getManagers() as $manager) {
            $manager->set($testingKey, '0');

            $stopwatchName = $this->getRandomChars();
            $this->stopwatch->start($stopwatchName);
            for ($i = 0; $i < $this->getIterations(); $i++) {
                $manager->increment($testingKey);
            }
            $this->stopwatch->stop($stopwatchName);

            if ((int)$manager->get($testingKey) !== $this->getIterations()) {
                throw new TestException('Invalid test result.');
            }

            $result = new TimeTestResult($testName, $this->getIterations(), $manager, $this->stopwatch->getEvent($stopwatchName));
            $this->addTestResult($result);

            $manager->delete($testingKey);
        }

        return $this->getTestResults($testName);
    }

    /**
     * @param int $keysAmount
     * @param string $testName
     * @return array
     * @throws \InvalidArgumentException
     * @throws TestException
     */
    public function getMultiTest(int $keysAmount, $testName = 'multiGet'): array
    {
        if ($keysAmount < 1) {
            throw new \InvalidArgumentException('Keys amount cannot be lest than 1.');
        }

        $items = [];
        for ($i = 0; $i < $keysAmount; $i++) {
            $items[$this->getRandomChars()] = $this->getRandomChars();
        }

        /** @var KeyValueManagerInterface $manager */
        foreach ($this->getManagers() as $manager) {
            $manager->setMulti($items);
            $keys = array_keys($items);

            $stopwatchName = $this->getRandomChars();
            $this->stopwatch->start($stopwatchName);
            for ($i = 0; $i < $this->getIterations(); $i++) {
                $manager->getMulti($keys);
            }
            $this->stopwatch->stop($stopwatchName);

            if (array_values($manager->getMulti($keys)) !== array_values($items)) {
                throw new TestException('Invalid test result.');
            }

            $result = new TimeTestResult($testName, $this->getIterations(), $manager, $this->stopwatch->getEvent($stopwatchName));
            $this->addTestResult($result);

            $manager->deleteMulti($items);
        }

        return $this->getTestResults($testName);
    }
}
