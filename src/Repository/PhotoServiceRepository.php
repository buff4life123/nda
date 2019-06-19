<?php

namespace App\Repository;

use App\Entity\PhotoService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ClientPhotoService|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientPhotoService|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientPhotoService[]    findAll()
 * @method ClientPhotoService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class PhotoServiceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhotoService::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('b')
            ->where('b.something = :value')->setParameter('value', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
