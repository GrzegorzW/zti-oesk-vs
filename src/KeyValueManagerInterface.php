<?php
declare(strict_types = 1);

namespace App;


interface KeyValueManagerInterface
{
    public function set(string $key, string $value);
    public function get(string $key);
    public function delete(string $key);
    public function increment(string $key);
}