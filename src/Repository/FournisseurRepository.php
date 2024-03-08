<?php

namespace App\Repository;

use App\Entity\Fournisseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Fournisseur>
 *
 * @method Fournisseur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fournisseur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fournisseur[]    findAll()
 * @method Fournisseur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FournisseurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fournisseur::class);
    }

    public function searchFournisseurs(string $query): array
    {
        return $this->createQueryBuilder('f')
            ->leftJoin('f.societe', 's') 
            ->where('f.nom LIKE :query')
            ->orWhere('f.adresse LIKE :query')
            ->orWhere('f.numdetel LIKE :query')
            ->orWhere('f.email LIKE :query')
            ->orWhere('s.societe LIKE :query') 
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('f.id', 'ASC') 
            ->getQuery()
            ->getResult();
    }

    public function findAllSorted(string $sort, string $direction): array
    {
        $queryBuilder = $this->createQueryBuilder('f');

        $this->addOrderBy($queryBuilder, $sort, $direction);

        return $queryBuilder->getQuery()->getResult();
    }

    private function addOrderBy(QueryBuilder $queryBuilder, string $sort, string $direction): void
    {
        $allowedFields = ['id', 'nom', 'adresse', 'numdetel', 'email'];

        if (!in_array($sort, $allowedFields, true)) {
            throw new \InvalidArgumentException('Invalid sort field.');
        }

        $queryBuilder->orderBy('f.' . $sort, $direction);
    }



//    /**
//     * @return Fournisseur[] Returns an array of Fournisseur objects
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

//    public function findOneBySomeField($value): ?Fournisseur
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
