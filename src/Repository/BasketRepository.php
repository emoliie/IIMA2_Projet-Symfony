<?php

namespace App\Repository;

use App\Entity\Basket;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Basket>
 */
class BasketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Basket::class);
    }

    // Récupère le dernier numéro de commande pour un utilisateur
    public function findLastUserOrderNumber(User $user): int
    {
        $result = $this->createQueryBuilder('b')
            ->select('MAX(b.userOrderNumber)')
            ->where('b.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();

        return $result ?: 0; // Si aucune commande n'existe, retourne 0
    }
}
