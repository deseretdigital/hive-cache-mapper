<?php

namespace DDM\CacheMapper\Client;

interface ClientInterface
{
    public function __construct(array $config = []);
    public function set($key, $data, $expiry);
    public function setAdd($key, array $data);
    public function setMembers($key);
    public function get($key);
    public function delete($key);
    public function exists($key);
}
