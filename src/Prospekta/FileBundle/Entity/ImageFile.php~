<?php

namespace Prospekta\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * ImageFile
 *
 * This is base class for entities holding image files with thumbnail functionality
 * 
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
class ImageFile extends File
{
    /**
     * $thumbnails property keeps thumbnail sizes which are automatically saved on Doctrine persist action
     * The format of single size is array($width, $height), so correct $thumbnails has type array ( array (integer, integer), ... ).
     * @var array this variable 
     */
    protected $thumbnails = array();
    
    /**
     * Add thumbnail
     * 
     * Adds new thumbnail to $thumbnails property. 
     * 
     * @param integer width
     * @param integer height
     */
    public function addThumbnail($x, $y)
    {
        $thumbnails[] = array($x, $y);
    }
    
    /**
     * Get thumbnails
     * 
     * @return array
     */
    public function getThumbnails()
    {
        return $this->thumbnails;
    }
}