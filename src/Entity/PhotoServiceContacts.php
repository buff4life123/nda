<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="photo_service_contacts")
 * @ORM\Entity(repositoryClass="App\Repository\PhotoServiceContactsRepository")
 */

class PhotoServiceContacts
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
    * @ORM\Column(type="string", length=50)
    * @Assert\NotBlank(message="Email *")
    */
    private $email;
    /** @ORM\ManyToOne(targetEntity="PhotoService", inversedBy="photo_service_contacts") */
    private $photo_service;

    public function getId()
    {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function getPhotoService()
    {
        return $this->photo_service;
    }

    public function setPhotoService(PhotoService $photo_service) {
        $this->photo_service = $photo_service;
    }
    

}