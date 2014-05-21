<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 **/

class manufacturer_model extends CI_Model 
{

	function __construct() 
	{
		parent::__construct();
	}
		
	function get_manufacturer_by_id($manufacturer_id) 
	{
		$this->db->select(get_fields_from_table('manufacturer', 'm.', array(), ''));
		$this->db->from('manufacturer m');
		$this->db->where('m.manufacturer_id', (int) $manufacturer_id);
		$this->db->where('m.status', '1');
		$this->db->limit(1);
		$query = $this->db->get();

		if($query->num_rows()) {
			return $query->row();
		} else {
			return FALSE;
		}
	}

	function get_manufacturer_by_seo($seo) 
	{
		$this->db->select(get_fields_from_table('manufacturer', 'm.', array(), ''));
		$this->db->from('manufacturer m');
		$this->db->where('m.seo', $seo);
		$this->db->where('m.status', '1');
		$this->db->limit(1);
		$query = $this->db->get();

		if($query->num_rows()) {
			return $query->row();
		} else {
			return FALSE;
		}
	}

	public function get_manufacturer_by_all($order = 'asc', $limit = NULL)
	{
		if ($limit == '') {
			$limit = (config('site_ayar_urun_site_sayfa')) ? config('site_ayar_urun_site_sayfa') : 9;
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

}

/* End of file class_name.php */
/* Location: ./application/models/class_name.php */