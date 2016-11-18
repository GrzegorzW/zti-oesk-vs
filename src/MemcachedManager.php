<?php
declare(strict_types = 1);

namespace App;

use Memcached;


class MemcachedManager implements KeyValueManagerInterface
{
    /** @var  Memcached */
    private $memcached;

    /**
     * MemcachedManager constructor.
     * @param string $host
     * @param int $port
     */
    public function __construct(string $host, int $port)
    {
        $this->memcached = new Memcached();
        $this->memcached->addServer($host, $port);
        $this->memcached->setOption(Memcached::OPT_BINARY_PROTOCOL, true);
    }

    /**
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function set(string $key, string $value)
    {
        return $this->memcached->set($key, $value);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->memcached->get($key);
    }

    /**
     * @param string $key
     */
    public function delete(string $key)
    {
        $this->memcached->delete($key);
    }

    /**
     * @param string $key
     */
    public function increment(string $key)
    {
        $this->memcached->increment($key);
    }
}
