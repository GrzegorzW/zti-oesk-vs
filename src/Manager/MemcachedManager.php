<?php
declare(strict_types = 1);

namespace App\Manager;

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

    /**
     * @return string
     */
    public function getStorageName(): string
    {
        return 'Memcached';
    }

    /**
     * @return string
     */
    public function getStorageVersion(): string
    {
        return array_values($this->memcached->getVersion())[0];
    }

    /**
     * @param array $items
     */
    public function setMulti(array $items)
    {
        $this->memcached->setMulti($items);
    }

    /**
     * @param array $keys
     * @return array
     */
    public function getMulti(array $keys)
    {
        return $this->memcached->getMulti($keys);
    }

    /**
     * @param array $keys
     */
    public function deleteMulti(array $keys)
    {
        $this->memcached->deleteMulti($keys);
    }

    /**
     * @param string $visitor
     */
    public function addVisitor(string $visitor)
    {
        if ($this->memcached->add($visitor, '')) {
            $this->memcached->increment(KeyValueManagerInterface::VISITOR_COUNTER_NAME, 1, 1);
        }
    }

    /**
     * @return int
     */
    public function countUniqueVisitors(): int
    {
        return (int)$this->memcached->get(KeyValueManagerInterface::VISITOR_COUNTER_NAME);
    }

    public function flush()
    {
        $this->memcached->flush();
    }

    public function getUsedMemory(): int
    {
        return current($this->memcached->getStats())['bytes'];
    }
}
