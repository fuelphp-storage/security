<?php
/**
 * @package    Fuel\Security
 * @version    2.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2014 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Security\Csrf;

use Fuel\Security\Csrf;

/**
 * Security Csrf Driver class
 *
 * @package  Fuel\Security
 *
 * @since    2.0.0
 */
abstract class Driver
{
	/**
	 * @var  Csrf  security manager that spawned this driver
	 *
	 * @since  2.0.0
	 */
	protected $parent;

	/**
	 * Constructor
	 *
	 * @param  Csrf  $parent  This class' csrf manager object
	 *
	 * @since  2.0.0
	 */
	public function __construct(Csrf $parent)
	{
		// store the object passed
		$this->parent = $parent;
	}

	/**
	 * Generate a unique CSRF token for the given form identification
	 *
	 * @param  string  $id  Unique identification of the object to protect
	 *
	 * @since  2.0.0
	 */
	abstract public function getToken($id);

	/**
	 * Validate a given token
	 *
	 * @param  string  $id     Unique identification of the object to protect
	 * @param  string  $token  Token to validate
	 *
	 * @since  2.0.0
	 */
	abstract public function validateToken($form_id, $token);

	/**
	 * generate a random token
	 *
	 * @return  string  the generated token
	 *
	 * @since  2.0.0
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
