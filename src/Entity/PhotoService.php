<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="gallery")
 * @ORM\Entity(repositoryClass="App\Repository\GalleryRepository")
 */

class PhotoService
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
    * @ORM\Column(type="string", length=50)
    * @Assert\NotBlank(message="client_name")
    */
    private $client_name;
    /**
    * @ORM\Column(type="string", length=50)
    * @Assert\NotBlank(message="client_email")
    */
    private $client_email;
    /**
     * @ORM\Column(type="string", length=20, name="telephone", nullable=true)
     */
    private $client_telephone;
    /**
    * @ORM\Column(type="datetime") 
    */
    private $createdAt;
    /**
    * @ORM\Column(type="string", length=50)
    */
    private $folder_name;

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
