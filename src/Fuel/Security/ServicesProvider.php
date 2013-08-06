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

use Fuel\Dependency\ServiceProvider;
use Fuel\Foundation\Application;

/**
 * ServicesProvider class
 *
 * Defines the services published by this namespace to the DiC
 *
 * @package  Fuel\Security
 *
 * @since  1.0.0
 */
class ServicesProvider extends ServiceProvider
{
	/**
	 * @var  array  list of service names provided by this provider
	 */
	public $provides = array('security',
		'security.filter.htmlentities',
		'security.csrf'
	);

	/**
	 * Service provider definitions
	 */
	public function provide()
	{
		// \Fuel\Security\Manager
		$this->register('security', function ($dic, Array $config = array())
		{
			return new Manager($config);
		});

		// \Fuel\Security\Filter\HtmlEntities
		$this->register('security.filter.htmlentities', function ($dic, Manager $manager)
		{
			return new Filter\HtmlEntities($manager);
		});

		// \Fuel\Security\Csrf
		$this->register('security.csrf', function ($dic, Array $config = array(), $session = null)
		{
			return new Csrf($config, $session);
		});
	}
}
