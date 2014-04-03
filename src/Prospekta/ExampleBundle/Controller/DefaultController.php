<?php

namespace Prospekta\ExampleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Prospekta\ExampleBundle\Entity\StockPhoto;

class DefaultController extends Controller
{
    public function addAction()
    {
        $s = new StockPhoto();
        $s->setAuthor('Andrzej');
        $s->setDescription('Beauty');
        $s->setFile('/home/rudy/Pictures/Examples/prettygirl2.jpg');
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($s);
        $em->flush();
        
        return $this->render('ProspektaExampleBundle:Default:index.html.twig');
    }
    
    public function updateAction($handle)
    {
        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository('ProspektaExampleBundle:StockPhoto')->findOneByFilehandle($handle);
        $file->setDescription('Updated description');
        $file->setFile('/home/rudy/Pictures/Examples/pretty-girl.jpg');
        $em->flush();
        
        return $this->render('ProspektaExampleBundle:Default:test.html.twig', array(
            'file' => $file
        ));
    }
    
    public function getAction($handle)
    {
        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository('ProspektaExampleBundle:StockPhoto')->findOneByFilehandle($handle);
        
        return $this->render('ProspektaExampleBundle:Default:test.html.twig', array(
            'file' => $file
        ));
    }
    
}
