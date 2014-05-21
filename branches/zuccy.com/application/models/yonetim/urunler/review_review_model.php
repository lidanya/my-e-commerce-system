<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class review_review_model extends CI_Model 
{

	function __construct() 
	{
		parent::__construct();
	}

	public function update_review($review_id, $get_values)
	{
		$_review_update_data = array(
			'product_id'			=> $get_values['product_id'],
			'user_id'				=> $get_values['user_id'],
			'email'					=> $get_values['email'],
			'author'				=> $get_values['author'],
			'text'					=> $get_values['text'],
			'rating'				=> $get_values['rating'],
			'status'				=> $get_values['status'],
			'date_modified'			=> standard_date('DATE_MYSQL', time(), 'tr')
		);
		$this->db->where('review_id', $review_id);
		$this->db->update('review', $_review_update_data);
		$review_update_status = $this->db->affected_rows();

		if($review_update_status) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function get_review_by_all($page, $sort = 'r.review_id', $order = 'desc', $filter = 'r.status|]', $sort_link)
	{
		$_array = explode(', ', get_fields_from_table('review', 'r.'));
		$_filter_allowed = $_array;
		$_sort_allowed = $_array;

		$per_page = (config('site_ayar_urun_yonetim_sayfa')) ? config('site_ayar_urun_yonetim_sayfa') : 20;

		if( ! in_array($sort, $_sort_allowed)) {
			$sort = 'r.review_id';
		}

		$_order_allowed = array('asc', 'desc', 'random');

		if( ! in_array($order, $_order_allowed)) {
			$order = 'desc';
		}

		$this->db->select('SQL_CALC_FOUND_ROWS ' . 
			get_fields_from_table('review', 'r.', array(), '')
		, FALSE);
		$this->db->from('review r');

		if ($filter != 'r.status|]') {
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

		$config['base_url'] 		= base_url() . 'yonetim/urunler/review/lists/' . $sort_link . '/' . $filter;
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
Toplam '. $toplam_sayfa .' sayfa içinde '. $mevcut_sayfa .'. sayfadasın, toplam yorum sayısı '. $query_count .'</div></div>';

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

	public function review_delete_by_id($review_id)
	{
		if(is_array($review_id)) {
			foreach($review_id as $review) {
				$this->db->delete('review', array('review_id' => (int) $review));
			}
		} else {
			$this->db->delete('review', array('review_id' => (int) $review_id));
		}

		return TRUE;
	}

	public function get_manufacturer_by_all()
	{
		$this->db->select(get_fields_from_table('review', 'r.', array(), ''));
		$this->db->from('review r');
		$query = $this->db->get();

		if($query->num_rows()) {
			return $query->result();
		} else {
			return FALSE;
		}
	}
	
	function get_review_by_id($review_id) 
	{
		$this->db->select(get_fields_from_table('review', 'r.', array(), ''));
		$this->db->from('review r');
		$this->db->where('r.review_id', (int) $review_id);
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