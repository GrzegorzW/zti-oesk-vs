<?php
declare(strict_types = 1);

namespace App\Result;

use App\Manager\KeyValueManagerInterface;
use Symfony\Component\Stopwatch\StopwatchEvent;


class TimeTestResult extends TestResult
{
    /** @var int */
    private $duration;

    public function __construct(string $name, int $iterations, KeyValueManagerInterface $manager, StopwatchEvent $event)
    {
        parent::__construct($name, $iterations, $manager);
        $this->duration = $event->getDuration();
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }
}