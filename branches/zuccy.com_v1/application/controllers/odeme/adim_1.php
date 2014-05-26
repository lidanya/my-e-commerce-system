<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class adim_1 extends Public_Controller {

	/**
	 * isimsiz construct
	 *
	 * @return void
	 **/

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Ödeme Adım 1 Controller Yüklendi');
	}

	/**
	 * index function
	 *
	 * @return void
	 **/

	/* Ödeme Sayfaları */
	function index()
	{
		$this->session->set_userdata('adim_1',FALSE);
		
		$sepet_verileri = $this->cart->contents();
		//exit(var_export($sepet_verileri));

		$sepet_toplam = 0;

		foreach($sepet_verileri as $urunler) {
			if($urunler['durum'] == '1') {
				$sepet_toplam += 1;
			}
		}

		if($sepet_toplam) {
			if($this->dx_auth->is_logged_in()) {

				$_siparis_detay = array();
				if($this->session->userdata('siparis_detay')) {
					$_siparis_detay = $this->session->userdata('siparis_detay');
					$_siparis_detay['kupon_indirim']['mesaj'] = $this->cart->kupon_mesaj();
					$_siparis_detay['kupon_indirim']['fiyat'] = $this->cart->toplam_indirim();
					$_siparis_detay['kupon_indirim']['kupon'] = $this->cart->get_kupon_kodu();
					$this->session->set_userdata('siparis_detay', $_siparis_detay);
				} else {
					$_siparis_detay['kupon_indirim']['mesaj'] = $this->cart->kupon_mesaj();
					$_siparis_detay['kupon_indirim']['fiyat'] = $this->cart->toplam_indirim();
					$_siparis_detay['kupon_indirim']['kupon'] = $this->cart->get_kupon_kodu();
					$this->session->set_userdata('siparis_detay', $_siparis_detay);
				}

				$this->db->where('siparis_flag', '-1');
				$this->db->where('user_id', $this->dx_auth->get_user_id());
				$sorgu = $this->db->get('siparis');
				foreach($sorgu->result() as $siparis) {
					/* Sipariş Detay Sil */
					$this->db->where('siparis_id', $siparis->siparis_id);
					$this->db->delete('siparis_detay');
					/* Sipariş Sil */
					$this->db->where('siparis_id', $siparis->siparis_id);
					$this->db->delete('siparis');
				}

				$siparis_data = array(
					'user_id' 		=> $this->dx_auth->get_user_id(),
					'kayit_tar' 	=> time(),
					'siparis_flag' 	=> '-1' // -1 sepetteki ürünler
				);

				$this->db->insert('siparis', $siparis_data);
				$siparis_id = $this->db->insert_id();

				foreach($sepet_verileri as $urunler) {
					if($urunler['durum'] == '1') {
						$siparis_detay_data = array(
							'siparis_id' 		=> $siparis_id,
							'stok_kodu'			=> $urunler['stok_kodu'],
							'stok_bfiyat' 		=> $urunler['price'],
							'stok_tfiyat' 		=> $urunler['subtotal'],
							'stok_kdv_orani'	=> (double) $urunler['kdv_orani'],
							'stok_miktar'		=> $urunler['qty'],
							'stok_tip'			=> $urunler['tip'], // 1 yıl 2 adet
							'kayit_tar'			=> time(),
						);

						$_siparis_det_data = array();

						if(isset($urunler['options']) AND is_array($urunler['options'])) {
							$_siparis_det_data = array_merge($_siparis_det_data, $urunler['options']);
						}

						if(isset($urunler['secenek']) AND is_array($urunler['secenek'])) {
							$_siparis_det_data = array_merge($_siparis_det_data, array('secenek' => $urunler['secenek']));
						}

						if(isset($urunler['secenek_fiyat'])) {
							$_siparis_det_data = array_merge($_siparis_det_data, array('secenek_fiyat' => $urunler['secenek_fiyat']));
						}

						if(isset($urunler['gercek_fiyat'])) {
							$_siparis_det_data = array_merge($_siparis_det_data, array('gercek_fiyat' => $urunler['gercek_fiyat']));
						}

						if(isset($urunler['gercek_fiyat_subtotal'])) {
							$_siparis_det_data = array_merge($_siparis_det_data, array('gercek_fiyat_subtotal' => $urunler['gercek_fiyat_subtotal']));
						}

						if(count($_siparis_det_data)) {
							$siparis_detay_data['siparis_det_data'] = @serialize($_siparis_det_data);
						}

						if(!empty($urunler['options'])) {
							if(!empty($urunler['options'])) {
								if(!empty($urunler['options']['aciklama_2'])) {
									$siparis_detay_data['stok_aciklama'] = $urunler['options']['aciklama_2'] . ' - ' . $urunler['options']['aciklama'];
								} else {
									$siparis_detay_data['stok_aciklama'] = $urunler['options']['aciklama'];
								}
							} else {
								$siparis_detay_data['stok_aciklama'] = $urunler['name'];
							}
						}
						$this->db->insert('siparis_detay', $siparis_detay_data);
					}
				}

				$content_data['siparis_id'] = $siparis_id;
				$this->template->set_master_template(tema() . 'odeme/adim_1/index');

				$_siparis_detay = array();
				if($this->session->userdata('siparis_detay')) {
					$_siparis_detay = $this->session->userdata('siparis_detay');
					$_siparis_detay['siparis_id'] = $siparis_id;
					$_siparis_detay['odeme_adim'] = 'fatura';
				} else {
					$_siparis_detay['siparis_id'] = $siparis_id;
					$_siparis_detay['odeme_adim'] = 'fatura';
				}
				$this->session->set_userdata('siparis_detay', $_siparis_detay);

				$content_data['siparis_detay'] = $this->session->userdata('siparis_detay');

				/* Sayfa Tanımlamaları */
				$this->template->add_region('baslik');
				$this->template->write('baslik', lang('messages_checkout_title_billing_information'));

				$this->template->add_region('content');
				$this->template->write_view('content', tema() . 'odeme/adim_1/content', $content_data);

				$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/sepet.css');
				$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/anasayfa.css');

				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.maskedinput-1.2.2.min.js');
				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/sa_tip-1.0.1.min.js');
				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/odeme_adimlari.js');

				$this->session->set_userdata('adim_1',true);
				/* Sayfa Tnımlamaları */
				$this->template->render();
			} else {
				$this->template->set_master_template(tema() . 'odeme/adim_1/index');
				/* Sayfa Tanımlamaları */
				$this->template->add_region('baslik');
				$this->template->write('baslik', lang('messages_checkout_title_login'));

				$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/sepet.css');
				$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/anasayfa.css');

				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/sa_tip-1.0.1.min.js');
				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/odeme_adimlari.js');

				$this->template->add_region('content');
				$this->template->write_view('content', tema() . 'odeme/adim_1/uye_content');
				/* Sayfa Tnımlamaları */
				$this->template->render();
			}
		} else {
			redirect('');
		}
	}

	function hizli($stok_kod)
	{
		$check = $this->checkout_model->get_product_by_model($stok_kod);
		$stok_adet = 1;
		if($check) {
			$stok_bilgi = $check;

			if(!($stok_bilgi->price > 0)) {
				redirect('');
			}

			$secenek_kontrol = $this->product_model->get_product_option_by_id($stok_bilgi->product_id);
			if($secenek_kontrol) {
				redirect('');
			}

			$fiyat_bilgi = fiyat_hesapla($stok_kod, 1, kur_oku('usd'), kur_oku('eur'));
			$fiyat = $fiyat_bilgi['fiyat'];
			$kdv_orani = $fiyat_bilgi['kdv_orani'];

			if($this->dx_auth->is_logged_in()) {
				$siparis_data = array(
					'user_id' 		=> $this->dx_auth->get_user_id(),
					'kayit_tar' 	=> time(),
					'siparis_flag' 	=> '-1', // -1 sepetteki ürünler
				);
				$this->db->insert('siparis', $siparis_data);
				$siparis_id = $this->db->insert_id();

				$siparis_detay_data = array(
					'siparis_id' 		=> $siparis_id,
					'stok_kodu'			=> $stok_bilgi->model,
					'stok_bfiyat' 		=> $fiyat,
					'stok_tfiyat' 		=> $fiyat_bilgi['fiyat_t'],
					'stok_kdv_orani'	=> (double) $fiyat_bilgi['kdv_orani'],
					'stok_miktar'		=> 1,
					'stok_tip'			=> $stok_bilgi->stock_type, // 1 yıl 2 adet
					'kayit_tar'			=> time(),
				);

				$this->db->insert('siparis_detay', $siparis_detay_data);

				$content_data['siparis_id'] = $siparis_id;
				$this->template->set_master_template(tema() . 'odeme/adim_1/index');

				$_siparis_detay = array();
				if($this->session->userdata('siparis_detay')) {
					$_siparis_detay = $this->session->userdata('siparis_detay');
					$_siparis_detay['siparis_id'] = $siparis_id;
					$_siparis_detay['odeme_adim'] = 'fatura';
				} else {
					$_siparis_detay['siparis_id'] = $siparis_id;
					$_siparis_detay['odeme_adim'] = 'fatura';
				}
				$this->session->set_userdata('siparis_detay', $_siparis_detay);

				$content_data['stok_kod'] = $stok_kod;

				/* Sayfa Tanımlamaları */
				$this->template->add_region('baslik');
				$this->template->write('baslik', lang('messages_checkout_title_billing_information'));

				$this->template->add_region('content');
				$this->template->write_view('content', tema() . 'odeme/adim_1/content', $content_data);

				$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/sepet.css');
				$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/anasayfa.css');

				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.maskedinput-1.2.2.min.js');
				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/sa_tip-1.0.1.min.js');
				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/odeme_adimlari.js');

				/* Sayfa Tnımlamaları */
				$this->template->render();
			} else {
				$content_data['stok_kod'] = $stok_kod;
				
				$this->template->set_master_template(tema() . 'odeme/adim_1/index');
				/* Sayfa Tanımlamaları */
				$this->template->add_region('baslik');
				$this->template->write('baslik', lang('messages_checkout_title_login'));

				$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/sepet.css');
				$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/anasayfa.css');

				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/sa_tip-1.0.1.min.js');
				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/odeme_adimlari.js');

				$this->template->add_region('content');
				$this->template->write_view('content', tema() . 'odeme/adim_1/uye_hizli_content', $content_data);

				/* Sayfa Tnımlamaları */
				$this->template->render();
			}
		} else {
			redirect('');
		}
	}

}

/* End of file isimsiz.php */
/*  */

?>