<?php

namespace DDM\CacheMapper\Client;

use Predis\Client;

class RedisClient implements ClientInterface
{
    protected $client;
    protected $clientConfig = [];
    public function __construct(array $clientConfig = [])
    {
        $this->setClientConfig($clientConfig);
    }
    public function set($key, $data, $expiry)
    {
        if (!is_string($data)) {
            throw new \Exception('Data sent to set must be a string');
        }
        $this->getClient()->set($key, $data, "ex", $expiry);
    }
    public function setAdd($key, array $data)
    {
        $this->getClient()->sadd($key, $data);
    }
    public function setMembers($key)
    {
        return $this->getClient()->smembers($key);
    }
    public function keys($pattern)
    {
        return $this->getClient()->keys($pattern);
    }
    public function get($key)
    {
        return $this->getClient()->get($key);
    }
    public function delete($key)
    {
        return $this->getClient()->del($key);
    }
    public function exists($key)
    {
        return $this->getClient()->exists($key);
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


    /**
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        if (is_null($this->client)) {
            return new Client($this->getClientConfig());
        }
        return $this->client;
    }
}
