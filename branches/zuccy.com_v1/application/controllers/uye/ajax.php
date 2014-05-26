<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class ajax extends Public_Controller {

	// Used for registering and changing password form validation
	var $min_username = 4;
	var $max_username = 20;
	var $min_password = 4;
	var $max_password = 20;

	/**
	 * Üye Ajax construct
	 *
	 * @return void
	 **/

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Üye Ajax Controller Yüklendi');

		$this->load->model('uye_model');
	}

	function kayit()
	{
		$val = $this->validation;

		$sonuc = NULL;

		$rules['email']				= 'trim|required|xss_clean|valid_email|callback_username_check|callback_email_check';
		$rules['password']			= 'trim|required|xss_clean|min_length['.$this->min_password.']|max_length['.$this->max_password.']|matches[confirm_password]';
		$rules['confirm_password']	= 'trim|required|xss_clean';
		$rules['adiniz']			= 'trim|required|xss_clean';
		$rules['soyadiniz']			= 'trim|required|xss_clean';
		$rules['captcha']			= 'trim|required|xss_clean|callback_captcha_check_kayit';
		$rules['satis_sozlesmesi']	= 'trim|required|numeric|xss_clean';

		$fields['email']			= 'E-Posta Adresi';
		$fields['password']			= 'Şifre';
		$fields['confirm_password']	= 'Tekrar Şifre';
		$fields['adiniz']			= 'Adınız';
		$fields['soyadiniz']		= 'Soyadınız';
		$fields['captcha']			= 'Güvanlik Kodu';
		$fields['satis_sozlesmesi']	= 'Satış Sözleşmesi';

		$val->set_rules($rules);
		$val->set_fields($fields);

		$val->set_error_delimiters('', '<br>');

		$sonuc['basarili']			= NULL;
		$sonuc['basarisiz']			= NULL;

		// Run form validation and register user if it's pass the validation
		if ($val->run() AND $this->uye_model->ajax_uye_kayit($val))
		{
			$sonuc['basarili']	= 'Üyelik işleminiz başarılı bir şekilde gerçekleşmiştir.';
		}
		else
		{
			if ($val->error_string)
			{
				$sonuc['basarisiz']	= $val->error_string;
			}
		}
		$this->output->set_output(json::encode($sonuc));
	}

	function kayit_v2()
	{
		$this->load->helper('string');
		$val = $this->validation;

		$sonuc = NULL;

		$rules['email']				= 'trim|required|xss_clean|valid_email|callback_username_check|callback_email_check';
		$rules['name']				= 'trim|required|xss_clean';
		$rules['captcha']			= 'trim|required|xss_clean|callback_captcha_check_kayit';
		$rules['agree']				= 'trim|required|numeric|xss_clean';

		$fields['email']			= lang('messages_checkout_user_check_fast_buy_form_email_text');
		$fields['name']				= lang('messages_checkout_user_check_fast_buy_form_name_text');
		$fields['captcha']			= lang('messages_checkout_user_check_fast_buy_form_captcha_text');
		$fields['agree']			= lang('messages_checkout_user_check_fast_buy_form_agree_text');

		$val->set_rules($rules);
		$val->set_fields($fields);

		$val->set_error_delimiters('', '');

		$sonuc['basarili']			= '';
		$sonuc['basarisiz']			= '';
		$sonuc['email_error']		= '';
		$sonuc['name_error']		= '';
		$sonuc['captcha_error']		= '';
		$sonuc['agree_error']		= '';

		$find_name					= ' ';
		$search						= strpos($val->name, $find_name);
		$name						= ($search) ? mb_substr($val->name, 0, $search)		: $val->name;
		$surname					= ($search) ? mb_substr($val->name, $search + 1)	: NULL;

		$send->email				= $val->email;
		$send->password				= random_string('alnum', 6);
		$send->adiniz				= $name;
		$send->soyadiniz			= $surname;
		$send->captcha				= $val->captcha;
		$send->satis_sozlesmesi		= $val->agree;

		// Run form validation and register user if it's pass the validation
		if ($val->run() AND $this->uye_model->ajax_uye_kayit($send)) {
			$sonuc['basarili']	= 'Üyelik işleminiz başarılı bir şekilde gerçekleşmiştir.';
		} else {
			if ($val->error_string) {
				$sonuc['basarisiz']	= $val->error_string;

				foreach($val as $key => $value) {
					if(strpos($key, '_error')) {
						$sonuc[$key] = $value;
					}
				}
			}
		}
		$this->output->set_output(json::encode($sonuc));
	}

	function giris()
	{
		$val = $this->validation;

		$sonuc = NULL;

		$rules['email']				= 'trim|required|valid_email|xss_clean';
		$rules['password']			= 'trim|required|xss_clean';
		$rules['remember']			= 'integer';
		$rules['captcha']			= 'trim|required|xss_clean|callback_captcha_check_giris';

		$fields['email']			= 'E-Posta Adresi';
		$fields['password']			= 'Şifre';
		$fields['remember']			= 'Beni Hatırla';
		$fields['captcha']			= 'Güvanlik Kodu';

		$val->set_rules($rules);
		$val->set_fields($fields);

		$val->set_error_delimiters('', '<br>');

		// Run form validation and register user if it's pass the validation
		if ($val->run() AND $this->uye_model->ajax_uye_giris($val))
		{
			$sonuc['basarili']	= 'Üyelik girişiniz başarılı bir şekilde gerçekleşmiştir.';
		}
		else
		{
			if ($val->error_string || $this->dx_auth->get_auth_error())
			{
				$sonuc['basarisiz']	= $val->error_string . $this->dx_auth->get_auth_error();
			}
		}
		$this->output->set_output(json::encode($sonuc));
	}

	function giris_v2()
	{
		$val = $this->validation;

		$sonuc = NULL;

		$rules['email']				= 'trim|required|valid_email|xss_clean';
		$rules['password']			= 'trim|required|xss_clean';
		$rules['agree']				= 'trim|required|numeric|xss_clean';

		$fields['email']			= lang('messages_checkout_user_check_login_form_email_text');
		$fields['password']			= lang('messages_checkout_user_check_login_form_password_text');
		$fields['agree']			= lang('messages_checkout_user_check_login_form_agree_text');

		$val->set_rules($rules);
		$val->set_fields($fields);

		$val->set_error_delimiters('', '');

		$sonuc['basarili']			= '';
		$sonuc['basarisiz']			= '';
		$sonuc['email_error']		= '';
		$sonuc['password_error']	= '';
		$sonuc['agree_error']		= '';

		$send->email				= $val->email;
		$send->password				= $val->password;
		$send->remember				= 1;

		// Run form validation and register user if it's pass the validation
		if ($val->run() AND $this->uye_model->ajax_uye_giris($send)) {
			$sonuc['basarili']	= 'Üyelik işleminiz başarılı bir şekilde gerçekleşmiştir.';
		} else {
			if ($val->error_string || $this->dx_auth->get_auth_error())
			{
				$sonuc['basarisiz']	= $this->dx_auth->get_auth_error();

				foreach($val as $key => $value) {
					if(strpos($key, '_error')) {
						$sonuc[$key] = $value;
					}
				}
			}
		}
		$this->output->set_output(json::encode($sonuc));
	}

	/* Callback function */
	
	function username_check($username)
	{
		$result = $this->dx_auth->is_username_available($username);
		if ( ! $result)
		{
			$this->validation->set_message('username_check', lang('messages_form_error_valid_email'));
		}
				
		return $result;
	}

	function email_check($email)
	{
		$result = $this->dx_auth->is_email_available($email);
		if ( ! $result)
		{
			$this->validation->set_message('email_check', lang('messages_form_error_valid_email'));
		}
				
		return $result;
	}

	function captcha_check_giris($code)
	{
		$result = TRUE;
		if ( ! $this->uye_model->captcha_check($code, 'uye_giris') )
		{
			$this->validation->set_message('captcha_check_giris', lang('messages_form_error_captcha'));
			$result = FALSE;
		}

		return $result;
	}

	function captcha_check_kayit($code)
	{
		$result = TRUE;
		if ( ! $this->uye_model->captcha_check($code, 'uye_kayit') )
		{
			$this->validation->set_message('captcha_check_kayit', lang('messages_form_error_captcha'));
			$result = FALSE;
		}

		return $result;
	}

}

/* End of file isimsiz.php */
/*  */

?>