<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class eklentiler_duyurular_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Duyurular Model Yüklendi');

		$this->load->model('information_model');
	}

	function duyurular_listele()
	{
		if(eklenti_ayar('duyurular', 'siralama_sekli') != NULL)
		{
			$siralama = eklenti_ayar('duyurular', 'siralama_sekli');
		} else {
			$siralama = 'desc';
		}

		$type = 'announcement';
		$limit = eklenti_ayar('duyurular', 'siralama_limit');
		$sort = 'i.sort_order';
		$order = $siralama;
		$information = $this->information_model->get_information_by_type($type, $limit, $sort, $order);

		return $information;
	}

	function kontrol()
	{
		$type = 'announcement';
		$check = $this->information_model->count_information_by_type($type);

		if($check) {
			return true;
		} else {
			return false;
		}
	}
}