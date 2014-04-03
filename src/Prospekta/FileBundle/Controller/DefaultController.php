<?php

namespace Prospekta\FileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;

class DefaultController extends Controller
{
    public function getFileAction($handle)
    {
        $path = $this->get('service_container')->getParameter('kernel.root_dir') . '/files/' . $handle;
        
        if (!file_exists($path))
        {
            throw new FileNotFoundException($path);
        }
        
        $file = new File($path, false);
        $response = new Response();
        
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $filename = pathinfo($path, PATHINFO_FILENAME);
        
        $filenameToSend = substr($filename, 0, -14);
        if ($extension != 'noext')
        {
            $filenameToSend = $filenameToSend . '.' . $extension;
        }
        
        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', $file->getMimeType());
        $response->headers->set('Content-Disposition', 'inline; filename="' . $filenameToSend . '"');
        $response->headers->set('Content-length', filesize($path));
        
        $response->sendHeaders();
        $response->setContent(readfile($path));
        
        return $response;
    }
    
    public function getThumbnailAction($handle, $x, $y)
    {
        $path = $this->get('service_container')->getParameter('kernel.root_dir') . '/files/thumbnails/' . $x . '.' . $y . '.' . $handle;
        
        if (!file_exists($path))
        {
            throw new FileNotFoundException($path);
        }
        
        $file = new File($path, false);
        $response = new Response();
         
        $filename = pathinfo($path, PATHINFO_BASENAME);

        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-Type', $file->getMimeType());
        $response->headers->set('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->headers->set('Content-length', filesize($path));
        
        $response->sendHeaders();
        $response->setContent(readfile($path));
        
        return $response;
    }
}
