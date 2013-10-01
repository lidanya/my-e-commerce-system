<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class adim_3 extends Public_Controller {

	/**
	 * isimsiz construct
	 *
	 * @return void
	 **/

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Ödeme Adım 3 Controller Yüklendi');
	}

	/**
	 * index function
	 *
	 * @return void
	 **/

	/* Ödeme Sayfaları */
	function index($siparis_id, $fatura_id)
	{
		
		$this->session->set_userdata('adim_3',FALSE);		
		
		$_siparis_detay = $this->session->userdata('siparis_detay');

		if($this->input->post('kargo_secimi')) {
			$_siparis_detay['kargo_id'] = $this->input->post('kargo_secimi');
			$_siparis_detay['kargo_ucret'] = $this->input->post('kargo_fiyat_' . $this->input->post('kargo_secimi'));
			$this->session->set_userdata('adim_2',TRUE); // Adım 2 doğrulandı.
		}
		
		if (!$this->session->userdata('adim_2')) {
			redirect('odeme/adim_2/'. $siparis_id . '/' . $fatura_id);
		}
		
		
		$_siparis_detay['odeme_adim'] = 'odeme';
		$_siparis_detay['kapida_odeme_ucret'] = '0.0000';
		$_siparis_detay['indirim_orani'] = '00';
		$this->session->set_userdata('siparis_detay', $_siparis_detay);

		if($this->dx_auth->is_logged_in()) {
			$user_id = $this->dx_auth->get_user_id();
			$siparis_sorgu = $this->db->get_where('siparis', array('siparis_id' => $siparis_id, 'user_id' => $user_id, 'siparis_flag' => '-1'));
			if($siparis_sorgu->num_rows() > 0) {
				$toplam_kdv_fiyati = 0;
				$this->db->select('stok_kdv_orani, stok_tfiyat');
				$siparis_detay_sorgu = $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_id));
				foreach($siparis_detay_sorgu->result() as $siparis_detay) {
					$toplam_kdv_fiyati += kdv_hesapla($siparis_detay->stok_tfiyat, $siparis_detay->stok_kdv_orani, true);
				}
				$content_data['toplam_kdv_fiyati'] = $toplam_kdv_fiyati;

				$this->db->order_by('odeme_sira','asc');
				$content_data['odeme_secenekleri'] = $this->db->get_where('odeme_secenekleri', array('odeme_durum' => '1'));
				$content_data['siparis_id'] = $siparis_id;
				$content_data['fatura_id']	= $fatura_id;
				if($this->input->post('kargo_secimi')) {
					$content_data['kargo_ucret'] = $this->input->post('kargo_fiyat_' . $this->input->post('kargo_secimi'));
				} else {
					if(array_key_exists('kargo_id', $_siparis_detay) && array_key_exists('kargo_ucret', $_siparis_detay)) {
						$content_data['kargo_ucret'] = $_siparis_detay['kargo_ucret'];
					} else {
						$content_data['kargo_ucret'] = '0';
					}
				}

				if(array_key_exists('kupon_indirim', $_siparis_detay) AND array_key_exists('fiyat', $_siparis_detay['kupon_indirim'])) {
					$content_data['kupon_ucret'] = $_siparis_detay['kupon_indirim']['fiyat'];
				} else {
					$content_data['kupon_ucret'] = '0';
				}

				$this->template->set_master_template(tema() . 'odeme/adim_3/index');
				/* Sayfa Tanımlamaları */
				$this->template->add_region('baslik');
				$this->template->write('baslik', lang('messages_checkout_title_payment_options'));

				$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/sepet.css');
				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/sa_tip-1.0.1.min.js');
				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/odeme_adimlari.js');

				$this->template->add_region('content');
				$this->template->write_view('content', tema() . 'odeme/adim_3/content', $content_data);
				
				$this->session->set_userdata('adim_3',TRUE);
				/* Sayfa Tnımlamaları */
				$this->template->render();
			} else {
				ssl_redirect('uye/siparisler');
			}
		} else {
			redirect('');
		}

	}
}

?>