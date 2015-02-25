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

/**
 * Security Csrf Noop class
 *
 * This class implements Noop, it doesn't do anything and validates all input
 */
class Noop extends Driver
{
	/**
	 * {@inheritdoc}
	 */
	public function getToken($id)
	{
		return 'dummy-token';
	}

	/**
	 * {@inheritdoc}
	 */
	public function validateToken($id, $token)
	{
		return $token === 'dummy-token';
	}
}
