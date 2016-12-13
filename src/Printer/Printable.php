<?php

namespace App\Printer;


interface Printable
{
    public function getPropertiesToPrint(): array;
}
