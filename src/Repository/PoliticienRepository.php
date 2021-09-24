<?php

namespace App\Repository;

use App\Entity\Politicien;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Politicien|null find($id, $lockMode = null, $lockVersion = null)
 * @method Politicien|null findOneBy(array $criteria, array $orderBy = null)
 * @method Politicien[]    findAll()
 * @method Politicien[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PoliticienRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Politicien::class);
    }

    // /**
    //  * @return Politicien[] Returns an array of Politicien objects
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
    public function findOneBySomeField($value): ?Politicien
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
