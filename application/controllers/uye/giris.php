<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class giris extends Public_Controller {

	/**
	 * Üye Giriş construct
	 *
	 * @return void
	 **/

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Üye Giriş Controller Yüklendi');

		$this->load->library('validation');
	}

	/**
	 * index function
	 *
	 * @return void
	 * @author Serkan Koch, Serkan Koch -> E-Ticaret Sistemim
	 **/

	function index()
	{
		if ( ! $this->dx_auth->is_logged_in())
		{
			$val = $this->validation;

			$rules['email']			= 'trim|required|xss_clean';
			$rules['password']		= 'trim|required|xss_clean';
			$rules['remember']		= 'integer';

			$fields['email']		= lang('messages_member_login_email');
			$fields['password']		= lang('messages_member_login_password');
			$fields['remember']		= lang('messages_member_login_remember');

			$val->set_rules($rules);
			$val->set_fields($fields);

			$val->set_error_delimiters('', '<br>');

			if ($val->run() AND $this->dx_auth->login($val->email, $val->password, $val->remember))
			{
				// Redirect to homepage
				redirect(site_url('site/index'), 'location');
			}
			else
			{
				// Check if the user is failed logged in because user is banned user or not
				if ($this->dx_auth->is_banned())
				{
					// Redirect to banned uri
					$this->dx_auth->deny_access('banned');
				}
				else
				{
					$content_data['val'] = $val;
					// Load registration page
					$this->template->set_master_template(tema() . 'uye/giris/index');

					$this->template->add_region('baslik');
					$this->template->write('baslik', lang('messages_member_login_title'));

					$this->template->add_region('content');
					$this->template->write_view('content', tema() . 'uye/giris/content', $content_data);
					$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/uyeislem.css');
					
					$this->output->enable_profiler(false);
					$this->template->render();
				}
			}
		}
		else
		{
			redirect(site_url('site/index'));
		}
	}

	function captcha_check($code)
	{
		$result = TRUE;
		$this->load->model('uye_model');
		if ( ! $this->uye_model->captcha_check($code, 'uye_giris') )
		{
			$this->validation->set_message('captcha_check', 'Girdiğiniz doğrulama kodu yanlış. Lütfen tekrar deneyiniz.');
			$result = FALSE;
		}

		return $result;
	}

	public function facebook()
	{
		if(config('site_ayar_facebook_status') AND config('site_ayar_facebook_app_id') != '' AND config('site_ayar_facebook_secret') != '')
		{
			$this->load->library('facebook_lib');
			$this->load->model('facebook_model');

			// Check if user is signed in on facebook
			if ($this->facebook_lib->user)
			{
				if( ! $this->dx_auth->is_logged_in())
				{
					// Check if user has connect facebook to dx_auth
					$user = $this->facebook_model->get_by_facebook_id($this->facebook_lib->user['id']);
					if ($user)
					{
						// Run sign in routine
						$this->dx_auth->login_by_id($user->user_id);
						redirect(site_url('site/index'));
					} else {
						$email = $this->facebook_lib->user['email'];
						$inf = $this->dx_auth->check_user_by_email($email);
						if($inf) {
							$user_id = $inf->id;

							$birthday = explode('/', $this->facebook_lib->user['birthday']);
							$_birthday = $birthday[2] . '-' . $birthday[0] . '-' . $birthday[1];

							$this->db->update('usr_ide_inf', array(
								'ide_adi'		=> $this->facebook_lib->user['first_name'],
								'ide_soy'		=> $this->facebook_lib->user['last_name'],
								'ide_dogtar'	=> $_birthday,
								'ide_cins'		=> ($this->facebook_lib->user['gender'] == 'female') ? 'k' : 'e'
							), array('user_id' => (int) $user_id));

							$this->facebook_model->insert($user_id, $this->facebook_lib->user['id']);

							// Run sign in routine
							$this->dx_auth->login_by_id($user_id);

							redirect(site_url('site/index'));
						} else {
							$this->load->helper('string');

							$username = $this->facebook_lib->user['email'];
							$email = $this->facebook_lib->user['email'];
							$password = random_string('alnum', 8);
							$_user = $this->dx_auth->register($username, $password, $email, $activation = false, $roleID = false);
							if($_user) {
								$user_id = $_user['sonid'];

								$birthday = explode('/', $this->facebook_lib->user['birthday']);
								$_birthday = $birthday[2] . '-' . $birthday[0] . '-' . $birthday[1];

								$this->db->update('usr_ide_inf', array(
									'ide_adi'		=> $this->facebook_lib->user['first_name'],
									'ide_soy'		=> $this->facebook_lib->user['last_name'],
									'ide_dogtar'	=> $_birthday,
									'ide_cins'		=> ($this->facebook_lib->user['gender'] == 'female') ? 'k' : 'e'
								), array('user_id' => (int) $user_id));

								$this->facebook_model->insert($user_id, $this->facebook_lib->user['id']);

								// Run sign in routine
								$this->dx_auth->login_by_id($user_id);

								redirect(site_url('site/index'));
							}
						}
						redirect(site_url('site/index'));
					}
				} else {
					$email = $this->facebook_lib->user['email'];
					$inf = $this->dx_auth->check_user_by_email($email);
					if($inf) {
						$user_id = $inf->id;

						$birthday = explode('/', $this->facebook_lib->user['birthday']);
						$_birthday = $birthday[2] . '-' . $birthday[0] . '-' . $birthday[1];

						$this->db->update('usr_ide_inf', array(
							'ide_adi'		=> $this->facebook_lib->user['first_name'],
							'ide_soy'		=> $this->facebook_lib->user['last_name'],
							'ide_dogtar'	=> $_birthday,
							'ide_cins'		=> ($this->facebook_lib->user['gender'] == 'female') ? 'k' : 'e'
						), array('user_id' => (int) $user_id));

						$this->facebook_model->insert($user_id, $this->facebook_lib->user['id']);

						// Run sign in routine
						$this->dx_auth->login_by_id($user_id);

						redirect(site_url('site/index'));
					}
				}
			} else {
				redirect($this->facebook_lib->fb->getLoginUrl(array('req_perms' => 'user_birthday,email', 'next' => current_url())));
			}

			redirect(site_url('site/index'));
		}
		redirect(site_url('site/index'));
	}

}

/* End of file isimsiz.php */
/*  */

?>