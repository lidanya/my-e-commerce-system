<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class adim_4 extends Public_Controller {

	/**
	 * isimsiz construct
	 *
	 * @return void
	 **/

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Ödeme Adım 4 Controller Yüklendi');
		
		//$this->session->set_userdata('adim_4',FALSE);
		
		$this->load->library('validation');
		$this->load->library('form_validation');
		
		
	}
	
	private function step_control(){
		if (!$this->session->userdata('adim_3')) {
			redirect('404');
		}
	}

	function belirle()
	{
		$siparis_id = $this->input->post('siparis_id');

		if($this->dx_auth->is_logged_in()) {
			$user_id = $this->dx_auth->get_user_id();

			$siparis_sorgu = $this->db->get_where('siparis', array('siparis_id' => $siparis_id, 'user_id' => $user_id, 'siparis_flag' => '-1'));
			if($siparis_sorgu->num_rows()) {
				$odeme_secenegi = $this->input->post('odeme_secenegi');
				$fatura_id = $this->input->post('fatura_id');
				$this->session->set_userdata('adim_3',TRUE);
				ssl_redirect('odeme/adim_4/' . $odeme_secenegi . '/' . $siparis_id . '/' . $fatura_id);
				
			} else {
				ssl_redirect('uye/siparisler');
			}
		} else {
			redirect('');
		}
	}

	function kredi_karti($siparis_id, $fatura_id)
	{
		
		self::step_control();
		
		$this->output->set_header("Pragma: no-cache");
		$this->output->set_header("Expires: now");
		if($this->dx_auth->is_logged_in()) {
			$user_id = $this->dx_auth->get_user_id();
			$siparis_sorgu = $this->db->get_where('siparis', array('siparis_id' => $siparis_id, 'user_id' => $user_id, 'siparis_flag' => '-1'));
			if($siparis_sorgu->num_rows()) {
				$odeme_sorgu = $this->db->get_where('odeme_secenekleri', array('odeme_model' => 'kredi_karti'), 1);
				if($odeme_sorgu->num_rows()) {
					$_siparis_detay = $this->session->userdata('siparis_detay');
					$_siparis_detay['odeme_adim'] = 'detay';
					$this->session->set_userdata('siparis_detay', $_siparis_detay);

					$toplam_kdv_fiyati = 0;
					$this->db->select('stok_kdv_orani, stok_tfiyat');
					$siparis_detay_sorgu = $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_id));
					foreach($siparis_detay_sorgu->result() as $siparis_detay) {
						$toplam_kdv_fiyati += kdv_hesapla($siparis_detay->stok_tfiyat, $siparis_detay->stok_kdv_orani, true);
					}
					$content_data['toplam_kdv_fiyati'] = $toplam_kdv_fiyati;

					$_siparis_detay = $this->session->userdata('siparis_detay');

					$secenek_bilgi = $odeme_sorgu->row();
					$content_data['secenek_bilgi'] 	= $secenek_bilgi;
					$content_data['siparis_bilgi'] 	= $siparis_sorgu->row();
					$content_data['siparis_id']		= $siparis_id;
					$content_data['fatura_id']		= $fatura_id;
					$content_data['siparis_detay']	= $_siparis_detay;

					if(array_key_exists('kargo_id', $_siparis_detay) AND array_key_exists('kargo_ucret', $_siparis_detay)) {
						$content_data['kargo_ucret'] = $_siparis_detay['kargo_ucret'];
					} else {
						$content_data['kargo_ucret'] = '0';
					}

					if(array_key_exists('kupon_indirim', $_siparis_detay) AND array_key_exists('fiyat', $_siparis_detay['kupon_indirim'])) {
						$content_data['kupon_ucret'] = $_siparis_detay['kupon_indirim']['fiyat'];
					} else {
						$content_data['kupon_ucret'] = '0';
					}

					$this->template->set_master_template(tema() . 'odeme/adim_4/kredi_karti/form/index');
					/* Sayfa Tanımlamaları */
					$this->template->add_region('baslik');
					$this->template->write('baslik', lang('messages_checkout_title_payment_details'));

					$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/sepet.css');
					$this->template->add_css(APPPATH . 'views/' . tema() . 'css/jquery.keypad.css');

					$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.maskedinput-1.2.2.min.js');
					$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.keypad.min.js');
					$this->template->add_js(APPPATH . 'views/' . tema() . 'js/sa_tip-1.0.1.min.js');
					$this->template->add_js(APPPATH . 'views/' . tema() . 'js/odeme_adimlari.js');

					$this->template->add_region('content');
					$this->template->write_view('content', tema() . 'odeme/adim_4/kredi_karti/form/content', $content_data);

					$this->session->set_userdata('adim_4',TRUE);
					/* Sayfa Tnımlamaları */
					$this->template->render();
				} else {
					ssl_redirect('uye/siparisler');
				}
			} else {
				ssl_redirect('uye/siparisler');
			}
		} else {
			redirect('');
		}
	}
	
	function havale($siparis_id, $fatura_id)
	{
		self::step_control();
		
		if($this->dx_auth->is_logged_in()) {
			$user_id = $this->dx_auth->get_user_id();
			$siparis_sorgu = $this->db->get_where('siparis', array('siparis_id' => $siparis_id, 'user_id' => $user_id, 'siparis_flag' => '-1'));
			if($siparis_sorgu->num_rows()) {
				$odeme_sorgu = $this->db->get_where('odeme_secenekleri', array('odeme_model' => 'havale'), 1);
				if($odeme_sorgu->num_rows()) {
					$secenek_bilgi = $odeme_sorgu->row();
					$_siparis_detay = $this->session->userdata('siparis_detay');
					$_siparis_detay['odeme_adim'] = 'detay';
					$this->session->set_userdata('siparis_detay', $_siparis_detay);

					$toplam_kdv_fiyati = 0;
					$this->db->select('stok_kdv_orani, stok_tfiyat');
					$siparis_detay_sorgu = $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_id));
					foreach($siparis_detay_sorgu->result() as $siparis_detay) {
						if($secenek_bilgi->odeme_indirim_orani != '00') {
							$siparis_detay->stok_tfiyat = (float) ($siparis_detay->stok_tfiyat * ((100-$secenek_bilgi->odeme_indirim_orani)/100));
						}
						$toplam_kdv_fiyati += kdv_hesapla($siparis_detay->stok_tfiyat, $siparis_detay->stok_kdv_orani, true);
					}
					$content_data['toplam_kdv_fiyati'] = $toplam_kdv_fiyati;
					$_siparis_detay = $this->session->userdata('siparis_detay');

					if(array_key_exists('kargo_id', $_siparis_detay) AND array_key_exists('kargo_ucret', $_siparis_detay)) {
						$content_data['kargo_ucret'] = $_siparis_detay['kargo_ucret'];
					} else {
						$content_data['kargo_ucret'] = '0';
					}

					if(array_key_exists('kupon_indirim', $_siparis_detay) AND array_key_exists('fiyat', $_siparis_detay['kupon_indirim'])) {
						$content_data['kupon_ucret'] = $_siparis_detay['kupon_indirim']['fiyat'];
					} else {
						$content_data['kupon_ucret'] = '0';
					}

					$_siparis_detay['kapida_odeme_ucret'] = '0.0000';
					$_siparis_detay['indirim_orani'] = $secenek_bilgi->odeme_indirim_orani;
					$this->session->set_userdata('siparis_detay', $_siparis_detay);

					$content_data['secenek_bilgi'] 	= $secenek_bilgi;
					$content_data['siparis_bilgi'] 	= $siparis_sorgu->row();
					$content_data['siparis_id']		= $siparis_id;
					$content_data['fatura_id']		= $fatura_id;

					$this->db->order_by('havale_sira','asc');
					$content_data['odeme_secenekleri'] = $this->db->get_where('odeme_secenek_havale', array('havale_durum' => '1'));

					$_siparis_detay = $this->session->userdata('siparis_detay');
					$content_data['siparis_detay']	= $_siparis_detay;

					$this->template->set_master_template(tema() . 'odeme/adim_4/havale/form/index');
					/* Sayfa Tanımlamaları */
					$this->template->add_region('baslik');
					$this->template->write('baslik', lang('messages_checkout_title_payment_details'));

					$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/sepet.css');

					$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.maskedinput-1.2.2.min.js');
					$this->template->add_js(APPPATH . 'views/' . tema() . 'js/sa_tip-1.0.1.min.js');
					$this->template->add_js(APPPATH . 'views/' . tema() . 'js/odeme_adimlari.js');

					$this->template->add_region('content');
					$this->template->write_view('content', tema() . 'odeme/adim_4/havale/form/content', $content_data);

					$this->session->set_userdata('adim_4',TRUE);
					/* Sayfa Tnımlamaları */
					$this->template->render();
				} else {
					ssl_redirect('uye/siparisler');
				}
			} else {
				ssl_redirect('uye/siparisler');
			}
		} else {
			redirect('');
		}
	}

	function kapida_odeme($siparis_id, $fatura_id)
	{
		
		self::step_control();
		
		if($this->dx_auth->is_logged_in())
		{
			$user_id = $this->dx_auth->get_user_id();
			$fatura_bilgileri_sorgu = $this->db->get_where('usr_inv_inf', array('inv_id' => $fatura_id, 'user_id' => $user_id), 1);
			$fatura_bilgi = $fatura_bilgileri_sorgu->row();
			$siparis_sorgu = $this->db->get_where('siparis', array('siparis_id' => $siparis_id, 'user_id' => $user_id, 'siparis_flag' => '-1'));
			if($siparis_sorgu->num_rows()) {
				$odeme_sorgu = $this->db->get_where('odeme_secenekleri', array('odeme_model' => 'kapida_odeme'), 1);
				if($odeme_sorgu->num_rows()) {
					$secenek_bilgi = $odeme_sorgu->row();
					$_siparis_detay = $this->session->userdata('siparis_detay');
					$_siparis_detay['odeme_adim'] = 'detay';
					$this->session->set_userdata('siparis_detay', $_siparis_detay);

					$toplam_kdv_fiyati = 0;
					$this->db->select('stok_kdv_orani, stok_tfiyat');
					$siparis_detay_sorgu = $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_id));
					foreach($siparis_detay_sorgu->result() as $siparis_detay) {
						if($secenek_bilgi->odeme_indirim_orani != '00') {
							$siparis_detay->stok_tfiyat = (float) ($siparis_detay->stok_tfiyat * ((100-$secenek_bilgi->odeme_indirim_orani)/100));
						}
						$toplam_kdv_fiyati += kdv_hesapla($siparis_detay->stok_tfiyat, $siparis_detay->stok_kdv_orani, true);
					}
					$content_data['toplam_kdv_fiyati'] = $toplam_kdv_fiyati;
					$_siparis_detay = $this->session->userdata('siparis_detay');

					if(array_key_exists('kargo_id', $_siparis_detay) AND array_key_exists('kargo_ucret', $_siparis_detay)) {
						$content_data['kargo_ucret'] = $_siparis_detay['kargo_ucret'];
					} else {
						$content_data['kargo_ucret'] = '0';
					}

					if(array_key_exists('kupon_indirim', $_siparis_detay) AND array_key_exists('fiyat', $_siparis_detay['kupon_indirim'])) {
						$content_data['kupon_ucret'] = $_siparis_detay['kupon_indirim']['fiyat'];
					} else {
						$content_data['kupon_ucret'] = '0';
					}

					$_siparis_detay['kapida_odeme_ucret'] = config('site_ayar_kapida_odeme_tutari');
					$this->session->set_userdata('siparis_detay', $_siparis_detay);
					$_siparis_detay = $this->session->userdata('siparis_detay');

					$content_data['siparis_id']		= $siparis_id;
					$content_data['fatura_id']		= $fatura_id;
					$content_data['siparis_detay']	= $_siparis_detay;

					$this->template->set_master_template(tema() . 'odeme/adim_4/kapida_odeme/form/index');
					/* Sayfa Tanımlamaları */
					$this->template->add_region('baslik');
					$this->template->write('baslik', lang('messages_checkout_title_payment_details'));

					$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/sepet.css');

					$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.maskedinput-1.2.2.min.js');
					$this->template->add_js(APPPATH . 'views/' . tema() . 'js/sa_tip-1.0.1.min.js');
					$this->template->add_js(APPPATH . 'views/' . tema() . 'js/odeme_adimlari.js');

					$this->template->add_region('content');
					$this->template->write_view('content', tema() . 'odeme/adim_4/kapida_odeme/form/content', $content_data);

					/* Sayfa Tnımlamaları */
					$this->template->render();
				} else {
					ssl_redirect('uye/siparisler');
				}
			} else {
				ssl_redirect('uye/siparisler');
			}
		} else {
			redirect('');
		}
	}

}

?>