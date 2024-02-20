<?php

namespace App\Repository;

use App\Entity\Festival;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Festival>
 *
 * @method Festival|null find($id, $lockMode = null, $lockVersion = null)
 * @method Festival|null findOneBy(array $criteria, array $orderBy = null)
 * @method Festival[]    findAll()
 * @method Festival[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FestivalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Festival::class);
    }

    /**
     * @return Festivals[] Returns an array of Festivals objects
     */
    public function listAllFestivals(): ?Query
    {
        return $this->createQueryBuilder('f')
            ->select('f', 'l')
            ->leftJoin('f.lieu', 'l')
            ->orderBy('f.nom_festival', 'ASC')
            ->getQuery();
        //->getResult() // à utiliser si je veux retourner les données
        ;
    }


    public function countAllFestivals(): array
    {
        return $this->createQueryBuilder('f')
            ->select('count(f.id)')
            ->getQuery()
            ->getResult();
    }

/*    public function getFestivalName($id_lieu): array
    {
        return $this->createQueryBuilder('f')
            ->select('f', 'l')
            ->leftJoin('f.lieu', 'l')
            ->where("f.lieu = $id_lieu")
            ->orderBy('f.nom_festival', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }*/

//    /**
//     * @return Festival[] Returns an array of Festival objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Festival
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
