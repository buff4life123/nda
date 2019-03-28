<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="product_description_translation")
 * @ORM\Entity(repositoryClass="App\Repository\ProductDescriptionTranslationRepository")
 */

class ProductDescriptionTranslation
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
    private $html;
    /**
    * @ORM\Column(type="string", length=50)
    * @Assert\NotBlank(message="name")
    */
    private $name;
    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="product_description_translation") 
     */
    private $product;
    /** 
    * @ORM\ManyToOne(targetEntity="Locales")
    */
    private $locales;

    public function getId()
    {
        return $this->id;
    }
    public function getProduct()
    {
        return $this->product;
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
    }
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = str_replace("'","’",$name);
    } 
    public function getHtml()
    {
        return $this->html;
    }

    public function setHtml($html)
    {
        $this->html = $html;
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