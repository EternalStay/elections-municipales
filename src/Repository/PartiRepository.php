<?php

namespace App\Repository;

use App\Entity\Parti;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Parti|null find($id, $lockMode = null, $lockVersion = null)
 * @method Parti|null findOneBy(array $criteria, array $orderBy = null)
 * @method Parti[]    findAll()
 * @method Parti[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parti::class);
    }

    public function ageMoyen($parti) {
        return $this->createQueryBuilder('p')
        ->select('AVG(o.age) as ageMoyen')
        ->join('p.politiciens', 'o', 'WITH', 'o.parti = :parti')
        ->setParameter('parti', $parti->getId())
        ->getQuery()
        ->getResult();
    }

    public function pariteM($parti) {
        return $this->createQueryBuilder('p')
        ->select('COUNT(o.id) as M')
        ->join('p.politiciens', 'o', 'WITH', 'o.parti = :parti')
        ->setParameter('parti', $parti->getId())
        ->where("o.sexe = 'M'")
        ->getQuery()
        ->getResult();
    }

    public function pariteF($parti) {
        return $this->createQueryBuilder('p')
        ->select('COUNT(o.id) as F')
        ->join('p.politiciens', 'o', 'WITH', 'o.parti = :parti')
        ->setParameter('parti', $parti->getId())
        ->where("o.sexe = 'F'")
        ->getQuery()
        ->getResult();
    }


    /*
    public function findByOrdinateur($ip) {
        return $this->createQueryBuilder('l')
        ->join('l.machine_installees', 'o', 'WITH', 'o.ip = :ip')
        ->setParameter('ip', $ip)
        ->getQuery()
        ->getResult();
    }

    public function getLogicielsEtEventuellementOrdinateurs() {
        return $this->createQueryBuilder('l')
        ->select('l.nom', 'o.ip')
        ->leftjoin('l.machine_installees', 'o')
        ->getQuery()
        ->getResult();
    }
    */

    // /**
    //  * @return Parti[] Returns an array of Parti objects
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
    public function findOneBySomeField($value): ?Parti
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
