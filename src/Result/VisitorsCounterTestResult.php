<?php
declare(strict_types = 1);

namespace App\Result;

use App\Manager\KeyValueManagerInterface;

class VisitorsCounterTestResult extends MemoryTestResult
{
    private $visitorsCount;

    public function __construct(
        int $iterations,
        int $visitorsCount,
        KeyValueManagerInterface $manager,
        int $memoryUsageBefore,
        int $memoryUsageAfter
    ) {
        parent::__construct($iterations, $manager, $memoryUsageBefore, $memoryUsageAfter);
        $this->visitorsCount = $visitorsCount;
    }

    /**
     * @return float
     */
    public function getVisitorsCountError(): float
    {
        return (float)abs(($this->getIterations() - $this->visitorsCount) / $this->getIterations());
    }

    public function getPropertiesToPrint(): array
    {
        return array_merge(parent::getPropertiesToPrint(), ['visitorsCount']);
    }
}
