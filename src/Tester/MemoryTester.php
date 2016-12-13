<?php
declare(strict_types = 1);

namespace App\Tester;

use App\Manager\KeyValueManagerInterface;
use App\Result\VisitorsCounterTestResult;

class MemoryTester extends Tester
{
    /**
     * @param int $iterations
     * @param string $testName
     * @return array
     * @throws \InvalidArgumentException
     */
    public function uniqueVisitorsCounterTest(int $iterations, $testName = 'uniqueVisitorsCounter'): array
    {
        $this->checkIterations($iterations);
        /** @var KeyValueManagerInterface $manager */
        foreach ($this->getManagers() as $manager) {
            $manager->flush();
            $memoryUsageBefore = $manager->getUsedMemory();
            for ($i = 0; $i < $iterations; $i++) {
                $visitor = $this->getRandomChars();
                $manager->addVisitor($visitor);
            }
            $memoryUsageAfter = $manager->getUsedMemory();
            $visitorsCount = $manager->countUniqueVisitors();

            $result = new VisitorsCounterTestResult(
                $iterations,
                $visitorsCount,
                $manager,
                $memoryUsageBefore,
                $memoryUsageAfter
            );
            $this->addTestResult($testName, $result);

            $manager->flush();
        }

        return $this->getTestResults($testName);
    }
}
