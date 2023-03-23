<?php
/*!
 * Plugin setup functionality.
 *
 * @since 2.0.0
 *
 * @package    ACF Stylizer
 * @subpackage Setup
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement the setup functionality.
 *
 * @since 2.0.0
 */
final class ACF_Stylizer_Setup
{
	/**
	 * Check and update the plugin version.
	 *
	 * @since 2.1.1 Added previous version constant.
	 * @since 2.0.3 Improved version sanitization.
	 * @since 2.0.2 Improved condition and changed force previous version definition.
	 * @since 2.0.1 Plugin development version check changes.
	 * @since 2.0.0
	 *
	 * @access public static
	 * @return void
	 */
	public static function check_version()
	{
		$current_version =
		(
			!defined('NDT_FORCE_PREVIOUS_VERSION')
			||
			!NDT_FORCE_PREVIOUS_VERSION
		)
		? wp_unslash(get_option(ACF_Stylizer_Constants::OPTION_VERSION))
		: ACF_Stylizer_Constants::VERSION_PREVIOUS;

		if (empty($current_version))
		{
			add_option(ACF_Stylizer_Constants::OPTION_VERSION, sanitize_text_field(ACF_Stylizer_Constants::VERSION));
		}
		else if ($current_version !== ACF_Stylizer_Constants::VERSION)
		{
			update_option(ACF_Stylizer_Constants::OPTION_VERSION, sanitize_text_field(ACF_Stylizer_Constants::VERSION));
		}
	}
}
