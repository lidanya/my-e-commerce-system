<?php if (!defined('BASEPATH')) exit('Doğrudan link ya da adres girişi yasaklanmıştır. !');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2009, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Config Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		
 */

// ------------------------------------------------------------------------

/**
 * Config
 *
 * @access	public
 * @param	string	the config line
 * @return	string
 */	
if ( ! function_exists('config'))
{
	function config($line)
	{

		$CI =& get_instance();
		$line = $CI->config->item($line);

		return $line;
	}
}

/**
 * Config Update
 *
 * @access	public
 * @param	string	the config update
 * @return	string
 */	

if ( ! function_exists('config_update'))
{
	function config_update($ayar_adi, $ayar_deger)
	{
		$CI =& get_instance();
		$CI->fonksiyonlar->config_update($ayar_adi, $ayar_deger);
	}
}



// ------------------------------------------------------------------------
/* End of file config_helper.php */
/* Location: ./dev10/helpers/config_helper.php */