<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class facebook_lib
{
	public $ci, $fb, $user, $message;

	function __construct()
	{
		log_message('debug', 'Facebook Library Initialized');

		$this->ci =& get_instance();

		$this->ci->load->helper('facebook');
		$facebook_status = config('site_ayar_facebook_status');
		$facebook_app_id = config('site_ayar_facebook_app_id');
		$facebook_secret = config('site_ayar_facebook_secret');

		if( ! $facebook_status OR ! $facebook_app_id OR ! $facebook_secret) {
			$this->user = false;
			$this->message = 'Uygulama ayarlarınız hatalı yada uygulamanız yok. Lütfen '.anchor('http://www.facebook.com/developers/createapp.php', 'http://www.facebook.com/developers/createapp.php').' adresinden uygulama oluşturup bilgilerinizi girin.';
		} else {
			// Create the Facebook object
			$this->fb = new Facebook(array(
				'appId' => $facebook_app_id,
				'secret' => $facebook_secret,
				'cookie' => true
			));
			// Check for Facebook session
			if ($this->fb->getUser()) {
				try {
					// Check for expired session by making a api call
					$this->user = $this->fb->api('/me');
				} catch (FacebookApiException $e) {
					error_log($e);
				}
			}
		}
	}

	public function get_userdata()
	{
		return $this->user;
	}

	public function get_message()
	{
		return $this->message;
	}

}