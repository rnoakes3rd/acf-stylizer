<?php
/*!
 * Submit field functionality.
 *
 * @since 2.0.0
 *
 * @package    ACF Stylizer
 * @subpackage Submit Field
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement the submit field object.
 *
 * @since 2.0.0
 *
 * @uses ACF_Stylizer_Field
 */
final class ACF_Stylizer_Field_Submit extends ACF_Stylizer_Field
{
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
			 * Additional actions added next to the submit button.
			 *
			 * @since 2.0.4
			 *
			 * @var string
			 */
			case 'additional_actions':
			
				return '';
				
			/**
			 * Label for the submit button.
			 *
			 * @since 2.0.0
			 *
			 * @var string
			 */
			case 'button_label':
			
				return __('Submit', 'acf-stylizer');
		}

		return parent::_default($name);
	}
	
	/**
	 * Generate the output for the submit field.
	 *
	 * @since 2.0.4 Added additional actions functionality.
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  boolean $echo True if the submit field should be echoed.
	 * @return string        Generated submit field if $echo is false.
	 */
	public function output($echo = false)
	{
		return parent::_output
		(
			'<div class="acfs-field-actions">'
				. '<button class="button button-large button-primary acfs-button' . $this->_field_classes(false) . '"' . $this->input_attributes . ' disabled="disabled" type="submit"><span>' . $this->button_label . '</span></button>'
				. $this->additional_actions
			. '</div>',
			
			'submit',
			$echo
		);
	}
}
