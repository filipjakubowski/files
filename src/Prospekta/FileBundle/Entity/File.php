<?php

namespace Prospekta\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;


/**
 * File
 * 
 * Base class for Entities which has files stored by FileBundle.
 * For images with thumbnails use ImageFile instead (which inherits this class, by the way).
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
class File
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255)
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="filehandle", type="string", length=255)
     */
    private $filehandle;

    /**
     * @var string
     */
    protected $path;
    
    /**
     * @var size
     *
     * @ORM\Column(name="size", type="integer", nullable=false)
     */
    protected $size;
    
    /**
     * @var mimetype
     *
     * @ORM\Column(name="mimetype", type="string", length=255, nullable=true)
     */
    protected $mimetype;
    
    /**
     *
     * @var boolean 
     */
    private $isFileSet = false;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set file
     * 
     * This method is responsible for setting file for entity
     * 
     * @param string local path to the file supposed to be stored
     * @param string optional filename under which file is going to be saved. If not set, name will be extracted from original filename (keep in mind it's going to be temporary name in most cases)
     * @return NULL
     */
    public function setFile($path, $filename = null)
    {
        if (!file_exists($path))
        {
            throw new \Exception("File not found!");
        }
        
        $this->path = $path;
        $this->size = filesize($path);
        $this->mimetype = MimeTypeGuesser::getInstance()->guess($path);
        
        if ($filename)
        {
            $this->setFilename($filename);
        }
        else
        {
            $basename = pathinfo($path, PATHINFO_BASENAME);
            $this->setFilename($basename);
        }
        
        $this->isFileSet = true;
    }
    
    /**
     * Checks if file is set
     * 
     * @return boolean
     */
    public function isFileSet()
    {
        return $this->isFileSet;
    }
    
    /**
     * Set filename
     *
     * @param string filename under which file is going to be stored
     * @return File
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    
        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set filehandle
     *
     * @param string $filehandle
     * @return File
     */
    public function setFilehandle($filehandle)
    {
        $this->filehandle = $filehandle;
    
        return $this;
    }

    /**
     * Get filehandle
     *
     * @return string 
     */
    public function getFilehandle()
    {
        return $this->filehandle;
    }
}