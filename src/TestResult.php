<?php
declare(strict_types = 1);

namespace App;


use Symfony\Component\Stopwatch\StopwatchEvent;

class TestResult
{
    /** @var string */
    private $name;
    /** @var int */
    private $iterations;
    /** @var string */
    private $storageName;
    /** @var int */
    private $duration;
    /** @var string */
    private $storageVersion;

    public function __construct(string $name, int $iterations, KeyValueManagerInterface $manager, StopwatchEvent $event)
    {
        $this->setName($name);
        $this->setIterations($iterations);
        $this->duration = $event->getDuration();
        $this->storageName = $manager->getStorageName();
        $this->storageVersion = $manager->getStorageVersion();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getIterations(): int
    {
        return $this->iterations;
    }

    /**
     * @param int $iterations
     */
    public function setIterations(int $iterations)
    {
        $this->iterations = $iterations;
    }

    /**
     * @return string
     */
    public function getStorageName(): string
    {
        return $this->storageName;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @return string
     */
    public function getStorageVersion(): string
    {
        return $this->storageVersion;
    }
}
