<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class customer extends Admin_Controller {

	var $izin_linki;
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('yonetim/customer_management/customer_group_model');
		$this->load->model('yonetim/customer_management/customer_customer_model');
		$this->izin_linki = 'customer/customer';

		$this->load->library('form_validation');
	}

	public function index()
	{
		redirect(yonetim_url('customer_management/customer/lists'));
	}

	function lists($sort_link = 'u.id-desc', $filter = 'u.banned|]', $page = 0)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/customer_management/customer/lists/' . $sort_link . '/' . $filter . '/' . $page);

		$sort_link_e			= explode('-', $sort_link);
		$sort					= $sort_link_e[0];
		$order					= $sort_link_e[1];

		$data					= array();
		$typ_title				= 'Müşteri';
		$data['title']			= ucwords($typ_title);
		$data['customers']		= array();
		$_customer_groups		= $this->customer_group_model->get_groups_by_parent_id(1); // Müşteriler
		$customer_groups		= array('' => '');
		if($_customer_groups) {
			foreach($_customer_groups as $_customer_group) {
				$customer_groups[$_customer_group->id] = $_customer_group->name;
			}
		}	
		$data['customer_groups']= $customer_groups;
		$customer				= $this->customer_customer_model->get_customer_by_all($page, $sort, $order, $filter, $sort_link);
		if($customer) {
			foreach ($customer->result() as $result) {
				$action = array();
				$action[] = array(
					'text' => 'Düzenle',
					'href' => 'yonetim/customer_management/customer/edit/' . $result->id
				);
				$data['customers'][] = array(
					'id'						=> $result->id,
					'role_id'					=> $result->role_id,
					'parent_id'					=> $result->parent_id,
					'name'						=> $result->name,
					'surname'					=> $result->surname,
					'namesurname'				=> $result->namesurname,
					'role_name'					=> $result->role_name,
					'username'					=> $result->username,
					'last_ip'					=> $result->last_ip,
					'last_login'				=> $result->last_login,
					'banned'					=> $result->banned,
					'created'					=> $result->created,
					'modified'					=> $result->modified,
					'selected'					=> ($this->input->post('selected') && in_array($result->id, $this->input->post('selected'))),
					'action'					=> $action
				);
			}
		}

		$data['sort_link']		= $sort_link;
		$data['filt_link']		= $filter;
		$data['page_link']		= $page;

		$sort_lnk_e				= explode('-', $sort_link);
		$data['sort']			= $sort_lnk_e[0];
		$data['order']			= $sort_lnk_e[1];

		if ($order) {
			if ($order == 'asc') {
				$data['order_link'] = 'desc';
			} else if ($order == 'desc') {
				$data['order_link'] = 'asc';
			} else {
				$data['order_link'] = 'desc';
			}
		} else {
			$data['order_link'] = 'asc';		
			$data['order'] = 'desc';		
		}

		$_array = explode(', ', get_fields_from_table('users', 'u.'));
		$_c_array = explode(', ', get_fields_from_table('users', 'u.'));
		$_r_array = explode(', ', get_fields_from_table('roles', 'r.'));
		$_cc_array = explode(', ', get_fields_from_table('usr_ide_inf', 'uii.'));
		$_cc2_array = array('uii.namesurname');
		$_filter_allowed = array_merge($_c_array, $_r_array, $_cc_array, $_cc2_array);

		if ($filter != 'u.banned|]') {
			$filter_e = explode(']', $filter);
			foreach($filter_e as $yaz) {
				if($yaz != '') {
					if(preg_match('/|/i', $yaz)) {
						$explode = explode('|', $yaz);
						if((isset($explode[0]) AND $explode[0] != '') AND (isset($explode[1]) AND $explode[1] != '')) {
							if(in_array($explode[0], $_filter_allowed)) {
								$data['filter_' . str_replace('.', '_', $explode[0])] = $explode[1];
							}
						}
					}
				}
			}
		}

		$this->load->view('yonetim/customer_management/customer_list_view' , $data);
	}

	function edit($user_id)
	{
		$check = $this->customer_customer_model->count_customer_by_id($user_id);
		if(!$check) {
			$yonetim_mesaj				= array();
			$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Düzenlemek istediğiniz müşteri bulunamadı!');
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/customer_management/customer/lists');
		}

		$form_type = array('type' => 'edit', 'user_id' => $user_id);
		$this->get_form($form_type);
	}

	function username_control_check($username)
	{
		$result = $this->dx_auth->is_username_available($username);
		if (!$result) {
			$val->set_message('username_control_check', 'Email adresi kullanılıyor lütfen başka deneyiniz.');
		}
		return $result;
	}
	
	function email_control_check($email)
	{
		$val = $this->form_validation;
		$result = $this->dx_auth->is_email_available($email);
		if (!$result) {
			$val->set_message('email_control_check', 'Email adresi kullanılıyor lütfen başka deneyiniz.');
		}
		return $result;
	}

	private function get_form($get_values)
	{
		if($get_values['type'] == 'edit') {
			$user_id = $get_values['user_id'];
			$user_info = $this->customer_customer_model->get_customer_by_id($user_id);
		}

		$typ_title					= 'Müşteri';
		$data['title']				= ucwords($typ_title);
		$data['cancel_url']			= 'customer_management/customer/lists';
		if(isset($user_info)) {
			$data['action_url']		= 'customer_management/customer/edit/' . $user_id;
		}

		$val = $this->form_validation;

		/* Kimlik Bilgileri Tab */
		$val->set_rules('identity_name', 'Adı', 'trim|xss_clean');
		$val->set_rules('identity_surname', 'Soyadı', 'trim|xss_clean');
		if($this->input->post()) {
			if(isset($user_info)) {
				if($user_info->username != $this->input->post('identity_email') AND $user_info->email != $this->input->post('identity_email')) {
					$val->set_rules('identity_email', 'E-Posta', 'trim|required|valid_email|callback_email_control_check|callback_username_control_check|xss_clean');
				} else {
					$val->set_rules('identity_email', 'E-Posta', 'trim|required|valid_email|xss_clean');
				}
			}
		} else {
			$val->set_rules('identity_email', 'E-Posta', 'trim|required|valid_email|callback_email_control_check|callback_username_control_check|xss_clean');
		}
		$val->set_rules('identity_sex', 'Cinsiyet', 'trim|required|xss_clean');
		$val->set_rules('identity_role_id', 'Müşteri Grubu', 'trim|required|xss_clean');
		$val->set_rules('identity_number', 'TC Kimlik Numarası', 'trim|min_lenght[11]|max_lenght[11]|numeric|xss_clean');
		$val->set_rules('identity_website', 'Web Sitesi', 'trim|valid_website|xss_clean');
		$val->set_rules('identity_birthday', 'Doğum Tarihi', 'trim|xss_clean');
		/* Kimlik Bilgileri Tab */

		/* İletişim Bilgileri */
		$val->set_rules('contact_gsm', 'Cep Telefonu', 'trim|xss_clean');
		$val->set_rules('contact_work', 'İş Telefonu', 'trim|xss_clean');
		$val->set_rules('contact_work_fax', 'İş Faks', 'trim|xss_clean');
		$val->set_rules('contact_home', 'Ev Telefonu', 'trim|xss_clean');
		$val->set_rules('contact_work_address', 'İş Adresi', 'trim|xss_clean');
		/* İletişim Bilgileri */

		/* Güvenlik Bilgileri */
		$val->set_rules('security_password', 'Parola', 'trim|min_length[6]|max_length[20]|matches[security_password_confirm]|xss_clean');
		$val->set_rules('security_password_confirm', 'Parola Tekrar', 'trim|min_length[6]|max_length[20]|xss_clean');
		/* Güvenlik Bilgileri */


		if ($val->run() == FALSE) {
			$data['errors'] = validation_errors();
		} else {
			if(isset($user_info)) {
				$check = $this->customer_customer_model->update_customer($user_id, $this->input->post());
				if(!$check) {
					$yonetim_mesaj				= array();
					$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('Düzenlemek istediğiniz müşteride sorun oluştu! Herhangi bir değişiklik yapmamış olabilirsiniz!');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/customer_management/customer/lists');
				} else {
					$yonetim_mesaj				= array();
					$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('Müşteri düzenleme işleminiz başarılı bir şekilde gerçekleşti.');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/customer_management/customer/lists');
				}
			}
		}

		if(isset($user_info)) {
			$data['user_info'] = $user_info;
		} else {
			$data['user_info'] = '';
		}

		/* Özet Tab */

		if(isset($user_info)) {
			$data['user_id'] = $user_info->id;
		} else {
			$data['user_id'] = '';
		}

		if(isset($user_info)) {
			$data['name'] = $user_info->name;
		} else {
			$data['name'] = '';
		}

		if(isset($user_info)) {
			$data['surname'] = $user_info->surname;
		} else {
			$data['surname'] = '';
		}

		if(isset($user_info)) {
			$data['username'] = $user_info->username;
		} else {
			$data['username'] = '';
		}

		if(isset($user_info)) {
			$data['adr_is_tel1'] = $user_info->adr_is_tel1;
		} else {
			$data['adr_is_tel1'] = '';
		}

		if(isset($user_info)) {
			$data['role_name'] = $user_info->role_name;
		} else {
			$data['role_name'] = '';
		}

		/* Özet Tab */
		
		/* Kimlik Bilgileri Tab */

		if($this->input->post('identity_name')) {
			$data['identity_name'] = $this->input->post('identity_name');
		} elseif(isset($user_info)) {
			$data['identity_name'] = $user_info->name;
		} else {
			$data['identity_name'] = '';
		}

		if($this->input->post('identity_surname')) {
			$data['identity_surname'] = $this->input->post('identity_surname');
		} elseif(isset($user_info)) {
			$data['identity_surname'] = $user_info->surname;
		} else {
			$data['identity_surname'] = '';
		}

		if($this->input->post('identity_email')) {
			$data['identity_email'] = $this->input->post('identity_email');
		} elseif(isset($user_info)) {
			$data['identity_email'] = $user_info->username;
		} else {
			$data['identity_email'] = '';
		}

		$_customer_parent_id = sayfa_kontrol($this->izin_linki, NULL, TRUE) ? 0 : 1;
		$customer_groups = $this->customer_group_model->create_tree_group_by_parent_id($_customer_parent_id, TRUE);
		$data['customer_groups'] = $customer_groups;

		if($this->input->post('identity_sex')) {
			$data['identity_sex'] = $this->input->post('identity_sex');
		} elseif(isset($user_info)) {
			$data['identity_sex'] = $user_info->ide_cins;
		} else {
			$data['identity_sex'] = '';
		}

		if($this->input->post('identity_role_id')) {
			$data['identity_role_id'] = $this->input->post('identity_role_id');
		} elseif(isset($user_info)) {
			$data['identity_role_id'] = $user_info->role_id;
		} else {
			$data['identity_role_id'] = '';
		}

		if($this->input->post('identity_number')) {
			$data['identity_number'] = $this->input->post('identity_number');
		} elseif(isset($user_info)) {
			$data['identity_number'] = $user_info->ide_tckimlik;
		} else {
			$data['identity_number'] = '';
		}

		if($this->input->post('identity_website')) {
			$data['identity_website'] = $this->input->post('identity_website');
		} elseif(isset($user_info)) {
			$data['identity_website'] = $user_info->ide_web_site;
		} else {
			$data['identity_website'] = '';
		}

		if($this->input->post('identity_birthday')) {
			$data['identity_birthday'] = $this->input->post('identity_birthday');
		} elseif(isset($user_info)) {
			$data['identity_birthday'] = $user_info->ide_dogtar;
		} else {
			$data['identity_birthday'] = '';
		}

		/* Kimlik Bilgileri Tab */

		/* İletişim Bilgileri */

		if($this->input->post('contact_gsm')) {
			$data['contact_gsm'] = $this->input->post('contact_gsm');
		} elseif(isset($user_info)) {
			$data['contact_gsm'] = $user_info->ide_cep;
		} else {
			$data['contact_gsm'] = '';
		}

		if($this->input->post('contact_work')) {
			$data['contact_work'] = $this->input->post('contact_work');
		} elseif(isset($user_info)) {
			$data['contact_work'] = $user_info->adr_is_tel1; // iş tel
		} else {
			$data['contact_work'] = '';
		}

		if($this->input->post('contact_work_fax')) {
			$data['contact_work_fax'] = $this->input->post('contact_work_fax');
		} elseif(isset($user_info)) {
			$data['contact_work_fax'] = $user_info->adr_is_fax; // iş fax
		} else {
			$data['contact_work_fax'] = '';
		}

		if($this->input->post('contact_home')) {
			$data['contact_home'] = $this->input->post('contact_home');
		} elseif(isset($user_info)) {
			$data['contact_home'] = $user_info->adr_is_tel2; // ev tel
		} else {
			$data['contact_home'] = '';
		}

		if($this->input->post('contact_work_address')) {
			$data['contact_work_address'] = $this->input->post('contact_work_address');
		} elseif(isset($user_info)) {
			$data['contact_work_address'] = $user_info->adr_is_adr_id; // iş adresi
		} else {
			$data['contact_work_address'] = '';
		}

		/* İletişim Bilgileri */

		/* Güvenlik Bilgileri */

		if(isset($user_info)) {
			$data['security_created'] = $user_info->created;
		} else {
			$data['security_created'] = '';
		}

		if(isset($user_info)) {
			$data['security_modified'] = $user_info->modified;
		} else {
			$data['security_modified'] = '';
		}

		if(isset($user_info)) {
			$data['security_last_login'] = $user_info->last_login;
		} else {
			$data['security_last_login'] = '';
		}

		if(isset($user_info)) {
			$data['security_last_ip'] = $user_info->last_ip;
		} else {
			$data['security_last_ip'] = '';
		}

		/* Güvenlik Bilgileri */

		$this->load->view('yonetim/customer_management/customer_form_view' , $data);
	}
}