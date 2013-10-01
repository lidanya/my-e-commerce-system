<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 **/

class eklentiler_doviz_kurlari_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Doviz Kurları Model Yüklendi');
	}

	function kontrol()
	{
		$sorgu = $this->db->count_all_results('kurlar');

		if($sorgu > 0)
		{
			return true;
		} else {
			return false;
		}
	}
}