<?php

use DDM\CacheMapper\CacheMapper;
use DDM\CacheMapper\Client\RedisClient;

class RedisClientTest extends PHPUnit_Framework_TestCase
{
    public function testConstructSetsConfig()
    {
        $config = ['test_config'=>true];
        $client = new RedisClient($config);
        $this->assertEquals($config, $client->getClientConfig());
    }
    public function testSet()
    {
        $client = new RedisClient([]);
        $redisMock = $this->getRedisMock($client);
        $redisMock->shouldReceive('set')->once()->with('KEY', 'DATA_HERE', 'ex', 'EXPIRE TIME');
        $client->set('KEY', 'DATA_HERE', 'EXPIRE TIME');
    }
    public function testSetAdd()
    {
        $client = new RedisClient([]);
        $redisMock = $this->getRedisMock($client);
        $redisMock->shouldReceive('sadd')->once()->with('KEY', ['1234','5678']);
        $client->setAdd('KEY', ['1234','5678']);
    }
    public function testSetMembers()
    {
        $client = new RedisClient([]);
        $redisMock = $this->getRedisMock($client);
        $redisMock->shouldReceive('smembers')->once()->with('KEY');
        $client->setMembers('KEY');
    }
    public function testGet()
    {
        $client = new RedisClient([]);
        $redisMock = $this->getRedisMock($client);
        $redisMock->shouldReceive('get')->once()->with('KEY');
        $client->get('KEY');
    }
    public function testDelete()
    {
        $client = new RedisClient([]);
        $redisMock = $this->getRedisMock($client);
        $redisMock->shouldReceive('del')->once()->with('KEY');
        $client->delete('KEY');
    }
    public function testExists()
    {
        $client = new RedisClient([]);
        $redisMock = $this->getRedisMock($client);
        $redisMock->shouldReceive('exists')->once()->with('KEY');
        $client->exists('KEY');
    }
    public function testGetClient()
    {
        $client = new RedisClient([]);
        $this->assertInstanceOf('Predis\Client', $client->getClient());
    }
    public function testSetExceptionThrownWhenNotString()
    {
        $this->setExpectedException('\Exception');
        $client = new RedisClient([]);
        $redisMock = $this->getRedisMock($client);
        $redisMock->shouldReceive('set')->never()->with('KEY', [], 'ex', 'EXPIRE TIME');
        $client->set('KEY', [], 'EXPIRE TIME');
    }
    protected function getRedisMock(&$client)
    {
        $redisMock = \Mockery::mock('Predis\Client');
        $client->setClient($redisMock);
        return $redisMock;
    }
}
 