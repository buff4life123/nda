<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Money\Money;

/**
 * @ORM\Entity
 * @ORM\Table(name="price")
 * @ORM\Entity(repositoryClass="App\Repository\PriceRepository")
 */



class Price
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="price") 
     */
    private $product;
    /** @ORM\OneToMany(targetEntity="PriceTranslation", mappedBy="price", cascade={"persist", "remove"}) */
    private $translation;
    /** @ORM\Column(type="boolean", name="is_active", options={"default":0}) */
    private $isActive;
    /** @ORM\Column(type="boolean", name="is_child", options={"default":0}) */
    private $isChild;
    /**
    * @Assert\NotBlank(message="amount")
    * @ORM\Column(type="money", name="amount", options={"unsigned"=true}) 
    */
    private $amount;
    
    public function __construct(){       
        $this->translation = new ArrayCollection();   
    }

    public function getId()
    {
        return $this->id;
    }
    /** 
     * @return \Money\Money
     */
    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount(Money $amount)
    {
        $this->amount = $amount;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
    }

    public function getIsActive() {
        return $this->isActive;
    }

    public function setIsActive($isActive) {
        $this->isActive = $isActive;
    }

    public function getIsChild() {
        return $this->isChild;
    }

    public function setIsChild($isChild) {
        $this->isChild = $isChild;
    }
    
    public function getTranslation()
    {
        return $this->translation;
    }

    public function setTranslation(PriceTranslation $translation)
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
