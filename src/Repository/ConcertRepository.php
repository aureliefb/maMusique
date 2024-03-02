<?php

namespace App\Repository;

use App\Entity\Artiste;
use App\Entity\Concert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Concert>
 *
 * @method Concert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Concert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Concert[]    findAll()
 * @method Concert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConcertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        //parent::__construct($registry, Artiste::class);
        parent::__construct($registry, Concert::class);
    }

    public function listAllConcerts(): array {
        return $this->createQueryBuilder('c')
            ->select('c', 'a', 'l', 'f')
            ->leftJoin('c.artiste', 'a')
            ->leftJoin('c.lieu', 'l')
            ->leftJoin('c.Festival', 'f')
            ->orderBy('c.date_concert', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function countAllConcerts(): array
    {
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Concert[] Returns an array of Concert objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Concert
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
