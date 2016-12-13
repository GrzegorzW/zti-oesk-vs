<?php
declare(strict_types = 1);

namespace App\Tester;

use App\Manager\KeyValueManagerInterface;
use App\Result\TestResult;

abstract class Tester
{
    /**
     * @var array
     */
    private $managers = [];

    /**
     * @var array
     */
    private $results = [];

    /**
     * Tester constructor.
     * @param array $managers
     * @throws \InvalidArgumentException
     */
    public function __construct(array $managers)
    {
        foreach ($managers as $manager) {
            if (!$manager instanceof KeyValueManagerInterface) {
                throw new \InvalidArgumentException('Manager must be instance of: %s', KeyValueManagerInterface::class);
            }

            $this->managers[] = $manager;
        }
    }

    /**
     * @param int $iterations
     * @throws \InvalidArgumentException
     */
    protected function checkIterations(int $iterations)
    {
        if ($iterations < 1) {
            throw new \InvalidArgumentException('Wrong number of iterations');
        }
    }

    /**
     * @param string $testName
     * @param TestResult $result
     */
    public function addTestResult(string $testName, TestResult $result)
    {
        $this->results[$testName][] = $result;
    }

    /**
     * @param string $testName
     * @return array
     */
    public function getTestResults(string $testName): array
    {
        return isset($this->results[$testName]) ? $this->results[$testName] : [];
    }

    /**
     * @return array
     */
    public function getAllTestsResults(): array
    {
        return $this->results;
    }

    /**
     * @return string
     */
    protected function getRandomChars(): string
    {
        return hash('sha512', random_bytes(random_int(16, 128)) . random_int(PHP_INT_MIN, PHP_INT_MAX));
    }

    protected function getManagers(): array
    {
        return $this->managers;
    }
}
