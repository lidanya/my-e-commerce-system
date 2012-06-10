<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author Daynex.com.tr
 **/

class bilgi extends Public_Controller {

	/**
	 * isimsiz construct
	 *
	 * @return void
	 **/

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Üye Bilgi Controller Yüklendi');
		$this->load->model('uye_model');
		$this->load->library('form_validation');
	}

	/**
	 * index function
	 *
	 * @return void
	 * @author Ali Çetin, Ahmet Çelebigil -> Daynex.com.tr
	 **/
	
	function index()
	{
		$val = $this->form_validation;

		if($this->dx_auth->is_logged_in()) {

			if($this->input->post('password') != '') {
				$val->set_rules('old_password', 'lang:messages_member_information_form_old_password', 'trim|required|xss_clean');
				$val->set_rules('password', 'lang:messages_member_information_form_password', 'trim|required|xss_clean|matches[confirm_password]');
				$val->set_rules('confirm_password', 'lang:messages_member_information_form_confirm_password', 'trim|required|xss_clean');
			}

			$val->set_rules('email', 'lang:messages_member_information_form_email', 'trim|xss_clean');
			$val->set_rules('name', 'lang:messages_member_information_form_name', 'trim|xss_clean');
			$val->set_rules('surname', 'lang:messages_member_information_form_surname', 'trim|xss_clean');
			$val->set_rules('id_number', 'lang:messages_member_information_form_id_number', 'trim|xss_clean');
			$val->set_rules('gender', 'lang:messages_member_information_form_gender', 'trim|xss_clean');
			$val->set_rules('address', 'lang:messages_member_information_form_address', 'trim|xss_clean');
			$val->set_rules('home_phone', 'lang:messages_member_information_form_home_phone', 'trim|xss_clean');
			$val->set_rules('work_phone', 'lang:messages_member_information_form_work_phone', 'trim|xss_clean');
			$val->set_rules('mobile_phone', 'lang:messages_member_information_form_mobile_phone', 'trim|xss_clean');
			$val->set_rules('fax_phone', 'lang:messages_member_information_form_fax_phone', 'trim|xss_clean');
			$val->set_rules('web_site', 'lang:messages_member_information_form_web_site', 'trim|xss_clean');

			if($val->run() === FALSE)
			{
				$this->template->set_master_template(tema() . 'uye/bilgi/index');
				$this->template->add_region('content');

				$this->template->add_region('baslik');
				$this->template->write('baslik', lang('messages_member_information_title'));

				$content_data['usr_ide_inf'] = user_ide_inf($this->dx_auth->get_user_id());
				$content_data['usr_adr_inf'] = user_adr_inf($this->dx_auth->get_user_id());

				$this->template->write_view('content', tema() . 'uye/bilgi/content', $content_data);
				$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/uyeislem.css');
				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.maskedinput-1.2.2.min.js');
				
					//SKOCH
		$this->template->add_css(APPPATH . 'views/' . tema() . 'css/jquery.countdown.css');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown.js');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown-tr.js');
	    $this->output->enable_profiler(false);
		//SKOCH

				$this->template->render();	
			} else {
				
				if($this->input->post('password') != '' AND set_value('password')) {
					if($this->dx_auth->change_password(set_value('old_password'), set_value('password'))) {
						$from = $this->config->item('site_ayar_email_cevapsiz');
						$subject = $this->lang->line('auth_account_subject');
						$mail_data['email'] 	= $this->input->post('email');
						$mail_data['password'] 	= set_value('password');
						$mail_data['adsoyad'] 	= $this->input->post('email');
						if(set_value('name') != '' AND set_value('surname') != '') {
							$mail_data['adsoyad'] = set_value('name') . ' ' . set_value('surname');
						}
						$message = $this->load->view(tema() . 'mail_sablon/uyelik/uye_sifre_degistirildi', $mail_data, true);
						$this->dx_auth->_email($this->input->post('eposta'), $from, $subject, $message);	
					}
				}

				$ide_inf  = array(
					'ide_adi'		=> set_value('name'),
					'ide_soy'		=> set_value('surname'),
					'ide_tckimlik'	=> set_value('id_number'),
					'ide_cins'		=> set_value('gender'),
					'ide_cep'		=> set_value('mobile_phone'),
					'ide_web_site'	=> set_value('web_site')
				);

				$snc_ide_inf = $this->uye_model->usr_ide_inf_update($ide_inf, $this->dx_auth->get_user_id());

				$adr_inf  = array(
					'adr_is_ack'	=> set_value('address'),
					'adr_is_tel1'	=> set_value('home_phone'),
					'adr_is_tel2'	=> set_value('work_phone'),
					'adr_is_fax'	=> set_value('fax_phone')
				);

				$snc_adr_inf = $this->uye_model->usr_adr_inf_update($adr_inf, $this->dx_auth->get_user_id());

				if($this->dx_auth->get_auth_error()) {
					$mesajlar['baslik'] = lang('messages_member_information_form_result_error');
					$mesajlar['icerik'] = $this->dx_auth->get_auth_error();
					$this->session->set_flashdata('mesajlar', $mesajlar);
					redirect('site/mesaj?tip=2&gd=true');
				} else {
					if($snc_ide_inf AND $snc_adr_inf) {
						$mesajlar['baslik'] = lang('messages_member_information_form_result_success');
						$mesajlar['icerik'] = '';
						$this->session->set_flashdata('mesajlar', $mesajlar);
						redirect('site/mesaj?tip=1&gd=true');
					} else {
						$mesajlar['baslik'] = lang('messages_member_information_form_result_error_2');
						$mesajlar['icerik'] = strtr(lang('messages_member_information_form_result_error_2_message'), array('{_url_}' => site_url('site/iletisim')));
						$this->session->set_flashdata('mesajlar', $mesajlar);
						redirect('site/mesaj?tip=2&gd=true');
					}
				}
			}
		}
	}
}
?>