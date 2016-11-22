<?php
declare(strict_types = 1);

namespace App\Result;

use App\Manager\KeyValueManagerInterface;


class VisitorsCountResult extends MemoryTestResult
{
    private $visitorsCount;

    public function __construct(string $name,
                                int $iterations,
                                KeyValueManagerInterface $manager,
                                int $visitorsCount,
                                int $memoryUsageBefore,
                                int $memoryUsageAfter
    )
    {

        parent::__construct($name, $iterations, $manager, $memoryUsageBefore, $memoryUsageAfter);
        $this->visitorsCount = $visitorsCount;
    }

    /**
     * @return float
     */
    public function getVisitorsCountError(): float
    {
        return ($this->getIterations() - $this->visitorsCount) / $this->getIterations();
    }
}
