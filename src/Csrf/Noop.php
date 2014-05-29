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

/**
 * Security Csrf Noop class
 *
 * This class implements Noop, it doesn't do anything and validates all input
 *
 * @package  Fuel\Security
 *
 * @since    2.0.0
 */
class Noop extends Driver
{
	/**
	 * Generate a unique CSRF token for the given form identification
	 *
	 * @param  string  $id  Unique identification of the object to protect
	 *
	 * @since  2.0.0
	 */
	public function getToken($id)
	{
		return 'dummy-token';
	}

	/**
	 * Validate a given token
	 *
	 * @param  string  $id     Unique identification of the object to protect
	 * @param  string  $token  Token to validate
	 *
	 * @since  2.0.0
	 */
	public function validateToken($id, $token)
	{
		return $token === 'dummy-token';
	}
}
