<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class eklentiler_yeni_urunlerimiz_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Yeni Ürünlerimiz Model Yüklendi');
	}

	function yeni_urunler_listele()
	{
		if(eklenti_ayar('yeni_urunlerimiz', 'siralama_sekli') != NULL)
		{
			$order = eklenti_ayar('yeni_urunlerimiz', 'siralama_sekli');
		} else {
			$order = 'desc';
		}

		if(eklenti_ayar('yeni_urunlerimiz', 'siralama_limit') != NULL)
		{
			$limit = eklenti_ayar('yeni_urunlerimiz', 'siralama_limit');
		} else {
			$limit = 5;
		}

		$language_id = get_language('language_id');
		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS pd.name AS name, p.image AS image, m.name AS manufacturer, ' . 
			get_fields_from_table('product', 'p.', array(), ', ') . 
			get_fields_from_table('product_description', 'pd.', array(), '')
			, FALSE);
		$this->db->from('product p');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->join('manufacturer m', 'p.manufacturer_id = m.manufacturer_id', 'left');
		$this->db->where('p.new_product', '1');
		$this->db->where('pd.language_id', (int) $language_id);
		$this->db->where('p.date_available >= UNIX_TIMESTAMP()');
		$this->db->where('p.status', '1');
		$this->db->order_by('p.sort_order', $order);
		$this->db->limit($limit);
		$query = $this->db->get();
		$total_row = $this->db->select('FOUND_ROWS() as total')->get()->row()->total;
		if($query->num_rows()) {
			return array(
				'query' => $query->result(),
				'total' => $total_row
			);
		} else {
			return FALSE;
		}
	}

	function kontrol()
	{
		if(eklenti_ayar('yeni_urunlerimiz', 'siralama_sekli') != NULL)
		{
			$order = eklenti_ayar('yeni_urunlerimiz', 'siralama_sekli');
		} else {
			$order = 'desc';
		}

		if(eklenti_ayar('yeni_urunlerimiz', 'siralama_limit') != NULL)
		{
			$limit = eklenti_ayar('yeni_urunlerimiz', 'siralama_limit');
		} else {
			$limit = 5;
		}

		$language_id = get_language('language_id');
		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS pd.name AS name, p.image AS image, m.name AS manufacturer, ' . 
			get_fields_from_table('product', 'p.', array(), ', ') . 
			get_fields_from_table('product_description', 'pd.', array(), '')
			, FALSE);
		$this->db->from('product p');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->join('manufacturer m', 'p.manufacturer_id = m.manufacturer_id', 'left');
		$this->db->where('p.new_product', '1');
		$this->db->where('pd.language_id', (int) $language_id);
		$this->db->where('p.date_available >= UNIX_TIMESTAMP()');
		$this->db->where('p.status', '1');
		$this->db->order_by('p.sort_order', $order);
		$this->db->limit($limit);
		$check = $this->db->count_all_results();

		if($check) {
			return true;
		} else {
			return false;
		}
	}
}