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
use Symfony\Component\Translation\TranslatorInterface;

use App\Utils\Utility;

class HomeController extends AbstractController
{
    /**
     * @Route({
     *     "fr": "/accueil",
     *     "en": "/home"
     * }, name="home")
     */
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
            'currentpage' => 'home',
        	'page_title' => 'Home'
        ]);
    }

    /**
     * @Route({
     *     "fr": "/produit/{id}",
     *     "en": "/product/{id}"
     * }, name="product")
     */
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
            'currentpage' => 'home',
        	'page_title' => $product[0]->getName()
        ]);
    }

    /**
     * @Route("/addtocart", name="addtocart")
     */
    public function addProductToCart(EntityManagerInterface $em,Request $request): Response
    {

        $idProduct = $request->query->get('id');
        $quantity   = $request->query->get('quantity');
        $from   = $request->query->get('from') ?? 'home';
        $locale   = $request->query->get('locale') ?? 'en';

        if (!is_numeric($quantity) || !is_numeric($idProduct)) return $this->redirectToRoute('error',array('type'=>'wrong_params'));
        
        $cart = Utility::unSerializeCartToJson(Utility::getCartInSession());

        if (!isset($idProduct)) return $this->redirectToRoute('error',array('type'=>'no_product'));

        $product = $em->getRepository(Product::class)->findById($idProduct);

        if (0 == count($product)) return $this->redirectToRoute('error',array('type'=>'no_product'));

        $cart->modifyCountProduct($em, $product[0], $quantity);

        $jsonSerializedCart = Utility::serializeCartToJson($cart);

        Utility::saveCartInSession($jsonSerializedCart);

        return $this->redirectToRoute($from.'.'.$locale);//,array('product_name'=>$product[0]->getName(),'quantity'=>$quantity));
    }

    /**
     * @Route({
     *     "fr": "/panier",
     *     "en": "/cart"
     * }, name="cart")
     */
    public function cart(EntityManagerInterface $em): Response
    {
        $cart = Utility::unSerializeCartToJson(Utility::getCartInSession());

        $listProducts = $em->getRepository(Product::class)->findFromCart($cart);

        return $this->render('cart.html.twig',[
            'cart' => $cart,
            'currentpage' => 'cart',
            'list_product' => $listProducts
        ]);
    }


    /**
     * @Route("/emptycart/{redirectto}/{locale}", name="emptycart")
     */
    public function emptycart(Request $request): Response
    {
        $cart = Utility::emptyCartInSession();
        $redirectTo = $request->attributes->get('redirectto') ?? 'home';
        $locale = $request->attributes->get('locale') ?? 'en';
        return $this->redirectToRoute($redirectTo.'.'.$locale);
    }

    /**
     * @Route("/deleteproduct/{id}", name="deleteproduct")
     */
    public function deleteproduct(EntityManagerInterface $em,Request $request): Response
    {
        $idProduct = $request->attributes->get('id');
        $product = $em->getRepository(Product::class)->findById($idProduct);
        $cart = Utility::unSerializeCartToJson(Utility::getCartInSession());
        $cart->removeProduct($product[0]);
        Utility::saveCartInSession(Utility::serializeCartToJson($cart));
        
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/changelocale/{redirectto}/{locale}", name="changelocale")
     */
    public function changeLocale(Request $request): Response
    {
        $redirectTo = $request->attributes->get('redirectto') ?? 'home';
        $locale = $request->attributes->get('locale') ?? 'en';

        Utility::saveLocaleInSession($locale);

        return $this->redirectToRoute($redirectTo.'.'.$locale);
    }


    /**
     * @Route("/error", name="error")
     */
    public function error(Request $request,TranslatorInterface $translator): Response
    {
        $cart = Utility::unSerializeCartToJson(Utility::getCartInSession());

        $typeError = $request->query->get('type');
        switch ($typeError) {
            case 'wrong_params':
                $msg = $translator->trans('An error occured');
                break;
            case 'no_product':
                $msg = $translator->trans('No product');
                break;
            default:
                $msg = $translator->trans('Please contact administrator');
                break;
        }
        return $this->render('error.html.twig',[
            'msg' => $msg,
            'cart' => $cart,
            'currentpage' => 'home'
        ]);
    }

}