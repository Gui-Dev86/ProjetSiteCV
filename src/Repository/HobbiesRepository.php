<?php

namespace App\Repository;

use App\Entity\Hobbies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Hobbies>
 *
 * @method Hobbies|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hobbies|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hobbies[]    findAll()
 * @method Hobbies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HobbiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hobbies::class);
    }

    /**
     * Count all Hobbies
     * @return void 
     */
    public function getTotalHobbiesAdmin(){
        $query = $this->createQueryBuilder('a')
            ->select('COUNT(a)')
        ;
        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * Returns all Hobbies
     * @return void 
     */
    public function getPaginateHobbiesAdmin($page, $limit){
        $query = $this->createQueryBuilder('a')
            ->orderBy('a.hobbiesName', 'DESC')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit)
        ;
        return $query->getQuery()->getResult();
    }

//    /**
//     * @return Hobbies[] Returns an array of Hobbies objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Hobbies
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
