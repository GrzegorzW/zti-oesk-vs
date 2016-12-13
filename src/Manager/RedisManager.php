<?php
declare(strict_types = 1);

namespace App\Manager;

use Redis;

class RedisManager implements KeyValueManagerInterface
{
    /** @var Redis */
    private $redis;

    /**
     * RedisManager constructor.
     * @param string $host
     * @param int $port
     */
    public function __construct(string $host, int $port)
    {
        $this->redis = new Redis();
        $this->redis->connect($host, $port);
    }

    /**
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function set(string $key, string $value)
    {
        return $this->redis->set($key, $value);
    }

    /**
     * @param string $key
     * @return bool|string
     */
    public function get(string $key)
    {
        return $this->redis->get($key);
    }

    /**
     * @param string $key
     */
    public function delete(string $key)
    {
        $this->redis->del($key);
    }

    /**
     * @param string $key
     */
    public function increment(string $key)
    {
        $this->redis->incr($key);
    }

    /**
     * @return string
     */
    public function getStorageName(): string
    {
        return 'Redis';
    }

    /**
     * @return string
     */
    public function getStorageVersion(): string
    {
        return $this->redis->info()['redis_version'];
    }

    /**
     * @param array $items
     */
    public function setMulti(array $items)
    {
        $this->redis->mset($items);
    }

    /**
     * @param array $keys
     * @return array
     */
    public function getMulti(array $keys)
    {
        return $this->redis->mget($keys);
    }

    /**
     * @param array $keys
     */
    public function deleteMulti(array $keys)
    {
        $this->redis->del($keys);
    }

    public function addVisitor(string $visitor)
    {
        $this->redis->pfAdd(KeyValueManagerInterface::VISITOR_COUNTER_NAME, [$visitor]);
    }

    public function countUniqueVisitors(): int
    {
        return $this->redis->pfCount(KeyValueManagerInterface::VISITOR_COUNTER_NAME);
    }

    public function flush()
    {
        $this->redis->flushAll();
    }

    public function getUsedMemory(): int
    {
        return $this->redis->info()['used_memory'];
    }
}
