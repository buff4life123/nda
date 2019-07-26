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
     *@ORM\OneToMany(targetEntity="MenuTranslation", mappedBy="menu", cascade={"persist", "remove"})  */
    private $translation;

    /** @ORM\Column(type="integer", name="order_by", nullable=true)*/
    private $orderBy;

    /** @ORM\Column(type="boolean", name="active", options={"default":0})*/
    private $active;

    /** @ORM\Column(type="string", length=100, name="icon")*/
    private $icon;

    /** @ORM\Column(type="string", length=200, name="path")*/
    private $path;
    

    public function __construct()
    {       
        $this->translation = new ArrayCollection();   
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTranslation()
    {
        return $this->translation;
    }

    public function setTranslation(MenuTranslation $translation)
    {
        $this->translation = $translation;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    public function getIPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
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