<?php
/*!
 * Wrapper for core class functionality.
 *
 * @since 2.0.0
 *
 * @package    ACF Stylizer
 * @subpackage Wrapper
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Abstract class used to implement the core class functionality.
 *
 * @since 2.0.0
 */
abstract class ACF_Stylizer_Wrapper
{
	/**
	 * Base plugin object.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @var    ACF_Stylizer
	 */
	public $base = null;

	/**
	 * Stored object properties.
	 *
	 * @since 2.0.0
	 *
	 * @access protected
	 * @var    array
	 */
	protected $_properties = array();

	/**
	 * Collection of object values.
	 *
	 * @since 2.0.1
	 *
	 * @access protected
	 * @var    mixed
	 */
	protected $_value_collection;

	/**
	 * Constructor function.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  array $properties Optional properties for the object.
	 * @return void
	 */
	public function __construct($properties = array())
	{
		$this->base = ACF_Stylizer();
		
		$this->_set_properties($properties);
	}
	
	/**
	 * Clone the current object.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @return void
	 */
	public function __clone()
	{
		$this->_properties = $this->_clone($this->_properties);
	}
	
	/**
	 * Check properties for clonable objects.
	 *
	 * @since 2.0.0
	 *
	 * @access private
	 * @param  mixed $cloning Properties to check for clonable objects.
	 * @return mixed          Modified properties with cloned objects.
	 */
	private function _clone($cloning)
	{
		if (is_array($cloning))
		{
			foreach ($cloning as $key => $value)
			{
				$cloning[$key] = $this->_clone($value);
			}
		}
		else if (is_object($cloning))
		{
			$cloning = clone $cloning;
		}
		
		return $cloning;
	}

	/**
	 * Get a property based on the provided name.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  string $name Name of the property to return.
	 * @return mixed        Property if it is found, otherwise an empty string.
	 */
	public function __get($name)
	{
		if
		(
			!isset($this->_properties[$name])
			||
			is_null($this->_properties[$name])
		)
		{
			return $this->_properties[$name] = $this->_default($name);
		}

		return $this->_properties[$name];
	}

	/**
	 * Check to see if a property exists with the provided name.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  string  $name Name of the property to check.
	 * @return boolean       True if the property is set.
	 */
	public function __isset($name)
	{
		if
		(
			!isset($this->_properties[$name])
			||
			is_null($this->_properties[$name])
		)
		{
			$default = $this->_default($name);

			if (!is_null($default))
			{
				$this->_properties[$name] = $default;
			}
		}

		return isset($this->_properties[$name]);
	}

	/**
	 * Set the property with the provided name to the provided value.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  string $name  Name of the property to set.
	 * @param  mixed  $value Value of the property to set.
	 * @return void
	 */
	public function __set($name, $value)
	{
		$this->_properties[$name] = $value;
	}

	/**
	 * Unset the property with the provided name.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  string $name Name of the property to unset.
	 * @return void
	 */
	public function __unset($name)
	{
		unset($this->_properties[$name]);
	}

	/**
	 * Get a default property based on the provided name.
	 *
	 * @since 2.0.0
	 *
	 * @access protected
	 * @param  string $name Name of the property to return.
	 * @return string       Null if the function is not overridden.
	 */
	protected function _default($name)
	{
		return null;
	}
	
	/**
	 * Get the collection of values for the object.
	 *
	 * @since 2.0.1
	 *
	 * @access protected
	 * @return mixed Value collection if it exists, otherwise the object properties.
	 */
	protected function _get_value_collection()
	{
		return (empty($this->_value_collection))
		? $this->_properties
		: $this->_value_collection;
	}

	/**
	 * Set the properties for the object.
	 *
	 * @since 2.0.0
	 *
	 * @access protected
	 * @param  array $properties Properties for the object.
	 * @return void
	 */
	protected function _set_properties($properties)
	{
		$properties = ACF_Stylizer_Utilities::check_array($properties);

		if (!empty($properties))
		{
			$this->_properties = array_merge($this->_properties, $properties);
		}
	}

	/**
	 * Push a value into a property array.
	 *
	 * @since 2.0.0
	 *
	 * @access protected
	 * @param  string $name  Name of the property array to push the value into.
	 * @param  string $value Value to push into the property array.
	 * @param  mixed  $index Optional array index for the value to push.
	 * @return void
	 */
	public function push($name, $value, $index = null)
	{
		$property = $this->$name;

		if (is_array($property))
		{
			if (is_null($index))
			{
				$property[] = $value;
			}
			else
			{
				$property[$index] = $value;
			}
		}

		$this->$name = $property;
	}
}
