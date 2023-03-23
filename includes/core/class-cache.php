<?php
/*!
 * Cached function calls and flags.
 *
 * @since 2.0.0
 *
 * @package    ACF Stylizer
 * @subpackage Cache
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement the cache functionality.
 *
 * @since 2.0.0
 *
 * @uses ACF_Stylizer_Wrapper
 */
final class ACF_Stylizer_Cache extends ACF_Stylizer_Wrapper
{
	/**
	 * Constructor function.
	 *
	 * @since 2.0.2 Changed remove query args hook name.
	 * @since 2.0.0
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		add_filter('ndt_remove_query_args', array($this, 'remove_query_args'));
	}
	
	/**
	 * Get a default cached item based on the provided name.
	 *
	 * @since 2.0.0
	 *
	 * @access protected
	 * @param  string $name Name of the cached item to return.
	 * @return mixed        Default cached item if it exists, otherwise an empty string.
	 */
	protected function _default($name)
	{
		switch ($name)
		{
			/**
			 * Current admin page being used.
			 *
			 * @since 2.0.4
			 *
			 * @var string
			 */
			case 'admin_page':

				return basename($_SERVER['SCRIPT_NAME']);
				
			/**
			 * Path to the plugin assets folder.
			 *
			 * @since 2.0.0
			 *
			 * @var string
			 */
			case 'assets_url':

				$folder = 'debug';
				
				if
				(
					!$this->script_debug
					||
					!file_exists(plugin_dir_path($this->base->plugin) . 'assets/' . $folder . '/')
				)
				{
					$folder = 'release';
				}

				return plugins_url('/assets/' . $folder . '/', $this->base->plugin);
				
			/**
			 * True if AJAX is currently being processed.
			 *
			 * @since 2.0.3 Changed to built-in function.
			 * @since 2.0.2
			 *
			 * @var boolean
			 */
			case 'doing_ajax':
			
				return wp_doing_ajax();

			/**
			 * Asset file names pulled from the manifest JSON.
			 *
			 * @since 2.0.0
			 *
			 * @var array
			 */
			case 'manifest':

				return ACF_Stylizer_Utilities::load_json('assets/manifest.json');

			/**
			 * Current option name being used.
			 *
			 * @since 2.0.4
			 *
			 * @var string
			 */
			case 'option_name':

				return
				(
					isset($_GET['page'])
					&&
					!empty($_GET['page'])
				)
				? sanitize_key($_GET['page'])
				: '';

			/**
			 * General details about the plugin.
			 *
			 * @since 2.0.0
			 *
			 * @var array
			 */
			case 'plugin_data':

				return ACF_Stylizer_Plugins::get_data(plugin_basename($this->base->plugin));
				
			/**
			 * Query args to remove from the current URL.
			 *
			 * @since 2.0.0
			 *
			 * @var array
			 */
			case 'remove_query_args':

				return array();

			/**
			 * Object for the current screen.
			 *
			 * @since 2.0.0
			 *
			 * @var WP_Screen
			 */
			case 'screen':

				return get_current_screen();
				
			/**
			 * True if script debugging is enabled.
			 *
			 * @since 2.0.0
			 *
			 * @var boolean
			 */
			case 'script_debug':
			
				return
				(
					defined('SCRIPT_DEBUG')
					&&
					SCRIPT_DEBUG
				);
		}

		return parent::_default($name);
	}
	
	/**
	 * Filter the query args that should be removed from a URL.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  array $query_args Current query args that should be removed from a URL.
	 * @return array             Modified query args that should be removed from a URL.
	 */
	public function remove_query_args($query_args)
	{
		return array_merge($query_args, $this->remove_query_args);
	}

	/**
	 * Obtain a path to an asset.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  string $path      Path to the asset folder.
	 * @param  string $file_name File name for the asset.
	 * @return string            Full path to the requested asset.
	 */
	public function asset_path($path, $file_name)
	{
		$manifest = $this->manifest;

		if (isset($manifest[$file_name]))
		{
			$file_name = $manifest[$file_name];
		}

		return trailingslashit($this->assets_url . $path) . $file_name;
	}
	
	/**
	 * Get the filtered query args that should be removed from a URL.
	 *
	 * @since 2.1.2 Added additional query args for the filter.
	 * @since 2.0.2 Changed remove query args hook name.
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  array $query_args Additional query args that should be removed from a URL.
	 * @return array             Filtered query args that should be removed from a URL.
	 */
	public function get_remove_query_args($query_args = array())
	{
		/**
		 * Filters the Noakes Development Tools query args that should be removed from the URL.
		 *
		 * @since 2.0.0
		 *
		 * @param  array $query_args Query args that should be removed.
		 * @return array             Modified query args that should be removed.
		 */
		return apply_filters('ndt_remove_query_args', ACF_Stylizer_Utilities::check_array($query_args));
	}
}

