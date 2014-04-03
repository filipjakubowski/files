<?php

namespace Prospekta\FileBundle\EventListener;


use Doctrine\ORM\Event\LifecycleEventArgs;
use Prospekta\FileBundle\Entity\File;
use Prospekta\FileBundle\Entity\ImageFile;

class FileSaver
{
    /**
     * @var \Prospekta\FileBundle\Service\StorageManager StorageManager instance
     */
    private $storageManager;
    
    /**
     * 
     * @param \Prospekta\FileBundle\Service\StorageManager $storageManager
     */
    public function __construct(\Prospekta\FileBundle\Service\StorageManager $storageManager)
    {
        $this->storageManager = $storageManager;
    }
    
    /**
     * prePersist Doctrine listener
     * 
     * This method listens if any File instance is persisted in Doctrine database. If so, it stores associated file and creates thumbnails if needed.
     * 
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        
        if ($entity instanceof File)
        {
            if (!$entity->isFileSet())            
            {
                throw new \Exception('You cannot persist FileBundle\Entity\File instance without setting a file');
            }
            
            $newHandle = $this->storageManager->getEngine()->store($entity);
            $entity->setFilehandle($newHandle);
            
            if ($entity instanceof ImageFile)
            {
                foreach($entity->getThumbnails() as $size)
                {
                    $x = $size[0];
                    $y = $size[1];
                    $this->storageManager->getEngine()->storeThumbnail($entity, $x, $y);
                }
            }
        } 
    }
    
    /**
     * preUpdate Doctrine listener
     * 
     * The same as prePersist, but for update action.
     * WARNING! So far, updating file itself for File Entity instance is not allowed! You can only change other details.
     * 
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        
        if ($entity instanceof File)
        {
            if ($entity->isFileSet())            
            {
                throw new \Exception('You cannot change file for already peristed FileBundle\Entity\File instance! You can only change properties stored in database.');
            }
            
//            
//            $newHandle = $this->storageManager->getEngine()->update($entity);
//            
//            if ($newHandle != null)
//            {
//                $entity->setFilehandle($newHandle);
//                
//                // funny workaround to make changes in entity visible in flush()
//                $em = $args->getEntityManager();
//                $uow = $em->getUnitOfWork();
//                $meta = $em->getClassMetadata(get_class($entity));
//                $uow->recomputeSingleEntityChangeSet($meta, $entity);
//            }
        }
    }
}
