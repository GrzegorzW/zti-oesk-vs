<?php
declare(strict_types = 1);

namespace App\Result;

use App\Manager\KeyValueManagerInterface;
use App\Printer\Printable;

abstract class TestResult implements Printable
{
    /** @var int */
    protected $iterations;
    /** @var string */
    protected $storageName;
    /** @var string */
    protected $storageVersion;

    public function __construct(int $iterations, KeyValueManagerInterface $manager)
    {
        $this->setIterations($iterations);
        $this->storageName = $manager->getStorageName();
        $this->storageVersion = $manager->getStorageVersion();
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
     * @return string
     */
    public function getStorageVersion(): string
    {
        return $this->storageVersion;
    }

    public function getPropertiesToPrint(): array
    {
        return ['storageName', 'storageVersion', 'iterations'];
    }
}
