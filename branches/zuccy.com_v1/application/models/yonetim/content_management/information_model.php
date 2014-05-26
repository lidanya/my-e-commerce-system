<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class information_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Information Model Yüklendi');
	}

	function get_information_by_type($type, $page, $sort = 'i.information_id', $order = 'desc', $filter = 'i.status|]', $sort_link)
	{
		$_i_array = explode(', ', get_fields_from_table('information', 'i.'));
		$_id_array = explode(', ', get_fields_from_table('information_description', 'id.'));
		$_filter_allowed = array_merge($_i_array, $_id_array);
		$_sort_allowed = array_merge($_i_array, $_id_array);

		$per_page = (config('site_ayar_urun_yonetim_sayfa')) ? config('site_ayar_urun_yonetim_sayfa') : 20;

		if( ! in_array($sort, $_sort_allowed)) {
			$sort = 'i.information_id';
		}

		$_order_allowed = array('asc', 'desc', 'random');

		if( ! in_array($order, $_order_allowed)) {
			$order = 'desc';
		}

		$language_id = $this->fonksiyonlar->get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS ' . 
			get_fields_from_table('information', 'i.', array(), ', ') . 
			get_fields_from_table('information_description', 'id.', array(), ', ')
		, FALSE);
		$this->db->from('information i');
		$this->db->join('information_description id', 'i.information_id = id.information_id', 'left');
		$this->db->where('id.language_id', $language_id);
		$this->db->where('i.type', $type);

		if ($filter != 'i.status|]') {
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

		$config['base_url'] 		= base_url() . 'yonetim/content_management/information/lists/' . $type . '/' . $sort_link . '/' . $filter;
		$config['uri_segment']		= 8;
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
Toplam '. $toplam_sayfa .' sayfa içinde '. $mevcut_sayfa .'. sayfadasın, toplam içerik sayısı '. $query_count .'</div></div>';

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

	function count_information_by_type($type)
	{
		$language_id = $this->fonksiyonlar->get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->from('information i');
		$this->db->join('information_description id', 'i.information_id = id.information_id', 'left');
		$this->db->where('id.language_id', $language_id);
		$this->db->where('i.type', $type);
		$this->db->where('i.status', '1');
		$query = $this->db->count_all_results();
		if($query) {
			return $query;
		} else {
			return FALSE;
		}
	}

	function get_information_category_by_type($type, $page, $sort = 'i.information_id', $order = 'desc', $filter = 'i.status|]', $sort_link)
	{
		$_i_array = explode(', ', get_fields_from_table('information', 'i.'));
		$_id_array = explode(', ', get_fields_from_table('information_description', 'id.'));
		$_filter_allowed = array_merge($_i_array, $_id_array);
		$_sort_allowed = array_merge($_i_array, $_id_array);

		$per_page = (config('site_ayar_urun_yonetim_sayfa')) ? config('site_ayar_urun_yonetim_sayfa') : 20;

		if( ! in_array($sort, $_sort_allowed)) {
			$sort = 'i.information_id';
		}

		$_order_allowed = array('asc', 'desc', 'random');

		if( ! in_array($order, $_order_allowed)) {
			$order = 'desc';
		}

		$language_id = $this->fonksiyonlar->get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS ' . 
			get_fields_from_table('information', 'i.', array(), ', ') . 
			get_fields_from_table('information_description', 'id.', array(), ', ')
		, FALSE);
		$this->db->from('information i');
		$this->db->join('information_description id', 'i.information_id = id.information_id', 'left');
		$this->db->where('id.language_id', $language_id);
		$this->db->where('i.type', $type);

		if ($filter != 'i.status|]') {
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

		$config['base_url'] 		= base_url() . 'yonetim/icerik_yonetimi/information/lists/' . $type . '/' . $sort_link . '/' . $filter;
		$config['uri_segment']		= 8;
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
Toplam '. $toplam_sayfa .' sayfa içinde '. $mevcut_sayfa .'. sayfadasın, toplam içerik sayısı '. $query_count .'</div></div>';

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

	function count_information_by_id($information_id)
	{
		$language_id = $this->fonksiyonlar->get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->from('information i');
		$this->db->where('i.information_id', $information_id);
		$query = $this->db->count_all_results();
		if($query) {
			return $query;
		} else {
			return FALSE;
		}
	}

	function count_information_by_id_type($type, $information_id)
	{
		$language_id = $this->fonksiyonlar->get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->from('information i');
		$this->db->where('i.information_id', $information_id);
		$this->db->where('i.type', $type);
		$query = $this->db->count_all_results();
		if($query) {
			return $query;
		} else {
			return FALSE;
		}
	}

	function count_information_category_by_id_type($type, $information_category_id)
	{
		$language_id = $this->fonksiyonlar->get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->from('information_category ic');
		$this->db->where('ic.information_category_id', $information_category_id);
		$this->db->where('ic.type', $type);
		$query = $this->db->count_all_results();
		if($query) {
			return $query;
		} else {
			return FALSE;
		}
	}

	function get_information_by_id($information_id)
	{
		$this->db->select(get_fields_from_table('information', 'i.', array(), ''));
		$this->db->from('information i');
		$this->db->where('information_id', $information_id);
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->row();
		} else {
			return false;
		}
	}

	function get_information_category_by_id($information_category_id)
	{
		$this->db->select(get_fields_from_table('information_category', 'ic.', array(), ''));
		$this->db->from('information_category ic');
		$this->db->where('information_category_id', $information_category_id);
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->row();
		} else {
			return false;
		}
	}

	function get_information_description_by_id($information_id)
	{
		$description_data = array();
		$this->db->select(get_fields_from_table('information_description id', 'id.', array(), ''));
		$this->db->from('information_description id');
		$this->db->where('id.information_id', $information_id);
		$query = $this->db->get();
		foreach($query->result() as $result) {
			$key = $result->language_id;
			$send_data['title']						= $result->title;
			$send_data['description']				= $result->description;
			$send_data['meta_keywords']				= $result->meta_keywords;
			$send_data['meta_description']			= $result->meta_description;
			$send_data['seo']						= $result->seo;
			$description_data[$result->language_id]	= $send_data;
		}

		return $description_data;
	}

	function get_information_category_description_by_id($information_category_id)
	{
		$description_data = array();
		$this->db->select(get_fields_from_table('information_category_description icd', 'icd.', array(), ''));
		$this->db->from('information_category_description icd');
		$this->db->where('icd.information_category_id', $information_category_id);
		$query = $this->db->get();
		foreach($query->result() as $result) {
			$key = $result->language_id;
			$send_data['title']						= $result->title;
			$send_data['description']				= $result->description;
			$send_data['meta_keywords']				= $result->meta_keywords;
			$send_data['meta_description']			= $result->meta_description;
			$send_data['seo']						= $result->seo;
			$description_data[$result->language_id]	= $send_data;
		}

		return $description_data;
	}

	function information_delete_by_type($type, $information_id)
	{
		$return = 0;
		if(is_array($information_id)) {
			foreach($information_id as $information) {
				$this->db->from('information');
				$this->db->where('delete', '1');
				$this->db->where('information_id', $information);
				$query = $this->db->count_all_results();
				if($query) {
					$this->db->delete('information', array('information_id' => $information));
					$this->db->delete('information_description', array('information_id' => $information));
					$return += 1;
				}
			}
		} else {
			$this->db->from('information');
			$this->db->where('delete', '1');
			$this->db->where('information_id', $information_id);
			$query = $this->db->count_all_results();
			if($query) {
				$this->db->delete('information', array('information_id' => $information_id));
				$this->db->delete('information_description', array('information_id' => $information_id));
				$return += 1;
			}
		}

		if($return) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function information_category_delete_by_type($type, $information_category_id)
	{
		if(is_array($information_category_id)) {
			foreach($information_category_id as $_information_category_id) {
				$this->db->delete('information_category', array('information_category_id' => $_information_category_id));
				$this->db->delete('information_category_description', array('information_category_id' => $_information_category_id));
			}
		} else {
			$information = $information_id;
			$this->db->delete('information_category', array('information_category_id' => $information_category_id));
			$this->db->delete('information_category_description', array('information_category_id' => $information_category_id));
		}
		return TRUE;
	}

	function get_information_category_category_by_type($type, $page, $sort = 'ic.information_category_id', $order = 'desc', $filter = 'ic.status|]', $sort_link)
	{
		$_i_array = explode(', ', get_fields_from_table('information_category', 'ic.'));
		$_id_array = explode(', ', get_fields_from_table('information_category_description', 'icd.'));
		$_sort_allowed = array_merge($_i_array, $_id_array);
		$_filter_allowed = array_merge($_i_array, $_id_array);

		$per_page = (config('site_ayar_urun_yonetim_sayfa')) ? config('site_ayar_urun_yonetim_sayfa') : 20;

		if( ! in_array($sort, $_sort_allowed)) {
			$sort = 'ic.information_category_id';
		}

		$_order_allowed = array('asc', 'desc', 'random');

		if( ! in_array($order, $_order_allowed)) {
			$order = 'desc';
		}

		$language_id = $this->fonksiyonlar->get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS ' . 
			get_fields_from_table('information_category', 'ic.', array(), ', ') . 
			get_fields_from_table('information_category_description', 'icd.', array(), ', ')
		, FALSE);
		$this->db->from('information_category ic');
		$this->db->join('information_category_description icd', 'ic.information_category_id = icd.information_category_id', 'left');
		$this->db->where('icd.language_id', $language_id);
		$this->db->where('ic.type', $type);

		if ($filter != 'ic.status|]') {
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

		$config['base_url'] 		= base_url() . 'yonetim/icerik_yonetimi/information_category/lists/' . $type . '/' . $sort_link . '/' . $filter;
		$config['uri_segment']		= 8;
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
Toplam '. $toplam_sayfa .' sayfa içinde '. $mevcut_sayfa .'. sayfadasın, toplam kategori sayısı '. $query_count .'</div></div>';

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

	function get_information_category_by_type_parent($type, $parent_id = 0, $limit = 10) 
	{
		$category_data = array();
		$language_id = $this->fonksiyonlar->get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('information_category', 'ic.', array(), ', ') . 
			get_fields_from_table('information_category_description', 'icd.', array(), ', ')
		);
		$this->db->from('information_category ic');
		$this->db->join('information_category_description icd', 'ic.information_category_id = icd.information_category_id', 'left');
		$this->db->where('icd.language_id', $language_id);
		$this->db->where('ic.type', $type);
		$this->db->where('ic.parent_id', (int) $parent_id);
		$this->db->where('ic.status', '1');
		if($limit != '-1') {
			$this->db->limit($limit);
		}
		$query = $this->db->get();
		foreach ($query->result() as $result)
		{
			$category_data[] = array(
				'information_category_id'	=> $result->information_category_id,
				'title'						=> $this->get_information_category_path_by_id($type, $result->information_category_id),
				'sort_order'				=> $result->sort_order,
				'parent_id'					=> $result->parent_id,
				'type'						=> $result->type,
				'status'					=> $result->status,
				'date_added'				=> $result->date_added,
				'date_modified'				=> $result->date_modified
			);
			$category_data = array_merge($category_data, $this->get_information_category_by_type_parent($type, $result->information_category_id, $limit));
		}
		return $category_data;
	}

	function get_information_category_path_by_id($type, $information_category_id) 
	{
		$language_id = $this->fonksiyonlar->get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('information_category', 'ic.', array(), ', ') . 
			get_fields_from_table('information_category_description', 'icd.', array(), ', ')
		);
		$this->db->from('information_category ic');
		$this->db->join('information_category_description icd', 'ic.information_category_id = icd.information_category_id', 'left');
		$this->db->where('icd.language_id', $language_id);
		$this->db->where('ic.type', $type);
		$this->db->where('ic.information_category_id', (int) $information_category_id);
		$this->db->where('ic.status', '1');
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows()) {
			$query_info = $query->row();
			if ($query_info->parent_id) {
				return $this->get_information_category_path_by_id($type, $query_info->parent_id) . ' > ' . $query_info->title;
			} else {
				return $query_info->title;
			}
		}
	}

	function update_information($information_id, $type, $get_values)
	{
		$_information_update_data = array(
			'sort_order'	=> $get_values['sort_order'],
			'category_id'	=> $get_values['category_id'],
			'status'		=> $get_values['status'],
			'image'			=> $get_values['image'],
			'date_modified'	=> standard_date('DATE_MYSQL', time(), 'tr')
		);
		$this->db->where('information_id', $information_id);
		$this->db->where('type', $type);
		$this->db->update('information', $_information_update_data);
		$information_update_status = $this->db->affected_rows();

		$this->db->delete('information_description', array('information_id' => $information_id));
		$information_description_delete_status = $this->db->affected_rows();

		$information_description_add_status = 0;
		foreach ($get_values['information_description'] as $language_id => $description)
		{
			$check_seo = ($description['seo'] AND $description['seo'] != '') ? url_title($description['seo'], 'dash', TRUE) : url_title($description['title'], 'dash', TRUE);
			$seo = $this->check_seo($check_seo, $language_id, $information_id);

			$_information_description_add_data = array(
				'information_id'	=> $information_id,
				'language_id'		=> $language_id,
				'title'				=> $description['title'],
				'description'		=> $description['description'],
				'meta_keywords'		=> $description['meta_keywords'],
				'meta_description'	=> $description['meta_description'],
				'seo'				=> $seo
			);
			$this->db->insert('information_description', $_information_description_add_data);
			$_information_description_delete_status = $this->db->affected_rows();
			$information_description_delete_status += $_information_description_delete_status;
		}

		if($information_update_status OR $information_description_delete_status OR $information_description_add_status) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function add_information($type, $get_values)
	{
		$_information_insert_data = array(
			'sort_order'	=> $get_values['sort_order'],
			'category_id'	=> $get_values['category_id'],
			'type'			=> $type,
			'status'		=> $get_values['status'],
			'image'			=> $get_values['image'],
			'date_added'	=> standard_date('DATE_MYSQL', time(), 'tr'),
			'date_modified'	=> standard_date('DATE_MYSQL', time(), 'tr')
		);
		$this->db->insert('information', $_information_insert_data);
		$information_id = $this->db->insert_id();
		$information_add_status = $this->db->affected_rows();

		$information_description_add_status = 0;
		foreach ($get_values['information_description'] as $language_id => $description)
		{
			$check_seo = ($description['seo'] AND $description['seo'] != '') ? url_title($description['seo'], 'dash', TRUE) : url_title($description['title'], 'dash', TRUE);
			$seo = $this->check_seo($check_seo, $language_id);

			$_information_description_add_data = array(
				'information_id'	=> $information_id,
				'language_id'		=> $language_id,
				'title'				=> $description['title'],
				'description'		=> $description['description'],
				'meta_keywords'		=> $description['meta_keywords'],
				'meta_description'	=> $description['meta_description'],
				'seo'				=> $seo
			);
			$this->db->insert('information_description', $_information_description_add_data);
			$_information_description_add_status = $this->db->affected_rows();
			$information_description_add_status += $_information_description_add_status;
		}

		if($information_add_status OR $information_description_add_status) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function check_seo($seo, $language_id, $information_id = FALSE)
	{
		$this->db->from('information_description id');
		$this->db->where('seo', $seo);
		$this->db->where('language_id', $language_id);
		if($information_id) {
			$this->db->where_not_in('information_id', (int) $information_id);
		}
		$count = $this->db->count_all_results();

		if($count) {
			$this->load->helper('string');
			$_seo = $seo . '-' . mb_strtolower(random_string('alnum', 4));
			return $this->check_seo($_seo, $language_id, $information_id);
		} else {
			return $seo;
		}
	}

	function update_information_category($information_category_id, $type, $get_values)
	{
		$_information_category_update_data = array(
			'sort_order'	=> $get_values['sort_order'],
			'parent_id'		=> $get_values['parent_id'],
			'status'		=> $get_values['status'],
			'image'			=> $get_values['image'],
			'date_modified'	=> standard_date('DATE_MYSQL', time(), 'tr')
		);
		$this->db->where('information_category_id', $information_category_id);
		$this->db->where('type', $type);
		$this->db->update('information_category', $_information_category_update_data);
		$information_category_update_status = $this->db->affected_rows();

		$this->db->delete('information_category_description', array('information_category_id' => $information_category_id));
		$information_category_description_delete_status = $this->db->affected_rows();

		$information_category_description_add_status = 0;
		foreach ($get_values['information_category_description'] as $language_id => $description)
		{
			$check_seo = ($description['seo'] AND $description['seo'] != '') ? url_title($description['seo'], 'dash', TRUE) : url_title($description['title'], 'dash', TRUE);
			$seo = $this->check_seo_category($check_seo, $language_id, $information_category_id);

			$_information_category_description_add_data = array(
				'information_category_id'		=> $information_category_id,
				'language_id'					=> $language_id,
				'title'							=> $description['title'],
				'description'					=> $description['description'],
				'meta_keywords'					=> $description['meta_keywords'],
				'meta_description'				=> $description['meta_description'],
				'seo'							=> $seo
			);
			$this->db->insert('information_category_description', $_information_category_description_add_data);
			$_information_category_description_add_status = $this->db->affected_rows();
			$information_category_description_add_status += $_information_category_description_add_status;
		}

		if($information_category_update_status OR $information_category_description_delete_status OR $information_category_description_add_status) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function add_information_category($type, $get_values)
	{
		$_information_category_insert_data = array(
			'sort_order'	=> $get_values['sort_order'],
			'parent_id'		=> $get_values['parent_id'],
			'type'			=> $type,
			'status'		=> $get_values['status'],
			'image'			=> $get_values['image'],
			'date_added'	=> standard_date('DATE_MYSQL', time(), 'tr'),
			'date_modified'	=> standard_date('DATE_MYSQL', time(), 'tr')
		);
		$this->db->insert('information_category', $_information_category_insert_data);
		$information_category_id = $this->db->insert_id();
		$information_category_add_status = $this->db->affected_rows();

		$information_category_description_add_status = 0;
		foreach ($get_values['information_category_description'] as $language_id => $description)
		{
			$check_seo = ($description['seo'] AND $description['seo'] != '') ? url_title($description['seo'], 'dash', TRUE) : url_title($description['title'], 'dash', TRUE);
			$seo = $this->check_seo_category($check_seo, $language_id);

			$_information_description_add_data = array(
				'information_category_id'	=> $information_category_id,
				'language_id'				=> $language_id,
				'title'						=> $description['title'],
				'description'				=> $description['description'],
				'meta_keywords'				=> $description['meta_keywords'],
				'meta_description'			=> $description['meta_description'],
				'seo'						=> $seo
			);
			$this->db->insert('information_category_description', $_information_description_add_data);
			$_information_category_description_add_status = $this->db->affected_rows();
			$information_category_description_add_status += $_information_category_description_add_status;
		}

		if($information_category_add_status OR $information_category_description_add_status) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function check_seo_category($seo, $language_id, $information_category_id = FALSE)
	{
		$this->db->from('information_category_description icd');
		$this->db->where('seo', $seo);
		$this->db->where('language_id', $language_id);
		if($information_category_id) {
			$this->db->where_not_in('information_category_id', (int) $information_category_id);
		}
		$count = $this->db->count_all_results();

		if($count) {
			$this->load->helper('string');
			$_seo = $seo . '-' . mb_strtolower(random_string('alnum', 4));
			return $this->check_seo_category($_seo, $language_id, $information_category_id);
		} else {
			return $seo;
		}
	}
}