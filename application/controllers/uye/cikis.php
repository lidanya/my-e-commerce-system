<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author Daynex.com.tr
 **/

class cikis extends Public_Controller {

	/**
	 * Üye Çıkış construct
	 *
	 * @return void
	 **/

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Üye Çıkış Controller Yüklendi');
	}

	/**
	 * index function
	 *
	 * @return void
	 * @author Ali Çetin, Ahmet Çelebigil -> Daynex.com.tr
	 **/

	function index()
	{
		$this->dx_auth->logout();
		if(config('site_ayar_facebook_status') AND config('site_ayar_facebook_app_id') != '' AND config('site_ayar_facebook_secret') != '') {
			$this->load->library('facebook_lib');
			if ($this->facebook_lib->user) {
				redirect($this->facebook_lib->fb->getLogoutUrl(array('next' => site_url('site/index'))));
			}
		}
		redirect(site_url('site/index'));
	}

}

/* End of file isimsiz.php */
/* Location: ./daynex/application/controllers/isimsiz.php */

?>