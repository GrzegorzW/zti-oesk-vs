<?php
declare(strict_types = 1);

namespace App\Tester;

use App\Manager\KeyValueManagerInterface;
use App\Result\VisitorsCounterTestResult;


class MemoryTester extends Tester
{
    /**
     * @param string $testName
     * @return array
     * @throws \InvalidArgumentException
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

            $result = new VisitorsCounterTestResult(
                $this->getIterations(),
                $manager,
                $memoryUsageBefore,
                $memoryUsageAfter
            );
            $this->addTestResult($testName, $result);
        }

        return $this->getTestResults($testName);
    }
}
