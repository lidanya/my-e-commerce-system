<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 **/

class product_model extends CI_Model 
{
	protected $product_options = array();

	public function __construct() 
	{
		parent::__construct();
	}
    
	public function is_campaign($pid)
	{
		$q = $this->db->get_where("e_product_special",array("product_id"=>$pid));
		
		if($q)
		return $q->row();
		else
		return false;
	
	}

	public function get_product_by_id($product_id)
	{
		$language_id = get_language('language_id');
		$this->db->distinct();
		$this->db->select('pd.name AS name, p.image AS image, m.name AS manufacturer, ' .
			get_fields_from_table('product', 'p.', array(), ', ') . 
			get_fields_from_table('product_description', 'pd.', array(), '')
			, FALSE);
		$this->db->from('product p');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->join('manufacturer m', 'p.manufacturer_id = m.manufacturer_id', 'left');
		$this->db->where('p.product_id', (int) $product_id);
		$this->db->where('pd.language_id', (int) $language_id);
		$this->db->where('p.date_available >= UNIX_TIMESTAMP()');
		$this->db->where('p.status', '1');
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->row();
		} else {
			return FALSE;
		}
	}

	public function get_product_by_seo($product_seo)
	{
        $language_id = get_language('language_id');

		$this->db->distinct();
		$this->db->select('pd.name AS name, p.image AS image, m.name AS manufacturer, ' .
			get_fields_from_table('product', 'p.', array(), ', ') . 
			get_fields_from_table('product_description', 'pd.', array(), '')
			, FALSE);
		$this->db->from('product p');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->join('manufacturer m', 'p.manufacturer_id = m.manufacturer_id', 'left');
		$this->db->where('pd.seo', $product_seo);
		$this->db->where('pd.language_id', (int) $language_id);
		$this->db->where('p.date_available >= UNIX_TIMESTAMP()');
		$this->db->where('p.status', '1');
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->row();
		} else {
			return FALSE;
		}
	}

	public function get_new_product($sort = 'product_id', $order = 'asc', $start = 0, $limit = 20)
	{
		$sort_allowed = array('product_id', 'price', 'name', 'viewed');
		if ( ! in_array($sort, $sort_allowed)) {
			$sort = 'product_id';
		} else {
			if ($sort == 'product_id' OR $sort == 'price' OR $sort == 'viewed') {
				$sort = 'p.' . $sort;
			} elseif ($sort == 'name') {
				$sort = 'pd.' . $sort;
			}
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
		$this->db->order_by($sort, $order);
		$this->db->limit($limit, $start);
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

	public function get_manufacturer_product_by_id($manufacturer_id, $sort = 'product_id', $order = 'asc', $start = 0, $limit = 20)
	{
		$sort_allowed = array('product_id', 'price', 'name', 'viewed');
		if ( ! in_array($sort, $sort_allowed)) {
			$sort = 'product_id';
		} else {
			if ($sort == 'product_id' OR $sort == 'price' OR $sort == 'viewed') {
				$sort = 'p.' . $sort;
			} elseif ($sort == 'name') {
				$sort = 'pd.' . $sort;
			}
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
		$this->db->where('p.manufacturer_id', (int) $manufacturer_id);
		$this->db->where('pd.language_id', (int) $language_id);
		$this->db->where('p.date_available >= UNIX_TIMESTAMP()');
		$this->db->where('p.status', '1');
		$this->db->order_by($sort, $order);
		$this->db->limit($limit, $start);
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

	public function get_products_by_category_id($category_id, $sort = 'product_id', $order = 'asc', $start = 0, $limit = 20)
	{
		$sort_allowed = array('product_id', 'price', 'name', 'viewed');
		if ( ! in_array($sort, $sort_allowed)) {
			$sort = 'product_id';
		} else {
			if ($sort == 'product_id' OR $sort == 'price' OR $sort == 'viewed') {
				$sort = 'p.' . $sort;
			} elseif ($sort == 'name') {
				$sort = 'pd.' . $sort;
			}
		}

		$language_id = get_language('language_id');
		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS pd.name AS name, p.image AS image, m.name AS manufacturer, ' . 
			get_fields_from_table('product', 'p.', array(), ', ') . 
			get_fields_from_table('product_description', 'pd.', array(), '')
			, FALSE);
		$this->db->from('product_to_category p2c');
		$this->db->join('product p', 'p2c.product_id = p.product_id', 'left');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->join('manufacturer m', 'p.manufacturer_id = m.manufacturer_id', 'left');
		$this->db->where('p2c.category_id', (int) $category_id);
		$this->db->where('pd.language_id', (int) $language_id);
		$this->db->where('p.date_available >= UNIX_TIMESTAMP()');
		$this->db->where('p.status', '1');
		$this->db->order_by($sort, $order);
		$this->db->limit($limit, $start);
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

	public function get_campaign_product($sort = 'product_id', $order = 'asc', $start = 0, $limit = 20)
	{
		$sort_allowed = array('product_id', 'price', 'name', 'viewed');
		if ( ! in_array($sort, $sort_allowed)) {
			$sort = 'product_id';
		} else {
			if ($sort == 'product_id' OR $sort == 'price' OR $sort == 'viewed') {
				$sort = 'p.' . $sort;
			} elseif ($sort == 'name') {
				$sort = 'pd.' . $sort;
			}
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
			get_fields_from_table('product_special', 'ps.', array('product_special_id','date_start','date_end'), ', ')
			, FALSE);
		$this->db->from('product_special ps');
		$this->db->join('product p', 'ps.product_id = p.product_id', 'left');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->join('manufacturer m', 'p.manufacturer_id = m.manufacturer_id', 'left');
		if ( ! $this->dx_auth->is_role('admin-gruplari')) {
			$this->db->where('ps.user_group_id', (int) $user_group_id);
		}
		$this->db->where('pd.language_id', (int) $language_id);
		$this->db->where('p.date_available >= UNIX_TIMESTAMP()');
		$this->db->where('p.status', '1');
		$this->db->order_by($sort, $order);
		$this->db->limit($limit, $start);
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
	
	public function get_related_campaign_product($product_id, $sort = 'product_id', $order = 'asc', $start = 0, $limit = 20)
	{
		$sort_allowed = array('product_id', 'price', 'name', 'viewed');
		if ( ! in_array($sort, $sort_allowed)) {
			$sort = 'product_id';
		} else {
			if ($sort == 'product_id' OR $sort == 'price' OR $sort == 'viewed') {
				$sort = 'p.' . $sort;
			} elseif ($sort == 'name') {
				$sort = 'pd.' . $sort;
			}
		}

		if ($this->dx_auth->is_logged_in()) {
			$user_group_id = $this->dx_auth->get_role_id();
		} else {
			$user_group_id = config('site_ayar_varsayilan_mus_grub');
		}

			
		$language_id = get_language('language_id');
		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS ptc.category_id,, pd.name AS name, p.image AS image, m.name AS manufacturer, ' . 
			get_fields_from_table('product', 'p.', array(), ', ') .
			get_fields_from_table('product_description', 'pd.', array(), ', ') .
			get_fields_from_table('product_special', 'ps.', array('product_special_id'), ', ')
			, FALSE);
		$this->db->from('product_special ps');
		$this->db->join('product p', 'ps.product_id = p.product_id', 'left');
		$this->db->join('product_to_category ptc', 'ptc.product_id = p.product_id', 'left');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->join('manufacturer m', 'p.manufacturer_id = m.manufacturer_id', 'left');
		if ( ! $this->dx_auth->is_role('admin-gruplari')) {
			$this->db->where('ps.user_group_id', (int) $user_group_id);
		}
		$this->db->where('pd.language_id', (int) $language_id);
		$this->db->where('p.date_available >= UNIX_TIMESTAMP()');
		$this->db->where('p.status', '1');
		$this->db->where("ptc.category_id IN (Select category_id From e_product_to_category Where product_id = '$product_id')");
		$this->db->order_by($sort, $order);
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		$total_row = $this->db->select('FOUND_ROWS() as total')->get()->row()->total;
		if($query->num_rows()) {
			return  $query->result();
		} else {
			return FALSE;
		}
	}	

	public function get_discount_product($sort = 'product_id', $order = 'asc', $start = 0, $limit = 20)
	{
		$sort_allowed = array('product_id', 'price', 'name', 'viewed');
		if ( ! in_array($sort, $sort_allowed)) {
			$sort = 'product_id';
		} else {
			if ($sort == 'product_id' OR $sort == 'price' OR $sort == 'viewed') {
				$sort = 'p.' . $sort;
			} elseif ($sort == 'name') {
				$sort = 'pd2.' . $sort;
			}
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
			get_fields_from_table('product_discount', 'pd.', array('product_discount_id','date_start','date_end'), ', ')
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
		$this->db->order_by($sort, $order);
		$this->db->limit($limit, $start);
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
	
	
	public function get_related_discount_product($product_id, $sort = 'product_id', $order = 'asc', $start = 0, $limit = 20)
	{
		$sort_allowed = array('product_id', 'price', 'name', 'viewed');
		if ( ! in_array($sort, $sort_allowed)) {
			$sort = 'product_id';
		} else {
			if ($sort == 'product_id' OR $sort == 'price' OR $sort == 'viewed') {
				$sort = 'p.' . $sort;
			} elseif ($sort == 'name') {
				$sort = 'pd2.' . $sort;
			}
		}

		if ($this->dx_auth->is_logged_in()) {
			$user_group_id = $this->dx_auth->get_role_id();
		} else {
			$user_group_id = config('site_ayar_varsayilan_mus_grub');
		}

		$language_id = get_language('language_id');
		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS ptc.category_id, pd2.name AS name, p.image AS image, m.name AS manufacturer, ' . 
			get_fields_from_table('product', 'p.', array(), ', ') .
			get_fields_from_table('product_description', 'pd2.', array(), ', ') .
			get_fields_from_table('product_discount', 'pd.', array('product_discount_id'), ', ')
			, FALSE);
		$this->db->from('product_discount pd');
		$this->db->join('product p', 'pd.product_id = p.product_id', 'left');
		$this->db->join('product_to_category ptc', 'ptc.product_id = p.product_id', 'left');
		$this->db->join('product_description pd2', 'p.product_id = pd2.product_id', 'left');
		$this->db->join('manufacturer m', 'p.manufacturer_id = m.manufacturer_id', 'left');
		$this->db->where('pd2.language_id', (int) $language_id);
		if ( ! $this->dx_auth->is_role('admin-gruplari')) {
			$this->db->where('pd.user_group_id', (int) $user_group_id);
		}
		$this->db->where('p.date_available >= UNIX_TIMESTAMP()');
		$this->db->where('p.status', '1');
		$this->db->where("ptc.category_id IN (Select category_id From e_product_to_category Where product_id = '$product_id')");
		$this->db->order_by($sort, $order);
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		$total_row = $this->db->select('FOUND_ROWS() as total')->get()->row()->total;
		if($query->num_rows()) {
			return $query->result();
		} else {
			return FALSE;
		}
	}	

	public function get_feature_by_id($product_id)
	{
		$language_id = get_language('language_id');
		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('category_features', 'cf.', array(), ', ') .
			get_fields_from_table('category_features_description', 'cfd.', array(), ', ') .
			get_fields_from_table('product_featured', 'pf.', array(), '')
		, FALSE);
		$this->db->from('category_features cf');
		$this->db->join('category_features_description cfd', 'cf.feature_id = cfd.feature_id', 'left');
		$this->db->join('product_featured pf', 'cf.feature_id = pf.feature_id', 'left');
		$this->db->where('pf.product_id', (int) $product_id);
		$this->db->where('cfd.language_id', (int) $language_id);
		$this->db->where('pf.language_id', (int) $language_id);
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	public function get_images_by_id($product_id, $limit = 10)
	{
		$this->db->select(get_fields_from_table('product_image', 'pi.', array(), ''));
		$this->db->from('product_image pi');
		$this->db->where('pi.product_id', (int) $product_id);
		$this->db->order_by('pi.product_id', 'asc');
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

	public function get_related_by_id($product_id)
	{
		$language_id = get_language('language_id');
		$this->db->distinct();
		$this->db->select('pd.name AS name, p.image AS image, m.name AS manufacturer, ' . 
			get_fields_from_table('product_related', 'pr.', array(), ', ') . 
			get_fields_from_table('product', 'p.', array(), ', ') . 
			get_fields_from_table('product_description', 'pd.', array(), '')
			, FALSE);
		$this->db->from('product_related pr');
		$this->db->join('product p', 'pr.related_id = p.product_id', 'left');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->join('manufacturer m', 'p.manufacturer_id = m.manufacturer_id', 'left');
		$this->db->where('pr.product_id', $product_id);
		$this->db->where('pd.language_id', (int) $language_id);
		$this->db->where('p.date_available >= UNIX_TIMESTAMP()');
		$this->db->where('p.status', '1');
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->result();
		} else {
			return FALSE;
		}
	}
	
	public function get_related_cheap_products($product_id) // daha ucuz ürünler
	{
		$language_id = get_language('language_id');
		$this->db->distinct();
		$this->db->select('pr.category_id, pd.name AS name, p.image AS image, m.name AS manufacturer, ' . 
			get_fields_from_table('product', 'p.', array(), ', ') . 
			get_fields_from_table('product_description', 'pd.', array(), '')
			, FALSE);
		$this->db->from('product_to_category pr');
		$this->db->join('product p', 'p.product_id = pr.product_id', 'left');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->join('manufacturer m', 'p.manufacturer_id = m.manufacturer_id', 'left');
		$this->db->where('pd.language_id', (int) $language_id);
		$this->db->where("pr.category_id IN (Select category_id From e_product_to_category Where product_id = '$product_id')");
		$this->db->where('p.date_available >= UNIX_TIMESTAMP()');
		$this->db->where('p.status', '1');
		$this->db->where("p.price < (Select price From e_product Where product_id = '$product_id')");
		$this->db->order_by('p.price','desc');
		$this->db->limit(20);
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->result();
		} else {
			return FALSE;
		}
	}	

	public function get_option_by_id($product_id)
	{
		$product_option_data = array();
		$language_id = get_language('language_id');

		$this->db->select(get_fields_from_table('product_option', 'po.', array(), ''));
		$this->db->from('product_option po');
		$this->db->where('po.product_id', (int) $product_id);
		$this->db->order_by('po.sort_order', 'asc');
		$product_option_query = $this->db->get();

		foreach($product_option_query->result() as $product_option) {
			$product_option_value_data = array();

			$this->db->select(get_fields_from_table('product_option_value', 'pov.', array(), ''));
			$this->db->from('product_option_value pov');
			$this->db->where('pov.product_option_id', (int) $product_option->product_option_id);
			$this->db->order_by('pov.sort_order', 'asc');
			$product_option_value_query = $this->db->get();

			foreach($product_option_value_query->result() as $product_option_value) {

				$this->db->distinct();
				$this->db->select(get_fields_from_table('product_option_value_description', 'povd.', array(), ''));
				$this->db->from('product_option_value_description povd');
				$this->db->where('povd.product_option_value_id', (int) $product_option_value->product_option_value_id);
				$this->db->where('povd.language_id', (int) $language_id);
				$this->db->limit(1);
				$product_option_value_description_query = $this->db->get();

				if($product_option_value_description_query->num_rows()) {
					$product_option_value_description_info = $product_option_value_description_query->row();

					$product_option_value_data[$product_option_value->product_option_value_id] = array(
						'product_option_value_id'	=> $product_option_value->product_option_value_id,
						'name'						=> $product_option_value_description_info->name,
						'price'						=> $product_option_value->price,
						'prefix'					=> $product_option_value->prefix
					);
				}
			}

			$this->db->distinct();
			$this->db->select(get_fields_from_table('product_option_description', 'pod.', array(), ''));
			$this->db->from('product_option_description pod');
			$this->db->where('pod.product_option_id', (int) $product_option->product_option_id);
			$this->db->where('pod.language_id', (int) $language_id);
			$this->db->limit(1);
			$product_option_value_description_query = $this->db->get();

			if($product_option_value_description_query->num_rows()) {
				$product_option_value_description_info = $product_option_value_description_query->row();

				$product_option_data[$product_option->product_option_id] = array(
					'product_option_id'	=> $product_option->product_option_id,
					'name'				=> $product_option_value_description_info->name,
					'option_value'		=> $product_option_value_data,
					'sort_order'		=> $product_option->sort_order
				);
			}
		}

		return $product_option_data;
	}

	/* New Product Options */

	public function get_product_option_by_id($product_id)
	{
		$language_id = get_language('language_id');
		$product_option_data = array();

		$this->db->select(
			get_fields_from_table('product_option', 'po.', array(), ', ') .
			get_fields_from_table('option', 'o.', array(), ', ') .
			get_fields_from_table('option_description', 'od.', array(), '')
		);
		$this->db->from('product_option po');
		$this->db->join('option o', 'po.option_id = o.option_id', 'left');
		$this->db->join('option_description od', 'o.option_id = od.option_id', 'left');
		$this->db->where('po.product_id', (int) $product_id);
		$this->db->where('od.language_id', (int) $language_id);
		$this->db->order_by('o.sort_order');
		$product_option_query = $this->db->get();
		//exit($this->db->last_query());

		foreach ($product_option_query->result_array() as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox') {
				$product_option_value_data = array();

				$this->db->select(
					get_fields_from_table('product_option_value', 'pov.', array(), ', ') .
					get_fields_from_table('option_value', 'ov.', array(), ', ') .
					get_fields_from_table('option_value_description', 'ovd.', array(), '')
				);
				$this->db->from('product_option_value pov');
				$this->db->join('option_value ov', 'pov.option_value_id = ov.option_value_id', 'left');
				$this->db->join('option_value_description ovd', 'ov.option_value_id = ovd.option_value_id', 'left');
				$this->db->where('pov.product_id', (int) $product_id);
				$this->db->where('pov.product_option_id', (int) $product_option['product_option_id']);
				$this->db->where('ovd.language_id', (int) $language_id);
				$this->db->order_by('ov.sort_order');
				$product_option_value_query = $this->db->get();

				foreach ($product_option_value_query->result_array() as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id'	=> $product_option_value['product_option_value_id'],
						'option_value_id'			=> $product_option_value['option_value_id'],
						'name'						=> $product_option_value['name'],
						'quantity'					=> $product_option_value['quantity'],
						'subtract'					=> $product_option_value['subtract'],
						'price'						=> $product_option_value['price'],
						'price_prefix'				=> $product_option_value['price_prefix'],		
					);
				}

				$product_option_data[] = array(
					'product_option_id' 			=> $product_option['product_option_id'],
					'option_id'						=> $product_option['option_id'],
					'name'							=> $product_option['name'],
					'type'							=> $product_option['type'],
					'option_value'					=> $product_option_value_data,
					'required'						=> $product_option['required'],
					'character_limit'				=> $product_option['character_limit']
				);
			} else {
				$product_option_data[] = array(
					'product_option_id'				=> $product_option['product_option_id'],
					'option_id'						=> $product_option['option_id'],
					'name'							=> $product_option['name'],
					'type'							=> $product_option['type'],
					'option_value'					=> $product_option['option_value'],
					'required'						=> $product_option['required'],
					'character_limit'				=> $product_option['character_limit']
				);				
			}
      	}
      	return $product_option_data;
	}

	public function get_product_option($product_id)
	{
		$get_option = $this->get_product_option_by_id($product_id);
		if($get_option) {
			return $get_option;
		} else {
			return FALSE;
		}
	}

	/* New Product Options */

	public function follow_status($get_values)
	{
		$user_id = $get_values['follow_user_id'];
		$product_id = $get_values['follow_product_id'];
		$follow_status = $get_values['follow_status']; // 0 sil 1 ekle

		if($follow_status == '1') {
			if(!$this->count_follow_by_id($product_id, $user_id)) {
				if($this->db->insert('product_follow', array('product_id' => $product_id, 'user_id' => $user_id))) {
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		} elseif($follow_status == '0') {
			if($this->count_follow_by_id($product_id, $user_id)) {
				if($this->db->delete('product_follow', array('product_id' => $product_id, 'user_id' => $user_id))) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function count_follow_by_id($product_id, $user_id)
	{
		$this->db->where('product_id', $product_id);
		$this->db->where('user_id', $user_id);
		$fallow_check = $this->db->count_all_results('product_follow');
		return $fallow_check;
	}

	public function update_view_by_id($product_id)
	{
		$this->db->set('viewed', 'viewed+1', FALSE);
		$this->db->where('product_id', (int) $product_id);
		$this->db->update('product');
	}

	public function stock_shema($values, $type, $layout = 'site')
	{
		$types					= $type;
		$content['degerler']	= $values;
		if ($layout == 'site') {
			$this->load->view(tema() . 'stok_gorunum_sablonlari/stok_gorunum_' . $types, $content);
		} elseif($layout == 'face') {
			$this->load->view(face_tema() . 'stok_gorunum_sablonlari/stok_gorunum_' . $types, $content);
		}
	}

}

/* End of file class_name.php */
/* Location: ./application/models/class_name.php */