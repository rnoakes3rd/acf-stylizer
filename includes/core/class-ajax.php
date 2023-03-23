<?php
/*!
 * AJAX functionality.
 *
 * @since 2.0.0
 *
 * @package    ACF Stylizer
 * @subpackage AJAX
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement the AJAX functionality.
 *
 * @since 2.0.0
 *
 * @uses ACF_Stylizer_Wrapper
 */
final class ACF_Stylizer_AJAX extends ACF_Stylizer_Wrapper
{
	/**
	 * Constructor function.
	 *
	 * @since 2.0.2 Changed AJAX check.
	 * @since 2.0.0
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		if ($this->base->cache->doing_ajax)
		{
			add_action('wp_ajax_' . ACF_Stylizer_Constants::HOOK_SAVE_SETTINGS, array($this, 'save_settings'));
		}
		else
		{
			$query_arg = '';
			
			if (isset($_GET[ACF_Stylizer_Constants::HOOK_SAVE_SETTINGS]))
			{
				$query_arg = ACF_Stylizer_Constants::HOOK_SAVE_SETTINGS;
				
				ACF_Stylizer_Noatice::add_success(__('Settings saved successfully.', 'acf-stylizer'));
			}
			
			if (!empty($query_arg))
			{
				$this->base->cache->push('remove_query_args', $query_arg);
			}
		}
	}
	
	/**
	 * Save the plugin settings.
	 *
	 * @since 2.1.2 Improved query argument.
	 * @since 2.1.0 Added data structure validation.
	 * @since 2.0.4 Improved structure.
	 * @since 2.0.3 Added capability check and additional data validation.
	 * @since 2.0.2 Removed escape from response URL.
	 * @since 2.0.1 Removed unused code.
	 * @since 2.0.0
	 *
	 * @access public
	 * @return void
	 */
	public function save_settings()
	{
		if ($this->_invalid_submission(ACF_Stylizer_Constants::HOOK_SAVE_SETTINGS))
		{
			$this->_send_error(__('You are not authorized to save settings.', 'acf-stylizer'), 403);
		}
		else if ($this->_invalid_redirect())
		{
			$this->_send_error(__('Settings could not be saved.', 'acf-stylizer'));
		}
		
		$this->base->settings->prepare_meta_boxes();
		
		$option_name = sanitize_key($_POST['option-name']);
		
		update_option($option_name, ACF_Stylizer_Sanitization::sanitize
		(
			/**
			 * Validate the data for the settings form.
			 *
			 * @since 2.1.0
			 *
			 * @param array $valid_data Validated data.
			 */
			apply_filters(ACF_Stylizer_Constants::HOOK_VALIDATE_DATA, array())
		));
		
		wp_send_json_success(array
		(
			'url' => add_query_arg
			(
				array
				(
					'page' => $option_name,
					ACF_Stylizer_Constants::HOOK_SAVE_SETTINGS => 1
				),
				
				admin_url(sanitize_text_field($_POST['admin-page']))
			)
		));
	}
	
	/**
	 * Check for invalid redirect data.
	 *
	 * @since 2.0.4
	 *
	 * @access private
	 * @return boolean True if the required redirect data is missing.
	 */
	private function _invalid_redirect()
	{
		return
		(
			!isset($_POST['admin-page'])
			||
			empty($_POST['admin-page'])
			||
			!isset($_POST['option-name'])
			||
			empty($_POST['option-name'])
		);
	}
	
	/**
	 * Check for an invalid submission.
	 *
	 * @since 2.0.4
	 *
	 * @access private
	 * @param  string $action     AJAX action to verify the nonce for.
	 * @param  string $capability User capability required to complete the submission.
	 * @return boolean            True if the submission is invalid.
	 */
	private function _invalid_submission($action, $capability = 'manage_options')
	{
		return
		(
			!check_ajax_referer($action, false, false)
			||
			(
				!empty($capability)
				&&
				!current_user_can($capability)
			)
		);
	}
	
	/**
	 * Send a general error message.
	 * 
	 * @since 2.0.4 Added status code argument.
	 * @since 2.0.0
	 * 
	 * @access private
	 * @param  string  $message     Message displayed in the error noatice.
	 * @param  integer $status_code HTTP status code to send with the error.
	 * @return void
	 */
	private function _send_error($message, $status_code = null)
	{
		wp_send_json_error
		(
			array
			(
				'noatice' => ACF_Stylizer_Noatice::generate_error($message)
			),
			
			$status_code
		);
	}
}
