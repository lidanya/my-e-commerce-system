<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author Daynex.com.tr
 **/

class eklentiler_kampanyali_urunler_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Kampanyalı Ürünler Model Yüklendi');
	}

	function kampanyali_urunler_listele()
	{
		if(eklenti_ayar('kampanyali_urunler', 'siralama_sekli') != NULL)
		{
			$order = eklenti_ayar('kampanyali_urunler', 'siralama_sekli');
		} else {
			$order = 'desc';
		}

		if(eklenti_ayar('kampanyali_urunler', 'siralama_limit') != NULL) {
			$limit = eklenti_ayar('kampanyali_urunler', 'siralama_limit');
		} else {
			$limit = 5;
		}

		if ($this->dx_auth->is_logged_in()) {
			$user_group_id = $this->dx_auth->get_role_id();
		} else {
			$user_group_id = config('site_ayar_varsayilan_mus_grub');
		}

		$language_id = get_language('language_id');
		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS pd.name AS name, p.image AS image, m.name AS manufacturer, ' . 
			get_fields_from_table('product', 'p.', array(), ', ') .
			get_fields_from_table('product_description', 'pd.', array(), ', ') .
			get_fields_from_table('product_special', 'ps.', array('	product_special_id','date_end'), ', ')
			, FALSE);
		$this->db->from('product_special ps');
		$this->db->join('product p', 'ps.product_id = p.product_id', 'left');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->join('manufacturer m', 'p.manufacturer_id = m.manufacturer_id', 'left');
		$this->db->where('pd.language_id', (int) $language_id);
		if ( ! $this->dx_auth->is_role('admin-gruplari')) {
			$this->db->where('ps.user_group_id', (int) $user_group_id);
		}
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
		if(eklenti_ayar('kampanyali_urunler', 'siralama_sekli') != NULL)
		{
			$order = eklenti_ayar('kampanyali_urunler', 'siralama_sekli');
		} else {
			$order = 'desc';
		}

		if(eklenti_ayar('kampanyali_urunler', 'siralama_limit') != NULL) {
			$limit = eklenti_ayar('kampanyali_urunler', 'siralama_limit');
		} else {
			$limit = 5;
		}

		if ($this->dx_auth->is_logged_in()) {
			$user_group_id = $this->dx_auth->get_role_id();
		} else {
			$user_group_id = config('site_ayar_varsayilan_mus_grub');
		}

		$language_id = get_language('language_id');
		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS pd.name AS name, p.image AS image, m.name AS manufacturer, ' . 
			get_fields_from_table('product', 'p.', array(), ', ') .
			get_fields_from_table('product_description', 'pd.', array(), ', ') .
			get_fields_from_table('product_special', 'ps.', array('	product_special_id'), ', ')
			, FALSE);
		$this->db->from('product_special ps');
		$this->db->join('product p', 'ps.product_id = p.product_id', 'left');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->join('manufacturer m', 'p.manufacturer_id = m.manufacturer_id', 'left');
		$this->db->where('pd.language_id', (int) $language_id);
		if ( ! $this->dx_auth->is_role('admin-gruplari')) {
			$this->db->where('ps.user_group_id', (int) $user_group_id);
		}
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