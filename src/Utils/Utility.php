<?php

namespace App\Utils;

use App\Model\Cart;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\HttpFoundation\Session\Session;

class Utility
{
	public static function serializeCartToJson(Cart $cart): string
	{
		$encoders = array(new JsonEncoder());
        $normalizers = array(new PropertyNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        return $serializer->serialize($cart, 'json');
	}

	public static function unSerializeCartToJson(string $jsonCart): Cart
	{
		$encoders = array(new JsonEncoder());
        $normalizers = array(new PropertyNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        return $serializer->deserialize($jsonCart, Cart::class, 'json');
	}

	public static function saveCartInSession(string $strCart): void
	{
		$session = new Session();
		//$session->start();
		$session->set('cart', $strCart);
	}

	public static function getCartInSession(): string
	{
		$session = new Session();
		//$session->start();
		return $session->get('cart') ?? '{}';
	}
}