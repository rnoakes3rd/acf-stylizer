<?php
/*!
 * Meta box functionality.
 *
 * @since 2.0.0
 *
 * @package    ACF Stylizer
 * @subpackage Meta Box
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement the meta box object.
 *
 * @since 2.0.0
 *
 * @uses ACF_Stylizer_Wrapper
 */
final class ACF_Stylizer_Meta_Box extends ACF_Stylizer_Wrapper
{
	/**
	 * Constructor function.
	 *
	 * @since 2.1.0 Added data structure validation.
	 * @since 2.0.2 Improved condition.
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  array $properties Properties for the meta box.
	 * @return void
	 */
	public function __construct($properties)
	{
		parent::__construct($properties);

		if
		(
			is_callable($this->callback)
			&&
			!empty($this->id)
			&&
			$this->title !== ''
		)
		{
			if ($this->base->cache->doing_ajax)
			{
				add_filter(ACF_Stylizer_Constants::HOOK_VALIDATE_DATA, array($this, 'validate_data'));
			}
			else
			{
				$this->id = ACF_Stylizer_Constants::TOKEN . '_meta_box_' . $this->id;

				add_action('add_meta_boxes', array($this, 'add_meta_box'));
				add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'), 0);
			}
		}
	}
	
	/**
	 * Get a default value based on the provided name.
	 *
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
			 * Function used to populate the meta box.
			 *
			 * @since 2.0.0
			 *
			 * @var function
			 */
			case 'callback':
			
				return array($this, 'callback');

			/**
			 * Data that should be set as the $args property of the box array.
			 *
			 * @since 2.0.0
			 *
			 * @var array
			 */
			case 'callback_args':
			
				return null;

			/**
			 * CSS classes added to the meta box.
			 *
			 * @since 2.0.0
			 *
			 * @var array
			 */
			case 'classes':
			
			/**
			 * Fields displayed in the meta box.
			 *
			 * @since 2.0.0
			 *
			 * @var array
			 */
			 case 'fields':
			
			/**
			 * Value collection for the fields displayed in the meta box.
			 *
			 * @since 2.0.0
			 *
			 * @var mixed
			 */
			case 'value_collection':
			
				return array();

			/**
			 * Context within the screen where the boxes should display.
			 *
			 * @since 2.0.0
			 *
			 * @var string
			 */
			case 'context':
			
				return 'advanced';

			/**
			 * Base ID for the meta box.
			 *
			 * @since 2.0.0
			 *
			 * @var string
			 */
			case 'id':
			
			/**
			 * Title displayed in the meta box.
			 *
			 * @since 2.0.0
			 *
			 * @var string
			 */
			case 'title':
			
				return '';
			
			/**
			 * Option name for the fields in the meta box.
			 *
			 * @since 2.0.0
			 *
			 * @var string
			 */
			case 'option_name':
			
				return ACF_Stylizer_Constants::TOKEN;

			/**
			 * Priority within the context where the boxes should show.
			 *
			 * @since 2.0.0
			 *
			 * @var string
			 */
			case 'priority':
			
