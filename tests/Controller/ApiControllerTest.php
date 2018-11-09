<?php

// tests/Controller/ApiControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testGetUsers()
    {
		$client = static::createClient();

        $crawler = $client->request('GET', '/api/users');

        //test home page status code 
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
		
		/*$nbDivProduct = $crawler->filter('div[data-type="product"]');
    	
    	//test count product on home page
    	$this->assertCount(12,$nbDivProduct);*/
    }
}