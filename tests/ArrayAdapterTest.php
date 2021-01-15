<?php

namespace Helix\GuzzleHttp\Middleware\Tests;

use Helix\GuzzleHttp\Middleware\Storage\Adapter\ArrayAdapter;
use Helix\GuzzleHttp\Middleware\Storage\Counter;
use Helix\GuzzleHttp\Middleware\Storage\ThrottleStorageInterface;
use PHPUnit\Framework\TestCase;

class ArrayAdapterTest extends TestCase
{

    public function testCreateCounter()
    {
        $storage = new ArrayAdapter();
        $this->assertFalse($storage->hasCounter('foo'));

        $counter = new Counter(10);
        $counter->increment();
        $storage->saveCounter('foo', $counter, 10);
        $this->assertTrue($storage->hasCounter('foo'));
        return $storage;
    }

    /**
     * @param ThrottleStorageInterface $storage
     * @depends testCreateCounter
     */
    public function testRetrieveCounter(ThrottleStorageInterface $storage)
    {
        $counter = $storage->getCounter('foo');
        $this->assertInstanceOf(Counter::class, $counter);
        $this->assertEquals(1, $counter->count());
        $this->assertFalse($storage->hasCounter('bar'));
        return $storage;
    }

    /**
     * @param ThrottleStorageInterface $storage
     * @depends testRetrieveCounter
     */
    public function testUpdateCounter(ThrottleStorageInterface $storage)
    {
        $counter = $storage->getCounter('foo');
        $this->assertInternalType('float', $counter->getRemainingTime());
        $counter->increment();
        $this->assertEquals(2, $counter->count());
        return $storage;
    }

    /**
     * @param ThrottleStorageInterface $storage
     * @depends testUpdateCounter
     */
    public function testDeleteCounter(ThrottleStorageInterface $storage)
    {
        $storage->deleteCounter('foo');
        $this->assertFalse($storage->hasCounter('foo'));
    }
}
