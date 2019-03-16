<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="App\Repository\MenuRepository")
 */

class Menu
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     *@ORM\OneToMany(targetEntity="MenuTranslation", mappedBy="menu") */
    private $menuTranslation;

    /** @ORM\Column(type="boolean", name="link_active", options={"default":0})*/
    private $link_active;

    public function __construct()
    {       
        $this->menuTranslation = new ArrayCollection();   
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTranslation()
    {
        return $this->menuTranslation;
    }

    public function setTranslation(MenuTranslation $menuTranslation)
    {
        $this->menuTranslation = $menuTranslation;
    }

    public function getLinkActive()
    {
        return $this->link_active;
    }

    public function setLinkActive($link_active)
    {
        $this->link_active = $link_active;
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