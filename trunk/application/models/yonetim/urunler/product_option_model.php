<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class product_option_model extends CI_Model
{

	protected $product_options = array();

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Category Model Yüklendi');
	}

	public function add_option($get_values)
	{
		$option_insert_data = array(
			'type'			=> $get_values['type'],
			'sort_order'	=> (int) $get_values['sort_order']
		);
		$this->db->insert('option', $option_insert_data);
		$option_id = $this->db->insert_id();
		
		foreach ($get_values['option_description'] as $language_id => $value) {
			$option_description_insert_data = array(
				'option_id'		=> (int) $option_id,
				'language_id'	=> (int) $language_id,
				'name'			=> $value['name']
			);
			$this->db->insert('option_description', $option_description_insert_data);
		}

		if (isset($get_values['option_value'])) {
			foreach ($get_values['option_value'] as $option_value) {
				$option_value_insert_data = array(
					'option_id'		=> (int) $option_id,
					'sort_order'	=> (int) $option_value['sort_order']
				);
				$this->db->insert('option_value', $option_value_insert_data);

				$option_value_id = $this->db->insert_id();

				foreach ($option_value['option_value_description'] as $language_id => $option_value_description) {
					$option_value_description_insert_data = array(
						'option_value_id'	=> (int) $option_value_id,
						'language_id'		=> (int) $language_id,
						'option_id'			=> (int) $option_id,
						'name'				=> $option_value_description['name']
					);
					$this->db->insert('option_value_description', $option_value_description_insert_data);
				}
			}
		}
		return TRUE;
	}

	public function update_option($option_id, $get_values)
	{
		$option_update_data = array(
			'type'			=> $get_values['type'],
			'sort_order'	=> $get_values['sort_order']
		);
		$this->db->update('option', $option_update_data, array('option_id' => (int) $option_id));
		$this->db->delete('option_description', array('option_id' => (int) $option_id));

		foreach ($get_values['option_description'] as $language_id => $value) {
			$option_description_insert_data = array(
				'option_id'		=> (int) $option_id,
				'language_id'	=> (int) $language_id,
				'name'			=> $value['name']
			);
			$this->db->insert('option_description', $option_description_insert_data);
		}

		$this->db->delete('option_value', array('option_id' => (int) $option_id));
		$this->db->delete('option_value_description', array('option_id' => (int) $option_id));
		
		if (isset($get_values['option_value'])) {
			foreach ($get_values['option_value'] as $option_value) {
				if ($option_value['option_value_id']) {
					$option_value_insert_data = array(
						'option_value_id'	=> (int) $option_value['option_value_id'],
						'option_id'			=> (int) $option_id,
						'sort_order'		=> (int) $option_value['sort_order']
					);
					$this->db->insert('option_value', $option_value_insert_data);
				} else {
					$option_value_insert_data = array(
						'option_id'			=> (int) $option_id,
						'sort_order'		=> (int) $option_value['sort_order']
					);
					$this->db->insert('option_value', $option_value_insert_data);
				}
				$option_value_id = $this->db->insert_id();

				foreach ($option_value['option_value_description'] as $language_id => $option_value_description) {
					$option_value_description_insert_data = array(
						'option_value_id'	=> (int) $option_value_id,
						'language_id'		=> (int) $language_id,
						'option_id'			=> (int) $option_id,
						'name'				=> $option_value_description['name']
					);
					$this->db->insert('option_value_description', $option_value_description_insert_data);
				}
			}
		}
		return TRUE;
	}

	public function get_options_by_all($page, $sort = 'o.option_id', $order = 'desc', $filter = 'o.option_id|]', $sort_link)
	{
		$_array = explode(', ', get_fields_from_table('option', 'o.'));
		$_d_array = explode(', ', get_fields_from_table('option_description', 'od.'));
		$_filter_allowed = array_merge($_array, $_d_array);
		$_sort_allowed = array_merge($_array, $_d_array);

		$per_page = (config('site_ayar_urun_yonetim_sayfa')) ? config('site_ayar_urun_yonetim_sayfa') : 20;

		if( ! in_array($sort, $_sort_allowed)) {
			$sort = 'o.option_id';
		}

		$_order_allowed = array('asc', 'desc', 'random');

		if( ! in_array($order, $_order_allowed)) {
			$order = 'desc';
		}

		$language_id = get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS ' . 
			get_fields_from_table('option', 'o.', array(), ', ') . 
			get_fields_from_table('option_description', 'od.', array(), '')
		, FALSE);
		$this->db->from('option o');
		$this->db->join('option_description od', 'o.option_id = od.option_id', 'left');
		$this->db->where('od.language_id', $language_id);

		if ($filter != 'o.option_id|]') {
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

		$config['base_url'] 		= base_url() . 'yonetim/urunler/option/lists/' . $sort_link . '/' . $filter;
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
Toplam '. $toplam_sayfa .' sayfa içinde '. $mevcut_sayfa .'. sayfadasın, toplam seçenek sayısı '. $query_count .'</div></div>';

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

	public function option_delete_by_id($option_id)
	{
		if(is_array($option_id)) {
			foreach($option_id as $option) {
				$this->db->delete('option', array('option_id' => (int) $option));
				$this->db->delete('option_description', array('option_id' => (int) $option));
				$this->db->delete('option_value', array('option_id' => (int) $option));
				$this->db->delete('option_value_description', array('option_id' => (int) $option));
			}
		} else {
			$this->db->delete('option', array('option_id' => (int) $option_id));
			$this->db->delete('option_description', array('option_id' => (int) $option_id));
			$this->db->delete('option_value', array('option_id' => (int) $option_id));
			$this->db->delete('option_value_description', array('option_id' => (int) $option_id));
		}

		return TRUE;
	}

	public function get_option_by_id($option_id)
	{
		$language_id = get_language('language_id', config('site_ayar_yonetim_dil'));

		$this->db->select(
			get_fields_from_table('option', 'o.', array(), ', ') .
			get_fields_from_table('option_description', 'od.', array(), '')
		);
		$this->db->from('option o');
		$this->db->join('option_description od', 'o.option_id = od.option_id', 'left');
		$this->db->where('o.option_id', $option_id);
		$this->db->where('od.language_id', (int) $language_id);
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->row();
		} else {
			return false;
		}
	}

	public function get_option_description_by_id($option_id)
	{
		$option_description_data = array();

		$this->db->select(get_fields_from_table('option_description', 'od.', array(), ''));
		$this->db->from('option_description od');
		$this->db->where('od.option_id', (int) $option_id);
		$query = $this->db->get();

		foreach ($query->result() as $result) {
			$option_description_data[$result->language_id] = array(
				'name'				=> $result->name
			);
		}

		return $option_description_data;
	}

	public function get_option_value_description_by_id($option_id)
	{
		$option_value_data = array();

		$this->db->select(get_fields_from_table('option_value', 'ov.', array(), ''));
		$this->db->from('option_value ov');
		$this->db->where('ov.option_id', (int) $option_id);
		$this->db->order_by('ov.sort_order');
		$option_value_query = $this->db->get();

		foreach ($option_value_query->result_array() as $option_value) {
			$option_value_description_data = array();

			$this->db->select(get_fields_from_table('option_value_description', 'ovd.', array(), ''));
			$this->db->from('option_value_description ovd');
			$this->db->where('ovd.option_value_id', (int) $option_value['option_value_id']);
			$option_value_description_query = $this->db->get();

			foreach ($option_value_description_query->result_array() as $option_value_description) {
				$option_value_description_data[$option_value_description['language_id']] = array('name' => $option_value_description['name']);
			}

			$option_value_data[] = array(
				'option_value_id'          => $option_value['option_value_id'],
				'option_value_description' => $option_value_description_data,
				'sort_order'               => $option_value['sort_order']
			);
		}
		
		return $option_value_data;

	}

	public function get_options_by_no_pag_all($page, $sort = 'od.name', $order = 'desc', $filter = 'od.status|]')
	{
		$_array = explode(', ', get_fields_from_table('option', 'o.'));
		$_d_array = explode(', ', get_fields_from_table('option_description', 'od.'));
		$_filter_allowed = array_merge($_array, $_d_array);
		$_sort_allowed = array_merge($_array, $_d_array);

		$per_page = (config('site_ayar_urun_yonetim_sayfa')) ? config('site_ayar_urun_yonetim_sayfa') : 20;

		if( ! in_array($sort, $_sort_allowed)) {
			$sort = 'od.name';
		}

		$_order_allowed = array('asc', 'desc', 'random');

		if( ! in_array($order, $_order_allowed)) {
			$order = 'desc';
		}

		$language_id = get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS ' . 
			get_fields_from_table('option', 'o.', array(), ', ') . 
			get_fields_from_table('option_description', 'od.', array(), '')
		, FALSE);
		$this->db->from('option o');
		$this->db->join('option_description od', 'o.option_id = od.option_id', 'left');
		$this->db->where('od.language_id', $language_id);

		if ($filter != 'od.status|]') {
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

		return $query->result_array();
	}

	public function get_option_values_by_id($option_id)
	{
		$option_value_data = array();

		$language_id = get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS ' . 
			get_fields_from_table('option_value', 'ov.', array(), ', ') . 
			get_fields_from_table('option_value_description', 'ovd.', array(), '')
		, FALSE);
		$this->db->from('option_value ov');
		$this->db->join('option_value_description ovd', 'ov.option_value_id = ovd.option_value_id', 'left');
		$this->db->where('ov.option_id', (int) $option_id);
		$this->db->where('ovd.language_id', (int) $language_id);
		$this->db->order_by('ov.sort_order');
		$option_value_query = $this->db->get();

		foreach ($option_value_query->result_array() as $option_value) {
			$option_value_data[] = array(
				'option_value_id'	=> $option_value['option_value_id'],
				'name'				=> $option_value['name'],
				'sort_order'		=> $option_value['sort_order']
			);
		}

		return $option_value_data;
	}

}