<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class giris extends Face_Controller {

	/**
	 * Üye Giriş construct
	 *
	 * @return void
	 * @author Serkan Koch, Serkan Koch -> E-Ticaret Sistemim
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
						if ($this->session->userdata('face_redirect')) {
							$this->load->view(face_tema() . 'redirect', array('redirect' => face_site_url($this->session->userdata('face_redirect'))));
						} else {
							$this->load->view(face_tema() . 'redirect', array('redirect' => face_site_url('site/index')));
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
							if ($this->session->userdata('face_redirect')) {
								$this->load->view(face_tema() . 'redirect', array('redirect' => face_site_url($this->session->userdata('face_redirect'))));
							} else {
								$this->load->view(face_tema() . 'redirect', array('redirect' => face_site_url('site/index')));
							}
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
								if ($this->session->userdata('face_redirect')) {
									$this->load->view(face_tema() . 'redirect', array('redirect' => face_site_url($this->session->userdata('face_redirect'))));
								} else {
									$this->load->view(face_tema() . 'redirect', array('redirect' => face_site_url('site/index')));
								}
							} else {
								$this->load->view(face_tema() . 'redirect', array('redirect' => face_site_url('site/index')));
							}
						}
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
						if ($this->session->userdata('face_redirect')) {
							$this->load->view(face_tema() . 'redirect', array('redirect' => face_site_url($this->session->userdata('face_redirect'))));
						} else {
							$this->load->view(face_tema() . 'redirect', array('redirect' => face_site_url('site/index')));
						}
					} else {
						$this->load->view(face_tema() . 'redirect', array('redirect' => face_site_url('site/index')));
					}
				}
			} else {
				$this->load->view(face_tema() . 'redirect', array('redirect' => $this->facebook_lib->fb->getLoginUrl(array('req_perms' => 'user_birthday,email', 'next' => face_site_url('uye/giris/facebook')))));
			}
		} else {
			$this->load->view(face_tema() . 'redirect', array('redirect' => face_site_url('site/index')));
		}
	}

}

/* End of file isimsiz.php */
/*  */

?>