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
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Utils\Utility;

class HomeController extends AbstractController
{
    
    private $session;
    
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route({
     *     "fr": "/accueil",
     *     "en": "/home"
     * }, name="home")
     */
    public function home(EntityManagerInterface $em): Response
    {
        $products = $em->getRepository(Product::class)->findAll();

        $cart = Utility::getCartInSession($this->session);

        return $this->render('home.html.twig',[
        	'products' => $products,
            'cart' => $cart,
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

        $cart = Utility::getCartInSession($this->session);

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
        $quantity = $request->query->get('quantity');
        $from = $request->query->get('from') ?? 'home';
        $locale = Utility::getLocaleInSession($this->session);

        if (!is_numeric($quantity) || !is_numeric($idProduct)) return $this->redirectToRoute('error',array('type'=>'wrong_params'));
        
        $cart = Utility::getCartInSession($this->session);

        if (!isset($idProduct)) return $this->redirectToRoute('error',array('type'=>'no_product'));

        $product = $em->getRepository(Product::class)->findById($idProduct);

        if (0 == count($product)) return $this->redirectToRoute('error',array('type'=>'no_product'));

        $cart->modifyCountProduct($product[0], $quantity);

        Utility::saveCartInSession($this->session, $cart);

        return $this->redirectToRoute($from.'.'.$locale);
    }

    /**
     * @Route({
     *     "fr": "/panier",
     *     "en": "/cart"
     * }, name="cart")
     */
    public function cart(EntityManagerInterface $em): Response
    {
        $cart = Utility::getCartInSession($this->session);

        $listProducts = $em->getRepository(Product::class)->findFromCart($cart);

        return $this->render('cart.html.twig',[
            'cart' => $cart,
            'currentpage' => 'cart',
            'list_product' => $listProducts
        ]);
    }

    /**
     * @Route("/emptycart/{redirectto}", name="emptycart")
     */
    public function emptycart(Request $request): Response
    {
        Utility::saveCartInSession($this->session,new Cart());
        $redirectTo = $request->attributes->get('redirectto') ?? 'home';
        $locale = Utility::getLocaleInSession($this->session);
        return $this->redirectToRoute($redirectTo.'.'.$locale);
    }

    /**
     * @Route("/deleteproduct/{id}", name="deleteproduct")
     */
    public function deleteproduct(EntityManagerInterface $em,Request $request): Response
    {
        $idProduct = $request->attributes->get('id');
        $locale = Utility::getLocaleInSession($this->session);

        $product = $em->getRepository(Product::class)->findById($idProduct);
        $cart = Utility::getCartInSession($this->session);
        $cart->removeProduct($product[0]);
        Utility::saveCartInSession($this->session,$cart);
        
        return $this->redirectToRoute('cart.'.$locale);
    }

    /**
     * @Route("/changelocale/{locale}", name="changelocale")
     */
    public function changeLocale(Request $request): Response
    {
        $locale = $request->attributes->get('locale') ?? 'en';

        Utility::saveLocaleInSession($this->session,$locale);

        return $this->redirectToRoute('home.'.$locale);
    }

    /**
     * @Route("/error", name="error")
     */
    public function error(Request $request,TranslatorInterface $translator): Response
    {
        $cart = Utility::getCartInSession($this->session);

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