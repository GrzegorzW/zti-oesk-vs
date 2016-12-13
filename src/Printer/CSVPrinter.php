<?php
declare(strict_types = 1);

namespace App\Printer;

use ReflectionClass;

final class CSVPrinter implements Printer
{
    public function printData(string $fileName, PrintableCollection $results)
    {
        $handler = $this->openFile($fileName);

        if (!$handler) {
            throw new FileException(sprintf('Unable to open file %s', $fileName));
        }

        $this->printHeader($results->current(), $handler);

        foreach ($results as $result) {
            $this->printRow($result, $handler);
        }

        $this->closeFile($handler);
    }

    private function openFile($fileName)
    {
        return fopen($fileName, 'wb+');
    }

    private function printHeader(Printable $result, $handler)
    {
        fputcsv($handler, $result->getPropertiesToPrint(), ';');
    }

    private function printRow(Printable $result, $handler)
    {
        fputcsv($handler, $this->toArray($result), ';');
    }

    private function toArray(Printable $printable)
    {
        $result = [];

        $reflectionClass = new ReflectionClass(get_class($printable));
        foreach ($printable->getPropertiesToPrint() as $propertyName) {
            $property = $reflectionClass->getProperty($propertyName);
            $property->setAccessible(true);
            $result[$property->getName()] = $property->getValue($printable);
            $property->setAccessible(false);
        }

        return $result;
    }

    private function closeFile($handler)
    {
        fclose($handler);
    }
}
