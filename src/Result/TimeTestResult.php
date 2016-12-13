<?php
declare(strict_types = 1);

namespace App\Result;

use App\Manager\KeyValueManagerInterface;
use App\Printer\Printable;
use Symfony\Component\Stopwatch\StopwatchEvent;

class TimeTestResult extends TestResult
{
    /** @var int */
    private $duration;

    public function __construct(int $iterations, KeyValueManagerInterface $manager, StopwatchEvent $event)
    {
        parent::__construct($iterations, $manager);
        $this->duration = $event->getDuration();
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    public function getPropertiesToPrint(): array
    {
        return array_merge(parent::getPropertiesToPrint(), ['duration']);
    }
}
