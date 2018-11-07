<?php

// src/Controller/HomeController.php
namespace App\Controller;

use App\Entity\Product;
use App\Model\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Utils\Utility;

class HomeController extends AbstractController
{
    public function home(EntityManagerInterface $em,Request $request): Response
    {
        $productName = $request->query->get('product_name');
        $quantity   = $request->query->get('quantity');

        $products = $em->getRepository(Product::class)->findAll();

        $cart = Utility::unSerializeCartToJson(Utility::getCartInSession());

        return $this->render('home.html.twig',[
        	'products' => $products,
            'cart' => $cart,
            'product_name' => $productName,
            'quantity' => $quantity,
        	'page_title' => 'Home'
        ]);
    }

    public function product(EntityManagerInterface $em,Request $request): Response
    {
    	$idProduct = $request->attributes->get('id');

    	if (!isset($idProduct)) return $this->redirectToRoute('error',array('type'=>'no_product'));

        $product = $em->getRepository(Product::class)->findById($idProduct);

        if (0 == count($product)) return $this->redirectToRoute('error',array('type'=>'no_product'));

        $cart = Utility::unSerializeCartToJson(Utility::getCartInSession());


        return $this->render('product.html.twig',[
        	'product' => $product[0],
            'cart' => $cart,
        	'page_title' => $product[0]->getName()
        ]);
    }

    public function addProductToCart(EntityManagerInterface $em,Request $request): Response
    {

        $idProduct = $request->query->get('id');
        $quantity   = $request->query->get('quantity');

        if (!is_numeric($quantity) || !is_numeric($idProduct)) return $this->redirectToRoute('error',array('type'=>'wrong_params'));
        
        $cart = Utility::unSerializeCartToJson(Utility::getCartInSession());

        if (!isset($idProduct)) return $this->redirectToRoute('error',array('type'=>'no_product'));

        $product = $em->getRepository(Product::class)->findById($idProduct);

        if (0 == count($product)) return $this->redirectToRoute('error',array('type'=>'no_product'));

        $cart->modifyCountProduct($em, $idProduct, $quantity, true);

        $jsonSerializedCart = Utility::serializeCartToJson($cart);

        Utility::saveCartInSession($jsonSerializedCart);

        return $this->redirectToRoute('home',array('product_name'=>$product[0]->getName(),'quantity'=>$quantity));

    }

    public function cart(): Response
    {
        $cart = Utility::unSerializeCartToJson(Utility::getCartInSession());

        //var_dump($cart);exit;

        return $this->render('cart.html.twig',[
            'cart' => $cart
        ]);
    }

    public function error(Request $request): Response
    {
        $typeError = $request->query->get('type');
        switch ($typeError) {
            case 'wrong_params':
                $msg = 'Une erreur s\'est produite';
                break;
            case 'no_product':
                $msg = 'Pas de produit';
                break;
            default:
                $msg = 'Un problème est survenu';
                break;
        }
        return $this->render('error.html.twig',[
            'msg' => $msg
        ]);
    }

}