<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class bosalt extends Public_Controller {

	/**
	 * Sepet Boşalt construct
	 *
	 * @return void
	 **/

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Sepet Boşalt Controller Yüklendi');
	}

	function tumu()
	{
		$this->cart->destroy();
		ssl_redirect('sepet/goster');
	}

}

/* End of file isimsiz.php */
/*  */

?>