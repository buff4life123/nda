<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="warning")
 * @ORM\Entity(repositoryClass="App\Repository\WarningRepository")
 */

class Warning
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
    * @ORM\Column(type="boolean", name="is_active", options={"default":0})
     */
    private $is_active;
    /**
     *@ORM\OneToMany(targetEntity="WarningTranslation", mappedBy="warning", cascade={"persist", "remove"}) */
    private $translation;

    public function __construct()
    {       
        $this->translation = new ArrayCollection();   
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIsActive()
    {
        return $this->is_active;
    }

    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
    }
    
    public function getTranslation()
    {
        return $this->translation;
    }

    public function setTranslation(WarningTranslation $translation)
    {
        $this->translation = $translation;
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