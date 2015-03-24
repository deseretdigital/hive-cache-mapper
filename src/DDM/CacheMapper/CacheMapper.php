<?php

namespace DDM\CacheMapper;

use DDM\CacheMapper\Client;
use DDM\CacheMapper\Client\ClientInterface;

class CacheMapper
{
    /**
     * @var $processor Processor\ProcessorInterface
     */
    protected $processor;
    /**
     * @var $client ClientInterface
     */
    protected $client;

    protected $clientConfig;
    public $expireTime = 60;
    public $keyPrefix = 'cache.';

    public function __construct(array $clientConfig = [])
    {
        $this->setClientConfig($clientConfig);
    }

    public function cache($key, array $data)
    {
        $client = $this->getClient();
        $cacheObject = $this->getProcessor()->process($data);

        $client->set("{$this->keyPrefix}{$key}", json_encode($cacheObject->getCacheData()), $this->expireTime);

        foreach ($cacheObject->getCacheMap() as $type => $mapEntries) {
            $mapKey = "{$this->keyPrefix}{$key}.map:{$type}";
            $client->delete($mapKey);
            $client->setAdd($mapKey, $mapEntries);
        }
    }

    public function getMapEntries($key)
    {
        $client = $this->getClient();
        $keys = $client->keys("{$this->keyPrefix}{$key}.map:*");
        $mapEntries = [];
        foreach ($keys as $key) {
            $mapEntries[$key] = $client->setMembers($key);
        }
        return $mapEntries;
    }

    /**
     * @param Processor\ProcessorInterface $processor
     */
    public function setProcessor(Processor\ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }
    public function getProcessor()
    {
        if (is_null($this->processor)) {
            $this->setProcessor(new Processor\ApiProcessor());
        }
        return $this->processor;
    }

    /**
     * @param \DDM\CacheMapper\Client\ClientInterface $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return \DDM\CacheMapper\Client\ClientInterface
     */
    public function getClient()
    {
        if (is_null($this->client)) {
            $this->setClient(new Client\RedisClient($this->getClientConfig()));
        }
        return $this->client;
    }

    /**
     * @param array $clientConfig
     */
    public function setClientConfig(array $clientConfig = [])
    {
        $this->clientConfig = $clientConfig;
    }

    /**
     * @return array
     */
    public function getClientConfig()
    {
        return $this->clientConfig;
    }
}
