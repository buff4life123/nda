<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="available")
 * @ORM\Entity(repositoryClass="App\Repository\AvailableRepository")
 */

class Available
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
    * @ORM\Column(type="datetime") */
    private $datetimestart;
    /**
    * @ORM\Column(type="integer", name="stock", nullable=true)
    */
    private $stock;
    /**
    * @ORM\Column(type="integer", name="lotation", nullable=true)
    */
    private $lotation;
    /**
    * @ORM\Column(type="datetime") */
    private $datetimeend;

    /** @ORM\ManyToOne(targetEntity="Product", inversedBy="available") */
    private $product;

    public function getId()
    {
        return $this->id;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /*
    must receive the date and hour of the booking
    */
    public function setDatetimeStart($datetimestart)
    {
        $this->datetimestart = $datetimestart;
    }

    public function getDatetimeStart()
    {
        return $this->datetimestart;
    }

    public function setDatetimeEnd($datetimeend)
    {
        $this->datetimeend = $datetimeend;
    }

    public function getDatetimeEnd()
    {
        return $this->datetimeend;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
    }
    public function getStock()
    {
        return $this->stock;
    }

    public function setStock($stock)
    {

        $this->stock = str_replace("'","’",$stock);
    }

    public function getLotation()
    {
        return $this->lotation;
    }

    public function setLotation($lotation)
    {
        $this->lotation = str_replace("'","’",$lotation);
    }

}
