<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class siparisler extends Public_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('siparis_model');
	}
	
	function index()
	{
		if($this->dx_auth->is_logged_in())
		{
			$this->template->set_master_template(tema() . 'uye/siparis/index');

			$user_id = $this->dx_auth->get_user_id();
			$content_data['siparisler'] = $this->siparis_model->siparis_getir($user_id);

			$this->template->add_region('baslik');
			$this->template->write('baslik', lang('messages_member_orders_title'));

			$this->template->add_region('content');
			$this->template->write_view('content', tema() . 'uye/siparis/content', $content_data);

			$this->template->add_css(APPPATH. 'views/' . tema_asset() . 'css/uyeislem.css');
		
			$this->template->render();
		}
	}
	
	function detay($siparis_id)
	{
		if($this->dx_auth->is_logged_in())
		{
			$user_id = $this->dx_auth->get_user_id();
			$sorgu = $this->db->get_where('siparis', array('user_id' => $user_id, 'siparis_id' => $siparis_id, 'siparis_flag !=' => '-1'), 1);
			if($sorgu->num_rows() > 0)
			{
				$this->template->set_master_template(tema() . 'uye/siparis/detay');

				$this->template->add_region('baslik');
				$this->template->write('baslik', lang('messages_member_order_detail_title'));

				$content_data['siparis_detay'] = $this->siparis_model->siparis_detay($siparis_id);
				$content_data['siparis_bilgi'] = $sorgu->row();

				$this->template->add_region('content');
				$this->template->write_view('content', tema() . 'uye/siparis/detay_content',$content_data);
				$this->template->add_css(APPPATH. 'views/' . tema_asset() . 'css/uyeislem.css');
	    
				$this->output->enable_profiler(false);
				$this->template->render();
			} else {
				redirect('uye/siparisler');
			}
		} else {
			redirect('uye/giris');
		}
	}
}
?>