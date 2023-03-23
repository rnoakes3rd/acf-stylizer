<?php
/*!
 * Functionality for plugin help.
 *
 * @since 2.0.0
 *
 * @package    ACF Stylizer
 * @subpackage Help
 */

if (!defined('ABSPATH'))
{
	exit;
}

/**
 * Class used to implement plugin help functionality.
 *
 * @since 2.0.0
 */
final class ACF_Stylizer_Help
{
	/**
	 * Output the help tabs.
	 *
	 * @since 2.1.0 Removed 'noreferrer' from links.
	 * @since 2.0.4 Help tab ID change.
	 * @since 2.0.2 Enabled help tabs and added AJAX check.
	 * @since 2.0.0
	 *
	 * @access public static
	 * @param  string  $kb_path     Path to the knowledge base article associated with this help tab.
	 * @param  boolean $plugin_page True if the help tab is being added to a plugin-specific page.
	 * @return void
	 */
	public static function output($kb_path, $plugin_page = true)
	{
		$acfs = ACF_Stylizer();
		
		if
		(
			!empty($kb_path)
			&&
			!$acfs->cache->doing_ajax
		)
		{
			$id = 'acfs-' . $acfs->cache->option_name;
			
			if ($plugin_page === true)
			{
				$acfs->cache->screen->set_help_sidebar('<p><strong>' . __('Plugin developed by', 'acf-stylizer') . '</strong><br />'
				. '<a href="https://robertnoakes.com/" rel="noopener" target="_blank">Robert Noakes</a></p>'
				. '<hr />'
				. '<p><a class="button" href="' . ACF_Stylizer_Constants::URL_SUPPORT . '" rel="noopener" target="_blank">' . __('Plugin Support', 'acf-stylizer') . '</a></p>'
				. '<p><a class="button" href="' . ACF_Stylizer_Constants::URL_REVIEW . '" rel="noopener" target="_blank">' . __('Review Plugin', 'acf-stylizer') . '</a></p>'
				. '<p><a class="button" href="' . ACF_Stylizer_Constants::URL_TRANSLATE . '" rel="noopener" target="_blank">' . __('Translate Plugin', 'acf-stylizer') . '</a></p>'
				. '<p><a class="button" href="' . ACF_Stylizer_Constants::URL_DONATE . '" rel="noopener" target="_blank">' . __('Plugin Donation', 'acf-stylizer') . '</a></p>');
			}
			else if ($plugin_page !== false)
			{
				$id .= $plugin_page;
			}
			
			$url = ACF_Stylizer_Constants::URL_KB . $kb_path . '/';
			
			$acfs->cache->screen->add_help_tab(array
			(
				'id' => $id,
				'priority' => 20,
				'title' => $acfs->cache->plugin_data['Name'],
				
				'content' => '<h3>' . __('For more information about this page, view the knowledge base article at:', 'acf-stylizer') . '<br />'
				. '<a href="' . esc_url($url) . '" rel="noopener" target="_blank">' . $url . '</a></h3>'
			));
		}
	}
}
