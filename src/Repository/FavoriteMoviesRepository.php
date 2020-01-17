<?php

namespace App\Repository;

use App\Entity\FavoriteMovies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FavoriteMovies|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoriteMovies|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoriteMovies[]    findAll()
 * @method FavoriteMovies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriteMoviesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoriteMovies::class);
    }

    // /**
    //  * @return FavoriteMovies[] Returns an array of FavoriteMovies objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FavoriteMovies
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function showRanking()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('SELECT b.movie_id, COUNT(a.movie_id) ranking 
         FROM App\Entity\FavoriteMovies a 
         inner join App\Entity\FavoriteMovies b 
         on b.id = a.id 
         GROUP BY a.movie_id 
         order by ranking 
         desc limit 5');
         return $query->execute();
    }
}
