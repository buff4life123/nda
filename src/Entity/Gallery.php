<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="gallery")
 * @ORM\Entity(repositoryClass="App\Repository\GalleryRepository")
 */

class Gallery
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="integer", name="order_by", nullable=true)*/
    private $orderBy;
    /**
     *@ORM\OneToMany(targetEntity="GalleryTranslation", mappedBy="gallery", cascade={"persist", "remove"}) */
    private $translation;
    /** @ORM\Column(type="boolean", name="is_active", options={"default":0}) */
    private $isActive;
    /**
     * @ORM\Column(type="string", name="image")
     * @Assert\File(mimeTypes={"image/gif", "image/png", "image/jpeg"})
     */
    private $image;

    public function __construct()
    {       
        $this->translation = new ArrayCollection();   
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }
    
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }


    public function getIsActive() {
        return $this->isActive;
    }

    public function setIsActive($isActive) {
        $this->isActive = $isActive;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTranslation()
    {
        return $this->translation;
    }

    public function setTranslation(GalleryTranslation $translation)
    {
        $this->translation = $translation;
    }

    public function getCurrentTranslation(Locales $locales)
    {
        $txt = '';
        
        if($this->getTranslation()){

            foreach ($this->getTranslation() as $translation){
                if( $locales->getName() == $translation->getLocales()->getName())
                    $txt = $translation->getName();
            }
        }
        return $txt;
    }

}
