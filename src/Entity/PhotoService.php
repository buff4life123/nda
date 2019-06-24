<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="photo_service")
 * @ORM\Entity(repositoryClass="App\Repository\PhotoServiceRepository")
 */

class PhotoService
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
    /**
    * @ORM\Column(type="string", length=50)
    * @Assert\NotBlank(message="email")
    */
    private $email;
    /**
     * @ORM\Column(type="string", length=20, name="telephone")
     * @Assert\NotBlank(message="telephone")
     */
    private $telephone;
    /**
    * @ORM\Column(type="datetime") 
    */
    private $created_date;
    /**
    * @ORM\Column(type="string", length=50)
    */
    private $folder;

    
    public function getId()
    {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getTelephone() {
        return $this->telephone;
    }

    public function setTelephone($telephone) {
        $this->telephone = $telephone;
    }

    public function getCreatedDate() {
        return $this->created_date;
    }

    public function setCreatedDate($created_date) {
        $this->created_date = $created_date;
    }

    public function getFolder() {
        return $this->folder;
    }

    public function setFolder($folder) {
        $this->folder = $folder;
    }
}
