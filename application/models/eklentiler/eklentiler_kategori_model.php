<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class eklentiler_kategori_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Kategori Model Yüklendi');

		$this->load->model('site/category_model');
		$this->load->model('site/product_model');
	}

	function kategori_listele($parent_id = 0)
	{
		if(eklenti_ayar('kategori', 'siralama_sekli') != NULL)
		{
			$siralama = eklenti_ayar('kategori', 'siralama_sekli');
		} else {
			$siralama = 'asc';
		}

		$limit = eklenti_ayar('kategori', 'siralama_limit');
		$sort = 'c.sort_order';
		$order = $siralama;
		$category = $this->category_model->get_categories_by_parent_id($parent_id, $limit, $sort, $order);

		return $category;
	}

	function kategori_urun_listele($kategori_id)
	{
		if(eklenti_ayar('kategori', 'siralama_sekli') != NULL)
		{
			$siralama = eklenti_ayar('kategori', 'siralama_sekli');
		} else {
			$siralama = 'desc';
		}

		$limit = eklenti_ayar('kategori', 'siralama_limit');
		$sort = 'p.sort_order';
		$order = $siralama;
		$category_id = $kategori_id;
		$products = $this->product_model->get_products_by_category_id($category_id, $sort = 'product_id', $order = 'asc', $start = 0, $limit = 10);

		return $products;
	}

	function kontrol()
	{
		$check = $this->category_model->count_category();
		if($check) {
			return true;
		} else {
			return false;
		}
	}
}