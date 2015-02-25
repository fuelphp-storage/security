<?php
/**
 * @package    Fuel\Security
 * @version    2.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2015 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Security\Filter;

/**
 * Security Base Filter class
 *
 * Basis for classes dealing with filtering variables for security purposes.
 */
abstract class Base
{
	/**
	 * @var Security
	 */
	protected $parent;

	public function __construct($parent)
	{
		$this->parent = $parent;
	}

	/**
	 * Cleans string, object or array
	 *
	 * @param mixed $input
	 *
	 * @return mixed
	 *
	 * @throws \RuntimeException if the variable passed can not be cleaned
	 */
	public function clean($input)
	{
		// Nothing to escape for non-string scalars, or for already processed values
		if (is_bool($input) or is_int($input) or is_float($input) or $this->parent->isCleaned($input))
		{
			return $input;
		}

		if (is_string($input))
		{
			$input = $this->cleanString($input);
		}

		elseif (is_array($input) or ($input instanceof \Iterator and $input instanceof \ArrayAccess))
		{
			$input = $this->cleanArray($input);
		}

		elseif ($input instanceof \Iterator or get_class($input) == 'stdClass')
		{
			$input = $this->cleanObject($input);
		}

		elseif (is_object($input))
		{
			// Check if the object is whitelisted and just return when that's the case
			foreach ($this->parent->getConfig('whitelistedClasses', array()) as $class)
			{
				if (is_a($input, $class))
				{
					return $input;
				}
			}

			// Throw exception when it wasn't whitelisted and can't be converted to String
			if (! method_exists($input, '__toString'))
			{
				throw new \RuntimeException('Object class "'.get_class($input).'" could not be converted to string or '.
					                            'sanitized as ArrayAccess. Whitelist it in security.whitelisted_classes in [application]/config/security.php '.
					                            'to allow it to be passed unchecked.');
			}

			$input = $this->cleanString(strval($input));
		}

		// mark this variable as cleaned
		$this->parent->isClean($input);

		return $input;
	}

	/**
	 * cleanString base method. Not defined as abstract become some filters might not implement cleaning strings
	 *
	 * @param string $input
	 *
	 * @return string
	 */
	protected function cleanString($input)
	{
		return $input;
	}

	/**
	 * cleanArray base method
	 *
	 * @param string $input
	 *
	 * @return string
	 */
	protected function cleanArray($input)
	{
		// add to the cleaned list when object
		if (is_object($input))
		{
			$this->parent->isClean($input);
		}

		foreach ($input as $k => $v)
		{
			$input[$k] = $this->clean($v);
		}

		return $input;
	}

	/**
	 * cleanObject base method. Not defined as abstract become some filters might not implement cleaning objects
	 *
	 * @param object $input
	 *
	 * @return object
	 */
	protected function cleanObject($input)
	{
		// add to the cleaned list
		$this->parent->isClean($input);

		foreach ($value as $k => $v)
		{
			$value->{$k} = $this->clean($v);
		}
		return $input;
	}
}
