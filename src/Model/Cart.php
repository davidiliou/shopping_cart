<?php

namespace App\Model;

use App\Entity\Product;

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
        return $this->price;
    }

    
    /**
     * Add product to cart
     *
     * @param @param Product the product to delete
     *
     * @return void
     *
     */
    public function removeProduct(Product $product): void
    {        
        $idProduct = $product->getId();
        if (isset($this->listProducts[$idProduct])) {
            $this->price -= $this->listProducts[$idProduct]['quantity']*$product->getPrice();
            $this->nbProducts -= $this->listProducts[$idProduct]['quantity'];
            unset($this->listProducts[$idProduct]);
            return;
        }

        return;
    }

    /**
     * Add product to cart
     *
     * @param Product the product to add
     * @param int $quantity quantity of product to add
     *
     * @return void
     *
     * @throws \RuntimeException When product not in base
     */
    public function modifyCountProduct(Product $product, int $quantity): void
    {        
        $this->nbProducts += $quantity;
        $idProduct = $product->getId();
        if (!isset($this->listProducts) || 0 == count($this->listProducts) || !isset($this->listProducts[$idProduct])) {
            $this->price += $quantity*$product->getPrice();
            $this->listProducts[$idProduct] = array('product' => $product->getName(), 'quantity' => $quantity);
            return;
        }

        if (isset($this->listProducts[$idProduct])) {
            $this->price += $quantity*$product->getPrice();
            $this->listProducts[$idProduct]['quantity'] += $quantity;
            return;
        }

        return;
    }
}