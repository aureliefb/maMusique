<?php

namespace App\Repository;

use App\Entity\Top50;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Top50>
 *
 * @method Top50|null find($id, $lockMode = null, $lockVersion = null)
 * @method Top50|null findOneBy(array $criteria, array $orderBy = null)
 * @method Top50[]    findAll()
 * @method Top50[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Top50Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Top50::class);
    }


    public function listAllTitres(): ?Query
    {
        return $this->createQueryBuilder('t')
            ->select('t', 's')
            ->leftJoin('t.style','s')
            ->orderBy('t.titre', 'ASC')
            ->getQuery();
        //->getResult() // à utiliser si je veux retourner les données
        ;
    }

    public function countAllTitres(): array
    {
        return $this->createQueryBuilder('t')
            ->select('count(t.id)')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Top50[] Returns an array of Top50 objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Top50
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
