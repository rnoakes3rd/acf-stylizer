<?php
/*!
 * Plugin utility functions.
 *
 * @since 2.0.0
 *
 * @package    ACF Stylizer
 * @subpackage Utilities
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement utility functions.
 *
 * @since 2.0.0
 */
final class ACF_Stylizer_Utilities
{
	/**
	 * Check a value to see if it is an array or convert to an array if necessary.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  mixed $value        Value to turn into an array.
	 * @param  mixed $return_empty True if an empty value should be returned as-is.
	 * @return mixed               Checked value as an array.
	 */
	public static function check_array($value, $return_empty = false)
	{
		$is_empty = empty($value);
		
		if
		(
			$is_empty
			&&
			$return_empty
		)
		{
			return $value;
		}

		if ($is_empty)
		{
			$value = array();
		}

		if (!is_array($value))
		{
			$value = array($value);
		}

		return $value;
	}

	/**
	 * Remove comments, line breaks and tabs from provided code.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  string $code Raw code to clean up.
	 * @return string       Code without comments, line breaks and tabs.
	 */
	public static function clean_code($code)
	{
		$code = preg_replace('/<!--(.*)-->/Uis', '', $code);
		$code = preg_replace('/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/', '', $code);

		return str_replace(array(PHP_EOL, "\r", "\n", "\t"), '', $code);
	}
	
	/**
	 * Check to see if a full string end with a specified string.
	 * 
	 * @since 2.0.2 Improved condition.
	 * @since 2.0.0
	 * 
	 * @access public static
	 * @param  string  $needle   String to check for.
	 * @param  string  $haystack Full string to check.
	 * @return boolean           True if the full string ends with the specified string.
	 */
	public static function ends_with($needle, $haystack)
	{
		$length = strlen($needle);
		
		if ($length === 0)
		{
			return true;
		}
		
		return (substr($haystack, -$length) === $needle);
	}
	
	/**
	 * Check to see if a variable is a valid field object.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  mixed   $variable Variable to check.
	 * @return boolean           True if the variable is a valid field object.
	 */
	public static function is_field($variable)
	{
		return
		(
			is_object($variable)
			&&
			self::starts_with('ACF_Stylizer_Field', get_class($variable))
			&&
			!is_a($variable, 'ACF_Stylizer_Field_Tab')
		);
	}

	/**
	 * Load and decode JSON from a provided file path.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  string $file_path   Path to the JSON file.
	 * @param  string $plugin_path Path for the current plugin.
	 * @return string              Decoded JSON file.
	 */
	public static function load_json($file_path, $plugin_path = '')
	{
		if (empty($plugin_path))
		{
			$plugin_path = ACF_Stylizer()->plugin;
		}
		
		$file = plugin_dir_path($plugin_path) . $file_path;

		if (!file_exists($file))
		{
			return '';
		}

		ob_start();

		require($file);

		return json_decode(ob_get_clean(), true);
	}
	
	/**
	 * Check to see if a full string starts with a specified string.
	 * 
	 * @since 2.0.0
	 * 
	 * @access public static
	 * @param  string  $needle   String to check for.
	 * @param  string  $haystack Full string to check.
	 * @return boolean           True if the full string starts with the specified string.
	 */
	public static function starts_with($needle, $haystack)
	{
		return
		(
			empty($needle)
			||
			strpos($haystack, $needle) === 0
		);
	}
}
