<?php

namespace DDM\CacheMapper\Processor;

class CacheObject
{
    protected $cacheMap = [];
    protected $cacheData = [];

    /**
     * @param array $cacheData
     */
    public function setCacheData($cacheData)
    {
        $this->cacheData = $cacheData;
    }

    /**
     * @return array
     */
    public function getCacheData()
    {
        return $this->cacheData;
    }

    /**
     * @param array $cacheMap
     */
    public function setCacheMap(array $cacheMap = [])
    {
        $cacheMap = $this->cleanCachemapData($cacheMap);
        $this->cacheMap = $cacheMap;
    }

    /**
     * @return array
     */
    public function getCacheMap()
    {
        return $this->cacheMap;
    }

    protected function cleanCachemapData(array $cacheMap = [])
    {
        $cleaned = [];
        foreach($cacheMap as $type=>$cacheObjects) {
            $objects = array_filter(array_map(function($cacheId) {
                return is_string($cacheId) ? $cacheId : null;
            },$cacheObjects));
            $cleaned[$type] = $objects;
        }
        return $cleaned;
    }

}
