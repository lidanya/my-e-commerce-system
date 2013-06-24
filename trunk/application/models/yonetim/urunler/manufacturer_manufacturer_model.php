<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class manufacturer_manufacturer_model extends CI_Model 
{

	function __construct() 
	{
		parent::__construct();
	}

	public function add_manufacturer($get_values)
	{
		$check_seo = ($get_values['seo'] AND $get_values['seo'] != '') ? url_title($get_values['seo'], 'dash', TRUE) : url_title($get_values['name'], 'dash', TRUE);
		$seo = $this->check_seo($check_seo);

		$_manufacturer_data = array(
			'name'					=> $get_values['name'],
			'image'					=> $get_values['image'],
			'seo'					=> $seo,
			'meta_description'		=> $get_values['meta_description'],
			'meta_keywords'			=> $get_values['meta_keywords'],
			'description'			=> $get_values['description'],
			'sort_order'			=> $get_values['sort_order'],
			'status'				=> $get_values['status'],
			'date_added'			=> standard_date('DATE_MYSQL', time(), 'tr'),
			'date_modified'			=> standard_date('DATE_MYSQL', time(), 'tr')
		);
		$this->db->insert('manufacturer', $_manufacturer_data);
		$manufacturer_add_status = $this->db->affected_rows();

		if($manufacturer_add_status) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function update_manufacturer($manufacturer_id, $get_values)
	{
		$check_seo = ($get_values['seo'] AND $get_values['seo'] != '') ? url_title($get_values['seo'], 'dash', TRUE) : url_title($get_values['name'], 'dash', TRUE);
		$seo = $this->check_seo($check_seo, $manufacturer_id);

		$_manufacturer_update_data = array(
			'name'					=> $get_values['name'],
			'image'					=> $get_values['image'],
			'seo'					=> $seo,
			'meta_description'		=> $get_values['meta_description'],
			'meta_keywords'			=> $get_values['meta_keywords'],
			'description'			=> $get_values['description'],
			'sort_order'			=> $get_values['sort_order'],
			'status'				=> $get_values['status'],
			'date_modified'	=> standard_date('DATE_MYSQL', time(), 'tr')
		);
		$this->db->where('manufacturer_id', $manufacturer_id);
		$this->db->update('manufacturer', $_manufacturer_update_data);
		$manufacturer_update_status = $this->db->affected_rows();

		if($manufacturer_update_status) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function check_seo($seo, $manufacturer_id = FALSE)
	{
		$this->db->from('manufacturer');
		$this->db->where('seo', $seo);
		if($manufacturer_id) {
			$this->db->where_not_in('manufacturer_id', (int) $manufacturer_id);
		}
		$count = $this->db->count_all_results();

		if($count) {
			$this->load->helper('string');
			$_seo = $seo . '-' . mb_strtolower(random_string('alnum', 4));
			return $this->check_seo($_seo, $manufacturer_id);
		} else {
			return $seo;
		}
	}

	public function get_manufacturers_by_all($page, $sort = 'm.manufacturer_id', $order = 'desc', $filter = 'm.status|]', $sort_link)
	{
		$_array = explode(', ', get_fields_from_table('manufacturer', 'm.'));
		$_filter_allowed = $_array;
		$_sort_allowed = $_array;

		$per_page = (config('site_ayar_urun_yonetim_sayfa')) ? config('site_ayar_urun_yonetim_sayfa') : 20;

		if( ! in_array($sort, $_sort_allowed)) {
			$sort = 'm.manufacturer_id';
		}

		$_order_allowed = array('asc', 'desc', 'random');

		if( ! in_array($order, $_order_allowed)) {
			$order = 'desc';
		}

		$this->db->select('SQL_CALC_FOUND_ROWS ' . 
			get_fields_from_table('manufacturer', 'm.', array(), '')
		, FALSE);
		$this->db->from('manufacturer m');

		if ($filter != 'm.status|]') {
			$filter_e = explode(']', $filter);
			foreach($filter_e as $yaz) {
				if($yaz != '') {
					if(preg_match('/|/i', $yaz)) {
						$explode = explode('|', $yaz);
						if((isset($explode[0]) AND $explode[0] != '') AND (isset($explode[1]) AND $explode[1] != '')) {
							if(in_array($explode[0], $_filter_allowed)) {
								$this->db->like($explode[0], $explode[1]);
							}
						}
					}
				}
			}
		}

		$this->db->order_by($sort, $order);
		$this->db->limit($per_page, $page);
		$query = $this->db->get();
		$query_count = $this->db->select('FOUND_ROWS() as count')->get()->row()->count;

		$config['base_url'] 		= base_url() . 'yonetim/urunler/manufacturer/lists/' . $sort_link . '/' . $filter;
		$config['uri_segment']		= 7;
		$config['per_page'] 	  	= $per_page;
		$config['total_rows'] 	  	= $query_count;
		$config['full_tag_open']  	= 'Sayfa : ';
		$config['full_tag_close'] 	= '';
		$config['num_links'] 	  	= 6;

		$mevcut_sayfa = floor(($page / $per_page) + 1);
		$toplam_sayisi = $query_count;
		$toplam_sayfa = ceil($toplam_sayisi / $per_page);

		$config['full_tag_open'] = '<div class="pagination"><div class="links">';
		$config['full_tag_close'] = '</div><div class="results">
Toplam '. $toplam_sayfa .' sayfa içinde '. $mevcut_sayfa .'. sayfadasın, toplam marka sayısı '. $query_count .'</div></div>';

		$config['first_link'] = '|&lt;';
		$config['first_tag_open'] = '';
		$config['first_tag_close'] = '';

		$config['last_link'] = '&gt;|';
		$config['last_tag_open'] = '';
		$config['last_tag_close'] = '';

		$config['next_link'] = '&gt;';
		$config['next_tag_open'] = '';
		$config['next_tag_close'] = '';

		$config['prev_link'] = '&lt;';
		$config['prev_tag_open'] = '';
		$config['prev_tag_close'] = '';

		$config['cur_tag_open'] = '<b>';
		$config['cur_tag_close'] = '</b>';

		$config['num_tag_open'] = '';
		$config['num_tag_close'] = '';

		$this->pagination->initialize($config);
		return $query;
	}

	public function manufacturer_delete_by_id($manufacturer_id)
	{
		if(is_array($manufacturer_id)) {
			foreach($manufacturer_id as $manufacturer) {
				$this->db->delete('manufacturer', array('manufacturer_id' => (int) $manufacturer));
				$this->db->update('product', array('manufacturer_id' => '0'), array('manufacturer_id' => (int) $manufacturer));
			}
		} else {
			$this->db->delete('manufacturer', array('manufacturer_id' => (int) $manufacturer_id));
			$this->db->update('product', array('manufacturer_id' => '0'), array('manufacturer_id' => (int) $manufacturer_id));
		}

		return TRUE;
	}

	public function get_manufacturer_by_all()
	{
		$this->db->select(get_fields_from_table('manufacturer', 'm.', array(), ''));
		$this->db->from('manufacturer m');
		$this->db->where('m.status', '1');
		$query = $this->db->get();

		if($query->num_rows()) {
			return $query->result();
		} else {
			return FALSE;
		}
	}
	
	function get_manufacturer_by_id($manufacturer_id) 
	{
		$this->db->select(get_fields_from_table('manufacturer', 'm.', array(), ''));
		$this->db->from('manufacturer m');
		$this->db->where('m.manufacturer_id', (int) $manufacturer_id);
		$this->db->limit(1);
		$query = $this->db->get();

		if($query->num_rows()) {
			return $query->row();
		} else {
			return FALSE;
		}
	}

	function get_manufacturer_by_name($name)
	{
		$this->db->select(get_fields_from_table('manufacturer', 'm.', array(), ''));
		$this->db->from('manufacturer m');
		$this->db->where('m.name', $name);
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
		$this->db->limit(1);
		$query = $this->db->get();

		if($query->num_rows()) {
			return $query->row();
		} else {
			return FALSE;
		}
	}

}

/* End of file class_name.php */
/* Location: ./application/models/class_name.php */