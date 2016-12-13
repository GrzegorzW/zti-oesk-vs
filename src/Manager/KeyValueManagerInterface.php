<?php
declare(strict_types = 1);

namespace App\Manager;

interface KeyValueManagerInterface
{
    public function set(string $key, string $value);
    public function setMulti(array $items);
    public function get(string $key);
    public function getMulti(array $keys);
    public function delete(string $key);
    public function deleteMulti(array $keys);
    public function increment(string $key);
    public function getStorageName(): string;
    public function getStorageVersion(): string;
    public function flush();


    const VISITOR_COUNTER_NAME = 'visitor_counter';
    public function addVisitor(string $visitorId);
    public function countUniqueVisitors(): int;

    public function getUsedMemory(): int;
}