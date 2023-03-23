<?php
/*!
 * Functionality for plugin uninstallation.
 * 
 * @since 2.0.4 Minor MySQL query cleanup.
 * @since 2.0.3 Added option unslashing.
 * @since 2.0.2 Improved condition and changed faux uninstall definition.
 * @since 2.0.1 Cleaned up database query.
 * @since 2.0.0
 * 
 * @package ACF Stylizer
 */

if
(
	!defined('WP_UNINSTALL_PLUGIN')
	&&
	!defined('NDT_FAUX_UNINSTALL_PLUGIN')
)
{
	exit;
}

global $wpdb;

require_once(dirname(__FILE__) . '/includes/static/class-constants.php');

$settings = wp_unslash(get_option(ACF_Stylizer_Constants::OPTION_SETTINGS));
$deleted = 0;

if
(
	isset($settings[ACF_Stylizer_Constants::SETTING_DELETE_SETTINGS])
	&&
	$settings[ACF_Stylizer_Constants::SETTING_DELETE_SETTINGS]
)
{
	delete_option(ACF_Stylizer_Constants::OPTION_SETTINGS);
	
	$deleted++;
}

if
(
	isset($settings[ACF_Stylizer_Constants::SETTING_DELETE_USER_META])
	&&
	$settings[ACF_Stylizer_Constants::SETTING_DELETE_USER_META]
)
{
	$wpdb->query($wpdb->prepare
	(
		"DELETE FROM 
			$wpdb->usermeta 
		WHERE 
			meta_key LIKE %s;\n",
			
		'%' . $wpdb->esc_like(ACF_Stylizer_Constants::TOKEN) . '%'
	));
	
	$deleted++;
}

if ($deleted === 2)
{
	delete_option(ACF_Stylizer_Constants::OPTION_VERSION);
}
