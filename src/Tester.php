<?php
declare(strict_types = 1);

namespace App;

use Symfony\Component\Stopwatch\Stopwatch;


class Tester
{
    /**
     * @var Stopwatch
     */
    private $stopwatch;

    /**
     * @var array
     */
    private $managers = [];

    /**
     * Number of iterations
     * @var integer
     */
    private $iterations;

    /**
     * @var array
     */
    private $results = [];

    /**
     * Tester constructor.
     * @param array $managers
     * @param $iterations
     * @throws \InvalidArgumentException
     */
    public function __construct(array $managers, $iterations)
    {
        foreach ($managers as $manager) {
            if (!$manager instanceof KeyValueManagerInterface) {
                throw new \InvalidArgumentException('Manager must be instance of: %s', KeyValueManagerInterface::class);
            }

            $this->managers[] = $manager;
        }

        $this->checkIterations($iterations);

        $this->iterations = $iterations;
        $this->stopwatch = new Stopwatch();
    }

    /**
     * @param int $iterations
     * @throws \InvalidArgumentException
     */
    private function checkIterations(int $iterations)
    {
        if ($iterations < 1) {
            throw new \InvalidArgumentException('Wrong number of iterations');
        }
    }

    public function setIterations(int $iterations)
    {
        $this->checkIterations($iterations);

        $this->iterations = $iterations;
    }

    /**
     * @throws \App\TestException
     * @throws \InvalidArgumentException
     */
    public function getValueTest(): array
    {
        $testName = 'getValue';

        foreach ($this->managers as $manager) {
            $key = $this->getRandomChars();
            $value = $this->getRandomChars();

            $manager->set($key, $value);

            $this->stopwatch->start($key);
            for ($i = 0; $i < $this->iterations; $i++) {
                $manager->get($key);
            }
            $this->stopwatch->stop($key);

            if ($manager->get($key) !== $value) {
                throw new TestException('Invalid test result.');
            }

            $this->results[$testName][] = new TestResult($testName, $this->iterations, $manager, $this->stopwatch->getEvent($key));

            $manager->delete($key);
        }

        return isset($this->results[$testName]) ? $this->results[$testName] : [];
    }

    /**
     * @return string
     */
    private function getRandomChars(): string
    {
        return hash('sha512', random_bytes(random_int(16, 128)) . random_int(PHP_INT_MIN, PHP_INT_MAX));
    }

    /**
     * @throws \App\TestException
     * @throws \InvalidArgumentException
     */
    public function incrementationTest(): array
    {
        $testName = 'incrementation';

        foreach ($this->managers as $manager) {
            $key = $this->getRandomChars();
            $manager->set($key, '0');

            $this->stopwatch->start($key);
            for ($i = 0; $i < $this->iterations; $i++) {
                $manager->increment($key);
            }
            $this->stopwatch->stop($key);

            if ((int)$manager->get($key) !== $this->iterations) {
                throw new TestException('Invalid test result.');
            }

            $this->results[$testName][] = new TestResult($testName, $this->iterations, $manager, $this->stopwatch->getEvent($key));

            $manager->delete($key);
        }

        return isset($this->results[$testName]) ? $this->results[$testName] : [];
    }
}
