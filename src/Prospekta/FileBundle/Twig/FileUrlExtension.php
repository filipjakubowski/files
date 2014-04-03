<?php

namespace Prospekta\FileBundle\Twig;

class FileUrlExtension extends \Twig_Extension
{
    private $storageManager;
    
    public function __construct($storageManager)
    {
        $this->storageManager = $storageManager;
    }
    
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('fileurl', array($this, 'fileUrlFilter')),
            new \Twig_SimpleFilter('thumbnail', array($this, 'thumbnailFilter')),
        );
    }

    public function fileUrlFilter(\Prospekta\FileBundle\Entity\File $file)
    {
        return $this->storageManager->getEngine()->getUrl($file);
    }

    public function thumbnailFilter(\Prospekta\FileBundle\Entity\ImageFile $file, $x, $y)
    {
        return $this->storageManager->getEngine()->getThumbnailUrl($file, $x, $y);
    }
    
    public function getName()
    {
        return 'fileurl';
    }
}
