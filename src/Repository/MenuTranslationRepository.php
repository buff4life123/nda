<?php

namespace App\Repository;

use App\Entity\MenuTranslation;
use App\Entity\SubmenuTranslation;

use App\Entity\Locales;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class MenuTranslationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MenuTranslation::class);
    }

    // public function getMenuTranslation(Locales $locales, User $user){

    //     $roles = str_replace("ROLE_","", $user->getRoles()[0]);

    //     $dql = 'SELECT mt.name as menu, m.path, m.icon, m.isSubmenu, (SELECT App\Entity\SubmenuTranslation as smt where smt.locales = :locales), sm.id
    //         FROM App\Entity\MenuTranslation mt
    //         JOIN mt.locales l
    //         JOIN mt.menu m
    //         JOIN App\Entity\SubmenuTranslation smt
    //         JOIN App\Entity\Submenu sm
    //         JOIN App\Entity\User u
    //         WHERE l = :locales
    //         AND mt.locales = :locales
    //         AND m.active = true 
    //         AND m.roles LIKE :roles
    //         GROUP BY m.orderBy
    //         ORDER BY m.orderBy ASC
    //         ';
    //     $query = $this->getEntityManager()->createQuery($dql)
    //       ->setParameter('locales', $locales)
    //       ->setParameter('roles', '%'.strtolower($roles). '%');
  
    //     //AND smt.locales = :locales
    //     //GROUP BY m.orderBy
    //     //dd($query);
    //     return $query->getResult();
    // }

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
