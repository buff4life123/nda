<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     *@ORM\ManyToOne(targetEntity="Locales") */
    private $locales;
    
    /**
     *@ORM\ManyToOne(targetEntity="MenuTranslation") */
    private $menuTranslation;

    /** @ORM\Column(type="boolean", name="link_active",nullable=true)*/
    private $link_active = false;

    public function getId()
    {
        return $this->id;
    }

    public function getMenuTranslation()
    {
        return $this->menuTranslation;
    }

    public function setMenuTranslation(MenuTranslation $menuTranslation)
    {
        $this->menuTranslation = $menuTranslation;
    }

    public function getLocales()
    {
        return $this->locales;
    }

    public function setLocales(Locales $locales)
    {
        $this->locales = $locales;
    }

    public function getLinkActive()
    {
        return $this->link_active;
    }

    public function setLinkActive($link_active)
    {
        $this->link_active = $link_active;
    }
}