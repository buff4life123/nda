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

    public function deleteExpiredFolders($startDateTime) {

        if ($startDateTime) 
            $filter = $this->createQueryBuilder('p')
            ->andWhere('p.created_date <= :start')
            ->andWhere('p.folder != :f')
            ->setParameter('start', $startDateTime)
            ->setParameter('f', "")
            ->orderBy('p.created_date', 'ASC')
            ->getQuery();

        return $filter->execute();
    }
    
    public function filter($start, $end){

        if ($start && $end)

            $filter = $this->createQueryBuilder('b')
                ->andWhere('b.created_date >= :start')
                ->andWhere('b.created_date <= :end')
                ->setParameter('start', $start)
                ->setParameter('end', $end)
                ->orderBy('b.created_date', 'ASC')
                ->getQuery();

        else if($start){
    
            $filter = $this->createQueryBuilder('b')
                ->andWhere('b.created_date = :start')
                ->setParameter('start', $start)
                ->orderBy('b.created_date', 'ASC')
                ->getQuery();
        }

        else if($end){
    
            $filter = $this->createQueryBuilder('b')
                ->andWhere('b.created_date = :end')
                ->setParameter('end', $end)
                ->orderBy('b.created_date', 'ASC')
                ->getQuery();
        }

        return $filter->execute();

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
