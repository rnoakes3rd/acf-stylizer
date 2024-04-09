<?php
/*!
 * Plugin constants.
 *
 * @since 2.0.0
 *
 * @package    ACF Stylizer
 * @subpackage Constants
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement plugin constants.
 *
 * @since 2.0.1 Alphabetized constant groups.
 * @since 2.0.0
 */
final class ACF_Stylizer_Constants
{
	/**
	 * Plugin prefix.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	const PREFIX = 'acfs_';

	/**
	 * Plugin token.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	const TOKEN = 'acf_stylizer';

	/**
	 * Plugin versions.
	 *
	 * @since 2.1.1 Added previous version.
	 * @since 2.0.0
	 *
	 * @var string
	 */
	const VERSION = '2.1.4';
	const VERSION_PREVIOUS = '2.1.3';
	
	/**
	 * Plugin hook names.
	 *
	 * @since 2.1.0 Added validate data hook.
	 * @since 2.0.0
	 *
	 * @var string
	 */
	const HOOK_SAVE_SETTINGS = self::PREFIX . 'save_settings';
	const HOOK_VALIDATE_DATA = self::PREFIX . 'validate_data';

	/**
	 * Plugin option names.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	const OPTION_SETTINGS = self::TOKEN . '_settings';
	const OPTION_VERSION = self::TOKEN . '_version';

	/**
	 * Plugin setting names.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	const SETTING_DELETE_SETTINGS = 'delete_settings';
	const SETTING_DELETE_USER_META = 'delete_user_meta';
	const SETTING_UNCONFIRMED = '_unconfirmed';

	/**
	 * Plugin URLs.
	 *
	 * @since 2.0.3 Changed donate link.
	 * @since 2.0.0
	 *
	 * @var string
	 */
	const URL_BASE = 'https://noakesplugins.com/';
	const URL_DONATE = 'https://www.paypal.com/donate?hosted_button_id=4NYEG2Q44CG8C&source=url';
	const URL_KB = self::URL_BASE . 'kb/acf-stylizer/';
	const URL_SUPPORT = 'https://wordpress.org/support/plugin/acf-stylizer/';
	const URL_REVIEW = self::URL_SUPPORT . 'reviews/?rate=5#new-post';
	const URL_TRANSLATE = 'https://translate.wordpress.org/projects/wp-plugins/acf-stylizer';
}
