<?php

namespace App\Repository;

use App\Entity\Annonces;
use App\Entity\User;
use App\Data\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Annonces>
 */
class AnnoncesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Annonces::class);
        $this->paginator = $paginator;
    }

    public function findSearch(SearchData $search)
    {
        $query = $this->createQueryBuilder('a')
                      ->select('a')
                      ->andWhere('a.ACTIVE = 1')
                    //   ->orderBy('a.ReferencementPaymentOK', 'DESC')
                    //   ->addOrderBy('a.DateBoost', 'DESC')
                      ->addOrderBy('a.id', 'DESC');
    
        if (!empty($search->q)) {
            $query->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('a.Titre', ':q'),
                    $query->expr()->like('a.TypeVehicule', ':q'),
                    $query->expr()->like('a.Description', ':q')
                )
            )
            ->setParameter('q', "%{$search->q}%");
        }
    
        if (!empty($search->Region) && $search->Region !== 'Toutes les regions') {
            $query->andWhere("a.Region LIKE :Region")
                  ->setParameter('Region', "%{$search->Region}%");
        }
    
        if (!empty($search->TypeVehicule) && $search->TypeVehicule !== 'Peu importe') {
            $query->andWhere("a.TypeVehicule LIKE :TypeVehicule")
                  ->setParameter('TypeVehicule', "%{$search->TypeVehicule}%");
        }
    
        if (!empty($search->min)) {
            $query->andWhere('a.Prix >= :min')
                  ->setParameter('min', $search->min);
        }
    
        if (!empty($search->max)) {
            $query->andWhere('a.Prix <= :max')
                  ->setParameter('max', $search->max);
        }
    
        // Utilisation de KnpPaginator pour la pagination
        $query = $query->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page, // La page actuelle
            10 // Nombre d'éléments par page
        );
    }
    
    

    public function getTotalPages(SearchData $searchData): int
    {
        // Votre logique pour récupérer le nombre total d'annonces
        $queryBuilder = $this->createQueryBuilder('a');
        $totalResults = (int) $queryBuilder
            ->select('COUNT(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return (int) ceil($totalResults / 40);
    }

    /**
     * Retourne les annonces d'un utilisateur spécifique
     *
     */
    public function findAnnoncesByUser(User $user): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.User = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Annonces[] Returns an array of Annonce objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Annonce
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
