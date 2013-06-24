<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class goster extends Public_Controller {

	/**
	 * Sepet Göster construct
	 *
	 * @return void
	 **/

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Sepet Göster Controller Yüklendi');
	}

	function index()
	{
		$this->template->set_master_template(tema() . 'sepet/index');

		$this->template->add_region('baslik');
		$this->template->write('baslik', lang('messages_cart_title'));

		$this->template->add_region('content');
		$this->template->write_view('content', tema() . 'sepet/content');
		$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/sepet.css');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.maskedinput-1.2.2.min.js');
		
		//SKOCH
		$this->template->add_css(APPPATH . 'views/' . tema() . 'css/jquery.countdown.css');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown.js');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown-tr.js');
	    $this->output->enable_profiler(false);
		//SKOCH

		$this->output->enable_profiler(false);
		$this->template->render();
	}

}
?>