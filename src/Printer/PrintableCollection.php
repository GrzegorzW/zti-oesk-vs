<?php
declare(strict_types = 1);

namespace App\Printer;

use Iterator;

class PrintableCollection implements Iterator
{
    private $position;
    /** @var array */
    private $data = [];

    public function __construct()
    {
        $this->position = 0;
    }

    public function addData(Printable $data)
    {
        $this->data[] = $data;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current(): Printable
    {
        return $this->data[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->data[$this->position]);
    }
}
