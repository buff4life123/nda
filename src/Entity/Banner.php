<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="banner")
 * @ORM\Entity(repositoryClass="App\Repository\BannerRepository")
 */

class Banner
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;   
    /**
     * @ORM\Column(type="string", name="image", nullable=true)
     */
    private $image;
    /** @ORM\Column(type="boolean", name="is_active", options={"default":0}) */
    private $isActive;
    /** @ORM\Column(type="integer", name="order_by", nullable=true)*/
    private $orderBy;
    /**
     *@ORM\OneToMany(targetEntity="BannerTranslation", mappedBy="banner", cascade={"persist", "remove"}) */
    private $bannerTranslation;
    /** @ORM\Column(type="boolean", name="text_active", options={"default":0}) */
    private $text_active;

    public function __construct()
    {       
        $this->bannerTranslation = new ArrayCollection();   
    }

    public function getId()
    {
        return $this->id;
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
    public function getIsActive() {
        return $this->isActive;
    }

    public function setIsActive($isActive) {
        $this->isActive = $isActive;
    }

    public function getTextActive() {
        return $this->text_active;
    }

    public function setTextActive($text_active) {
        $this->text_active = $text_active;
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }

    public function getTranslation()
    {
        return $this->bannerTranslation;
    }

    public function setTranslation(BannerTranslation $bannerTranslation)
    {
        $this->bannerTranslation = $bannerTranslation;
    }

    public function getCurrentTranslation(Locales $locales)
    {
        $txt = '';
        
        if($this->getTranslation()){

            foreach ($this->getTranslation() as $translation){
                if( $locales->getName() == $translation->getLocales()->getName())
                    if($this->getTextActive())
                        $txt = $translation->getName();
            }
        }
        return $txt;
    }

}