<?php
declare(strict_types = 1);

namespace App\Printer;

interface Printer
{
    public function printData(string $fileName, PrintableCollection $results);
}
