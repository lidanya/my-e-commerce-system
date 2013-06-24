<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class urun_takip extends Public_Controller {

	/**
	 * Ürün Takip construct
	 *
	 * @return void
	 **/

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Ürün Takip Controller Yüklendi');

		$this->load->model('urun_takip_model');
	}

	/**
	 * index function
	 *
	 * @return void
	 * @author Serkan Koch, Serkan Koch -> E-Ticaret Sistemim
	 **/

	function index()
	{
		if($this->dx_auth->is_logged_in())
		{
			$this->template->set_master_template(tema() . 'uye/urun_takip/index');
			$this->template->add_region('content');

			$this->template->add_region('baslik');
			$this->template->write('baslik', lang('messages_member_product_title'));

			$user_id = $this->dx_auth->get_user_id();
			$content_data['urunler'] = $this->urun_takip_model->takip_listele($user_id);
			$this->template->write_view('content', tema() . 'uye/urun_takip/content', $content_data);

			$this->template->add_css(APPPATH. 'views/' . tema_asset() . 'css/uyeislem.css');
				//SKOCH
		$this->template->add_css(APPPATH . 'views/' . tema() . 'css/jquery.countdown.css');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown.js');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown-tr.js');
	    $this->output->enable_profiler(false);
		//SKOCH
			
			$this->template->render();
		} else {
			redirect('');
		}
	}

	function listeden_cik($stok_takip_id)
	{
		if($this->dx_auth->is_logged_in())
		{
			$user_id = $this->dx_auth->get_user_id();
			$durum = $this->urun_takip_model->takip_sil($user_id, $stok_takip_id);

			/* durumlar 1 silme başarılı 2 silme başarısız 3 kullanıcı eşleşmedi */
			if($durum == 1)
			{
				$mesajlar['baslik'] = lang('messages_member_product_detele_process_title');
				$mesajlar['icerik'] = lang('messages_member_product_detele_process_1');
				$this->session->set_flashdata('mesajlar', $mesajlar);
				redirect('site/mesaj?tip=1&gd=true');
			} elseif ($durum == 2) {
				$mesajlar['baslik'] = lang('messages_member_product_detele_process_title');
				$mesajlar['icerik'] = lang('messages_member_product_detele_process_2');
				$this->session->set_flashdata('mesajlar', $mesajlar);
				redirect('site/mesaj?tip=2&gd=true');
			} elseif ($durum == 3) {
				$mesajlar['baslik'] = lang('messages_member_product_detele_process_title');
				$mesajlar['icerik'] = lang('messages_member_product_detele_process_3');
				$this->session->set_flashdata('mesajlar', $mesajlar);
				redirect('site/mesaj?tip=2&gd=true');
			}
		} else {
			redirect('');
		}
	}

}

/* End of file isimsiz.php */
/*  */

?>