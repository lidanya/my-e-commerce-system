<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class sifre_hatirlat extends Public_Controller
{
	function __construct()
	{
		parent::__construct();
	}	
	
	function captcha_check($code)
	{
		$result = TRUE;
		$this->load->model('uye_model');
		if ( ! $this->uye_model->captcha_check($code, 'sifre_hatirlat') )
		{
			$this->validation->set_message('captcha_check', lang('messages_member_forget_password_error_captcha'));
			$result = FALSE;
		}

		return $result;
	}


	function email_check($email)
	{
		$result = $this->dx_auth->is_email_available($email);
		if ( ! $result)
		{
			$result = TRUE;
		} else {
			$this->validation->set_message('email_check', lang('messages_member_forget_password_error_email'));
			$result = FALSE;
		}
				
		return $result;
	}
	
	function index()
	{
		$val = $this->validation;
		$rules['eposta'] 		= 'trim|required|xss_clean|valid_email|callback_email_check';
		$rules['captcha']		= 'trim|required|xss_clean|callback_captcha_check';
		
		$fields['eposta']		= lang('messages_member_forget_password_email');
		$fields['captcha']		= lang('messages_member_forget_password_security_code');
		$val->set_rules($rules);
		$val->set_fields($fields);
		if($val->run() == FALSE)
		{
			$this->template->set_master_template(tema() . 'uye/sifre_hatirlat/index');
			$this->template->add_region('baslik');
			$this->template->write('baslik', lang('messages_member_forget_password_title'));
			$this->template->add_region('content');
			$content_data['val'] = $val;
			$this->template->write_view('content', tema() . 'uye/sifre_hatirlat/content',$content_data);
			$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/uyeislem.css');
			
			$this->output->enable_profiler(false);
			$this->template->render();
		} else {
			$kontrol = $this->dx_auth->forgot_password($val->eposta);
			if($kontrol)
			{
				$mesajlar['baslik'] = strtr(lang('messages_member_forget_password_success_title'), array('{_email_}' => $val->eposta));
				$mesajlar['icerik'] = lang('messages_member_forget_password_success_message');
				$this->session->set_flashdata('mesajlar', $mesajlar);
				redirect('site/mesaj?tip=1');
			} else {
				$mesajlar['baslik'] = lang('messages_member_forget_password_error_title');
				$mesajlar['icerik'] = strtr(lang('messages_member_forget_password_error_message'), array('{_url_}' => site_url('site/iletisim')));
				$this->session->set_flashdata('mesajlar', $mesajlar);
				redirect('site/mesaj?tip=2');
			}
			
		}

	}

}