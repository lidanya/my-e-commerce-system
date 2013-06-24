<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class eklentiler_indirimli_urunler_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'İndirimli Ürünler Model Yüklendi');
	}

	function indirimli_urunler_listele()
	{
		if(eklenti_ayar('indirimli_urunler', 'siralama_sekli') != NULL)
		{
			$order = eklenti_ayar('indirimli_urunler', 'siralama_sekli');
		} else {
			$order = 'desc';
		}

		if(eklenti_ayar('indirimli_urunler', 'siralama_limit') != NULL) {
			$limit = eklenti_ayar('indirimli_urunler', 'siralama_limit');
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
		$this->db->select('SQL_CALC_FOUND_ROWS pd2.name AS name, p.image AS image, m.name AS manufacturer, ' . 
			get_fields_from_table('product', 'p.', array(), ', ') .
			get_fields_from_table('product_description', 'pd2.', array(), ', ') .
			get_fields_from_table('product_discount', 'pd.', array('product_discount_id','date_end'), ', ')
			, FALSE);
		$this->db->from('product_discount pd');
		$this->db->join('product p', 'pd.product_id = p.product_id', 'left');
		$this->db->join('product_description pd2', 'p.product_id = pd2.product_id', 'left');
		$this->db->join('manufacturer m', 'p.manufacturer_id = m.manufacturer_id', 'left');
		$this->db->where('pd2.language_id', (int) $language_id);
		if ( ! $this->dx_auth->is_role('admin-gruplari')) {
			$this->db->where('pd.user_group_id', (int) $user_group_id);
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
		if(eklenti_ayar('indirimli_urunler', 'siralama_sekli') != NULL)
		{
			$order = eklenti_ayar('indirimli_urunler', 'siralama_sekli');
		} else {
			$order = 'desc';
		}

		if(eklenti_ayar('indirimli_urunler', 'siralama_limit') != NULL) {
			$limit = eklenti_ayar('indirimli_urunler', 'siralama_limit');
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
		$this->db->select('SQL_CALC_FOUND_ROWS pd2.name AS name, p.image AS image, m.name AS manufacturer, ' . 
			get_fields_from_table('product', 'p.', array(), ', ') .
			get_fields_from_table('product_description', 'pd2.', array(), ', ') .
			get_fields_from_table('product_discount', 'pd.', array('product_discount_id'), ', ')
			, FALSE);
		$this->db->from('product_discount pd');
		$this->db->join('product p', 'pd.product_id = p.product_id', 'left');
		$this->db->join('product_description pd2', 'p.product_id = pd2.product_id', 'left');
		$this->db->join('manufacturer m', 'p.manufacturer_id = m.manufacturer_id', 'left');
		$this->db->where('pd2.language_id', (int) $language_id);
		if ( ! $this->dx_auth->is_role('admin-gruplari')) {
			$this->db->where('pd.user_group_id', (int) $user_group_id);
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