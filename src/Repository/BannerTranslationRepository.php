<?php

namespace App\Repository;

use App\Entity\BannerTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class BannerTranslationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BannerTranslation::class);
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
