<?php
/**
 * @package    Fuel\Security
 * @version    2.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2014 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Security\Providers;

use Fuel\Dependency\ServiceProvider;

/**
 * FuelPHP ServiceProvider class for this package
 *
 * @package  Fuel\Security
 *
 * @since  1.0.0
 */
class FuelServiceProvider extends ServiceProvider
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
			$stack = $this->container->resolve('requeststack');
			if ($request = $stack->top())
			{
				$instance = $request->getComponent();
			}
			else
			{
				$instance = $dic->resolve('application::__main')->getRootComponent();
			}

			$config = \Arr::merge($instance->getConfig()->load('security', true), $config);

			return $dic->resolve('Fuel\Security\Manager', array($config));
		});

		// \Fuel\Security\Filter\HtmlEntities
		$this->register('security.filter.htmlentities', function ($dic, Manager $manager)
		{
			return $dic->resolve('Fuel\Security\Filter\HtmlEntities', array($manager));
		});

		// \Fuel\Security\Csrf
		$this->register('security.csrf', function ($dic, Array $config = array(), $session = null)
		{
			return $dic->resolve('Fuel\Security\Csrf', array($config, $session));
		});
	}
}
