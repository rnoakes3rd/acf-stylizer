<?php
/*!
 * Settings functionality.
 *
 * @since 2.0.0
 *
 * @package    ACF Stylizer
 * @subpackage Settings
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement the settings functionality.
 *
 * @since 2.0.0
 *
 * @uses ACF_Stylizer_Wrapper
 */
final class ACF_Stylizer_Settings extends ACF_Stylizer_Wrapper
{
	/**
	 * Constructor function.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->load_option();
		
		add_action('admin_menu', array($this, 'admin_menu'));
		
		add_filter('plugin_action_links_' . plugin_basename($this->base->plugin), array($this, 'plugin_action_links'));
	}
	
	/**
	 * Get a default value based on the provided name.
	 *
	 * @since 2.0.1 Reordered default names.
	 * @since 2.0.0
	 *
	 * @access protected
	 * @param  string $name Name of the value to return.
	 * @return mixed        Default value if it exists, otherwise an empty string.
	 */
	protected function _default($name)
	{
		switch ($name)
		{
			/**
			 * Settings page title.
			 *
			 * @since 2.0.0
			 *
			 * @var string
			 */
			case 'page_title':
			
				return __('Settings', 'acf-stylizer');
				
			/**
			 * True if seamless metaboxes should be styled as well.
			 *
			 * @since 2.0.0
			 *
			 * @var boolean
			 */
			case 'include_seamless':
			
			/**
			 * True if plugin settings should be deleted when the plugin is uninstalled.
			 *
			 * @since 2.0.0
			 *
			 * @var boolean
			 */
			case ACF_Stylizer_Constants::SETTING_DELETE_SETTINGS:
			
			/**
			 * True if plugin settings should be deleted when the plugin is uninstalled.
			 *
			 * @since 2.0.0
			 *
			 * @var boolean
			 */
			case ACF_Stylizer_Constants::SETTING_DELETE_SETTINGS . ACF_Stylizer_Constants::SETTING_UNCONFIRMED:
			
			/**
			 * True if plugin user meta should be deleted when the plugin is uninstalled.
			 *
			 * @since 2.0.0
			 *
			 * @var boolean
			 */
			case ACF_Stylizer_Constants::SETTING_DELETE_USER_META:
			
			/**
			 * True if plugin user meta should be deleted when the plugin is uninstalled.
			 *
			 * @since 2.0.0
			 *
			 * @var boolean
			 */
			case ACF_Stylizer_Constants::SETTING_DELETE_USER_META . ACF_Stylizer_Constants::SETTING_UNCONFIRMED:
			
				return false;
		}

		return parent::_default($name);
	}

	/**
	 * Load the settings option.
	 *
	 * @since 2.0.3 Added option unslashing.
	 * @since 2.0.1 Improved value collection functionality.
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  array $settings Settings array to load, or null of the settings should be loaded from the database.
	 * @return void
	 */
	public function load_option($settings = null)
	{
		if (empty($settings))
		{
			$settings = wp_unslash(get_option(ACF_Stylizer_Constants::OPTION_SETTINGS));
		}
		
		if (empty($settings))
		{
			$this->_value_collection = $this;
		}
		else
		{
			$this->_set_properties($settings);
		}
	}

	/**
	 * Add the settings menu item.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @return void
	 */
	public function admin_menu()
	{
		$settings_page = add_options_page(ACF_Stylizer_Output::page_title($this->page_title), $this->base->cache->plugin_data['Name'], 'manage_options', ACF_Stylizer_Constants::OPTION_SETTINGS, array($this, 'settings_page'));

		if ($settings_page)
		{
			ACF_Stylizer_Output::add_tab('options-general.php', ACF_Stylizer_Constants::OPTION_SETTINGS, $this->page_title);
			ACF_Stylizer_Output::add_tab('edit.php?post_type=acf-field-group', '', __('ACF Field Groups', 'acf-stylizer'));
			
			add_action('load-' . $settings_page, array($this, 'load_settings_page'));
		}
	}

	/**
	 * Output the settings page.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @return void
	 */
	public function settings_page()
	{
		ACF_Stylizer_Output::admin_form_page($this->page_title, ACF_Stylizer_Constants::HOOK_SAVE_SETTINGS, ACF_Stylizer_Constants::OPTION_SETTINGS);
	}

