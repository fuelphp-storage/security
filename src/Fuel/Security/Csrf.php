<?php
/**
 * @package    Fuel\Security
 * @version    2.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Security;

use Fuel\Foundation\Application;
use Fuel\Security\Manager;

/**
 * Security Csrf class
 *
 * Implements measures against Csrf attacks
 *
 * @package  Fuel\Security
 *
 * @since    2.0.0
 */
class Csrf
{
	/**
	 * @var  Application  application that owns the security manager
	 *
	 * @since  2.0.0
	 */
	protected $app;

	/**
	 * @var  Manager  security manager that spawned this filter
	 *
	 * @since  2.0.0
	 */
	protected $parent;

	/**
	 * @var  Fuel\Session\Manager
	 *
	 * @since  2.0.0
	 */
	protected $session;

	/**
	 * @var  string  key to indicate where to store tokens in the session
	 *
	 * @since  2.0.0
	 */
	protected $sessionKey;

	/**
	 * Constructor
	 *
	 * @param  Application  $app     This class' application object
	 * @param  Manager      $parent  This class' security manager object
	 *
	 * @throws  RuntimeException  if the application does not have sessions activated
	 *
	 * @since  2.0.0
	 */
	public function __construct(Application $app, Manager $parent)
	{
		if ( ! $this->session = $app->getSession())
		{
			throw new \RuntimeException('Your application "'.$app->getName().'" does not have sessions active. This is required for CSRF mitigation functionality');
		}

		// store the objects passed
		$this->app    = $app;
		$this->parent = $parent;

		// generate a session key to store the generated csrf tokens
		$this->sessionKey = 'fuel.'.$this->app->getName().'.csrftokens';
	}

	/**
	 * Generate a unique CSRF token for the given form identification
	 *
	 * @param  string  $form_id  Unique identification of the form to protect
	 *
	 * @since  2.0.0
	 */
	public function getToken($form_id)
	{
		if (function_exists("hash_algos") and in_array("sha256",hash_algos()))
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

		// store it in the session
		$this->sessionStore($form_id, $token);

		return $token;
	}

	/**
	 * Validate a given token
	 *
	 * @param  string  $form_id  Unique identification of the form to protect
	 * @param  string  $token    Token to validate
	 *
	 * @since  2.0.0
	 */
	public function validateToken($form_id, $token)
	{
		return $token === $this->sessionRetrieve($form_id);
	}

	/**
	 * Store a token in the session
	 *
	 * @param  string  $form_id  Unique identification of the form to protect
	 * @param  string  $token    Token to validate
	 *
	 * @since  2.0.0
	 */
	protected function sessionStore($form_id, $token)
	{
		$keys = $this->session->get($this->sessionKey, array());
		$keys[$form_id] = $token;
		$this->session->set($this->sessionKey, $keys);
	}

	/**
	 * Get a token from the session
	 *
	 * @param  string  $form_id  Unique identification of the form to protect
	 *
	 * @since  2.0.0
	 */
	protected function sessionRetrieve($form_id)
	{
		if ($token = $this->session->get($this->sessionKey.'.'.$form_id, false))
		{
			$this->session->delete($this->sessionKey.'.'.$form_id);
		}

		return $token;
	}

}
