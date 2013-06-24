<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class eklentiler_urun_arama_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Ürün Arama Model Yüklendi');
	}

	function kontrol()
	{
		$this->db->where('status', '1');
		$check = $this->db->count_all_results('product');

		if($check) {
			return true;
		} else {
			return false;
		}
	}
}