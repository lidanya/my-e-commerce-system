<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class siparis_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function siparis_getir($user_id)
	{
		$this->db->select(get_fields_from_table('siparis', 's.', array(), ''));
		$this->db->from('siparis s');
		$this->db->where('s.user_id', (int) $user_id);
		$this->db->where('s.siparis_flag !=', '-1');
		$this->db->order_by('s.siparis_id','desc');
		$query = $this->db->get();
		return $query;
	}

	function siparis_detay($siparis_id)
	{
		$language_id = get_language('language_id');

		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('product', 'p.', array(), ', ') .
			get_fields_from_table('product_description', 'pd.', array(), ', ') .
			get_fields_from_table('siparis_detay', 'sd.', array(), '')
		);
		$this->db->from('siparis_detay sd');
		$this->db->join('product p', 'sd.stok_kodu = p.model', 'left');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->where('pd.language_id', (int) $language_id);
		$this->db->where('sd.siparis_id', (int) $siparis_id);
		$query = $this->db->get();
		return $query;
	}
}