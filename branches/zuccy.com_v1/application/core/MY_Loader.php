<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Loader Class
 *
 * Loads views and files
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @author		ExpressionEngine Dev Team
 * @category	Loader
 * @link		http://codeigniter.com/user_guide/libraries/loader.html
 */
class MY_Loader extends CI_Loader {

	function __construct() {
		parent::__construct();
		$this->_set_configs();
	}

	function _set_configs() {
		$ci =& get_instance();
		$db = $this->_database();
		$db->select('ayar_adi, ayar_deger');
		$sogru = $db->get('ayarlar');
		if($sogru) {
			foreach ($sogru->result() as $ayarlar) {
				$ci->config->set_item($ayarlar->ayar_adi, $ayarlar->ayar_deger);
			}
		}
	}

	function _database()
	{
		// load database configs
		require APPPATH.'config/database'.EXT;
		$_array_params = $db[$active_group];
		$_array_params['db_debug'] = FALSE;

		// set db configs debug false
		require_once BASEPATH.'database/DB'.EXT;

		// Load the DB class
		$db =& DB($_array_params, TRUE);
		return $db;
	}

}