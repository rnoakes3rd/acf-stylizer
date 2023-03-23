<?php
/*!
 * HTML field functionality.
 *
 * @since 2.0.0
 *
 * @package    ACF Stylizer
 * @subpackage HTML Field
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement the HTML field object.
 *
 * @since 2.0.0
 *
 * @uses ACF_Stylizer_Field
 */
final class ACF_Stylizer_Field_HTML extends ACF_Stylizer_Field
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
			 * Content added to the field.
			 *
			 * @since 2.0.0
			 *
			 * @var string
			 */
			case 'content':
			
				return '';
		}

		return parent::_default($name);
	}
	
	/**
	 * Generate the output for the HTML field.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @param  boolean $echo True if the HTML field should be echoed.
	 * @return string        Generated HTML field if $echo is false.
	 */
	public function output($echo = false)
	{
		$output = '<div class="acfs-html' . $this->_field_classes(false) . '">'
			. wpautop(do_shortcode($this->content))
		. '</div>';
		
		return parent::_output($output, 'html', $echo);
	}
}
