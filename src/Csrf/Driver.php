<?php
/**
 * @package    Fuel\Security
 * @version    2.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2015 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Security\Csrf;

use Fuel\Security\Csrf;

/**
 * Security Csrf Driver class
 */
abstract class Driver
{
	/**
	 * @var Csrf
	 */
	protected $parent;

	/**
	 * @param Csrf $parent
	 */
	public function __construct(Csrf $parent)
	{
		// store the object passed
		$this->parent = $parent;
	}

	/**
	 * Generates a unique CSRF token for the given form identification
	 *
	 * @param string $id
	 */
	abstract public function getToken($id);

	/**
	 * Validates a given token
	 *
	 * @param string $id
	 * @param string $token
	 */
	abstract public function validateToken($form_id, $token);

	/**
	 * Generates a random token
	 *
	 * @return string
	 */
	protected function generateToken()
	{
		if (function_exists('openssl_random_pseudo_bytes'))
		{
			$token = base64_encode( openssl_random_pseudo_bytes(32));
		}
		elseif (function_exists("hash_algos") and in_array("sha256",hash_algos()))
		{
			// generate a random token using sha256
			$token = hash("sha256",mt_rand(0,mt_getrandmax()));
		}
		else
		{
			// use a randomizer algorithm if we don't have hash-sha256 available
			$token='';
			for ($i=0;$i<64;++$i)
			{
				$r=mt_rand(0,35);
				$token .= $r<26 ? chr(ord('a')+$r) : chr(ord('0')+$r-26);
			}
		}

		return $token;
	}
}
