<?php

namespace App\Repository;

use App\Entity\Available;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\DBAL\LockMode;

class AvailableRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Available::class);
    }

    public function findByProductDateTomorrow(Product $product, $startdt, $totalPax){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT a
            FROM App\Entity\Available a
            WHERE a.product = :product AND a.stock >= :stock 
            ORDER BY a.datetimestart ASC')
            ->setParameter('product', $product)
            ->setParameter('stock', $totalPax);
        return $query->execute();
    }

    public function findAvailableFromInterval($start, $end){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT a
            FROM App\Entity\Available a
            WHERE a.datetimeend <= :end
            AND a.datetimestart >= :start
            ORDER BY a.datetimestart ASC')
            ->setParameter('start', $start->format('Y-m-d'))
            ->setParameter('end', $end->format('Y-m-d'));
        return $query->execute();
    }



}
