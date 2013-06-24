<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class eklentiler_haberler_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Haberler Model Yüklendi');

		$this->load->model('information_model');
	}

	function haberler_listele()
	{
		if(eklenti_ayar('haberler', 'siralama_sekli') != NULL)
		{
			$siralama = eklenti_ayar('haberler', 'siralama_sekli');
		} else {
			$siralama = 'desc';
		}

		$type = 'news';
		$limit = eklenti_ayar('haberler', 'siralama_limit');
		$sort = 'i.sort_order';
		$order = $siralama;
		$information = $this->information_model->get_information_by_type($type, $limit, $sort, $order);

		return $information;
	}

	function kontrol()
	{
		$type = 'news';
		$check = $this->information_model->count_information_by_type($type);

		if($check) {
			return true;
		} else {
			return false;
		}
	}
}