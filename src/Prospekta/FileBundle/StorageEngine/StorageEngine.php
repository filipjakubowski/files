<?php

namespace Prospekta\FileBundle\StorageEngine;

use Prospekta\FileBundle\Entity\File;
use Prospekta\FileBundle\Entity\ImageFile;

/**
 * Storage Engine
 * 
 * The heart of FileBundle. This class is the interface between application and real storage implementation.
 * Every storage implementation (in local file system, cloud, remote server, etc) should implement its own StorageEngine, which
 * has to extend this class.
 */
abstract class StorageEngine
{
    private $serviceContainer;
    
    /**
     * StorageEngine constructor
     * 
     * @param type $serviceContainer
     */
    public function __construct($serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }
    
    /**
     * store file
     * 
     * This method is supposed to store $file
     * It doesn't say how to do it, this is left for specific implementation
     * 
     * @var \Prospekta\FileBundle\Entity\File file to be stored
     * @return string storage-specific handle which will let access the file later
     */
    abstract public function store(File $file);
    
    /**
     * update file
     * 
     * @var \Prospekta\FileBundle\Entity\File file to be updated
     * @return string storage-specific handle which will let access the file later
     */
    abstract public function update(File $file);
    
    /**
     * get file local path
     * 
     * This method is supposed to return local path for the file.
     * In case of local filesystem storage, it may just return the storage path. In case of external storage, it should download file to local filesystem and return temporary path.
     * 
     * @var \Prospekta\FileBundle\Entity\File file
     * @return string local filepath
     */
    abstract public function getLocalPath(File $file);
    
    /**
     * get file local path only by raw filehandle
     * 
     * Works like getLocalPath but takes handle instead of File instance
     * 
     * @var string file handle
     * @return string local filepath
     */
    abstract public function getLocalPathByFileHandle($handle);
    
    /**
     * get file url
     * 
     * getUrl method returns URL for web browser to download the file
     * 
     * @var \Prospekta\FileBundle\Entity\File file
     * @return string URL
     */
    abstract public function getUrl(File $file);
    
    /**
     * get thumbnail URL
     * 
     * this method is supposed to get URL for web browser to download ImageFile thumbnail
     * 
     * @var \Prospekta\FileBundle\Entity\ImageFile file
     * @var integer $x thumbnail width
     * @var integer $y thumbnail height
     */
    abstract public function getThumbnailUrl(ImageFile $file, $x, $y);
    
    /**
     * stores thumbnail
     * 
     * this method is supposed to store thumbnail (to prevent creating it dynamically at every request -> this would kill the server obviously)
     * 
     * @var \Prospekta\FileBundle\Entity\File file
     * @var integer $x thumbnail width
     * @var integer $y thumbnail height
     */
    abstract public function storeThumbnail(ImageFile $file, $x, $y);
    
    /**
     * removes thumbnails
     * 
     * removes all thumbnails for $file
     * 
     * @var \Prospekta\FileBundle\Entity\ImageFile file
     */
    abstract public function clearThumbnails(ImageFile $file);
    
    /**
     * Gets Service Container
     * 
     * This method gives storage implementations access to Symfony2 services
     * 
     * @return ServiceContainer
     */
    protected function getContainer()
    {
        return $this->serviceContainer;
    }
    
    /**
     * gives StorageEngine access to private properties of File instances
     * 
     * This method is neccessary to make File Entity 
     * 
     * @param \Prospekta\FileBundle\Entity\File $file
     * @param string $property
     * @return type
     */
    protected function getFilePrivateProperty(File $file, $property)
    {
        /*
         * Reflection is used below in order to get private $path property of File object. 
         * It is important for this property to be private, because we don't want to give any access to it
         * for classes inheriting from File (even no getters)
         */
        $reflection = new \ReflectionClass($file);
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);
        return $property->getValue($file);
    }
}
