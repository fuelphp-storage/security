<?php
/**
 * @package    Fuel\Security
 * @version    2.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Security\Csrf;

use Fuel\Security\Csrf;

/**
 * Security Csrf Session class
 *
 * This Csrf driver class uses a session-based token
 *
 * @package  Fuel\Security
 *
 * @since    2.0.0
 */
class Session extends Driver
{
	/**
	 * @var  string  Key to use to access the session
	 */
	protected $sessionKey = 'csrf.session-token';

	/**
	 * Constructor
	 *
	 * @param  Csrf  $parent  This class' csrf manager object
	 *
	 * @since  2.0.0
	 */
	public function __construct(Csrf $parent)
	{
		parent::__construct($parent);

		// check if the config defines a custom session key
		if (isset($parent->config['csrf']['session_key']))
		{
			$this->sessionKey = $parent->config['csrf']['session_key'];
		}
	}

	/**
	 * Generate a unique CSRF token for the given form identification
	 *
	 * @param  string  $id  // not used by this driver
	 *
	 * @since  2.0.0
	 */
	public function getToken($id)
	{
		if ( ! $token = $this->sessionRetrieve())
		{
			// generate a random token
			$token = $this->generateToken();
			$this->sessionStore($token);
		}

		return $token;
	}

	/**
	 * Validate a given token
	 *
	 * @param  string  $id      // not used by this driver
	 * @param  string  $token  Token to validate
	 *
	 * @since  2.0.0
	 */
	public function validateToken($id, $token)
	{
		return $token === $this->sessionRetrieve();
	}

	/**
	 * Store a token in the session
	 *
	 * @param  string  $token  Token issued
	 *
	 * @since  2.0.0
	 */
	protected function sessionStore($token)
	{
		$this->parent->getSession()->set($this->sessionKey, $token);
	}

	/**
	 * Get a token from the session
	 *
	 * @since  2.0.0
	 */
	protected function sessionRetrieve()
	{
		return $this->parent->getSession()->get($this->sessionKey, false);
	}

}
