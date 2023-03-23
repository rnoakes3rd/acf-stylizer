<?php
/*!
 * Functionality for field sanitization.
 *
 * @since 2.0.0
 *
 * @package    ACF Stylizer
 * @subpackage Sanitization
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement plugin sanitization functionality.
 *
 * @since 2.0.0
 */
final class ACF_Stylizer_Sanitization
{
	/**
	 * Sanitization name for confirmation fields.
	 *
	 * @since 2.0.0
	 *
	 * @const string
	 */
	const CONFIRMATION = 'confirmation';
	
	/**
	 * Fields that should not be returned during sanitization.
	 *
	 * @since 2.0.0
	 *
	 * @const string
	 */
	const EXCLUDE = 'exclude';
	
	/**
	 * Sanitization name for simple text fields.
	 *
	 * @since 2.0.0
	 *
	 * @const string
	 */
	const TEXT = 'text';
	
	/**
	 * Sanitize the provided values.
	 *
	 * @since 2.0.3 Changed type check to switch/case.
	 * @since 2.0.2 Improved conditions.
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  array $input Values to sanitize.
	 * @return array        Sanitized values.
	 */
	public static function sanitize($input)
	{
		if
		(
			!is_array($input)
			||
			empty($input)
		)
		{
			return array();
		}
		
		$output = array();
		
		foreach ($input as $type => $fields)
		{
			if
			(
				$type !== self::EXCLUDE
				&&
				is_array($fields)
			)
			{
				foreach ($fields as $name => $value)
				{
					switch ($type)
					{
						case self::CONFIRMATION:
						
							$unconfirmed = $name . ACF_Stylizer_Constants::SETTING_UNCONFIRMED;

							$output[$name] = $output[$unconfirmed] =
							(
								!isset($input[self::EXCLUDE][$unconfirmed])
								||
								empty($input[self::EXCLUDE][$unconfirmed])
							)
							? ''
							: $value;
							
						break;
						
						default:
						
							$output[$name] = sanitize_text_field($value);
					}
				}
			}
		}
		
		return $output;
	}
}
