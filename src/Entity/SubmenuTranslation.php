<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="submenu_translation")
 * @ORM\Entity(repositoryClass="App\Repository\SubmenuTranslationRepository")
 */

class SubmenuTranslation
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

    /** @ORM\ManyToOne(targetEntity="Submenu", inversedBy="translation") */
    private $submenu;
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

    public function getSubmenu()
    {
        return $this->submenu;
    }

    public function setSubmenu(Submenu $submenu) {
        $this->submenu = $submenu;
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