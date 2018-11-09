<?php

// src/Controller/ApiController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Api controller.
 *
 * @Route("/api")
 */
class ApiController extends Controller
{
     /**
     * Lists all Articles.
     * @FOSRest\Get("/users")
     *
     * @return array
     */
    public function showProducts(EntityManagerInterface $em)
    {        
        $products = $em->getRepository(Product::class)->findall();
        return View::create($products, Response::HTTP_OK , []);
    }
}
