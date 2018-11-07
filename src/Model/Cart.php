<?php

namespace App\Model;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class Cart
{

	private $listProducts = array();
    private $nbProducts = 0;
    private $price = 0;

    public function getNbProducts(): int
    {
        return $this->nbProducts;
    }

    public function setNbProducts(): void
    {
        if (!isset($this->listProducts) || 0 == count($this->listProducts)) return;
        foreach ($this->listProducts as $arrayProduct) {
            $this->nbProducts += $arrayProduct['quantity'];
        }
    }

    public function getListProducts(): array
    {
        return $this->listProducts;
    }

    public function getPrice(): int
    {
        return $price;
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
    public function modifyCountProduct(EntityManagerInterface $em, $idProduct, $quantity, $add): void
    {        
        $this->nbProducts += $quantity;
        if (!isset($this->listProducts) || 0 == count($this->listProducts) || !isset($this->listProducts[$idProduct])) {
            $product = $em->getRepository(Product::class)->findById($idProduct);
            $this->listProducts[$product[0]->getId()] = array('product' => $product[0]->getName(), 'quantity' => $quantity, 'price' => $product[0]->getPrice());
            return;
        }

        if (isset($this->listProducts[$idProduct])) {
            $this->listProducts[$idProduct]['quantity'] = $add ? $this->listProducts[$idProduct]['quantity'] + $quantity : $quantity;
            return;
        }

        return;
    }
}