<?php

use DDM\CacheMapper\CacheMapper;
use DDM\CacheMapper\Client\RedisClient;

class CacheMapperTest extends PHPUnit_Framework_TestCase
{
    public $sampleData = [
        'id'=>'01',
        '_cachemap'=>[
            'contentprofiles'=>['contentprofile_a','contentprofile_b'],
            'pages'=>['page_c','page_d'],
        ]
    ];

    public function getClientMock()
    {
        return \Mockery::mock('DDM\CacheMapper\Client\RedisClient');
    }
    public function testConstruct()
    {
        $cacheMapper = new CacheMapper([
            'host'=>'127.0.0.1',
            'port'=>'6379'
        ]);

        $client = $this->getClientMock();
        $client->shouldReceive('set')->once()->with('cms.page:01', json_encode($this->sampleData), 60);
        $client->shouldReceive('delete')->once()->with('cms.page:01.map:contentprofiles');
        $client->shouldReceive('setAdd')->once()->with('cms.page:01.map:contentprofiles', $this->sampleData['_cachemap']['contentprofiles']);
        $client->shouldReceive('delete')->once()->with('cms.page:01.map:pages');
        $client->shouldReceive('setAdd')->once()->with('cms.page:01.map:pages', $this->sampleData['_cachemap']['pages']);
        $cacheMapper->setClient($client);

        $cacheMapper->keyPrefix = 'cms.';
        $cacheMapper->cache('page:01', $this->sampleData);
    }
    public function testConstructWithRedisClient()
    {
        $cacheMapper = new CacheMapper([
            'host'=>'127.0.0.1',
            'port'=>'6379'
        ]);

        $client = \Mockery::mock('Predis\Client');
        $client->shouldReceive('set')->once()->with('cms.page:01', json_encode($this->sampleData), 'ex', 60);
        $client->shouldReceive('del')->once()->with('cms.page:01.map:contentprofiles');
        $client->shouldReceive('sadd')->once()->with('cms.page:01.map:contentprofiles', $this->sampleData['_cachemap']['contentprofiles']);
        $client->shouldReceive('del')->once()->with('cms.page:01.map:pages');
        $client->shouldReceive('sadd')->once()->with('cms.page:01.map:pages', $this->sampleData['_cachemap']['pages']);

        $redisClient = new RedisClient();
        $redisClient->setClient($client);
        $cacheMapper->setClient($redisClient);

        $cacheMapper->keyPrefix = 'cms.';
        $cacheMapper->cache('page:01', $this->sampleData);
    }
    public function testGetClientWithoutMock()
    {
        $cacheMapper = new CacheMapper();
        $client = $cacheMapper->getClient();
        $this->assertInstanceOf('DDM\CacheMapper\Client\ClientInterface', $client);
        $this->assertInstanceOf('DDM\CacheMapper\Client\RedisClient', $client);
    }
    public function testGetMapEntries()
    {
        $cacheMapper = new CacheMapper();
        $client = $this->getClientMock();
        $cacheMapper->setClient($client);

        $client->shouldReceive('keys')->once()->with('cms.page:01.map:*')->andReturn([
            'cms.page:01.map:contentprofiles',
            'cms.page:01.map:pages'
        ]);
        $client->shouldReceive('setMembers')->once()->with('cms.page:01.map:contentprofiles')->andReturn(['c_1','c_2']);
        $client->shouldReceive('setMembers')->once()->with('cms.page:01.map:pages')->andReturn(['p_1','p_2']);
        $cacheMapper->keyPrefix = 'cms.';
        $cacheMapper->getMapEntries('page:01');
    }

} 