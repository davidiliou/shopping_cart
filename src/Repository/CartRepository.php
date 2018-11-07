<?php

namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Cart::class);
    }

        /**
     * Add product to cart
     *
     * @param int $idProduct id of the product to add
     * @param int $quantity quantity of product to add
     * @param boolean $add true if add "$quantity" products to cart, false to init quantity in cart to "$quantity"
     *
     * @return array The cart modified
     *
     * @throws \RuntimeException When product not in base
     */
    public function modifyCountProduct($idProduct, $quantity, $add)
    {
        if (0 == count($listProducts)) {
            $product = $em->getRepository(Product::class)->findById($idProduct);
            $listProducts[] = array('id' => $product->getId() ,'product' => $product, 'quantity' => $quantity );
            return $listProducts;
        }

        foreach ($listProducts as $arrayProduct) {
            if ($arrayProduct['id'] == $idProduct) {
                $arrayProduct['quantity'] = $add ? $arrayProduct['quantity'] + $quantity : $quantity;
                return $listProducts;
            }
        }

        throw new \RuntimeException(sprintf('Product not found "%s"', $idProduct));
    }
}
