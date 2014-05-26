<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class Public_Controller extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$language = get_language('directory');
		$this->config->set_item('language', $language);

		$languages = array();
		foreach(get_languages() as $language) {
			if($language['status']) {
				$languages[$language['code']] = $language['directory'];
			}
		}
		$this->config->set_item('languages', $languages);
		$this->config->set_item('default_language', $this->config->item('site_ayar_dil'));
		$language = get_language('code');
		$this->config->set_item('current_language', $language);

		$this->load->language(array('common/header', 'common/footer', 'common/messages'));
		$this->load->model(array('site/product_model', 'site/category_model', 'site/information_model', 'yonetim/content_management/information_model'));
	}

}

class Face_Controller extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		if( ! config('site_ayar_facebook_status')) {
			redirect(site_url('site/index'));
		}

		$language = get_language('directory');
		$this->config->set_item('language', $language);

		$languages = array();
		foreach(get_languages() as $language) {
			if($language['status']) {
				$languages[$language['code']] = $language['directory'];
			}
		}
		$this->config->set_item('languages', $languages);
		$this->config->set_item('default_language', $this->config->item('site_ayar_dil'));
		$language = get_language('code');
		$this->config->set_item('current_language', $language);

		$this->load->library(array('facebook_lib'));
		$this->load->helper(array('face_app'));
		$this->load->language(array('common/header', 'common/footer', 'common/messages'));
		$this->load->model(array('site/product_model', 'site/category_model', 'site/manufacturer_model','site/information_model', 'yonetim/content_management/information_model'));

//		if ( ! config('facebook_app_status')) {
//			if ($this->uri->segment(4) != 'error') {
//				redirect(face_site_url('site/error'));
//			}
//		}
	}

}

class Admin_Controller extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$language = get_language('directory');
		$this->config->set_item('language', $language);

		$languages = array();
		foreach(get_languages() as $language) {
			if($language['status']) {
				$languages[$language['code']] = $language['directory'];
			}
		}
		$this->config->set_item('languages', $languages);
		$this->config->set_item('default_language', $this->config->item('site_ayar_dil'));
		$language = get_language('code');
		$this->config->set_item('current_language', $language);

		$this->load->model(array('yonetim/content_management/information_model'));
	}

}