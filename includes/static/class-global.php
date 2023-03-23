<?php
/*!
 * Global plugin hooks.
 *
 * @since 2.0.0
 *
 * @package    ACF Stylizer
 * @subpackage Global
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement global hooks.
 *
 * @since 2.0.1 Removed legacy body class.
 * @since 2.0.0
 */
final class ACF_Stylizer_Global
{
	/**
	 * Enqueue plugin assets.
	 *
	 * @since 2.0.4 Added AJAX script options.
	 * @since 2.0.0
	 *
	 * @access public static
	 * @return void
	 */
	public static function admin_enqueue_scripts()
	{
		$acfs = ACF_Stylizer();
		
		wp_enqueue_style('noatice', $acfs->cache->asset_path('styles', 'noatice.css'), array(), ACF_Stylizer_Constants::VERSION);
		wp_enqueue_style('acfs-style', $acfs->cache->asset_path('styles', 'style.css'), array('noatice'), ACF_Stylizer_Constants::VERSION);
		
		wp_enqueue_script('noatice', $acfs->cache->asset_path('scripts', 'noatice.js'), array(), ACF_Stylizer_Constants::VERSION, true);
		wp_enqueue_script('acfs-script', $acfs->cache->asset_path('scripts', 'script.js'), array('noatice', 'postbox'), ACF_Stylizer_Constants::VERSION, true);
		
		wp_localize_script
		(
			'acfs-script',
			'acfs_script_options',

			array
			(
				'admin_page' => $acfs->cache->admin_page,
				'noatices' => ACF_Stylizer_Noatice::output(),
				'option_name' => $acfs->cache->option_name,
				'token' => ACF_Stylizer_Constants::TOKEN,

				'strings' => array
				(
					'save_alert' => __('The changes you made will be lost if you navigate away from this page.', 'acf-stylizer')
				),
				
				'urls' => array
				(
					'ajax' => admin_url('admin-ajax.php'),
					'current' => remove_query_arg($acfs->cache->get_remove_query_args())
				)
			)
		);
	}
}
