<?php
/**
 * @package    Fuel\Security
 * @version    2.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2014 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Security;

/**
 * Security Csrf Manager class
 *
 * @package  Fuel\Security
 *
 * @since    2.0.0
 */
class Csrf
{
	/**
	 * @var  array  Security configuration
	 *
	 * @since  2.0.0
	 */
	protected $config = array();

	/**
	 * @var  Fuel\Session\Manager
	 *
	 * @since  2.0.0
	 */
	protected $session;

	/**
	 * @var  Fuel\Security\Csrf\Driver
	 *
	 * @since  2.0.0
	 */
	protected $driver;

	/**
	 * Constructor
	 *
	 * @param  string               $driver  Csrf driver name or FQCN
	 * @param  Fuel\Session\Manager $session Optional session driver instance
	 *
	 * @throws  RuntimeException  if the application does not have sessions activated
	 *
	 * @since  2.0.0
	 */
	public function __construct(Array $config = array(), \Fuel\Session\Manager $session = null)
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
	 * Capture method calls so we can pass them on to the loaded driver
	 *
	 * @param  string  $name       name of the method to call on the driver
	 * @params mixed   $arguments  argument list to pass on
	 *
	 * @throws InvalidArgumentException  if the method is not callable on the driver
	 *
	 * @return mixed
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
	 * Get the session manager instance used for this Csrf manager
	 *
	 * @return  Fuel\Session\Manager
	 *
	 * @since  2.0.0
	 */
	public function getSession()
	{
		return $this->session;
	}

	/**
	 * Pass a session manager instance to the Csrf manager at runtime
	 *
	 * @param  Fuel\Session\Manager  $session  Session manager instance
	 *
	 * @since  2.0.0
	 */
	public function setSession($session)
	{
		$this->session = $session;
	}
}
