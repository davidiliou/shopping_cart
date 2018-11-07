<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Return all products
     *
     * @return array List of product object
     *
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('Product')->orderBy('Product.name','ASC')->getQuery()->getResult();
    }

    /**
     * find one product object from id
     *
     * @param int $id id of the product
     *
     * @return array List of product object
     *
     */
    public function findById(int $id): array
    {
        return $this->createQueryBuilder('Product')
            ->andWhere('Product.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * find list of product object from cart
     *
     * @param Cart $cart cart object
     *
     * @return array List of product object
     *
     */
    public function findFromCart($cart): array
    {
        return $this->createQueryBuilder('Product')
            ->andWhere('Product.id IN (:arrayid)')
            ->setParameter('arrayid', array_keys($cart->getListProducts()))
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
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
    public function findOneBySomeField($value): ?Product
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
