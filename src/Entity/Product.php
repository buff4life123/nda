<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */

class Product
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @ORM\OneToMany(targetEntity="Event", mappedBy="product", cascade={"persist"}) */
    private $event;
    /** @ORM\ManyToOne(targetEntity="App\Entity\Category") 
     */
    private $category;

    /** @ORM\OneToMany(targetEntity="Available", mappedBy="product", cascade={"persist", "remove"}) */
    private $available;
    
    /** @ORM\OneToMany(targetEntity="ProductDescriptionTranslation", mappedBy="product", cascade={"persist", "remove"}) */
    private $product_description_translation;
    
    /** @ORM\OneToMany(targetEntity="ProductWPTranslation", mappedBy="product", cascade={"persist", "remove"}) */
    private $product_wp_translation;

    /** @ORM\OneToMany(targetEntity="Amount", mappedBy="product", cascade={"persist", "remove"}) */
    private $amount;

    /** @ORM\OneToMany(targetEntity="Price", mappedBy="product", cascade={"persist", "remove"}) */
    private $price;

    /** @ORM\Column(type="boolean", name="is_active", options={"default":0}) */
    private $isActive;
    /**
     * @ORM\Column(type="string", name="image")
     * @Assert\File(mimeTypes={"image/gif", "image/png", "image/jpeg"})
     */
    private $image;

    /** @ORM\Column(type="integer", name="availability")*/
    private $availability;

    /** @ORM\Column(type="boolean", name="highlight", options={"default":0}) */
    private $highlight = false;
    
    /** @ORM\Column(type="boolean", name="warranty_payment", options={"default":0}) */
    private $warrantyPayment = false;

     /** @ORM\Column(type="string", length=5, name="duration", options={"default":"00:00"})*/
    private $duration; 

    /** @ORM\Column(type="integer", name="order_by", nullable=true)*/
    private $orderBy;

    public function __construct()
    {   
        $this->available = new ArrayCollection();
        $this->event = new ArrayCollection();
        $this->price = new ArrayCollection();
        $this->amount = new ArrayCollection();
        $this->product_description_translation = new ArrayCollection();
        $this->product_wp_translation = new ArrayCollection();
    }

    public function getCategory() {
        return $this->category;
    }
    
    public function setCategory(Category $category) {
        $this->category = $category;
    }

    public function getWarrantyPayment()
    {
        return $this->warrantyPayment;
    }

    public function setWarrantyPayment($warrantyPayment)
    {
        $this->warrantyPayment = $warrantyPayment;
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }

    public function getAvailability()
    {
        return $this->availability;
    }

    public function setAvailability($availability)
    {
        $this->availability = $availability;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    public function getHighlight()
    {
        return $this->highlight;
    }

    public function setHighlight($highlight)
    {
        $this->highlight = $highlight;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getIsActive() {
        return $this->isActive;
    }

    public function setIsActive($isActive) {
        $this->isActive = $isActive;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function setEvent(Event $event)
    {
        $this->event = $event;
    }
    
    public function addEvent(Event $event)
    {
        $event->setProduct($this);
        $this->event->add($event);
    }


    public function addProductDescriptionTranslation(ProductDescriptionTranslation $product_description_translation)
    {
        $product_description_translation->setProduct($this);
        $this->product_description_translation->add($product_description_translation);
    }
    
    public function removeProductDescriptionTranslation(ProductDescriptionTranslation $product_description_translation)
    {
        $this->product_description_translation->removeElement($product_description_translation);
    }
    
    public function getProductDescriptionTranslation()
    {
        return $this->product_description_translation;
    }

    public function setProductDescriptionTranslation(ProductDescriptionTranslation $product_description_translation)
    {
        $this->product_description_translation = $product_description_translation;
    }

    public function getCurrentTranslationName(Locales $locales)
    {
        $txt = '';
        if($this->getProductDescriptionTranslation()){
            foreach ($this->getProductDescriptionTranslation() as $translation){
                if( $locales->getName() == $translation->getLocales()->getName())
                    $txt = $translation->getName();
            }
        }
        return $txt;
    }
    
    public function getCurrentTranslationHtml(Locales $locales)
    {
        $txt = '';
        if($this->getProductDescriptionTranslation()){
            foreach ($this->getProductDescriptionTranslation() as $translation){
                if( $locales->getHtml() == $translation->getLocales()->getName())
                    $txt = $translation->getHtml();
            }
        }
        return $txt;
    }

    public function addProductWPTranslation(ProductWPTranslation $product_wp_translation)
    {
        $product_wp_translation->setProduct($this);
        $this->product_wp_translation->add($product_wp_translation);
    }
    
    public function removeProductWPTranslation(ProductWPTranslation $product_wp_translation)
    {
        $this->product_wp_translation->removeElement($product_wp_translation);
    }
    
    public function getProductWPTranslation()
    {
        return $this->product_wp_translation;
    }

    public function setProductWPTranslation(ProductWPTranslation $product_wp_translation)
    {
        $this->product_wp_translation = $product_wp_translation;
    }

    public function getCurrentTranslationWP(Locales $locales)
    {
        $txt = '';
        if($this->getProductWPTranslation()){
            foreach ($this->getProductWPTranslation() as $translation){
                if( $locales->getName() == $translation->getLocales()->getName())
                    $txt = $translation->getName();
            }
        }
        return $txt;
    }
    


    public function addAmount(Amount $amount)
    {
        $amount->setProduct($this);
        $this->amount->add($amount);
    }
    
    public function removeAmount(Amount $amount)
    {
        $this->amount->removeElement($amount);
    }
    
    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount(Amount $amount)
    {
        $this->amount = $amount;
    }








/*
    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice(Price $price)
    {
        $this->price = $price;
    }
    
    public function addPrice(Price $price)
    {
        $price->setProduct($this);
        $this->price->add($price);
    }
    
    public function removePrice(Price $price)
    {
        $this->price->removeElement($price);
    }
    
*/





    public function removeEvent(Event $event)
    {
        $this->event->removeElement($event);
    }

    public function getAvailable()
    {
        return $this->available;
    }

    public function setAvailable(Available $available)
    {
        $this->available = $available;
    }
    
    public function addAvailable(Available $available)
    {
        $available->setProduct($this);
        $this->available->add($available);
    }
    
    public function removeAvailable(Available $available)
    {
        $this->available->removeElement($available);
    }

}