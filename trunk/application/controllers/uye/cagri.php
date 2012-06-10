<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author Daynex.com.tr
 **/

class cagri extends Public_Controller {
	
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Üye Çağrı Controller Yüklendi');
		$this->load->model('cagri_model');
		$this->load->library('form_validation');
	}
	
	function index($sayfa = 0)
	{
		$this->template->set_master_template(tema() . 'uye/cagri/index');
		$this->template->add_region('content');

		$this->template->add_region('baslik');
		$this->template->write('baslik', lang('messages_member_tickets_title'));

		$content_data = array();
		$content_data['cagrilar'] = $this->cagri_model->cagri_liste($sayfa);
		$this->template->write_view('content', tema() . 'uye/cagri/content', $content_data);
		$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/uyeislem.css');
		
			//SKOCH
		$this->template->add_css(APPPATH . 'views/' . tema() . 'css/jquery.countdown.css');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown.js');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown-tr.js');
	    $this->output->enable_profiler(false);
		//SKOCH

		$this->template->render();
	}
	
	function cagri_goster($ticket_id)
	{
		$val = $this->form_validation;
		if ($this->input->post('kapat') == '2') {
			$val->set_rules('ticket_mesaj', 'lang:messages_member_tickets_form_message', 'trim|xss_clean');
			$val->set_rules('ticket_tip', 'lang:messages_member_tickets_form_type', 'trim|xss_clean');
			$val->set_rules('ticket_prm', 'lang:messages_member_tickets_form_prm', 'trim|xss_clean');
		} else {
			$val->set_rules('ticket_mesaj', 'lang:messages_member_tickets_form_message', 'trim|required|xss_clean');
			$val->set_rules('ticket_tip', 'lang:messages_member_tickets_form_type', 'trim|required|xss_clean');
			$val->set_rules('ticket_prm', 'lang:messages_member_tickets_form_prm', 'trim|required|xss_clean');
		}

		if($val->run() == FALSE) {
			$this->template->set_master_template(tema() . 'uye/cagri/cagri_goster');
			$this->template->add_region('content');

			$this->template->add_region('baslik');
			$this->template->write('baslik', lang('messages_member_tickets_title'));

			$content_data = array();
			$content_data['cagri'] = $this->cagri_model->cagri_goster($ticket_id);
			$this->template->write_view('content', tema() . 'uye/cagri/cagri_goster_content', $content_data);
			$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/uyeislem.css');
			$this->template->render();
		} else {
			$this->cagri_model->cagri_yaz();
			if($this->input->post('kapat') == '2') {
				$this->cagri_model->cagri_durum($this->input->post('ticket_prm'));
				$mesajlar['baslik'] = lang('messages_member_tickets_result_ticket_closed');
				$mesajlar['icerik'] = lang('messages_member_tickets_result_ticket_closed_message');
				$this->session->set_flashdata('mesajlar', $mesajlar);
				redirect('site/mesaj?tip=1&gd=false');
			}

			$mesajlar['baslik'] = lang('messages_member_tickets_result_ticket_success');
			$mesajlar['icerik'] = strtr(lang('messages_member_tickets_result_ticket_success_message'), array('{_url_}' => site_url('uye/cagri')));
			$this->session->set_flashdata('mesajlar', $mesajlar);
		 	redirect('site/mesaj?tip=1&gd=false');
		}
	}
}
?>
