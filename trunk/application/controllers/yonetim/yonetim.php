<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class yonetim extends Admin_Controller
{
	function __construct()
	{
		parent::__construct();

		giris_kontrol();
		$this->output->enable_profiler(FALSE);
	}

	function index()
	{
		giris_kontrol();
		$this->load->view('yonetim/main_view');
	}
}

?>
