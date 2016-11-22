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
    }

    /**
     * @return string
     */
    protected function getRandomChars(): string
    {
        return hash('sha512', random_bytes(random_int(16, 128)) . random_int(PHP_INT_MIN, PHP_INT_MAX));
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
     * @param int $iterations
     * @throws \InvalidArgumentException
     */
    public function setIterations(int $iterations)
    {
        $this->checkIterations($iterations);

        $this->iterations = $iterations;
    }

    protected function getManagers(): array
    {
        return $this->managers;
    }

    protected function getIterations(): int
    {
        return $this->iterations;
    }

    /**
     * @param TestResult $result
     */
    public function addTestResult(TestResult $result)
    {
        $this->results[$result->getName()] = $result;
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
}
