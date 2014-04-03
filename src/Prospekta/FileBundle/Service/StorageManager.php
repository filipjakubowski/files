<?php

namespace Prospekta\FileBundle\Service;

/**
 * Storage Manager
 * 
 * The main storage service -> the only interface to StorageEngine methods
 */
class StorageManager
{
    /**
     *
     * @var type 
     */
    private $storageEngine;
    
    /**
     * 
     * @param type $storageEngine
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $serviceContainer
     */
    public function __construct($storageEngine, \Symfony\Component\DependencyInjection\ContainerInterface $serviceContainer)
    {
        $this->storageEngine = new $storageEngine($serviceContainer);
    }
    
    /**
     * Get Storage Engine
     * 
     * The main method of StorageManager service, gives user access to Storage Engine
     * 
     * @return \Prospekta\FileBundle\StorageEngine\StorageEngine Storage Engine
     */
    public function getEngine()
    {
        return $this->storageEngine;
    }
    
}