<?php

namespace Prospekta\FileBundle\StorageEngine;

use Prospekta\FileBundle\Entity\File;
use Prospekta\FileBundle\Entity\ImageFile;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 *
 * @author Andrzej Dąbrowski
 */
class FileStorageEngine extends StorageEngine
{
    public function store(File $file)
    {
        $tmpPath = $this->getFilePrivateProperty($file, 'path');
        
        if (!file_exists($tmpPath))
        {
            throw new FileNotFoundException($tmpPath);
        }
        
        $ext = pathinfo($file->getFilename(), PATHINFO_EXTENSION);
        $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);
        $directory = $this->getContainer()->getParameter('kernel.root_dir') . '/files/';
        
        if($ext == '')
        {
            $ext = 'noext';
        }
        
        if(!file_exists($directory))
        {
            mkdir($directory);
        }
        
        $newFileName = null;
        $newFilePath = null;
        
        do
        {
            $newFileName = $filename . '.' . uniqid() . '.' . $ext;
            $newFilePath = $directory . $newFileName;
        }
        while( file_exists($newFilePath) );
        
        copy($tmpPath, $newFilePath);
        
        return $newFileName;
    }
    
    public function update(File $file)
    {
        $tmpPath = $this->getFilePrivateProperty($file, 'path');
        if ($tmpPath == null)
        {
            return null;
        }
        $newFileName = $this->store($file);
        return $newFileName;
    }
    
    public function getLocalPath(File $file)
    {
        return $this->getLocalPathByFilehandle($file->getFilehandle());
    }
    
    public function getLocalPathByFileHandle($handle)
    {
        $filePath = $this->getContainer()->getParameter('kernel.root_dir') . '/files/' . $handle;
        
        if (!file_exists($filePath))
        {
            throw new FileNotFoundException($filePath);
        }
        
        return $filePath;
    }
    
    public function getUrl(File $file)
    {
        $filePath = $this->getContainer()->getParameter('kernel.root_dir') . '/files/' . $file->getFilehandle();
        
        if (!file_exists($filePath))
        {
            throw new FileNotFoundException($filePath);
        }
        
        return $this->getContainer()->get('router')->getContext()->getBaseUrl() . '/get_file/' . $file->getFilehandle();
    }
    
    public function getThumbnailUrl(ImageFile $file, $x, $y)
    {
        return $this->getContainer()->get('router')->getContext()->getBaseUrl() . '/get_file/' . $file->getFilehandle() . "/thumb/$x/$y";
    }
    
    public function storeThumbnail(ImageFile $file, $x, $y)
    {
        $directory = $this->getContainer()->getParameter('kernel.root_dir') . '/files/thumbnails/';
        if(!file_exists($directory))
        {
            mkdir($directory);
        }
        
        $thumbnailFileName = $x . '.' . $y . '.' . $file->getFilehandle();
        $originalFile = $this->getLocalPath($file);
        
        $image = new \Imagick($originalFile);
        $image->cropThumbnailImage($x, $y);
        $image->writeImage($directory . $thumbnailFileName);
    }
    
    public function clearThumbnails(ImageFile $file)
    {
        $directory = $this->getContainer()->getParameter('kernel.root_dir') . '/files/thumbnails/';
        if(!file_exists($directory))
        {
            return;
        }
        
        // removing thumbnails!!! In FileStorageEngine we should have hierarchy of files!!!
        
        return;
    }
}
