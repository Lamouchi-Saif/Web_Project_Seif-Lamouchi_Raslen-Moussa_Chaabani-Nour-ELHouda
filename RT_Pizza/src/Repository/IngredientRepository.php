<?php

namespace App\Repository;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ingredient>
 */
class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    public function findAllWithStock()
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.ingredientStock', 's')
            ->addSelect('s')
            ->getQuery()
            ->getResult();
    }
    public function findAllSorted(string $sortBy, string $sortDirection)
    {
        $validSortFields = ['id', 'name', 'type', 'quantity', 'price'];
        $sortBy = in_array($sortBy, $validSortFields) ? $sortBy : 'name';
        $sortDirection = strtoupper($sortDirection) === 'DESC' ? 'DESC' : 'ASC';

        $qb = $this->createQueryBuilder('i')
            ->leftJoin('i.ingredientStock', 's')
            ->addSelect('s');

        // Special case for quantity and price which are in IngredientStock
        if (in_array($sortBy, ['quantity', 'price'])) {
            $qb->orderBy('s.' . $sortBy, $sortDirection);
        } else {
            $qb->orderBy('i.' . $sortBy, $sortDirection);
        }

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Ingredient[] Returns an array of Ingredient objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Ingredient
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
