<?php

// tests/Controller/HomeControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

class HomeControllerTest extends WebTestCase
{
    public function testHome()
    {
		$client = static::createClient();

		$session = $client->getContainer()->get('session');

        $crawler = $client->request('GET', '/home');



        //test home page status code 
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
		
		$nbDivProduct = $crawler->filter('div[data-type="product"]');
    	
    	//test count product on home page
    	$this->assertCount(12,$nbDivProduct);
    }
}