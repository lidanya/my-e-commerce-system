<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class statistic extends Admin_Controller {

	/**
	 * İstatistik construct
	 *
	 * @return void
	 **/

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'İstatistik Controller Yüklendi');

		giris_kontrol();
	}

	public function chart_change()
	{
		$type = $this->input->post('type');
		$redirect = $this->input->post('redirect');
		if ( ! in_array($type, array('daily', 'weekly', 'monthly', 'yearly'))) {
			$type = 'daily';
		}
		$this->session->set_userdata('chart_type', $type);
		redirect($redirect);
	}
}

/* End of file isimsiz.php */
/*  */

?>