<?php

namespace App\Utils;

use App\Model\Cart;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Utility
{
	public static function saveCartInSession(SessionInterface $session,Cart $cart): void
	{
		$encoders = array(new JsonEncoder());
        $normalizers = array(new PropertyNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
		$session->set('cart', $serializer->serialize($cart, 'json'));
		return;
	}

	public static function getCartInSession(SessionInterface $session): cart
	{
		$jsonCart = $session->get('cart') ?? '{}';
		$encoders = array(new JsonEncoder());
        $normalizers = array(new PropertyNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        return $serializer->deserialize($jsonCart, Cart::class, 'json');
	}

	public static function emptyCartInSession(SessionInterface $session): string
	{
		$session->set('cart', '{}');
		return '{}';
	}

	public static function saveLocaleInSession(SessionInterface $session,string $locale): void
	{
		$locale = in_array($locale, array('en','fr')) ? $locale : 'en';
		$session->set('_locale', $locale);
	}

	public static function getLocaleInSession(SessionInterface $session): string
	{
		return ($session->get('_locale') ?? 'en');
	}
}