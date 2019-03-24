<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="menu_translation")
 * @ORM\Entity(repositoryClass="App\Repository\MenuTranslationRepository")
 */

class MenuTranslation
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
    * @ORM\Column(type="string", length=50)
    * @Assert\NotBlank(message="name")
    */
    private $name;

    /** @ORM\ManyToOne(targetEntity="Menu") */
    private $menu;
    /**
     *@ORM\ManyToOne(targetEntity="Locales") */
    private $locales;


    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = str_replace("'","’",$name);
    }    

    public function getMenu()
    {
        return $this->menu;
    }

    public function setMenu(Menu $menu) {
        $this->menu = $menu;
    }
    
    public function getLocales()
    {
        return $this->locales;
    }

    public function setLocales(Locales $locales)
    {
        $this->locales = $locales;
    }


}