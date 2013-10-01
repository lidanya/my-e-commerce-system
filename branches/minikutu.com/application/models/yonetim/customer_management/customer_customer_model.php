<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class customer_customer_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Customer Model Yüklendi');
	}

	public function get_customer_by_all($page, $sort = 'u.id-desc', $order = 'desc', $filter = 'u.banned|]', $sort_link)
	{
		$_c_array = explode(', ', get_fields_from_table('users', 'u.'));
		$_r_array = explode(', ', get_fields_from_table('roles', 'r.'));
		$_cc_array = explode(', ', get_fields_from_table('usr_ide_inf', 'uii.'));
		$_cc2_array = array('uii.namesurname');
		$_cc3_array = array('namesurname');
		$_filter_allowed = array_merge($_c_array, $_r_array, $_cc_array, $_cc2_array);
		$_sort_allowed = array_merge($_c_array, $_r_array, $_cc_array, $_cc3_array);

		$per_page = (config('site_ayar_urun_yonetim_sayfa')) ? config('site_ayar_urun_yonetim_sayfa') : 20;

		if( ! in_array($sort, $_sort_allowed)) {
			$sort = 'u.id-desc';
		}

		$_order_allowed = array('asc', 'desc', 'random');

		if( ! in_array($order, $_order_allowed)) {
			$order = 'desc';
		}

		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS ' . 
			get_fields_from_table('users', 'u.', array(), ', ') . 
			get_fields_from_table('roles', 'r.', array('parent_id'), ', ') . 
			'r.name as role_name, uii.ide_adi as name, uii.ide_soy as surname, CONCAT(uii.ide_adi, \' \', uii.ide_soy) as namesurname'
		, FALSE);
		$this->db->from('users u');
		$this->db->join('roles r', 'u.role_id = r.id', 'left');
		$this->db->join('usr_ide_inf uii', 'u.id = uii.user_id', 'left');
		$this->db->where_not_in('r.parent_id', '0');
		$this->db->where_not_in('r.parent_id', '2');
		$this->db->where('u.durum', '0');

		if ($filter != 'u.banned|]') {
			$filter_e = explode(']', $filter);
			foreach($filter_e as $yaz) {
				if($yaz != '') {
					if(preg_match('/|/i', $yaz)) {
						$explode = explode('|', $yaz);
						if((isset($explode[0]) AND $explode[0] != '') AND (isset($explode[1]) AND $explode[1] != '')) {
							if(in_array($explode[0], $_filter_allowed)) {
								if($explode[0] == 'uii.namesurname') {
									$this->db->like('CONCAT(uii.ide_adi, \' \', uii.ide_soy)', $explode[1]);
								} else {
									$this->db->like($explode[0], $explode[1]);
								}
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

		$config['base_url'] 		= base_url() . 'yonetim/customer_management/customer/lists/' . $sort_link . '/' . $filter;
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
Toplam '. $toplam_sayfa .' sayfa içinde '. $mevcut_sayfa .'. sayfadasın, toplam müşteri sayısı '. $query_count .'</div></div>';

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

	public function count_customer_by_id($user_id)
	{
		$this->db->from('users u');
		$this->db->join('roles r', 'u.role_id = r.id', 'left');
		$this->db->where('r.parent_id', '1');
		$this->db->where('u.id', (int) $user_id);
		$query = $this->db->count_all_results();
		if($query) {
			return $query;
		} else {
			return FALSE;
		}
	}

	public function get_customer_by_id($user_id)
	{
		$this->db->select(
			get_fields_from_table('users', 'u.', array(), ', ') .
			get_fields_from_table('roles', 'r.', array('parent_id', 'name'), ', ') .
			get_fields_from_table('usr_ide_inf', 'uii.', array(), ', ') .
			get_fields_from_table('usr_adr_inf', 'uai.', array(), ', ') .
			'r.name as role_name, uii.ide_adi as name, uii.ide_soy as surname, CONCAT(uii.ide_adi, \' \', uii.ide_soy) as namesurname'
		, FALSE);
		$this->db->from('users u');
		$this->db->join('roles r', 'u.role_id = r.id', 'left');
		$this->db->join('usr_ide_inf uii', 'u.id = uii.user_id', 'left');
		$this->db->join('usr_adr_inf uai', 'u.id = uai.user_id', 'left');
		$this->db->where('u.id', $user_id);
		$this->db->where('r.parent_id', '1');
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->row();
		} else {
			return FALSE;
		}
	}

	public function update_customer($user_id, $get_values)
	{
		$_users_update_data = array(
			'role_id'				=> $get_values['identity_role_id'],
			'modified'				=> standard_date('DATE_MYSQL', time(), 'tr')
		);

		if($get_values['identity_email'] != '') {
			$_users_update_data['username'] = $get_values['identity_email'];
			$_users_update_data['email'] = $get_values['identity_email'];
		}

		if($get_values['security_password'] != '') {
			// Crypt and encode new password
			$_users_update_data['password'] = crypt($this->dx_auth->_encode($get_values['security_password']));
		}
		$this->db->where('id', (int) $user_id);
		$this->db->update('users', $_users_update_data);
		$users_update_status = $this->db->affected_rows();

		$_usr_ide_inf_update_data = array(
			'ide_adi'				=> $get_values['identity_name'],
			'ide_soy'				=> $get_values['identity_surname'],
			'ide_cins'				=> $get_values['identity_sex'],
			'ide_tckimlik'			=> $get_values['identity_number'],
			'ide_web_site'			=> $get_values['identity_website'],
			'ide_dogtar'			=> $get_values['identity_birthday'],
			'ide_cep'				=> $get_values['contact_gsm']
		);
		$this->db->where('user_id', (int) $user_id);
		$this->db->update('usr_ide_inf', $_usr_ide_inf_update_data);
		$usr_ide_inf_update_status = $this->db->affected_rows();

		$_usr_adr_inf_update_data = array(
			'adr_is_tel1'			=> $get_values['contact_work'],
			'adr_is_tel2'			=> $get_values['contact_home'],
			'adr_is_fax'			=> $get_values['contact_work_fax'],
			'adr_is_adr_id'			=> $get_values['contact_work_address']
		);
		$this->db->where('user_id', (int) $user_id);
		$this->db->update('usr_adr_inf', $_usr_adr_inf_update_data);
		$usr_adr_inf_update_status = $this->db->affected_rows();

		if($usr_ide_inf_update_status OR $usr_adr_inf_update_status OR $users_update_status) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

}