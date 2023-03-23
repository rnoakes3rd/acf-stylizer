<?php
/*!
 * Noatice plugin functionality.
 *
 * @since 2.0.0
 *
 * @package    ACF Stylizer
 * @subpackage Noatice
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement the Noatice functionality.
 *
 * @since 2.0.0
 */
final class ACF_Stylizer_Noatice
{
	/**
	 * Current collection of noatices.
	 *
	 * @since 2.0.0
	 *
	 * @access private static
	 * @var    array
	 */
	private static $_noatices = array();
	
	/**
	 * Add a noatice.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  array $options Options for the noatice.
	 * @return void
	 */
	public static function add($options)
	{
		if (is_array($options))
		{
			self::$_noatices[] = $options;
		}
	}
	
	/**
	 * Generate a noatice.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  array $options Options for the noatice.
	 * @return array          Generated noatice.
	 */
	public static function generate($options)
	{
		return (is_array($options))
		? $options
		: array();
	}
	
	/**
	 * Add a general noatice.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  string $css_class CSS class applied to the noatice.
	 * @param  string $message   Message displayed in the noatice.
	 * @param  array  $options   Additional options for the noatice.
	 * @return void
	 */
	public static function add_general($css_class, $message, $options = array())
	{
		self::add(self::generate_general($css_class, $message, $options));
	}
	
	/**
	 * Generate a general noatice.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  string $css_class CSS class applied to the noatice.
	 * @param  string $message   Message displayed in the noatice.
	 * @param  array  $options   Additional options for the noatice.
	 * @return array             Generated general noatice.
	 */
	public static function generate_general($css_class, $message, $options = array())
	{
		if (!is_array($options))
		{
			$options = array();
		}
		
		$options['css_class'] = (empty($css_class))
		? 'noatice-general'
		: $css_class;
		
		$options['message'] = $message;
		
		return $options;
	}
	
	/**
	 * Add an error noatice.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  string $message Message displayed in the noatice.
	 * @param  array  $options Additional options for the noatice.
	 * @return void
	 */
	public static function add_error($message, $options = array())
	{
		self::add(self::generate_error($message, $options));
	}
	
	/**
	 * Generate an error noatice.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  string $message Message displayed in the noatice.
	 * @param  array  $options Additional options for the noatice.
	 * @return array           Generated error noatice.
	 */
	public static function generate_error($message, $options = array())
	{
		return self::generate_general('noatice-error', $message, $options);
	}
	
	/**
	 * Add an info noatice.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  string $message Message displayed in the noatice.
	 * @param  array  $options Additional options for the noatice.
	 * @return void
	 */
	public static function add_info($message, $options = array())
	{
		self::add(self::generate_info($message, $options));
	}
	
	/**
	 * Generate an info noatice.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  string $message Message displayed in the noatice.
	 * @param  array  $options Additional options for the noatice.
	 * @return array           Generated info noatice.
	 */
	public static function generate_info($message, $options = array())
	{
		return self::generate_general('noatice-info', $message, $options);
	}
	
	/**
	 * Add an success noatice.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  string $message Message displayed in the noatice.
	 * @param  array  $options Additional options for the noatice.
	 * @return void
	 */
	public static function add_success($message, $options = array())
	{
		self::add(self::generate_success($message, $options));
	}
	
	/**
	 * Generate an success noatice.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  string $message Message displayed in the noatice.
	 * @param  array  $options Additional options for the noatice.
	 * @return array           Generated success noatice.
	 */
	public static function generate_success($message, $options = array())
	{
		return self::generate_general('noatice-success', $message, $options);
	}
	
	/**
	 * Add an warning noatice.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  string $message Message displayed in the noatice.
	 * @param  array  $options Additional options for the noatice.
	 * @return void
	 */
	public static function add_warning($message, $options = array())
	{
		self::add(self::generate_warning($message, $options));
	}
	
	/**
	 * Generate an warning noatice.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  string $message Message displayed in the noatice.
	 * @param  array  $options Additional options for the noatice.
	 * @return array           Generated warning noatice.
	 */
	public static function generate_warning($message, $options = array())
	{
		return self::generate_general('noatice-warning', $message, $options);
	}
	
	/**
	 * Output the current noatices.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @return array Current noatices.
	 */
	public static function output()
	{
		$output = self::$_noatices;
		
		self::$_noatices = array();
		
		return $output;
	}
}
