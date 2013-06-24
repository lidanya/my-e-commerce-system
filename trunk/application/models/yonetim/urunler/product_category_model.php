<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class product_category_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Category Model Yüklendi');
	}

	public function add_category($get_values)
	{
		$_category_insert_data = array(
			'sort_order'	=> $get_values['sort_order'],
			'parent_id'		=> $get_values['parent_id'],
			'top'			=> '0',//$get_values['top'],
			'column'		=> '1',//$get_values['column'],
			'status'		=> $get_values['status'],
			'image'			=> $get_values['image'],
			'date_added'	=> standard_date('DATE_MYSQL', time(), 'tr'),
			'date_modified'	=> standard_date('DATE_MYSQL', time(), 'tr')
		);
		$this->db->insert('category', $_category_insert_data);
		$category_id = $this->db->insert_id();
		$category_add_status = $this->db->affected_rows();

		$category_description_add_status = 0;
		foreach ($get_values['category_description'] as $language_id => $description)
		{
			$check_seo = ($description['seo'] AND $description['seo'] != '') ? url_title($description['seo'], 'dash', TRUE) : url_title($description['name'], 'dash', TRUE);
			$seo = $this->check_seo($check_seo, $language_id);

			$_description_add_data = array(
				'category_id'				=> $category_id,
				'language_id'				=> $language_id,
				'name'						=> $description['name'],
				'description'				=> $description['description'],
				'meta_keywords'				=> $description['meta_keywords'],
				'meta_description'			=> $description['meta_description'],
				'seo'						=> $seo,
			);
			$this->db->insert('category_description', $_description_add_data);
			$_category_description_add_status = $this->db->affected_rows();
			$category_description_add_status += $_category_description_add_status;
		}

		if(isset($get_values['category_features'])) {
			foreach($get_values['category_features'] as $cf_key => $cf_value) {
				$_category_features_insert_data = array(
					'category_id'	=> $category_id,
					'status'		=> $get_values['status'],
					'date_added'	=> standard_date('DATE_MYSQL', time(), 'tr'),
					'date_modified'	=> standard_date('DATE_MYSQL', time(), 'tr')
				);
				$this->db->insert('category_features', $_category_features_insert_data);
				$category_features_id = $this->db->insert_id();

				foreach ($cf_value as $language_id => $value) {
					$_category_features_description_insert_data = array(
						'feature_id'	=> $category_features_id,
						'language_id'	=> $language_id,
						'name'			=> $value['name']
					);
					$this->db->insert('category_features_description', $_category_features_description_insert_data);
				}
			}
		}

		if($category_add_status OR $category_description_add_status) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function update_category($category_id, $get_values)
	{
		$_category_update_data = array(
			'sort_order'	=> $get_values['sort_order'],
			'parent_id'		=> $get_values['parent_id'],
			'top'			=> '0',//$get_values['top'],
			'column'		=> '1',//$get_values['column'],
			'status'		=> $get_values['status'],
			'image'			=> $get_values['image'],
			'date_modified'	=> standard_date('DATE_MYSQL', time(), 'tr')
		);
		$this->db->where('category_id', $category_id);
		$this->db->update('category', $_category_update_data);
		$category_update_status = $this->db->affected_rows();

		$this->db->delete('category_description', array('category_id' => $category_id));
		$category_description_delete_status = $this->db->affected_rows();

		$category_description_add_status = 0;
		foreach ($get_values['category_description'] as $language_id => $description)
		{
			$check_seo = ($description['seo'] AND $description['seo'] != '') ? url_title($description['seo'], 'dash', TRUE) : url_title($description['name'], 'dash', TRUE);
			$seo = $this->check_seo($check_seo, $language_id, $category_id);

			$_category_description_add_data = array(
				'category_id'					=> $category_id,
				'language_id'					=> $language_id,
				'name'							=> $description['name'],
				'description'					=> $description['description'],
				'meta_keywords'					=> $description['meta_keywords'],
				'meta_description'				=> $description['meta_description'],
				'seo'							=> $seo,
			);
			$this->db->insert('category_description', $_category_description_add_data);
			$_category_description_add_status = $this->db->affected_rows();
			$category_description_add_status += $_category_description_add_status;
		}

		if(isset($get_values['category_features'])) {
			foreach($get_values['category_features'] as $cf_key => $cf_value) {
				$this->db->select(get_fields_from_table('category_features', 'cf.', array(), ''));
				$this->db->from('category_features cf');
				$this->db->where('cf.feature_id', (int) $cf_key);
				$this->db->where('cf.category_id', (int) $category_id);
				$this->db->limit(1);
				$query = $this->db->get();
				if($query->num_rows()) {
					$query_info = $query->row();

					// Update
					$_category_features_update_data = array(
						'category_id'	=> (int) $category_id,
						'status'		=> (int) $get_values['status'],
						'date_modified'	=> standard_date('DATE_MYSQL', time(), 'tr')
					);
					$this->db->where('feature_id', (int) $query_info->feature_id);
					$this->db->update('category_features', $_category_features_update_data);

					$this->db->delete('category_features_description', array('feature_id' => $query_info->feature_id));

					foreach ($cf_value as $language_id => $value) {
						$_category_features_description_insert_data = array(
							'feature_id'	=> (int) $query_info->feature_id,
							'language_id'	=> (int) $language_id,
							'name'			=> $value['name']
						);
						$this->db->insert('category_features_description', $_category_features_description_insert_data);
					}
				} else {
					// Insert
					$_category_features_insert_data = array(
						'category_id'	=> (int) $category_id,
						'status'		=> (int) $get_values['status'],
						'date_added'	=> standard_date('DATE_MYSQL', time(), 'tr'),
						'date_modified'	=> standard_date('DATE_MYSQL', time(), 'tr')
					);
					$this->db->insert('category_features', $_category_features_insert_data);
					$category_feature_id = $this->db->insert_id();

					foreach ($cf_value as $language_id => $value) {
						$_category_features_description_insert_data = array(
							'feature_id'	=> (int) $category_feature_id,
							'language_id'	=> (int) $language_id,
							'name'			=> $value['name']
						);
						$this->db->insert('category_features_description', $_category_features_description_insert_data);
					}
				}
			}
		}

		if($category_update_status OR $category_description_delete_status OR $category_description_add_status) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function check_seo($seo, $language_id, $category_id = FALSE)
	{
		$this->db->from('category_description cd');
		$this->db->where('seo', $seo);
		$this->db->where('language_id', $language_id);
		if($category_id) {
			$this->db->where_not_in('category_id', (int) $category_id);
		}
		$count = $this->db->count_all_results();

		if($count) {
			$this->load->helper('string');
			$_seo = $seo . '-' . mb_strtolower(random_string('alnum', 4));
			return $this->check_seo($_seo, $language_id, $category_id);
		} else {
			return $seo;
		}
	}

	public function get_categories_by_all($page, $sort = 'c.category_id', $order = 'desc', $filter = 'c.status|]', $sort_link)
	{
		$_c_array = explode(', ', get_fields_from_table('category', 'c.'));
		$_cd_array = explode(', ', get_fields_from_table('category_description', 'cd.'));
		$_filter_allowed = array_merge($_c_array, $_cd_array);
		$_sort_allowed = array_merge($_c_array, $_cd_array);

		$per_page = (config('site_ayar_urun_yonetim_sayfa')) ? config('site_ayar_urun_yonetim_sayfa') : 20;

		if( ! in_array($sort, $_sort_allowed)) {
			$sort = 'c.category_id';
		}

		$_order_allowed = array('asc', 'desc', 'random');

		if( ! in_array($order, $_order_allowed)) {
			$order = 'desc';
		}

		$language_id = $this->fonksiyonlar->get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS ' . 
			get_fields_from_table('category', 'c.', array(), ', ') . 
			get_fields_from_table('category_description', 'cd.', array(), '')
		, FALSE);
		$this->db->from('category c');
		$this->db->join('category_description cd', 'c.category_id = cd.category_id', 'left');
		$this->db->where('cd.language_id', $language_id);

		if ($filter != 'c.status|]') {
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

		$this->db->order_by('c.parent_id', 'asc');
		$this->db->order_by($sort, $order);
		$this->db->order_by('cd.name', 'asc');
		$this->db->limit($per_page, $page);
		$query = $this->db->get();
		$query_count = $this->db->select('FOUND_ROWS() as count')->get()->row()->count;

		$config['base_url'] 		= base_url() . 'yonetim/urunler/product_category/lists/' . $sort_link . '/' . $filter;
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
Toplam '. $toplam_sayfa .' sayfa içinde '. $mevcut_sayfa .'. sayfadasın, toplam ürün sayısı '. $query_count .'</div></div>';

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

	public function get_category_by_id($category_id)
	{
		$this->db->select(get_fields_from_table('category', 'c.', array(), ''));
		$this->db->from('category c');
		$this->db->where('c.category_id', $category_id);
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->row();
		} else {
			return false;
		}
	}

	public function get_category_by_name_and_parent_id($name, $parent_id = 0)
	{
		$language_id = $this->fonksiyonlar->get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->select(get_fields_from_table('category', 'c.', array(), ''));
		$this->db->from('category c');
		$this->db->join('category_description cd', 'c.category_id = cd.category_id', 'left');
		$this->db->where('c.parent_id', (int) $parent_id);
		$this->db->where('cd.language_id', (int) $language_id);
		$this->db->where('cd.name', $name);
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->row();
		} else {
			return false;
		}
	}

	public function category_delete_by_id($category_id)
	{
		if(is_array($category_id)) {
			foreach($category_id as $information) {
				$this->db->delete('category', array('category_id' => $information));
				$this->db->delete('category_description', array('category_id' => $information));

				$this->db->query('
					DELETE cf, cfd
					FROM '. $this->db->dbprefix('category_features') .' AS cf
					LEFT JOIN '. $this->db->dbprefix('category_features_description') .' AS cfd ON (cf.feature_id= cfd.feature_id)
					WHERE cf.category_id = '. $information .'
				');
			}
		} else {
			$information = $category_id;
			$this->db->delete('category', array('category_id' => $information));
			$this->db->delete('category_description', array('category_id' => $information));

			$this->db->query('
				DELETE cf, cfd
				FROM '. $this->db->dbprefix('category_features') .' AS cf
				LEFT JOIN '. $this->db->dbprefix('category_features_description') .' AS cfd ON (cf.feature_id= cfd.feature_id)
				WHERE cf.category_id = '. $information .'
			');
		}

		return TRUE;
	}

	public function count_category_by_id($category_id)
	{
		$language_id = $this->fonksiyonlar->get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->from('category c');
		$this->db->join('category_description cd', 'c.category_id = cd.category_id', 'left');
		$this->db->where('cd.language_id', $language_id);
		$query = $this->db->count_all_results();
		if($query) {
			return $query;
		} else {
			return FALSE;
		}
	}

	public function get_category_by_parent($parent_id = 0, $limit = 10) 
	{
		$category_data = array();
		$language_id = get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('category', 'c.', array(), ', ') . 
			get_fields_from_table('category_description', 'cd.', array(), ', ')
		);
		$this->db->from('category c');
		$this->db->join('category_description cd', 'c.category_id = cd.category_id', 'left');
		$this->db->where('cd.language_id', $language_id);
		$this->db->where('c.parent_id', (int) $parent_id);
		$this->db->where('c.status', '1');
		if($limit != '-1') {
			$this->db->limit($limit);
		}
		$query = $this->db->get();
		foreach ($query->result() as $result)
		{
			$category_data[$result->category_id] = array(
				'category_id'				=> $result->category_id,
				'name'						=> $this->get_category_path_by_id($result->category_id),
				'sort_order'				=> $result->sort_order,
				'parent_id'					=> $result->parent_id,
				'status'					=> $result->status,
				'date_added'				=> $result->date_added,
				'date_modified'				=> $result->date_modified
			);
			$category_data = array_merge($category_data, $this->get_category_by_parent($result->category_id, $limit));
		}
		return $category_data;
	}
	
	
	private function get_child_category($category_id){
		//echo $category_id."<br>";
		$ramdata = $this->db->query("Select count(*) as rs, category_id From e_category Where parent_id = $category_id");
		$row = $ramdata->row_array();
		if ($row["rs"] > 0) {
			self::get_child_category($row["category_id"]);
		} else {
			return $category_id;
		}
	}
	
	
	public function get_final_child_categories() {
		$language_id = get_language('language_id', config('site_ayar_yonetim_dil'));
		
		$ramdata = $this->db->query("Select category_id From e_category");
		foreach ($ramdata->result_array() as $row) {
			$cat_id = self::get_child_category($row["category_id"]);
			
			$this->db->distinct();
			$this->db->select(
				get_fields_from_table('category', 'c.', array(), ', ') . 
				get_fields_from_table('category_description', 'cd.', array(), ', ')
			);
			$this->db->from('category c');
			$this->db->join('category_description cd', 'c.category_id = cd.category_id', 'left');
			$this->db->where('cd.language_id', $language_id);
			$this->db->where('c.status', '1');
			$this->db->where('c.category_id', $cat_id);
			$query = $this->db->get();
			
			$result = $query->row_array();
				$category_data[$result["category_id"]] = array(
					'category_id'				=> $result["category_id"],
					'name'						=> self::get_category_path_by_id($result["category_id"]),
					'sort_order'				=> $result["sort_order"],
					'parent_id'					=> $result["parent_id"],
					'status'					=> $result["status"],
					'date_added'				=> $result["date_added"],
					'date_modified'				=> $result["date_modified"]
				);
		} // foreach - $ramdata->result_array() sonu
		return $category_data;
	}

	public function get_category_path_by_id($category_id)
	{
		$language_id = get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('category', 'c.', array('parent_id'), ', ') . 
			get_fields_from_table('category_description', 'cd.', array('name'), '')
		);
		$this->db->from('category c');
		$this->db->join('category_description cd', 'c.category_id = cd.category_id', 'left');
		$this->db->where('c.category_id', (int) $category_id);
		$this->db->where('cd.language_id', (int) $language_id);
		$this->db->order_by('c.sort_order, cd.name ASC');
		$query = $this->db->get();

		if($query->num_rows()) {
			$category_info = $query->row();
			if ($category_info->parent_id) {
				return $this->get_category_path_by_id($category_info->parent_id) . ' > ' . $category_info->name;
			} else {
				return $category_info->name;
			}
		}
	}

	public function get_category_description_by_id($category_id)
	{
		$description_data = array();
		$this->db->select(get_fields_from_table('category_description cd', 'cd.', array(), ''));
		$this->db->from('category_description cd');
		$this->db->where('cd.category_id', $category_id);
		$query = $this->db->get();
		foreach($query->result() as $result) {
			$key = $result->language_id;
			$send_data['name']						= $result->name;
			$send_data['description']				= $result->description;
			$send_data['meta_keywords']				= $result->meta_keywords;
			$send_data['meta_description']			= $result->meta_description;
			$send_data['seo']						= $result->seo;
			$description_data[$result->language_id]	= $send_data;
		}

		return $description_data;
	}

	public function get_features_by_category_ids($category_ids = array())
	{
		$this->db->select(
			get_fields_from_table('category_features', 'cf.', array(), '')
		);
		$this->db->from('category_features cf');
		$this->db->where_in('cf.category_id', $category_ids);
		$this->db->where('cf.status', '1');
		$query = $this->db->get();

		$return_data = array();
		foreach($query->result() as $result) {

			$this->db->select(
				get_fields_from_table('category_features_description', 'cfd.', array(), '') 
			);
			$this->db->from('category_features_description cfd');
			$this->db->where('cfd.feature_id', $result->feature_id);
			$query = $this->db->get();

			$description_data = array();
			foreach($query->result() as $result_r) {
				$key = $result_r->language_id;
				$send_data['feature_id']	= $result_r->feature_id;
				$send_data['name']			= $result_r->name;
				$send_data['lang_data']		= get_language_v2(NULL, $result_r->language_id);
				$description_data[$key]		= $send_data;
			}

			$key = $result->feature_id;
			$return_data[$key] = array(
				'name' => $this->get_category_path_by_id($result->category_id),
				'data' => $description_data
			);
		}

		return $return_data;
	}

	public function get_features_by_category_id($category_id)
	{
		$this->db->select(
			get_fields_from_table('category_features', 'cf.', array(), '') 
		);
		$this->db->from('category_features cf');
		$this->db->where('cf.category_id', $category_id);
		$query = $this->db->get();

		$return_data = array();
		foreach($query->result() as $result) {

			$this->db->select(
				get_fields_from_table('category_features_description', 'cfd.', array(), '') 
			);
			$this->db->from('category_features_description cfd');
			$this->db->where('cfd.feature_id', $result->feature_id);
			$query = $this->db->get();

			$description_data = array();
			foreach($query->result() as $result) {
				$key = $result->language_id;
				$send_data['feature_id']				= $result->feature_id;
				$send_data['name']						= $result->name;
				$description_data[$result->language_id]	= $send_data;
			}

			$key = $result->feature_id;
			$return_data[$key] = $description_data;
		}

		return $return_data;
	}

	public function feature_delete_by_id($feature_id)
	{
		$this->db->delete('category_features', array('feature_id' => $feature_id));
		$this->db->delete('category_features_description', array('feature_id' => $feature_id));
		$this->db->delete('product_featured', array('feature_id' => $feature_id));

		return TRUE;
	}

}