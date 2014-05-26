<?php

if (!defined('BASEPATH')) {
	header('Location: http://' . getenv('SERVER_NAME') . '/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 * */
class adim_2 extends Public_Controller
{

	/**
	 * isimsiz construct
	 *
	 * @return void
	 * */
	function __construct() {
		parent::__construct();
		log_message('debug', 'Ödeme Adım 2 Controller Yüklendi');
	}

	/**
	 * index function
	 *
	 * @return void
	 * */
	/* Ödeme Sayfaları */
	function index($siparis_id, $fatura_id) {
		$this->session->set_userdata('adim_2', FALSE);


		if ($this->dx_auth->is_logged_in()) {
			$user_id = $this->dx_auth->get_user_id();
			$siparis_sorgu = $this->db->get_where('siparis', array('siparis_id' => $siparis_id, 'user_id' => $user_id, 'siparis_flag' => '-1'));
			if ($siparis_sorgu->num_rows()) {
				$toplam_kdv_fiyati = 0;
				$stok_id = array();
				$siparis_detay_sorgu = $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_id));
				if ($siparis_detay_sorgu->num_rows()) {
					$fi = 0;
					foreach ($siparis_detay_sorgu->result() as $siparis_detay) {
						$toplam_kdv_fiyati += kdv_hesapla($siparis_detay->stok_tfiyat, $siparis_detay->stok_kdv_orani, true);

						$stock_check = $this->checkout_model->get_product_by_model($siparis_detay->stok_kodu);
						if ($stock_check) {
							$stok_bilgi = $stock_check;
							$stok_id[] = $stok_bilgi->product_id;
						}
						$fi++;
					}
				}

				if ($stok_id) {
					$cargo_check = $this->checkout_model->get_products_cargo_required_by_array_id($stok_id, $siparis_id);
					if ($cargo_check) {
						$_siparis_detay = array();
						if ($this->session->userdata('siparis_detay')) {
							$_siparis_detay = $this->session->userdata('siparis_detay');
							$_siparis_detay['odeme_adim'] = 'kargo';
							$this->session->set_userdata('siparis_detay', $_siparis_detay);
						}

						$this->db->order_by('odeme_sira', 'asc');
						$content_data['odeme_secenekleri'] = $this->db->get_where('odeme_secenekleri', array('odeme_durum' => '1'));
						$content_data['siparis_id'] = $siparis_id;
						$content_data['fatura_id'] = $fatura_id;
						$content_data['stok_sorgu'] = $cargo_check;
						$content_data['toplam_kdv_fiyati'] = $toplam_kdv_fiyati;

						$this->template->set_master_template(tema() . 'odeme/adim_2/index');
						/* Sayfa Tanımlamaları */
						$this->template->add_region('baslik');
						$this->template->write('baslik', lang('messages_checkout_title_shipping_choice'));

						$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/sepet.css');
						$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/anasayfa.css');

						$this->template->add_js(APPPATH . 'views/' . tema() . 'js/sa_tip-1.0.1.min.js');
						$this->template->add_js(APPPATH . 'views/' . tema() . 'js/odeme_adimlari.js');

						$this->template->add_region('content');
						$this->template->write_view('content', tema() . 'odeme/adim_2/content', $content_data);
						$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.maskedinput-1.2.2.min.js');

						/* Sayfa Tnımlamaları */
						$this->template->render();
					} else {

						ssl_redirect('odeme/adim_3/' . $siparis_id . '/' . $fatura_id . '');
					}
				} else {
					ssl_redirect('odeme/adim_3/' . $siparis_id . '/' . $fatura_id . '');
				}
			} else {
				ssl_redirect('uye/siparisler');
			}
		} else {
			redirect('');
		}
	}

}

/* End of file isimsiz.php */
/*  */
?>