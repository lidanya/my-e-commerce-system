<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class eklentiler_icerik_kategorileri_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'İçerik Kategorileri Model Yüklendi');
	}

	function icerik_listele()
	{
		if(eklenti_ayar('icerik_kategorileri', 'siralama_sekli') != NULL)
		{
			$siralama = eklenti_ayar('icerik_kategorileri', 'siralama_sekli');
		} else {
			$siralama = 'desc';
		}

		if(eklenti_ayar('icerik_kategorileri', 'kategori_id') != NULL)
		{
			$kategori_id = eklenti_ayar('icerik_kategorileri', 'kategori_id');
		} else {
			$kategori_id = -1;
		}

		$type = 'information';
		$limit = eklenti_ayar('icerik_kategorileri', 'siralama_limit');
		$sort = 'i.sort_order';
		$category_id = $kategori_id;
		$order = $siralama;
		$information = $this->information_model->get_information_by_type_category_id($type, $category_id, $limit, $sort, $order);

		return $information;
	}

	function kategori_detay()
	{
		if(eklenti_ayar('icerik_kategorileri', 'kategori_id') != NULL)
		{
			$kategori_id = eklenti_ayar('icerik_kategorileri', 'kategori_id');
		} else {
			$kategori_id = -1;
		}

		$type = 'information';
		$category_id = $kategori_id;
		$information = $this->information_model->get_information_category_by_type_category_id($type, $category_id);
		return $information;
	}

	function kontrol()
	{
		if(eklenti_ayar('icerik_kategorileri', 'kategori_id') != NULL)
		{
			$kategori_id = eklenti_ayar('icerik_kategorileri', 'kategori_id');
		} else {
			$kategori_id = -1;
		}

		$type = 'information';
		$category_id = $kategori_id;
		$check = $this->information_model->count_information_by_type_category_id($type, $category_id);
		if($check) {
			return true;
		} else {
			return false;
		}
	}
}