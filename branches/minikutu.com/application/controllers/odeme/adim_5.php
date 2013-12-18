<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class adim_5 extends Public_Controller {

	/**
	 * isimsiz construct
	 *
	 * @return void
	 **/

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Ödeme Adım 5 Controller Yüklendi');
		
		//if (!$this->session->userdata('adim_4')) {
			//redirect(''); 
		//}

			
		$this->load->library('validation');
		$this->load->model('siparis_model');
	}

	function belirle()
	{
		if(!$this->dx_auth->is_logged_in()) {
			redirect('');
		}

		$yonlendirme = $this->session->userdata('sanal_pos_yonlendirme');
		if(!$yonlendirme) {
			redirect('');
		}

		$return = '';
		if(count($_GET) > 0)
		{
			$get =  array();
			foreach($_GET as $key => $val)
			{
				$get[] = $key.'='.$val;
			}
			$return .= '?' . implode('&', $get);
		}

		redirect($yonlendirme . $return);
	}

	function kredi_karti($siparis_id, $fatura_id, $banka, $model)
	{
		if($this->dx_auth->is_logged_in()) {
			$user_id = $this->dx_auth->get_user_id();

			$this->output->set_header("Pragma: no-cache");
			$this->output->set_header("Expires: now");

			//$tip_	= base64_decode($model);
			//$tip	= $this->encrypt->decode($tip_);
			$tip	= $model;
			$banka_kontrol = FALSE;
			$banka_kontrol = $this->db->get_where('odeme_secenek_kredi_karti', array('kk_banka_adi_ascii' => $banka, 'kk_banka_pos_tipi' => $tip, 'kk_banka_durum' => '1'), 1);
			if(($_POST OR $_GET) AND $banka_kontrol->num_rows()) {
				$fatura_bilgileri_sorgu = $this->db->get_where('usr_inv_inf', array('inv_id' => $fatura_id, 'user_id' => $user_id), 1);
				$fatura_bilgi = $fatura_bilgileri_sorgu->row();

				$_siparis_detay = $this->session->userdata('siparis_detay');
				$_siparis_detay['odeme_adim'] = 'siparis';
				$this->session->set_userdata('siparis_detay', $_siparis_detay);

				$_siparis_detay = $this->session->userdata('siparis_detay');
				if(isset($_siparis_detay['teslimat'])) {
					$teslimat_bilgi->ad_soyad = $_siparis_detay['teslimat']['ad_soyad'];
					$teslimat_bilgi->adres = $_siparis_detay['teslimat']['adres'];
					$teslimat_bilgi->ulke = $_siparis_detay['teslimat']['ulke'];
					$teslimat_bilgi->sehir = $_siparis_detay['teslimat']['sehir'];
					$teslimat_bilgi->ilce = $_siparis_detay['teslimat']['ilce'];
					$teslimat_bilgi->posta_kodu = $_siparis_detay['teslimat']['posta_kodu'];
					$teslimat_bilgi->telefon = $_siparis_detay['teslimat']['telefon'];
				} else {
					$teslimat_bilgi->ad_soyad = '';
					$teslimat_bilgi->adres = '';
					$teslimat_bilgi->ulke = '';
					$teslimat_bilgi->sehir = '';
					$teslimat_bilgi->ilce = '';
					$teslimat_bilgi->posta_kodu = '';
					$teslimat_bilgi->telefon = '';
				}

				$banka_bilgi = $banka_kontrol->row();
				$user_id = $this->dx_auth->get_user_id();
				$siparis_sorgu = $this->db->get_where('siparis', array('siparis_id' => $siparis_id, 'user_id' => $user_id, 'siparis_flag' => '-1'));
				if($siparis_sorgu->num_rows()) {
					$siparis_sorgu_detay = $siparis_sorgu->row();
					$this->template->set_master_template(tema() . 'odeme/adim_5/kredi_karti/onay/index');
					$content_data['siparis_id'] 				= $siparis_id;
					$content_data['fatura_id'] 					= $fatura_id;

					//$content_data['gonder_bilgi']			= array('result' => false, 'error_msg' => 'Bağlantı Sağlanamadı.', 'tip' => 'normal');

					$odeme_tipi_sorgu = $this->db->get_where('odeme_secenekleri', array('odeme_model' => 'kredi_karti'), 1);
					$odeme_tipi_bilgi = $odeme_tipi_sorgu->row();
					
					if($sanal_pos = $this->sanal_pos->banka_sec_baglan($banka_bilgi->kk_id) AND $sanal_pos->durum) {
						//Zorunlu Gidecek Veriler
						//$siparis_bilgi_gonder->siparis_id = '1';
						//$siparis_bilgi_gonder->fatura_id = '2';
						//$siparis_bilgi_gonder->ip_adres = '127.0.0.1';
						//$siparis_bilgi_gonder->email_adres = 'mail@mail.com.tr';
						//$siparis_bilgi_gonder->user_id = '31';

						$this->load->library('user_agent');

						if ($this->agent->is_browser()) {
							$agent = $this->agent->browser() . ' ' . $this->agent->version();
						} elseif ($this->agent->is_robot()) {
							$agent = $this->agent->robot();
						} elseif ($this->agent->is_mobile()) {
							$agent = $this->agent->mobile();
						} else {
						    $agent = 'Unidentified User Agent';
						}

						$session_siparis_detay = $this->session->userdata('siparis_detay');

						$form_deger_yolla->siparis_id = $siparis_id;
						$form_deger_yolla->fatura_id = $fatura_id;
						$form_deger_yolla->ip_adres = $this->input->ip_address();
						$form_deger_yolla->platform = $this->agent->platform();
						$form_deger_yolla->user_agent = $this->agent->agent_string();
						$form_deger_yolla->agent = $agent;
						$form_deger_yolla->email_adres = $this->dx_auth->get_username();
						$form_deger_yolla->user_id = $user_id;
						$form_deger_yolla->post_verileri = $_POST;
						$form_deger_yolla->get_verileri = $_GET;
						$form_deger_yolla->request_verileri = $_REQUEST;
						$form_deger_yolla->siparis_verileri = $siparis_sorgu_detay;
						$form_deger_yolla->fatura_bilgi = $fatura_bilgi;
						$form_deger_yolla->teslimat_bilgi = $teslimat_bilgi;
						$form_deger_yolla->session_siparis_detay = $session_siparis_detay;
						$form_deger_yolla->uye_bilgi = get_usr_ide_inf($user_id);

						$form_gonder = $sanal_pos->class->form_sonuc($form_deger_yolla);

						//exit(debug($form_gonder));

						if($form_gonder->durum) {
							$odeme_durum 	= true;
							$content_data['gonder_bilgi'] = array('result' => $form_gonder->durum, 'error_msg' => $form_gonder->mesaj, 'kod' => $form_gonder->kod, 'debug' => $form_gonder->debug, 'ucret' => $_siparis_detay['kredi_kart']['toplam_ucret']);
						} else {
							$odeme_durum 	= false;
							$content_data['gonder_bilgi'] = array('result' => $form_gonder->durum, 'error_msg' => $form_gonder->mesaj, 'kod' => $form_gonder->kod, 'debug' => $form_gonder->debug);
						}
					} else {
						$content_data['gonder_bilgi'] = array('result' => $form_gonder->durum, 'error_msg' => $form_gonder->mesaj, 'kod' => $form_gonder->kod, 'debug' => $form_gonder->debug);
					}

					if($odeme_durum) {
						$this->cart->destroy();

						$siparis_detay_sorgu = $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_id, 'siparis_det_flag' => '1'));
						foreach($siparis_detay_sorgu->result() as $siparis_det) {
							$unserialize_data = @unserialize($siparis_det->siparis_det_data);
							if(isset($unserialize_data['secenek']) AND is_array($unserialize_data['secenek'])) {
								foreach($unserialize_data['secenek'] as $secenek) {
									if($secenek['type'] == 'file') {
										$file = $secenek['option_value'];
										@rename(DIR_DOWNLOAD . 'temp/' . $file, DIR_DOWNLOAD . $file);
									}
								}
							}
						}

						$_siparis_detay = $this->session->userdata('siparis_detay');
						if(isset($_siparis_detay['kupon_indirim']['kupon']) AND $_siparis_detay['kupon_indirim']['kupon'] != '') {
							$kupon = $_siparis_detay['kupon_indirim']['kupon'];
							$this->db->update('coupon', array('status' => '1'), array('code' => $kupon));
						}

						$data_array = array();
						$data_array['teslimat_bilgileri'] = $_siparis_detay;

						if(isset($_siparis_detay['kredi_kart']['kart_numarasi'])) {
							$kart_no_parcala = substr($_siparis_detay['kredi_kart']['kart_numarasi'], 0, 6) . '******' . substr($_siparis_detay['kredi_kart']['kart_numarasi'], -4);
						} else {
							$kart_no_parcala = 'Kart Numarasını Alamadım';
						}

						if(isset($_siparis_detay['kredi_kart']['taksit_sayisi'])) {
							$taksit_sayisi = $_siparis_detay['kredi_kart']['taksit_sayisi'];
						} else {
							$taksit_sayisi = 'Taksit Sayısını Alamadım';
						}

						$kredi_kart_no = $kart_no_parcala;

						$data_array['odeme_tipi'] = 'kredi_karti';
						$data_array['odeme_secenegi'] = $banka_bilgi->kk_odeme_id;
						$data_array['odeme_secenegi_detay'] = $banka_bilgi->kk_id;
						$data_array['kredi_kart_no'] = $kredi_kart_no;
						$data_array['taksit_sayisi'] = $taksit_sayisi;

						$siparis_data = array(
							'usr_inv_id' 	=> $fatura_id,
							'odeme_tip'		=> $odeme_tipi_bilgi->odeme_id,
							'siparis_flag'	=> $odeme_tipi_bilgi->odeme_siparis_durum,
							'siparis_data'	=> serialize($data_array)
						);

						$this->db->where('siparis_id', $siparis_id);
						$this->db->update('siparis', $siparis_data);

						if ($odeme_tipi_bilgi->odeme_siparis_durum == '2') {
							$siparis_detay_sorgu = $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_id));
							foreach($siparis_detay_sorgu->result() as $siparis_det) {
								if ($siparis_det->stokdan_dus_durum == '0') {
									$product_query = $this->db->get_where('product', array('model' => $siparis_det->stok_kodu, 'subtract' => '1'));
									if ($product_query->num_rows()) {
										$product_info = $product_query->row();
										$quantity = ($product_info->quantity - $siparis_det->stok_miktar);
										if ($quantity < 0) {
											$quantity = 0;
										}
										$this->db->update('product', array('quantity' => (int) $quantity), array('model' => $siparis_det->stok_kodu));
									}

									$this->db->update('siparis_detay', array('stokdan_dus_durum' => '1'), array('siparis_det_id' => $siparis_det->siparis_det_id));

									if ($siparis_det->siparis_det_data) {
										$unserialize_data = @unserialize($siparis_det->siparis_det_data);
										if ($unserialize_data) {
											if (isset($unserialize_data['secenek']) AND $unserialize_data['secenek']) {
												foreach ($unserialize_data['secenek'] as $secenek) {
													if (isset($secenek['subtract']) AND $secenek['subtract']) {
														$option_query = $this->db->get_where('product_option_value', array('product_option_value_id' => (int) $secenek['product_option_value_id'], 'subtract' => '1'));
														if ($option_query->num_rows()) {
															$option_info = $option_query->row();
															$quantity = ($option_info->quantity - $siparis_det->stok_miktar);
															if ($quantity < 0) {
																$quantity = 0;
															}
															$this->db->update('product_option_value', array('quantity' => (int) $quantity), array('product_option_value_id' => (int) $secenek['product_option_value_id']));
														}
													}
												}
											}
										}
									}
								}
							}
						}

						//email fonksiyonu - >başlangıç
						$from = config('site_ayar_email_cevapsiz');
						$subject = 'Siparişiniz Alınmıştır';
						$uye_bilgi = uye_bilgi($user_id);
						$mail_data['sip_det_q'] = $this->siparis_model->siparis_detay($siparis_id);
						$mail_data['siparis_bilgi'] = $siparis_sorgu->row();
						$mail_data['ad'] = $uye_bilgi->ide_adi;
						$mail_data['soyad'] = $uye_bilgi->ide_soy;
						$mail_data['siparis_tarih'] = standard_date('DATE_TR1', time(), 'tr');
						$mail_data['siparis_no'] = $siparis_id;
						$message = $this->load->view(tema() . 'mail_sablon/siparis/siparis_bildirimi', $mail_data, true);
						$this->dx_auth->_email($uye_bilgi->username, $from, $subject, $message);

						$from = config('site_ayar_email_cevapsiz');
						$subject = 'Siparişiniz Alınmıştır';
						$uye_bilgi = uye_bilgi($user_id);
						$mail_data['sip_det_q'] = $this->siparis_model->siparis_detay($siparis_id);
						$mail_data['siparis_bilgi'] = $siparis_sorgu->row();
						$mail_data['ad'] = $uye_bilgi->ide_adi;
						$mail_data['soyad'] = $uye_bilgi->ide_soy;
						$mail_data['siparis_tarih'] = standard_date('DATE_TR1', time(), 'tr');
						$mail_data['siparis_no'] = $siparis_id;
						$message = $this->load->view(tema() . 'mail_sablon/siparis/siparis_bildirimi', $mail_data, true);
						$this->dx_auth->_email(config('site_ayar_email_admin'), $from, $subject, $message);

						$this->session->unset_userdata('siparis_detay');
					}

					/* Sayfa Tanımlamaları */
					$this->template->add_region('baslik');
					$this->template->write('baslik', lang('messages_checkout_title_order_details'));

					$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/sepet.css');

					$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.maskedinput-1.2.2.min.js');
					$this->template->add_js(APPPATH . 'views/' . tema() . 'js/sa_tip-1.0.1.min.js');
					$this->template->add_js(APPPATH . 'views/' . tema() . 'js/odeme_adimlari.js');

					$this->template->add_region('content');
					$this->template->write_view('content', tema() . 'odeme/adim_5/kredi_karti/onay/content', $content_data);

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

	function havale()
	{
		if($this->dx_auth->is_logged_in()) {

			$siparis_id 				= $this->input->post('siparis_id');
			$fatura_id 					= $this->input->post('fatura_id');
			$odeme_secenegi				= $this->input->post('odeme_secenegi');
			$odeme_secenegi_detay		= $this->input->post('tipi_' . $odeme_secenegi);

			if($this->input->post()) {
				$user_id = $this->dx_auth->get_user_id();
				$fatura_bilgileri_sorgu = $this->db->get_where('usr_inv_inf', array('inv_id' => $fatura_id, 'user_id' => $user_id), 1);
				$fatura_bilgi = $fatura_bilgileri_sorgu->row();
				$siparis_sorgu = $this->db->get_where('siparis', array('siparis_id' => $siparis_id, 'user_id' => $user_id, 'siparis_flag' => '-1'));
				if($siparis_sorgu->num_rows()) {
					$this->cart->destroy();

					$data_array = array();
					$data_array['odeme_tipi'] = 'havale';
					$data_array['odeme_secenegi'] = $odeme_secenegi;
					$data_array['odeme_secenegi_detay'] = $odeme_secenegi_detay;

					$_siparis_detay = $this->session->userdata('siparis_detay');
					$_siparis_detay['odeme_adim'] = 'siparis';
					$this->session->set_userdata('siparis_detay', $_siparis_detay);

					$_siparis_detay = $this->session->userdata('siparis_detay');
					$data_array['teslimat_bilgileri'] = $_siparis_detay;

					$odeme_tipi_sorgu = $this->db->get_where('odeme_secenekleri', array('odeme_model' => 'havale'), 1);
					$odeme_tipi_bilgi = $odeme_tipi_sorgu->row();

					$siparis_data = array(
						'usr_inv_id' 	=> $fatura_id,
						'odeme_tip'		=> $odeme_tipi_bilgi->odeme_id,
						'siparis_flag'	=> $odeme_tipi_bilgi->odeme_siparis_durum,
						'siparis_data'	=> serialize($data_array)
					);

					$this->db->where('siparis_id', $siparis_id);
					$this->db->update('siparis', $siparis_data);

					$secenek_sorgu = $this->db->get_where('odeme_secenek_havale', array('havale_id' => $odeme_secenegi), 1);
					$secenek_detay_sorgu = $this->db->get_where('odeme_secenek_havale_detay', array('havale_detay_id' => $odeme_secenegi_detay), 1);

					$content_data['secenek_bilgi'] = $secenek_sorgu->row();
					$content_data['secenek_detay_bilgi'] = $secenek_detay_sorgu->row();
					$content_data['siparis_id'] = $siparis_id;

					$siparis_detay_sorgu = $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_id, 'siparis_det_flag' => '1'));
					foreach($siparis_detay_sorgu->result() as $siparis_det) {
						$unserialize_data = @unserialize($siparis_det->siparis_det_data);
						if(isset($unserialize_data['secenek']) AND is_array($unserialize_data['secenek'])) {
							foreach($unserialize_data['secenek'] as $secenek) {
								if($secenek['type'] == 'file') {
									$file = $secenek['option_value'];
									@rename(DIR_DOWNLOAD . 'temp/' . $file, DIR_DOWNLOAD . $file);
								}
							}
						}
					}

					$_siparis_detay = $this->session->userdata('siparis_detay');
					if(isset($_siparis_detay['kupon_indirim']['kupon']) AND $_siparis_detay['kupon_indirim']['kupon'] != '') {
						$kupon = $_siparis_detay['kupon_indirim']['kupon'];
						$this->db->update('coupon', array('status' => '1'), array('code' => $kupon));
					}

					if ($odeme_tipi_bilgi->odeme_siparis_durum == '2') {
						$siparis_detay_sorgu = $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_id));
						foreach($siparis_detay_sorgu->result() as $siparis_det) {
							if ($siparis_det->stokdan_dus_durum == '0') {
								$product_query = $this->db->get_where('product', array('model' => $siparis_det->stok_kodu, 'subtract' => '1'));
								if ($product_query->num_rows()) {
									$product_info = $product_query->row();
									$quantity = ($product_info->quantity - $siparis_det->stok_miktar);
									if ($quantity < 0) {
										$quantity = 0;
									}
									$this->db->update('product', array('quantity' => (int) $quantity), array('model' => $siparis_det->stok_kodu));
								}

								$this->db->update('siparis_detay', array('stokdan_dus_durum' => '1'), array('siparis_det_id' => $siparis_det->siparis_det_id));

								if ($siparis_det->siparis_det_data) {
									$unserialize_data = @unserialize($siparis_det->siparis_det_data);
									if ($unserialize_data) {
										if (isset($unserialize_data['secenek']) AND $unserialize_data['secenek']) {
											foreach ($unserialize_data['secenek'] as $secenek) {
												if (isset($secenek['subtract']) AND $secenek['subtract']) {
													$option_query = $this->db->get_where('product_option_value', array('product_option_value_id' => (int) $secenek['product_option_value_id'], 'subtract' => '1'));
													if ($option_query->num_rows()) {
														$option_info = $option_query->row();
														$quantity = ($option_info->quantity - $siparis_det->stok_miktar);
														if ($quantity < 0) {
															$quantity = 0;
														}
														$this->db->update('product_option_value', array('quantity' => (int) $quantity), array('product_option_value_id' => (int) $secenek['product_option_value_id']));
													}
												}
											}
										}
									}
								}
							}
						}
					}

					//email fonksiyonu - >başlangıç
					$from = config('site_ayar_email_cevapsiz');
					$subject = 'Siparişiniz Alınmıştır';
					$uye_bilgi = uye_bilgi($user_id);
					$mail_data['sip_det_q'] = $this->siparis_model->siparis_detay($siparis_id);
					$mail_data['siparis_bilgi'] = $siparis_sorgu->row();
					$mail_data['ad'] = $uye_bilgi->ide_adi;
					$mail_data['soyad'] = $uye_bilgi->ide_soy;
					$mail_data['siparis_tarih'] = standard_date('DATE_TR1', time(), 'tr');
					$mail_data['siparis_no'] = $siparis_id;
					$message = $this->load->view(tema() . 'mail_sablon/siparis/siparis_bildirimi', $mail_data, true);
					$this->dx_auth->_email($uye_bilgi->username, $from, $subject, $message);

					$from = config('site_ayar_email_cevapsiz');
					$subject = 'Siparişiniz Alınmıştır';
					$uye_bilgi = uye_bilgi($user_id);
					$mail_data['sip_det_q'] = $this->siparis_model->siparis_detay($siparis_id);
					$mail_data['siparis_bilgi'] = $siparis_sorgu->row();
					$mail_data['ad'] = $uye_bilgi->ide_adi;
					$mail_data['soyad'] = $uye_bilgi->ide_soy;
					$mail_data['siparis_tarih'] = standard_date('DATE_TR1', time(), 'tr');
					$mail_data['siparis_no'] = $siparis_id;
					$message = $this->load->view(tema() . 'mail_sablon/siparis/siparis_bildirimi', $mail_data, true);
					$this->dx_auth->_email(config('site_ayar_email_admin'), $from, $subject, $message);

					$this->template->set_master_template(tema() . 'odeme/adim_5/havale/onay/index');
					/* Sayfa Tanımlamaları */
					$this->template->add_region('baslik');
					$this->template->write('baslik', lang('messages_checkout_title_order_details'));

					$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/sepet.css');

					$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.maskedinput-1.2.2.min.js');
					$this->template->add_js(APPPATH . 'views/' . tema() . 'js/sa_tip-1.0.1.min.js');
					$this->template->add_js(APPPATH . 'views/' . tema() . 'js/odeme_adimlari.js');

					$this->template->add_region('content');
					$this->template->write_view('content', tema() . 'odeme/adim_5/havale/onay/content', $content_data);

					/* Sayfa Tnımlamaları */
					$this->template->render();

					$this->session->unset_userdata('siparis_detay');
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
    

	function kapida_odeme()
	{
		//print_r($this->dx_auth->is_logged_in()); echo 'ads'; return false;
		if($this->dx_auth->is_logged_in()) {
			$siparis_id 			= $this->input->post('siparis_id');
			$fatura_id 				= $this->input->post('fatura_id');

			$user_id = $this->dx_auth->get_user_id();
			$fatura_bilgileri_sorgu = $this->db->get_where('usr_inv_inf', array('inv_id' => $fatura_id, 'user_id' => $user_id), 1);
			$fatura_bilgi = $fatura_bilgileri_sorgu->row();
			$siparis_sorgu = $this->db->get_where('siparis', array('siparis_id' => $siparis_id, 'user_id' => $user_id, 'siparis_flag' => '-1'));
			if($siparis_sorgu->num_rows()) {
				$this->cart->destroy();

				$odeme_tipi_sorgu = $this->db->get_where('odeme_secenekleri', array('odeme_model' => 'kapida_odeme'), 1);
				$odeme_tipi_bilgi = $odeme_tipi_sorgu->row();

				$data_array = array();
				$data_array['odeme_tipi'] = 'kapida_odeme';
				$data_array['odeme_secenegi'] = '1';
				$data_array['odeme_secenegi_detay'] = '1';

				$_siparis_detay = $this->session->userdata('siparis_detay');
				$data_array['teslimat_bilgileri'] = $_siparis_detay;

				$_siparis_detay = $this->session->userdata('siparis_detay');
				$_siparis_detay['odeme_adim'] = 'siparis';
				$this->session->set_userdata('siparis_detay', $_siparis_detay);

				$siparis_data = array(
					'usr_inv_id' 	=> $fatura_id,
					'odeme_tip'		=> '3',
					'siparis_flag'	=> $odeme_tipi_bilgi->odeme_siparis_durum,
					'siparis_data'	=> serialize($data_array)
				);

				$this->db->where('siparis_id', $siparis_id);
				$this->db->update('siparis', $siparis_data);

				$content_data['siparis_id'] = $siparis_id;

				if ($odeme_tipi_bilgi->odeme_siparis_durum == '2') {
					$siparis_detay_sorgu = $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_id));
					foreach($siparis_detay_sorgu->result() as $siparis_det) {
						if ($siparis_det->stokdan_dus_durum == '0') {
							$product_query = $this->db->get_where('product', array('model' => $siparis_det->stok_kodu, 'subtract' => '1'));
							if ($product_query->num_rows()) {
								$product_info = $product_query->row();
								$quantity = ($product_info->quantity - $siparis_det->stok_miktar);
								if ($quantity < 0) {
									$quantity = 0;
								}
								$this->db->update('product', array('quantity' => (int) $quantity), array('model' => $siparis_det->stok_kodu));
							}

							$this->db->update('siparis_detay', array('stokdan_dus_durum' => '1'), array('siparis_det_id' => $siparis_det->siparis_det_id));

							if ($siparis_det->siparis_det_data) {
								$unserialize_data = @unserialize($siparis_det->siparis_det_data);
								if ($unserialize_data) {
									if (isset($unserialize_data['secenek']) AND $unserialize_data['secenek']) {
										foreach ($unserialize_data['secenek'] as $secenek) {
											if (isset($secenek['subtract']) AND $secenek['subtract']) {
												$option_query = $this->db->get_where('product_option_value', array('product_option_value_id' => (int) $secenek['product_option_value_id'], 'subtract' => '1'));
												if ($option_query->num_rows()) {
													$option_info = $option_query->row();
													$quantity = ($option_info->quantity - $siparis_det->stok_miktar);
													if ($quantity < 0) {
														$quantity = 0;
													}
													$this->db->update('product_option_value', array('quantity' => (int) $quantity), array('product_option_value_id' => (int) $secenek['product_option_value_id']));
												}
											}
										}
									}
								}
							}
						}
					}
				}

				$_siparis_detay = $this->session->userdata('siparis_detay');
				if(isset($_siparis_detay['kupon_indirim']['kupon']) AND $_siparis_detay['kupon_indirim']['kupon'] != '') {
					$kupon = $_siparis_detay['kupon_indirim']['kupon'];
					$this->db->update('coupon', array('status' => '1'), array('code' => $kupon));
				}

				//email fonksiyonu - >başlangıç
				$from = config('site_ayar_email_cevapsiz');
				$subject = 'Siparişiniz Alınmıştır';
				$uye_bilgi = uye_bilgi($user_id);
				$mail_data['sip_det_q'] = $this->siparis_model->siparis_detay($siparis_id);
				$mail_data['siparis_bilgi'] = $siparis_sorgu->row();
				$mail_data['ad'] = $uye_bilgi->ide_adi;
				$mail_data['soyad'] = $uye_bilgi->ide_soy;
				$mail_data['siparis_tarih'] = standard_date('DATE_TR1', time(), 'tr');
				$mail_data['siparis_no'] = $siparis_id;
				$message = $this->load->view(tema() . 'mail_sablon/siparis/siparis_bildirimi', $mail_data, true);
				$this->dx_auth->_email($uye_bilgi->username, $from, $subject, $message);

				$from = config('site_ayar_email_cevapsiz');
				$subject = 'Siparişiniz Alınmıştır';
				$uye_bilgi = uye_bilgi($user_id);
				$mail_data['sip_det_q'] = $this->siparis_model->siparis_detay($siparis_id);
				$mail_data['siparis_bilgi'] = $siparis_sorgu->row();
				$mail_data['ad'] = $uye_bilgi->ide_adi;
				$mail_data['soyad'] = $uye_bilgi->ide_soy;
				$mail_data['siparis_tarih'] = standard_date('DATE_TR1', time(), 'tr');
				$mail_data['siparis_no'] = $siparis_id;
				$message = $this->load->view(tema() . 'mail_sablon/siparis/siparis_bildirimi', $mail_data, true);
				$this->dx_auth->_email(config('site_ayar_email_admin'), $from, $subject, $message);

				$this->template->set_master_template(tema() . 'odeme/adim_5/kapida_odeme/onay/index');
				/* Sayfa Tanımlamaları */
				$this->template->add_region('baslik');
				$this->template->write('baslik', lang('messages_checkout_title_order_details'));

				$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/sepet.css');

				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.maskedinput-1.2.2.min.js');
				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/sa_tip-1.0.1.min.js');
				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/odeme_adimlari.js');

				$this->template->add_region('content');
				$this->template->write_view('content', tema() . 'odeme/adim_5/kapida_odeme/onay/content', $content_data);

				/* Sayfa Tnımlamaları */
				$this->template->render();

				$this->session->unset_userdata('siparis_detay');
			} else {
				ssl_redirect('uye/siparisler');
			}
		} else {
			redirect();
		}
	}
}

?>