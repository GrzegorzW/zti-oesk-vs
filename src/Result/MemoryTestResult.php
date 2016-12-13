<?php
declare(strict_types = 1);

namespace App\Result;

use App\Manager\KeyValueManagerInterface;

class MemoryTestResult extends TestResult
{
    /** @var int */
    protected $memoryUsageBefore;
    /** @var int */
    protected $memoryUsageAfter;

    public function __construct(
        int $iterations,
        KeyValueManagerInterface $manager,
        int $memoryUsageBefore,
        int $memoryUsageAfter
    ) {
        parent::__construct($iterations, $manager);
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
        return (int)abs($this->memoryUsageAfter - $this->memoryUsageBefore);
    }

    public function getPropertiesToPrint(): array
    {
        return array_merge(parent::getPropertiesToPrint(), ['memoryUsageBefore', 'memoryUsageAfter']);
    }
}
