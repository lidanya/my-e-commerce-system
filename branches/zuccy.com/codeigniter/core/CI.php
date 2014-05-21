<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://CIgniter.com/user_guide/license.html
 * @link		http://CIgniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CI Singleton Class
 *
 * Provides access to the global CIgniter Singleton.
 * 
 *
 * @package		CIgniter
 * @subpackage	Libraries
 * @category	CI
 */
class CI {
	
	protected static $instance = NULL;
	
	/**
	 * instance
	 * 
	 * Returns the Singleton instance of the class
	 * 
	 * @access	public
	 * @return	object
	 */
	public static function &instance()
	{
		if (CI::$instance === NULL)
		{
			CI::$instance = new CI();
		}
		
		return CI::$instance;
	}

	/**
	 * config
	 * 
	 * Provides global easy access to the config items.
	 * 
	 * @access	public
	 * @return	mixed
	 */
	public static function config($item, $index = '')
	{
		return CI::instance()->config->item($item, $index);
	}

	/**
	 * __clone
	 * 
	 * Protect the singleton from being cloned.
	 * 
	 * @access	private
	 */
	private function __clone() { }
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		if (CI::$instance === NULL)
		{
			CI::$instance =& $this;
		}

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');
		$this->load->_base_classes =& is_loaded();
		$this->load->_ci_autoloader();
		log_message('debug', "CI Class Initialized");
	}
	
}
// END CI Class

/* End of file Ci.php */
/* Location: ./system/core/Ci.php */