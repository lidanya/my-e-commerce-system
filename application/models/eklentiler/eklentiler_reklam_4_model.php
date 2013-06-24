<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class eklentiler_reklam_4_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Reklam Model Yüklendi');
	}

	function kontrol()
	{
		$sorgu = eklenti_ayar('reklam_4', 'icerik');

		if($sorgu)
		{
			return true;
		} else {
			return false;
		}
	}
}