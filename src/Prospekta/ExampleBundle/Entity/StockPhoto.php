<?php

namespace Prospekta\ExampleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StockPhoto
 *
 * @ORM\Table(name="stock_photo")
 * @ORM\Entity
 */
class StockPhoto extends \Prospekta\FileBundle\Entity\ImageFile
{
    protected $thumbnails = array(
        array(200,200),
        array(100,100)
    );
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * Set description
     *
     * @param string $description
     * @return StockPhoto
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return StockPhoto
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }
}