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
 * Security Csrf Session class
 *
 * This Csrf driver class uses a session-based token
 */
class Session extends Driver
{
	/**
	 * @var string
	 */
	protected $sessionKey = 'csrf.session-token';

	/**
	 * @param Csrf $parent
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
	 * {@inheritdoc}
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
	 * {@inheritdoc}
	 */
	public function validateToken($id, $token)
	{
		return $token === $this->sessionRetrieve();
	}

	/**
	 * Returns a token from the session
	 *
	 * @return string
	 */
	protected function sessionRetrieve()
	{
		return $this->parent->getSession()->get($this->sessionKey, false);
	}

	/**
	 * Stores a token in the session
	 *
	 * @param string $token
	 */
	protected function sessionStore($token)
	{
		$this->parent->getSession()->set($this->sessionKey, $token);
	}
}
