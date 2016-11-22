<?php
declare(strict_types = 1);

namespace App\Tester;

use App\Manager\KeyValueManagerInterface;
use App\Result\VisitorsCountResult;


class MemoryTester extends Tester
{
    /**
     * @param string $testName
     * @return array
     */
    public function uniqueVisitorsCounterTest($testName = 'uniqueVisitorsCounter'): array
    {
        /** @var KeyValueManagerInterface $manager */
        foreach ($this->getManagers() as $manager) {
            $manager->flush();
            $memoryUsageBefore = $manager->getUsedMemory();
            for ($i = 0; $i < $this->getIterations(); $i++) {
                $visitor = $this->getRandomChars();
                $manager->addVisitor($visitor);
            }
            $memoryUsageAfter = $manager->getUsedMemory();
            $manager->flush();

            $result = new VisitorsCountResult(
                $testName,
                $this->getIterations(),
                $manager,
                $manager->countUniqueVisitors(),
                $memoryUsageBefore,
                $memoryUsageAfter
            );
            $this->addTestResult($result);
        }

        return $this->getTestResults($testName);
    }
}
