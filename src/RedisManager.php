<?php
declare(strict_types = 1);

namespace App;

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
}
