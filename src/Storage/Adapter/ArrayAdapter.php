<?php

namespace FaritSlv\GuzzleHttp\Middleware\Storage\Adapter;

use FaritSlv\GuzzleHttp\Middleware\Storage\Counter;
use FaritSlv\GuzzleHttp\Middleware\Storage\ThrottleStorageInterface;

class ArrayAdapter implements ThrottleStorageInterface
{

    private $storage = [];

    /**
     * @inheritDoc
     */
    public function hasCounter(string $storageKey): bool
    {
        return isset($this->storage[$storageKey]);
    }

    /**
     * @inheritDoc
     */
    public function getCounter(string $storageKey): Counter
    {
        return $this->storage[$storageKey] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function saveCounter(string $storageKey, Counter $counter, float $ttl = null)
    {
        $this->storage[$storageKey] = $counter;
    }

    /**
     * @inheritDoc
     */
    public function deleteCounter(string $storageKey)
    {
        unset($this->storage[$storageKey]);
    }
}
