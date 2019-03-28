<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="warning_translation")
 * @ORM\Entity(repositoryClass="App\Repository\WarningTranslationRepository")
 */

class WarningTranslation
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
    /** @ORM\ManyToOne(targetEntity="Warning", inversedBy="translation") */
    private $warning;
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
    
    public function getWarning()
    {
        return $this->warning;
    }

    public function setWarning(Warning $warning) {
        $this->warning = $warning;
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