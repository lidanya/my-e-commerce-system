<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class urun_takip_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Ürün Takip Model Yüklendi');
	}

	function takip_listele($user_id)
	{
		$language_id = get_language('language_id');

		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('product', 'p.', array('product_id'), ', ') .
			get_fields_from_table('product_description', 'pd.', array('seo', 'name'), ', ') .
			get_fields_from_table('product_follow', 'pf.', array(), '')
		);
		$this->db->from('product_follow pf');
		$this->db->join('product p', 'pf.product_id = p.product_id', 'left');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->where('pd.language_id', (int) $language_id);
		$this->db->where('pf.user_id', (int) $user_id);
		$query = $this->db->get();
		return $query;
	}

	function takip_sil($user_id, $follow_id)
	{
		$this->db->select(get_fields_from_table('product_follow', 'pf.', array('follow_id','product_id','user_id'), ''));
		$this->db->from('product_follow pf');
		$this->db->where('pf.user_id', (int) $user_id);
		$this->db->where('pf.follow_id', (int) $follow_id);
		$this->db->limit(1);
		$check = $this->db->get();
		if($check->num_rows()) {
			if($this->db->delete('product_follow', array('follow_id' => $follow_id, 'user_id' => $user_id))) {
				return 1; // silme başarılı
			} else {
				return 2; // silme başarısız
			}
		} else {
			return 3; // kullanıcı eşleşemedi
		}
	}
}