<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="banner_translation")
 * @ORM\Entity(repositoryClass="App\Repository\BannerTranslationRepository")
 */

class BannerTranslation
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
    * @ORM\Column(type="string", length=50)
    * @Assert\NotBlank(message="Texto *")
    */
    private $name;
    /** @ORM\ManyToOne(targetEntity="Banner") */
    private $banner;
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
        $this->name = $name;
    }
    
    public function getBanner()
    {
        return $this->banner;
    }

    public function setBanner(Banner $banner) {
        $this->banner = $banner;
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