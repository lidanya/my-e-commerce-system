<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 **/

class category_model extends CI_Model
{
	protected $category_product_count = array();

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Category Model Yüklendi');
	}

	public function get_category_by_id($category_id, $limit = 1)
	{
		$language_id = get_language('language_id');
		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('category', 'c.', array(), ', ') . 
			get_fields_from_table('category_description', 'cd.', array(), ', ')
		);
		$this->db->from('category c');
		$this->db->join('category_description cd', 'c.category_id = cd.category_id', 'left');
		$this->db->where('cd.language_id', $language_id);
		$this->db->where('c.category_id', $category_id);
		$this->db->where('c.status', '1');
		if($limit != '-1') {
			$this->db->limit($limit);
		}
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->row();
		} else {
			return FALSE;
		}
	}

	public function get_category_by_seo($seo, $limit = 1)
	{
		$language_id = get_language('language_id');
		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('category', 'c.', array(), ', ') . 
			get_fields_from_table('category_description', 'cd.', array(), ', ')
		);
		$this->db->from('category c');
		$this->db->join('category_description cd', 'c.category_id = cd.category_id', 'left');
		$this->db->where('cd.language_id', $language_id);
		$this->db->where('cd.seo', $seo);
		$this->db->where('c.status', '1');
		if($limit != '-1') {
			$this->db->limit($limit);
		}
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->row();
		} else {
			return FALSE;
		}
	}

	public function get_image_product_by_random($category_id)
	{
		$this->db->select(
			get_fields_from_table('product', 'p.', array(), '')
		);
		$this->db->from('product_to_category p2c');
		$this->db->join('product p', 'p2c.product_id = p.product_id');
		$this->db->where('p2c.category_id', (int) $category_id);
		$this->db->order_by('p2c.category_id', 'random');
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->row();
		} else {
			return FALSE;
		}
	}

	public function get_features($category_id, $limit = 10, $sort = 'cf.feature_id', $order = 'desc')
	{
		$_c_array = explode(', ', get_fields_from_table('category_features', 'cf.'));
		$_cd_array = explode(', ', get_fields_from_table('category_features_description', 'cfd.'));
		$_sort_allowed = array_merge($_c_array, $_cd_array);

		if( ! in_array($sort, $_sort_allowed)) {
			$sort = 'cf.feature_id';
		}

		$_order_allowed = array('asc', 'desc', 'random');

		if( ! in_array($order, $_order_allowed)) {
			$sort = 'desc';
		}

		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('category_features', 'cf.', array(), ', ') . 
			get_fields_from_table('category_features_description', 'cfd.', array(), '')
		);
		$this->db->from('category_features cf');
		$this->db->join('category_features_description cfd', 'cf.feature_id = cfd.feature_id', 'left');
		$this->db->where('cfd.language_id', $language_id);
		$this->db->where('cf.category_id', $category_id);
		$this->db->where('cf.status', '1');
		$this->db->order_by($sort, $order);
		if($limit != '-1') {
			$this->db->limit($limit);
		}
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	public function get_categories_by_parent_id($parent_id, $limit = 10, $sort = 'c.category_id', $order = 'desc')
	{
		$_c_array = explode(', ', get_fields_from_table('category', 'c.'));
		$_cd_array = explode(', ', get_fields_from_table('category_description', 'cd.'));
		$_sort_allowed = array_merge($_c_array, $_cd_array);

		if( ! in_array($sort, $_sort_allowed)) {
			$sort = 'c.category_id';
		}

		$_order_allowed = array('asc', 'desc', 'random');

		if( ! in_array($order, $_order_allowed)) {
			$sort = 'desc';
		}

		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('category', 'c.', array(), ', ') . 
			get_fields_from_table('category_description', 'cd.', array(), ', ')
		);
		$this->db->from('category c');
		$this->db->join('category_description cd', 'c.category_id = cd.category_id', 'left');
		$this->db->where('cd.language_id', $language_id);
		$this->db->where('c.parent_id', $parent_id);
		$this->db->where('c.status', '1');
		$this->db->order_by($sort, $order);
		if($limit != '-1') {
			$this->db->limit($limit);
		}
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	public function save_category($category_id)
	{
		$this->category_product_count = array();
		$this->get_save_category($category_id);
		if(count($this->category_product_count))
		{
			$this->category_product_count = array_unique($this->category_product_count);
		}
		return $this->category_product_count;
	}

	public function get_save_category($category_id)
	{
		$this->db->select(
			get_fields_from_table('category', 'c.', array('category_id', 'parent_id'), '')
		);
		$this->db->from('category c');
		$this->db->where('parent_id', (int) $category_id);
		$query = $this->db->get();
		$this->category_product_count[] = $category_id;
		foreach($query->result() as $result) {
			$this->category_product_count[] = $result->category_id;
			$this->get_save_category($result->category_id);
		}
	}

	public function get_product_count($category_id)
	{
		$count = 0;
		$cache_grup = 'category_product_count';
		$cache_expire = '60'; // minutes
		$cache_check = $this->cache->get($category_id, $cache_grup);
		if($cache_check === FALSE) {
			$categories = $this->save_category($category_id);
			if(count($categories)) {
				foreach($categories as $category) {
					$language_id = get_language('language_id');
					$this->db->distinct();
					$this->db->select('SQL_CALC_FOUND_ROWS pd.name AS name, p.image AS image, ' . 
						get_fields_from_table('product', 'p.', array(), ', ') . 
						get_fields_from_table('product_description', 'pd.', array(), '')
						, FALSE);
					$this->db->from('product_to_category p2c');
					$this->db->join('product p', 'p2c.product_id = p.product_id', 'left');
					$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
					$this->db->where('p2c.category_id', (int) $category);
					$this->db->where('pd.language_id', (int) $language_id);
					$this->db->where('p.date_available >= UNIX_TIMESTAMP()');
					$this->db->where('p.status', '1');
					$query = $this->db->count_all_results();
					if($query)
					{
						$count += $query;
					}
				}

				$this->cache->set($category_id, $count, $cache_grup, $cache_expire);
			}
		} else {
			$count = $cache_check;
		}

		return $count;
	}

	function count_category()
	{
		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->from('category c');
		$this->db->join('category_description cd', 'c.category_id = cd.category_id', 'left');
		$this->db->where('cd.language_id', $language_id);
		$this->db->where('c.status', '1');
		$query = $this->db->count_all_results();
		if($query) {
			return $query;
		} else {
			return FALSE;
		}
	}

	public function get_categories_by_menu($parent_id = 0)
	{
		$return_categories = array();

		$categories = $this->get_categories_by_parent_id($parent_id, '-1', 'c.sort_order', 'desc');
		if ($categories) {
			foreach ($categories as $category) {
				if ($category->top) {
					$children_data = array();

					$children = $this->get_categories_by_parent_id($category->category_id, '-1', 'c.sort_order', 'desc');
					if ($children) {
						foreach ($children as $child) {
							$product_total = $this->get_product_count($child->category_id);
							$children_data[] = array(
								'name'  => $child->name . ' (' . $product_total . ')',
								'href'  => $category->seo . '---' . $child->seo
							);
						}
					}

					// Level 1
					$return_categories[] = array(
						'name'     => $category->name,
						'children' => $children_data,
						'column'   => $category->column ? $category->column : 1,
						'href'     => $category->seo
					);
				}
			}
		}

		return $return_categories;
	}

}