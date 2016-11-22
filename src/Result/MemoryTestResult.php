<?php
declare(strict_types = 1);

namespace App\Result;

use App\Manager\KeyValueManagerInterface;


class MemoryTestResult extends TestResult
{
    /** @var int */
    private $memoryUsageBefore;
    /** @var int */
    private $memoryUsageAfter;

    public function __construct(string $name,
                                int $iterations,
                                KeyValueManagerInterface $manager,
                                int $memoryUsageBefore,
                                int $memoryUsageAfter
    )
    {
        parent::__construct($name, $iterations, $manager);
        $this->memoryUsageBefore = $memoryUsageBefore;
        $this->memoryUsageAfter = $memoryUsageAfter;
    }

    /**
     * @return int
     */
    public function getMemoryUsageBefore(): int
    {
        return $this->memoryUsageBefore;
    }

    /**
     * @return int
     */
    public function getMemoryUsageAfter(): int
    {
        return $this->memoryUsageAfter;
    }

    /**
     * @return int
     */
    public function getDiff(): int
    {
        return $this->memoryUsageAfter - $this->memoryUsageBefore;
    }
}
