<?php

namespace DDM\CacheMapper\Processor;

interface ProcessorInterface
{
    /**
     * @param array $data
     * @return CacheObject
     */
    public function process(array $data);
}
