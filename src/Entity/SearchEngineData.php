<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="search_engine_data")
 * @ORM\Entity(repositoryClass="App\Repository\SearchEngineDataRepository")
 */

class SearchEngineData
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
    * @ORM\Column(type="string", length=50)
    * @Assert\NotBlank(message="title")
    */
    private $title_tag;
    /**
     * @ORM\Column(type="meta_description")
     */
    private $meta_description;
    /**
     * @ORM\Column(type="meta_description")
     */
    private $meta_keywords;

    /** @ORM\ManyToOne(targetEntity="Company", inversedBy="search_engine_data") */
    private $company;

    /** @ORM\ManyToOne(targetEntity="Locales") */
    private $locales;

    public function getId()
    {
        return $this->id;
    }

    public function getTitleTag()
    {
        return $this->title_tag;
    }

    public function setTitleTag($title_tag)
    {
        $this->title_tag = str_replace("'","’",$title_tag);
    }  
    
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    public function setMetaDescription($meta_description)
    {
        $this->meta_description = str_replace("'","’",$meta_description);
    }

    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    public function setMetaKeywords($meta_keywords)
    {
        $this->meta_keywords = str_replace("'","’",$meta_keywords);
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