<?php
/*!
 * Base plugin functionality.
 *
 * @since 2.0.0
 *
 * @package    ACF Stylizer
 * @subpackage Base
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement the base plugin functionality.
 *
 * @since 2.0.0
 *
 * @uses ACF_Stylizer_Wrapper
 */
final class ACF_Stylizer extends ACF_Stylizer_Wrapper
{
	/**
	 * Main instance of ACF_Stylizer.
	 *
	 * @since 2.0.0
	 *
	 * @access private static
	 * @var    ACF_Stylizer
	 */
	private static $_instance = null;

	/**
	 * Returns the main instance of ACF_Stylizer.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  string       $file Main plugin file.
	 * @return ACF_Stylizer       Main ACF_Stylizer instance. 
	 */
	public static function _get_instance($file)
	{
		if (is_null(self::$_instance))
		{
			self::$_instance = new self($file);
		}

		return self::$_instance;
	}

	/**
	 * Base name for the plugin.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @var    string
	 */
	public $plugin;

	/**
	 * Global cache object.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @var    ACF_Stylizer_Cache
	 */
	public $cache;

	/**
	 * Global settings object.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @var    ACF_Stylizer_Settings
	 */
	public $settings;

	/**
	 * Global AJAX object.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @var    ACF_Stylizer_AJAX
	 */
	public $ajax;

	/**
	 * Constructor function.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  string $file Main plugin file.
	 * @return void
	 */
	public function __construct($file)
	{
		if
		(
			!empty($file)
			&&
			file_exists($file)
		)
		{
			$this->plugin = $file;

			add_action('plugins_loaded', array($this, 'plugins_loaded'));
		}
	}

	/**
	 * Load the plugin functionality.
	 *
	 * @since 2.1.2 Removed PHP_INT_MAX reference.
	 * @since 2.0.0
	 *
	 * @access public
	 * @return void
	 */
	public function plugins_loaded()
	{
		if
		(
			ACF_Stylizer_Plugins::is_active('advanced-custom-fields-pro/acf.php')
			||
			ACF_Stylizer_Plugins::is_active('advanced-custom-fields/acf.php')
		)
		{
			$this->cache = new ACF_Stylizer_Cache();
			$this->settings = new ACF_Stylizer_Settings();
			$this->ajax = new ACF_Stylizer_AJAX();

			add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'), 9999999);
			add_action('admin_init', array('ACF_Stylizer_Setup', 'check_version'), 0);
			add_action('init', array($this, 'init'));
			
			if ($this->settings->include_seamless)
			{
				add_filter('admin_body_class', array($this, 'admin_body_class'));
			}
		}
		else
		{
			add_filter('plugin_action_links_' . plugin_basename($this->plugin), array($this, 'plugin_action_links'), 11);
		}

		add_filter('plugin_row_meta', array($this, 'plugin_row_meta'), 10, 2);
	}

	/**
	 * Enqueue the meta box stylesheet.
	 * 
	 * @since 2.0.0
	 * 
	 * @access public
	 * @return void
	 */
	public function admin_enqueue_scripts()
	{
		wp_enqueue_style('acfs-meta-box', $this->cache->asset_path('styles', 'meta-box.css'), array(), ACF_Stylizer_Constants::VERSION);
	}

	/**
	 * Initialize the plugin.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @return void
	 */
	public function init()
	{
		load_plugin_textdomain('acf-stylizer', false, dirname(plugin_basename($this->plugin)) . '/languages/');
	}
	
	/**
	 * Add the include seamless class to the admin BODY tag.
	 * 
	 * @since 2.0.0
	 * 
	 * @access public
	 * @param  string $classes Existing classes.
	 * @return string          Modified classes.
	 */
	public function admin_body_class($classes)
	{
		$classes .= ' acfs-include-seamless';

		return $classes;
	}

	/**
	 * Add action links to the plugin list.
	 * 
	 * @since 2.0.2 Removed escape from admin URL.
	 * @since 2.0.0
	 * 
	 * @access public
	 * @param  array $links Existing action links.
	 * @return array        Modified action links.
	 */
	public function plugin_action_links($links)
	{
		array_unshift($links, '<a class="thickbox open-plugin-details-modal" data-title="' . esc_attr__('Advanced Custom Fields', 'iem-clients-plus') . '" href="' . admin_url('plugin-install.php?tab=plugin-information&plugin=advanced-custom-fields&TB_iframe=true&width=772&height=871') . '">' . __('ACF Required', 'acf-stylizer') . '</a>');

		return $links;
	}

	/**
	 * Add links to the plugin page.
	 *
	 * @since 2.1.0 Removed 'noreferrer' from links and added non-breaking space before dashicons.
	 * @since 2.0.3 Added Dashicons to links.
	 * @since 2.0.2 Improved condition.
	 * @since 2.0.1 Simplified functionality.
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  array  $links Default links for the plugin.
	 * @param  string $file  Main plugin file name.
	 * @return array         Modified links for the plugin.
	 */
	public function plugin_row_meta($links, $file)
	{
		return ($file === plugin_basename($this->plugin))
		? array_merge
		(
			$links,

			array
			(
				'<a class="dashicons-before dashicons-sos" href="' . ACF_Stylizer_Constants::URL_SUPPORT . '" rel="noopener" target="_blank">&nbsp;' . __('Support', 'acf-stylizer') . '</a>',
				'<a class="dashicons-before dashicons-star-filled" href="' . ACF_Stylizer_Constants::URL_REVIEW . '" rel="noopener" target="_blank">&nbsp;' . __('Review', 'acf-stylizer') . '</a>',
				'<a class="dashicons-before dashicons-translation" href="' . ACF_Stylizer_Constants::URL_TRANSLATE . '" rel="noopener" target="_blank">&nbsp;' . __('Translate', 'acf-stylizer') . '</a>',
				'<a class="dashicons-before dashicons-coffee" href="' . ACF_Stylizer_Constants::URL_DONATE . '" rel="noopener" target="_blank">&nbsp;' . __('Donate', 'acf-stylizer') . '</a>'
			)
		)
		: $links;
	}
}
