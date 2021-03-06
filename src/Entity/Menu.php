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
    
    /** @ORM\Column(type="boolean", name="is_submenu", options={"default":0})*/
    private $isSubmenu;
    
    /**
     * @ORM\Column(type="array", name="roles")*/
    private $roles;

        /**
    *@ORM\OneToMany(targetEntity="Submenu", mappedBy="menu", cascade={"persist", "remove"}) */
    private $submenu;

    public function __construct()
    {       
        $this->translation = new ArrayCollection();  
        $this->submenu = new ArrayCollection();    
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

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    

    public function getIsSubmenu()
    {
        return $this->isSubmenu;
    }

    public function setIsSubmenu($isSubmenu)
    {
        $this->isSubmenu = $isSubmenu;
    }

    public function getSubmenu()
    {
        return $this->submenu;
    }

    public function setSubmenu($submenu)
    {
        $this->submenu = $submenu;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
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