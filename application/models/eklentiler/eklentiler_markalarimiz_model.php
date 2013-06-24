<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class eklentiler_markalarimiz_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Markalarımız Model Yüklendi');
	}

	function marka_getir()
	{
		if(eklenti_ayar('markalarimiz', 'siralama_sekli') != NULL)
		{
			$order = eklenti_ayar('markalarimiz', 'siralama_sekli');
		} else {
			$order = 'desc';
		}

		if(eklenti_ayar('markalarimiz', 'siralama_limit') != NULL) {
			$limit = eklenti_ayar('markalarimiz', 'siralama_limit');
		} else {
			$limit = 5;
		}

		$language_id = get_language('language_id');
		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS ' .
			get_fields_from_table('manufacturer', 'm.', array(), '')
			, FALSE);
		$this->db->from('manufacturer m');
		$this->db->where('m.status', '1');
		$this->db->order_by('m.sort_order', $order);
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
		if(eklenti_ayar('markalarimiz', 'siralama_sekli') != NULL)
		{
			$order = eklenti_ayar('markalarimiz', 'siralama_sekli');
		} else {
			$order = 'desc';
		}

		if(eklenti_ayar('markalarimiz', 'siralama_limit') != NULL) {
			$limit = eklenti_ayar('markalarimiz', 'siralama_limit');
		} else {
			$limit = 5;
		}

		$language_id = get_language('language_id');
		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS ' .
			get_fields_from_table('manufacturer', 'm.', array(), '')
			, FALSE);
		$this->db->from('manufacturer m');
		$this->db->where('m.status', '1');
		$this->db->order_by('m.sort_order', $order);
		$this->db->limit($limit);
		$check = $this->db->count_all_results();

		if($check) {
			return true;
		} else {
			return false;
		}
	}
}