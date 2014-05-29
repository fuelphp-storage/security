<?php
/**
 * @package    Fuel\Security
 * @version    2.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2014 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Security\Filter;

/**
 * Security HTML entities filter class
 *
 * Uses htmlentities() to encode variables for safer output.
 *
 * @package  Fuel\Security
 *
 * @since    2.0.0
 */
class HtmlEntities extends Base
{
	/**
	 * @param string $input the variable to be cleaned by escaping HTML entities
	 *
	 * @return string
	 */
	protected function cleanString($input)
	{
		return htmlentities(
			$input,
			$this->parent->getConfig('htmlentities_flags', ENT_QUOTES),
			$this->parent->getConfig('htmlentities_encoding', 'UTF-8'),
			$this->parent->getConfig('htmlentities_double_encode', false)
		);
	}
}
