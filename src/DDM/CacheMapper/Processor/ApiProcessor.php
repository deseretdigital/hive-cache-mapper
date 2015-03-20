<?php

namespace DDM\CacheMapper\Processor;

class ApiProcessor implements ProcessorInterface
{
    /**
     * @param array $data
     * @return CacheObject
     */
    public function process(array $data)
    {
        $cacheObject = new CacheObject();
        if (array_key_exists('_cachemap', $data) && is_array($data['_cachemap'])) {
            $apiCacheMap = $data['_cachemap'];
            $cacheObject->setCacheMap($apiCacheMap);
        }
        $cacheObject->setCacheData($data);
        return $cacheObject;
    }
} 