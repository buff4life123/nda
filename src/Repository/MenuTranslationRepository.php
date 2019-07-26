<?php

namespace App\Repository;

use App\Entity\MenuTranslation;
use App\Entity\Locales;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class MenuTranslationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MenuTranslation::class);
    }

    public function getMenuTranslation(Locales $locales){
        $dql = 'SELECT  mt.name
            FROM App\Entity\MenuTranslation mt
            JOIN mt.locales l
            JOIN mt.menu m
            WHERE l = :locales
            AND m.active = true
            ORDER BY m.orderBy ASC
            ';
        $query = $this->getEntityManager()->createQuery($dql)
          ->setParameter('locales', $locales);
  
        return $query->getResult();
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