	/**
	 * Load settings page functionality.
	 * 
	 * @since 2.1.2 Removed PHP_INT_MAX reference.
	 * @since 2.1.0 Changed hook priority and added data structure validation.
	 * @since 2.0.4 Changed uninstall setting labels.
	 * @since 2.0.2 Changed screen setup hook name.
	 * @since 2.0.1 Removed legacy body class and improved value collection functionality.
	 * @since 2.0.0
	 * 
	 * @access public
	 * @return void
	 */
	public function load_settings_page()
	{
		/**
		 * Setup the screen for Noakes Development Tools.
		 *
		 * @since 2.0.0
		 *
		 * @param  array $suffix Page suffix to use when resetting the screen.
		 * @return void
		 */
		do_action('ndt_screen_setup');
		
		add_action('admin_enqueue_scripts', array('ACF_Stylizer_Global', 'admin_enqueue_scripts'), 9999999);
		
		add_screen_option
		(
			'layout_columns',

			array
			(
				'default' => 2,
				'max' => 2
			)
		);

		ACF_Stylizer_Help::output('settings');
		
		$this->prepare_meta_boxes();

		ACF_Stylizer_Meta_Box::side_meta_boxes();
		ACF_Stylizer_Meta_Box::finalize_meta_boxes();
	}
	
	/**
	 * Prepare the settings form meta boxes.
	 * 
	 * @since 2.1.0
	 * 
	 * @access public
	 * @return void
	 */
	public function prepare_meta_boxes()
	{
		$plugin_name = $this->base->cache->plugin_data['Name'];
		$value_collection = $this->_get_value_collection();
		
		$save_all_settings = array
		(
			'button_label' => __('Save All Settings', 'acf-stylizer')
		);
		
		new ACF_Stylizer_Meta_Box(array
		(
			'context' => 'normal',
			'id' => 'general_settings',
			'option_name' => ACF_Stylizer_Constants::OPTION_SETTINGS,
			'title' => __('General Settings', 'acf-stylizer'),
			'value_collection' => $value_collection,
			
			'fields' => array
			(
				new ACF_Stylizer_Field_Checkbox(array
				(
					'field_label' => __('If checked, seamless meta boxes will be styled to be more obvious as well.', 'acf-stylizer'),
					'label' => __('Include Seamless', 'acf-stylizer'),
					'name' => 'include_seamless'
				)),
				
				new ACF_Stylizer_Field_Submit($save_all_settings)
			)
		));
		
		$uninstall_settings_box = ACF_Stylizer_Field_Checkbox::add_confirmation
		(
			new ACF_Stylizer_Meta_Box(array
			(
				'context' => 'normal',
				'id' => 'uninstall_settings',
				'option_name' => ACF_Stylizer_Constants::OPTION_SETTINGS,
				'title' => __('Uninstall Settings', 'acf-stylizer'),
				'value_collection' => $value_collection
			)),
			
			sprintf
			(
				_x('Delete settings for %1$s when the plugin is uninstalled.', 'Plugin Name', 'acf-stylizer'),
				$plugin_name
			),
			
			__('Delete Settings', 'acf-stylizer'),
			ACF_Stylizer_Constants::SETTING_DELETE_SETTINGS,
			$this->{ACF_Stylizer_Constants::SETTING_DELETE_SETTINGS}
		);
		
		$uninstall_settings_box = ACF_Stylizer_Field_Checkbox::add_confirmation
		(
			$uninstall_settings_box,
			
			sprintf
			(
				_x('Delete user meta for %1$s when the plugin is uninstalled.', 'Plugin Name', 'acf-stylizer'),
				$plugin_name
			),
			
			__('Delete User Meta', 'acf-stylizer'),
			ACF_Stylizer_Constants::SETTING_DELETE_USER_META,
			$this->{ACF_Stylizer_Constants::SETTING_DELETE_USER_META}
		);
		
		$uninstall_settings_box->add_fields(new ACF_Stylizer_Field_Submit($save_all_settings));
	}

	/**
	 * Add settings to the plugin action links.
	 *
	 * @since 2.1.0 Added non-breaking space before dashicon.
	 * @since 2.0.3 Added Dashicon to link.
	 * @since 2.0.2 Removed escape from admin URL.
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  array $links Existing action links.
	 * @return array        Modified action links.
	 */
	public function plugin_action_links($links)
	{
		array_unshift($links, '<a class="dashicons-before dashicons-admin-tools" href="' . get_admin_url(null, 'options-general.php?page=' . ACF_Stylizer_Constants::OPTION_SETTINGS) . '">&nbsp;' . $this->page_title . '</a>');

		return $links;
	}
}
