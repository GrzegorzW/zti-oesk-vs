<?php
declare(strict_types = 1);

namespace App\Result;

use App\Printer\PrintableCollection;
use App\Printer\Printer;
use App\Tester\Tester;
use DateTime;

final class TesterResultsHandler
{
    private $printer;

    public function __construct(Printer $printer)
    {
        $this->printer = $printer;
    }

    public function printResults(Tester $tester, string $resultDir)
    {
        $printableCollections = $this->extractPrintableCollections($tester->getAllTestsResults());

        /** @var PrintableCollection $printableCollection */
        foreach ($printableCollections as $testName => $printableCollection) {
            $filename = $this->createFilename($resultDir, $testName);
            $this->printer->printData($filename, $printableCollection);
        }
    }

    private function extractPrintableCollections(array $allTestsResults)
    {
        $printableCollections = [];
        foreach ($allTestsResults as $key => $allTestResults) {
            $printableCollection = new PrintableCollection();
            /** @var TestResult $testResult */
            foreach ($allTestResults as $testResult) {
                $printableCollection->addData($testResult);
            }
            $printableCollections[$key] = $printableCollection;
        }

        return $printableCollections;
    }

    private function createFilename(string $resultDir, string $testName): string
    {
        return $resultDir . '/' . date_timestamp_get(new DateTime()) . '_' . $testName . '.csv';
    }
}
