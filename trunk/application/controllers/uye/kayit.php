<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class kayit extends Public_Controller {

	// Used for registering and changing password form validation
	var $min_username = 4;
	var $max_username = 20;
	var $min_password = 4;
	var $max_password = 20;

	/**
	 * Üye Kayıt construct
	 *
	 * @return void
	 **/

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Üye Kayıt Controller Yüklendi');
		$this->load->library('form_validation');
	}
	
	/* Callback function */
	function username_check($username)
	{
		$val = $this->form_validation;
		$result = $this->dx_auth->is_username_available($username);
		if ( ! $result) {
			$val->set_message('username_check', lang('messages_user_register_used_email'));
		}
		return $result;
	}

	function email_check($email)
	{
		$val = $this->form_validation;
		$result = $this->dx_auth->is_email_available($email);
		if ( ! $result) {
			$val->set_message('email_check', lang('messages_user_register_used_email'));
		}
		return $result;
	}

	function captcha_check($code)
	{
		$val = $this->form_validation;
		$result = TRUE;
		$this->load->model('uye_model');
		if ( ! $this->uye_model->captcha_check($code, 'uye_kayit') ) {
			$val->set_message('captcha_check', lang('messages_user_register_error_security_code'));
			$result = FALSE;
		}
		return $result;
	}

	function index()
	{		
		if ( ! $this->dx_auth->is_logged_in() AND $this->dx_auth->allow_registration) {	
			$val = $this->form_validation;

			$val->set_rules('email', 'lang:messages_user_register_form_email_text', 'trim|required|xss_clean|valid_email|callback_username_check|callback_email_check');
			$val->set_rules('password', 'lang:messages_user_register_form_password_text', 'trim|required|xss_clean|min_length['.$this->min_password.']|max_length['.$this->max_password.']|matches[confirm_password]');
			$val->set_rules('confirm_password', 'lang:messages_user_register_form_confirm_password_text', 'trim|required|xss_clean');
			$val->set_rules('adiniz', 'lang:messages_user_register_form_name_text', 'trim|required|xss_clean');
			$val->set_rules('soyadiniz', 'lang:messages_user_register_form_surname_text', 'trim|required|xss_clean');
			$val->set_rules('captcha', 'lang:messages_user_register_form_captcha_text', 'trim|required|xss_clean|callback_captcha_check');
			$val->set_rules('satis_sozlesmesi', 'lang:messages_user_register_form_agree_text', 'trim|required|xss_clean');

			$role_id = config('site_ayar_varsayilan_mus_grub') ? config('site_ayar_varsayilan_mus_grub') : 4;
			
			if ($val->run() AND $uye = $this->dx_auth->register($this->input->post('email'), $this->input->post('password'), $this->input->post('email'), false, $role_id)) {
				$bilgiler = array(
					'ide_adi'	=> $this->input->post('adiniz'),
					'ide_soy'	=> $this->input->post('soyadiniz')
				);
				$kontrol = $this->uye_model->usr_ide_inf_update($bilgiler, $uye['sonid']);
				if($kontrol) {
					$mesajlar['baslik'] = lang('messages_user_register_title');
					$mesajlar['icerik'] = strtr(lang('messages_user_register_success_message'), array('{name}' => $this->input->post('adiniz') . ' ' . $this->input->post('soyadiniz')));
					$this->session->set_flashdata('mesajlar', $mesajlar);
					redirect('site/mesaj?tip=1');
				} else {
					$mesajlar['baslik'] = lang('messages_user_register_title');
					$mesajlar['icerik'] = strtr(lang('messages_user_register_success_message_no_update'), array('{name}' => $this->input->post('adiniz') . ' ' . $this->input->post('soyadiniz')));
					$this->session->set_flashdata('mesajlar', $mesajlar);
					redirect('site/mesaj?tip=2');
				}
			} else {
				$content_data['val'] = $val;
				// Load registration page
				$this->template->set_master_template(tema() . 'uye/kayit/index');

				$this->template->add_region('baslik');
				$this->template->write('baslik', lang('messages_user_register_title'));

				$this->template->add_region('content');
				$this->template->write_view('content', tema() . 'uye/kayit/content', $content_data);
				$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/uyeislem.css');
		        
				$this->output->enable_profiler(false);
		
				$this->template->render();
			}
		}
		else {
			redirect(site_url('site/index'));
		}
	}
	
	function logout()
	{
		$this->dx_auth->logout();
		
		$data['auth_message'] = 'You have been logged out.';		
		$this->load->view($this->dx_auth->logout_view, $data);
	}

	function activate()
	{
		// Get username and key
		$username = $this->uri->segment(3);
		$key = $this->uri->segment(4);

		// Activate user
		if ($this->dx_auth->activate($username, $key)) 
		{
			$data['auth_message'] = 'Your account have been successfully activated. '.anchor(site_url($this->dx_auth->login_uri), 'Login');
			$this->load->view($this->dx_auth->activate_success_view, $data);
		}
		else
		{
			$data['auth_message'] = 'The activation code you entered was incorrect. Please check your email again.';
			$this->load->view($this->dx_auth->activate_failed_view, $data);
		}
	}
	
	function forgot_password()
	{
		$val = $this->form_validation;
		
		// Set form validation rules
		$val->set_rules('login', 'Username or Email address', 'trim|required|xss_clean');

		// Validate rules and call forgot password function
		if ($val->run() AND $this->dx_auth->forgot_password($val->set_value('login')))
		{
			$data['auth_message'] = 'An email has been sent to your email with instructions with how to activate your new password.';
			$this->load->view($this->dx_auth->forgot_password_success_view, $data);
		}
		else
		{
			$this->load->view($this->dx_auth->forgot_password_view);
		}
	}
	
	function reset_password()
	{
		// Get username and key
		$username = $this->uri->segment(3);
		$key = $this->uri->segment(4);

		// Reset password
		if ($this->dx_auth->reset_password($username, $key))
		{
			$data['auth_message'] = 'You have successfully reset you password, '.anchor(site_url($this->dx_auth->login_uri), 'Login');
			$this->load->view($this->dx_auth->reset_password_success_view, $data);
		}
		else
		{
			$data['auth_message'] = 'Reset failed. Your username and key are incorrect. Please check your email again and follow the instructions.';
			$this->load->view($this->dx_auth->reset_password_failed_view, $data);
		}
	}
	
	function change_password()
	{
		// Check if user logged in or not
		if ($this->dx_auth->is_logged_in())
		{			
			$val = $this->form_validation;
			
			// Set form validation
			$val->set_rules('old_password', 'Old Password', 'trim|required|xss_clean|min_length['.$this->min_password.']|max_length['.$this->max_password.']');
			$val->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->min_password.']|max_length['.$this->max_password.']|matches[confirm_new_password]');
			$val->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean');
			
			// Validate rules and change password
			if ($val->run() AND $this->dx_auth->change_password($val->set_value('old_password'), $val->set_value('new_password')))
			{
				$data['auth_message'] = 'Your password has successfully been changed.';
				$this->load->view($this->dx_auth->change_password_success_view, $data);
			}
			else
			{
				$this->load->view($this->dx_auth->change_password_view);
			}
		}
		else
		{
			// Redirect to login page
			$this->dx_auth->deny_access('login');
		}
	}	
	
	function cancel_account()
	{
		// Check if user logged in or not
		if ($this->dx_auth->is_logged_in())
		{			
			$val = $this->form_validation;
			
			// Set form validation rules
			$val->set_rules('password', 'Password', "trim|required|xss_clean");
			
			// Validate rules and change password
			if ($val->run() AND $this->dx_auth->cancel_account($val->set_value('password')))
			{
				// Redirect to homepage
				redirect(site_url('site/index'), 'location');
			}
			else
			{
				$this->load->view($this->dx_auth->cancel_account_view);
			}
		}
		else
		{
			// Redirect to login page
			$this->dx_auth->deny_access('login');
		}
	}
}

/* End of file isimsiz.php */
/*  */

?>