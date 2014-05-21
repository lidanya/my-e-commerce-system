<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 **/

class review_model extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
	}

	public function add_review($values)
	{
		$insert_data = array(
			'product_id'	=> $values['review_product_id'],
			'user_id'		=> $values['review_user_id'],
			'email'			=> $values['review_email'],
			'author'		=> $values['review_author'],
			'text'			=> strip_tags($values['review_text']),
			'rating'		=> (int) $values['review_rating'],
			'date_added'	=> standard_date('DATE_MYSQL', time(), 'tr'),
			'date_modified'	=> standard_date('DATE_MYSQL', time(), 'tr')
		);

		$this->db->insert('review', $insert_data);
		$affected_rows = $this->db->affected_rows();
		if($affected_rows) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function get_review_by_id($product_id)
	{
		$this->db->select(
			get_fields_from_table('review', 'r.', array(), ', ')
		, FALSE);
		$this->db->from('review r');
		$this->db->where('r.product_id', (int) $product_id);
		$this->db->where('r.status', '1');
		$this->db->order_by('r.review_id', 'desc');
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	public function get_average_rating_by_id($product_id)
	{
		$this->db->select_avg('rating');
		$this->db->from('review');
		$this->db->where('product_id', (int) $product_id);
		$this->db->where('status', '1');
		$this->db->group_by('product_id');
		$query = $this->db->get();
		if($query->num_rows()) {
			$query_info = $query->row();
			return round($query_info->rating);
		} else {
			return 0;
		}
	}

	public function check_review_security_code($code)
	{
		$result = strtolower($code) == strtolower($this->session->userdata('urun_yorum_yaz'));
		return $result;
	}

}