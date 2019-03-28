<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="easytext")
 * @ORM\Entity(repositoryClass="App\Repository\EasyTextRepository")
 */

class EasyText
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="text")
     */
    private $easyText;
    /**
     * @ORM\Column(type="text")
     */
    private $easyTextHtml;

    /**
    * @ORM\Column(type="string", length=50)
    * @Assert\NotBlank(message="NAME")
    */
    private $name;

    /** @ORM\ManyToOne(targetEntity="Locales") */
    private $locales;

    public function getId()
    {
        return $this->id;
    }

    public function getLocales()
    {
        return $this->locales;
    }

    public function setLocales(Locales $locales)
    {
        $this->locales = $locales;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = str_replace("'","’",$name);
    }

    public function getEasyText()
    {
        return $this->easyText;
    }

    public function setEasyText($easyText)
    {
        $this->easyText = $easyText;
    }

    public function getEasyTextHtml()
    {
        return $this->easyTextHtml;
    }

    public function setEasyTextHtml($easyTextHtml)
    {
        $this->easyTextHtml = $easyTextHtml;
    }
}