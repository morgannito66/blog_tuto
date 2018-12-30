<?php

namespace App\Repository;

use App\Entity\PaymentCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PaymentCommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentCommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentCommande[]    findAll()
 * @method PaymentCommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentCommandeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PaymentCommande::class);
    }

    // /**
    //  * @return PaymentCommande[] Returns an array of PaymentCommande objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PaymentCommande
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
