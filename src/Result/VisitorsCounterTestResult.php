<?php
declare(strict_types = 1);

namespace App\Result;

use App\Manager\KeyValueManagerInterface;


class VisitorsCounterTestResult extends MemoryTestResult
{
    private $visitorsCount;

    public function __construct(int $iterations,
                                KeyValueManagerInterface $manager,
                                int $memoryUsageBefore,
                                int $memoryUsageAfter
    )
    {
        parent::__construct($iterations, $manager, $memoryUsageBefore, $memoryUsageAfter);
        $this->visitorsCount = $manager->countUniqueVisitors();
    }

    /**
     * @return float
     */
    public function getVisitorsCountError(): float
    {
        return (($this->getIterations() - $this->visitorsCount) / $this->getIterations()) * 100;
    }
}
