<?php

namespace App\Repository;

use App\Entity\Menu;
use App\Entity\Submenu;
use App\Entity\Locales;
use App\Entity\MenuTranslation;
use App\Entity\SubmenuTranslation;
use App\Entity\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class MenuRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    public function getMenusByUser(User $user){

        $roles = str_replace("ROLE_","", $user->getRoles()[0]);

        $qb = $this->createQueryBuilder('m');

        return $qb
            ->where($qb->expr()->like('m.roles', '?2' ))
            ->orderBy('m.orderBy', 'ASC')
            ->setParameter(2, '%' . strtolower($roles) . '%')
            ->getQuery()
            ->getResult();
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
