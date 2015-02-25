<?php
/**
 * @package    Fuel\Security
 * @version    2.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2015 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Security;

use Fuel\Session\Manager;
use Fuel\Security\Csrf\Driver;

/**
 * Security Csrf Manager class
 */
class Csrf
{
	/**
	 * @var array
	 */
	protected $config = [];

	/**
	 * @var Manager
	 */
	protected $session;

	/**
	 * @var Driver
	 */
	protected $driver;

	/**
	 * @param array   $config
	 * @param Manager $session
	 *
	 * @throws \RuntimeException if the application does not have sessions activated
	 */
	public function __construct(array $config = [], Manager $session = null)
	{
		// store the config
		$this->config = $config;

		// store the session object passed
		$this->session = $session;

		// get the required driver from the config
		if ( ! isset($config['csrf']['driver']))
		{
			throw new \RuntimeException('The applications security configuration doesn\'t define a CSRF mitigation driver.');
		}

		// get us a driver instance
		$class = strpos($config['csrf']['driver'], '\\') === false ? 'Fuel\Security\Csrf\\'.ucfirst($config['csrf']['driver']) : $config['csrf']['driver'];

		if ( ! class_exists($class))
		{
			throw new \RuntimeException('Requested CSRF mitigation driver "'.$config['csrf']['driver'].'" does not exist.');
		}

		$this->driver = new $class($this);
	}

	/**
	 * Captures method calls so we can pass them on to the loaded driver
	 *
	 * @param string  $name
	 * @param mixed   $arguments
	 *
	 * @return mixed
	 *
	 * @throws \InvalidArgumentException if the method is not callable on the driver
	 */
	public function __call($name, $arguments)
	{
		if (is_callable(array($this->driver, $name)))
		{
			return call_user_func_array(array($this->driver, $name), $arguments);
		}

		throw new \InvalidArgumentException('There is no CSRF driver method called "'.$name.'".');
	}

	/**
	 * Returns the session manager instance used for this Csrf manager
	 *
	 * @return Manager
	 */
	public function getSession()
	{
		return $this->session;
	}

	/**
	 * Sets a session manager instance to the Csrf manager at runtime
	 *
	 * @param Manager $session
	 */
	public function setSession(Manager $session)
	{
		$this->session = $session;
	}
}
