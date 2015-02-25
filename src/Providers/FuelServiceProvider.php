<?php
/**
 * @package    Fuel\Security
 * @version    2.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2015 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Security\Providers;

use League\Container\ServiceProvider;
use Fuel\Security;

/**
 * Fuel ServiceProvider class for Security
 */
class FuelServiceProvider extends ServiceProvider
{
	/**
	 * @var array
	 */
	protected $provides = [
		'security',
		'security.filter.htmlentities',
		'security.csrf'
	];

	/**
	 * {@inheritdoc}
	 */
	public function register()
	{
		$this->container->add('security', function (array $config = [])
		{
			$configInstance = $this->container->get('configInstance');

			$config = \Arr::merge($configInstance->load('security', true), $config);

			return new Security\Manager($config);
		});

		$this->container->add('security.filter.htmlentities', function (Manager $manager)
		{
			return new Security\Filter\HtmlEntities($manager);
		});

		$this->container->add('security.csrf', function (array $config = [], $session = null)
		{
			return new Security\Csrf($config, $session);
		});
	}
}
