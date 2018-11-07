<?php

// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 1; $i <= 12; $i++) {
            $product = new Product();
            $product->setName('product '.$i);
            $product->setDescription('Product '.$i.'  : Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas et maximus lorem. Phasellus a nisi convallis est ornare facilisis. Sed porttitor dolor tincidunt justo efficitur dapibus. Morbi vitae est eu libero eleifend ultrices. Aenean dignissim nunc non elit molestie fermentum. Cras pellentesque arcu nec enim consequat tempus. Ut imperdiet at libero ut tempor. Sed condimentum sed nunc non vulputate. Cras in sapien at lectus lacinia aliquet vel eu odio.');
            $product->setPrice(mt_rand(10, 100));
            $product->setImageFilename($i.'.jpg');
            $manager->persist($product);
        }

        $manager->flush();
    }
}