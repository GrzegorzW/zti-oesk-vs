<?php
declare(strict_types = 1);

namespace App;

use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;


class Tester
{
    /** @var KeyValueManagerInterface */
    private $keyValueManager;
    /** @var string */
    private $keyValueManagerName;
    /** @var Stopwatch */
    private $stopwatch;

    /**
     * Tester constructor.
     * @param KeyValueManagerInterface $keyValueManager
     */
    public function __construct(KeyValueManagerInterface $keyValueManager)
    {
        $this->keyValueManager = $keyValueManager;
        $this->stopwatch = new Stopwatch();

        $reflection = new \ReflectionClass($this->keyValueManager);
        $this->keyValueManagerName = $reflection->getName();
    }

    /**
     * @param int $iterations
     * @return StopwatchEvent
     * @throws \App\TestException
     * @throws \InvalidArgumentException
     */
    public function getValueTest(int $iterations): StopwatchEvent
    {
        $this->checkIterations($iterations);

        echo $this->keyValueManagerName . "\n";

        $key = $this->getRandomChars();
        $value = $this->getRandomChars();

        $this->keyValueManager->set($key, $value);

        $this->stopwatch->start($key);
        for ($i = 0; $i < $iterations; $i++) {
            $this->keyValueManager->get($key);
        }
        $this->stopwatch->stop($key);

        if ($this->keyValueManager->get($key) !== $value) {
            throw new TestException('Invalid test result.');
        }

        $this->keyValueManager->delete($key);

        return $this->stopwatch->getEvent($key);
    }

    /**
     * @param int $iterations
     * @return StopwatchEvent
     * @throws \App\TestException
     * @throws \InvalidArgumentException
     */
    public function incrementationTest(int $iterations): StopwatchEvent
    {
        $this->checkIterations($iterations);

        echo $this->keyValueManagerName . "\n";

        $key = $this->getRandomChars();
        $this->keyValueManager->set($key, '0');

        $this->stopwatch->start($key);
        for ($i = 0; $i < $iterations; $i++) {
            $this->keyValueManager->increment($key);
        }
        $this->stopwatch->stop($key);

        if ((int)$this->keyValueManager->get($key) !== $iterations) {
            throw new TestException('Invalid test result.');
        }

        $this->keyValueManager->delete($key);

        return $this->stopwatch->getEvent($key);
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

    /**
     * @return string
     */
    private function getRandomChars(): string
    {
        return hash('sha512', random_bytes(random_int(16, 128)) . random_int(PHP_INT_MIN, PHP_INT_MAX));
    }
}