				return 'default';
		}
		
		return parent::_default($name);
	}
	
	/**
	 * Validate data associated with this meta box.
	 *
	 * @since 2.1.0
	 *
	 * @access public
	 * @param  array $valid_data Existing validated data.
	 * @return array             Modified validated data.
	 */
	public function validate_data($valid_data)
	{
		if
		(
			!empty($this->option_name)
			&&
			isset($_POST[$this->option_name])
			&&
			is_array($_POST[$this->option_name])
		)
		{
			foreach ($this->fields as $field)
			{
				$valid_data = array_merge_recursive($valid_data, $field->validate_data($_POST[$this->option_name]));
			}
		}
		
		return $valid_data;
	}

	/**
	 * Add the meta box to the page.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @return void
	 */
	public function add_meta_box()
	{
		$title = esc_html($this->title);
		
		add_meta_box($this->id, $title, $this->callback, $this->base->cache->screen, $this->context, $this->priority, $this->callback_args);

		add_filter('postbox_classes_' . esc_attr($this->base->cache->screen->id) . '_' . esc_attr($this->id), array($this, 'postbox_classes'));
	}

	/**
	 * The default callback that is fired for the meta box when one isn't provided.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @return void
	 */
	public function callback()
	{
		echo '<div class="acfs-field-wrapper">';
		
		foreach ($this->fields as $field)
		{
			$field->output(true);
		}
		
		echo '</div>';

		wp_nonce_field($this->id, $this->id . '_nonce', false);
	}

	/**
	 * Add additional classes to a meta box.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  array $classes Current meta box classes.
	 * @return array          Modified meta box classes.
	 */
	public function postbox_classes($classes)
	{
		$add_classes = ACF_Stylizer_Utilities::check_array($this->classes);
		
		array_unshift($add_classes, 'acfs-meta-box');

		return array_merge($classes, $add_classes);
	}

	/**
	 * Verify and setup the meta box fields.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @return void
	 */
	public function admin_enqueue_scripts()
	{
		$fields = (is_array($this->fields))
		? $this->fields
		: array();
		
		$verified_fields = array();
		
		foreach ($fields as $field)
		{
			if (ACF_Stylizer_Utilities::is_field($field))
			{
				$field->option_name = $this->option_name;
				$field->value_collection = $this->value_collection;
				
				$verified_fields[] = $field;
			}
		}
		
		$this->fields = $verified_fields;
	}
	
	/**
	 * Add one or more fields to the meta box.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  mixed $fields Field object or an array of field objects to add to the meta box.
	 * @return void
	 */
	public function add_fields($fields)
	{
		$fields = ACF_Stylizer_Utilities::check_array($fields);
		
		foreach ($fields as $field)
		{
			$this->push('fields', $field);
		}
	}

	/**
	 * Generate the side meta boxes.
	 *
	 * @since 2.1.0 Removed 'noreferrer' from links.
	 * @since 2.0.3 Knowldge base and donation changes.
	 * @since 2.0.2 Added knowledge base field.
	 * @since 2.0.0
	 *
	 * @access public static
	 * @return void
	 */
	public static function side_meta_boxes()
	{
		$acfs = ACF_Stylizer();
		
		new self(array
		(
			'classes' => array('acfs-meta-box-locked'),
			'context' => 'side',
			'id' => 'support',
			'title' => __('Support', 'acf-stylizer'),
			
			'fields' => array
			(
				new ACF_Stylizer_Field_HTML(array
				(
					'content' => '<strong>' . __('Plugin developed by', 'acf-stylizer') . '</strong><br />'
					. '<a href="https://robertnoakes.com/" rel="noopener" target="_blank"><img alt="Robert Noakes" height="62" src="' . $acfs->cache->asset_path('images', 'robert-noakes.png') . '" width="514" /></a>'
				)),
				
				new ACF_Stylizer_Field_HTML(array
				(
					'content' => '<strong>' . __('Knowledge base available on', 'acf-stylizer') . '</strong><br />'
					. '<a href="' . ACF_Stylizer_Constants::URL_KB . '" rel="noopener" target="_blank"><img alt="Noakes Plugins" height="75" src="' . $acfs->cache->asset_path('images', 'noakes-plugins.png') . '" width="514" /></a>'
				)),
				
				new ACF_Stylizer_Field_HTML(array
				(
					'content' => __('Running into issues with the plugin?', 'acf-stylizer') . '<br />'
					. '<a href="' . ACF_Stylizer_Constants::URL_SUPPORT . '" rel="noopener" target="_blank"><strong>' . __('Submit a ticket.', 'acf-stylizer') . '</strong></a>'
				)),
				
				new ACF_Stylizer_Field_HTML(array
				(
					'content' => __('Have some feedback you\'d like to share?', 'acf-stylizer') . '<br />'
					. '<a href="' . ACF_Stylizer_Constants::URL_REVIEW . '" rel="noopener" target="_blank"><strong>' . __('Provide a review.', 'acf-stylizer') . '</strong></a>'
				)),
				
				new ACF_Stylizer_Field_HTML(array
				(
					'content' => __('Want to see the plugin in your language?', 'acf-stylizer') . '<br />'
					. '<a href="' . ACF_Stylizer_Constants::URL_TRANSLATE . '" rel="noopener" target="_blank"><strong>' . __('Assist with translation.', 'acf-stylizer') . '</strong></a>'
				)),
				
				new ACF_Stylizer_Field_HTML(array
				(
					'content' => __('Would you like to support development?', 'acf-stylizer') . '<br />'
					. '<strong>'
						. sprintf
						(
							_x('Sign up for WPEngine using the banner in the \'Better Hosting with WPEngine\' meta box or %1$s.', 'Donate Link', 'acf-stylizer'),
							'<a href="' . ACF_Stylizer_Constants::URL_DONATE . '" rel="noopener" target="_blank">' . __('make a donation', 'acf-stylizer') . '</a>'
						)
					. '</strong>'
				))
			)
		));

		new self(array
		(
			'classes' => array('acfs-meta-box-locked'),
			'context' => 'normal',
			'id' => 'advertising',
			'title' => __('Better Hosting with WPEngine', 'acf-stylizer'),
			
			'fields' => array
			(
				new ACF_Stylizer_Field_HTML(array
				(
					'content' => '<a href="https://shareasale.com/r.cfm?b=1144535&amp;u=1815763&amp;m=41388&amp;urllink=&amp;afftrack=" rel="noopener" target="_blank">'
						. '<img alt="WPEngine - Your WordPress Digital Experience Platform. Get 3 months free with annual plan purchases. - LEARN MORE" border="0" class="acfs-banner-tall" src="' . $acfs->cache->asset_path('images', 'YourWordPressDXP300x600.png') . '" />'
						. '<img alt="WPEngine - High performance WordPress hosting that just works. Get 3 months free with annual plan purchases - LEARN MORE" border="0" class="acfs-banner-wide" src="' . $acfs->cache->asset_path('images', 'YourWordPressDXP728x90.png') . '" />'
					. '</a>'
				))
			)
		));
	}

	/**
	 * Finalize the meta boxes.
	 *
	 * @since 2.1.2 Removed PHP_INT_MAX reference.
	 * @since 2.0.0
	 *
	 * @access public static
	 * @return void
	 */
	public static function finalize_meta_boxes()
	{
		add_action('add_meta_boxes', array(__CLASS__, 'remove_meta_boxes'), 9999999);
		do_action('add_meta_boxes', ACF_Stylizer()->cache->screen->id, null);
	}

	/**
	 * Remove unnecessary meta boxes.
	 *
	 * @since 2.0.0
	 *
	 * @access public static
	 * @return void
	 */
	public static function remove_meta_boxes()
	{
		$acfs = ACF_Stylizer();

		remove_meta_box('eg-meta-box', $acfs->cache->screen->id, 'normal');
		remove_meta_box('mymetabox_revslider_0', $acfs->cache->screen->id, 'normal');
	}
}
