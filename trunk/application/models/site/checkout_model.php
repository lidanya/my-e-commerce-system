<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 **/

class checkout_model extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_product_by_model($model)
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
		$this->db->where('p.model', $model);
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

	public function get_product_cargo_required_by_id($product_id)
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
		$this->db->where('p.cargo_required', '1');
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

	public function get_products_cargo_required_by_array_id($product_id_array, $order_id = FALSE)
	{
		$language_id = get_language('language_id');
		$this->db->distinct();
		$this->db->select('pd.name AS name, p.image AS image, m.name AS manufacturer, sd.stok_miktar AS pay_quantity, ' . 
			get_fields_from_table('product', 'p.', array(), ', ') . 
			get_fields_from_table('product_description', 'pd.', array(), ', ') .
			get_fields_from_table('siparis_detay', 'sd.', array('stok_miktar'), '')
			, FALSE);
		$this->db->from('product p');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->join('manufacturer m', 'p.manufacturer_id = m.manufacturer_id', 'left');
		$this->db->join('siparis_detay sd', 'p.model = sd.stok_kodu', 'left');
		$this->db->where_in('p.product_id', $product_id_array);
		$this->db->where('sd.siparis_id', (int) $order_id);
		$this->db->where('p.cargo_required', '1');
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

}