<?php 

if (! defined('BASEPATH')) exit('No direct script access');

class face_app extends Face_Controller 
{

	function __construct() 
	{
		parent::__construct();
	}

	function index() 
	{
		$this->template->set_master_template(face_tema() . 'index/index');
		$this->template->add_region('content');
		$this->template->write_view('content', face_tema() . 'index/content');
		$this->template->add_css(APPPATH . 'views/' . face_tema_asset() . 'css/anasayfa.css');

		$this->template->render();
	}

}

/* End of file class_name.php */
/* Location: ./application/controllers/class_name.php */