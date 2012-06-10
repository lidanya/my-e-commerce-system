<?php $this->load->view(tema() . 'odeme/header'); ?>

<?php //echo debug($siparis_detay); ?>

<?php
	$user_id							= $this->dx_auth->get_user_id();
	$banka_host_bilgileri				= $this->config->item('banka_detaylari');
	$val								= $this->form_validation;
	$kredi_karti_odeme_tipi				= $this->input->post('kredi_karti_odeme_tipi');

	$fatura_bilgileri_sorgu				= $this->db->get_where('usr_inv_inf', array('inv_id' => $fatura_id, 'user_id' => $user_id), 1);
	$fatura_bilgi						= $fatura_bilgileri_sorgu->row();

	$teslimat_bilgi->ad_soyad			= '';
	$teslimat_bilgi->adres				= '';
	$teslimat_bilgi->ulke				= '';
	$teslimat_bilgi->sehir 				= '';
	$teslimat_bilgi->ilce 				= '';
	$teslimat_bilgi->posta_kodu			= '';
	$teslimat_bilgi->telefon			= '';

	if(isset($siparis_detay['teslimat'])) {
		$teslimat_bilgi->ad_soyad		= $siparis_detay['teslimat']['ad_soyad'];
		$teslimat_bilgi->adres			= $siparis_detay['teslimat']['adres'];
		$teslimat_bilgi->ulke			= $siparis_detay['teslimat']['ulke'];
		$teslimat_bilgi->sehir			= $siparis_detay['teslimat']['sehir'];
		$teslimat_bilgi->ilce			= $siparis_detay['teslimat']['ilce'];
		$teslimat_bilgi->posta_kodu		= $siparis_detay['teslimat']['posta_kodu'];
		$teslimat_bilgi->telefon		= $siparis_detay['teslimat']['telefon'];
	} else {
		$teslimat_bilgi->ad_soyad		= '';
		$teslimat_bilgi->adres			= '';
		$teslimat_bilgi->ulke			= '';
		$teslimat_bilgi->sehir			= '';
		$teslimat_bilgi->ilce			= '';
		$teslimat_bilgi->posta_kodu		= '';
		$teslimat_bilgi->telefon		= '';
	}

	$this->db->select_sum('stok_tfiyat');
	$toplam_tfiyat_sorgu				= $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_id));
	$toplam_tfiyat_bilgi				= $toplam_tfiyat_sorgu->row();
	$stok_toplam_fiyat					= $toplam_tfiyat_bilgi->stok_tfiyat;

	if(isset($siparis_detay['kargo_ucret']) AND $siparis_detay['kargo_ucret']) {
		$kargo_ucret					= ($siparis_detay['kargo_ucret']) ? $siparis_detay['kargo_ucret'] : 0;
	} else {
		$kargo_ucret					= 0;
	}

	$toplam_kdv_fiyati					= 0;
	$this->db->select('stok_kdv_orani, stok_tfiyat');
	$siparis_detay_sorgu				= $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_id));
	foreach($siparis_detay_sorgu->result() as $siparis_detay) {
		$toplam_kdv_fiyati 				+= kdv_hesapla($siparis_detay->stok_tfiyat, $siparis_detay->stok_kdv_orani, true);
	}

	if(!$this->input->post()) {
		$val->set_rules('kredi_karti_adi_pesin', 'lang:messages_checkout_4_credit_cart_cash_form_name_text', 'trim|required|min_length[3]|xss_clean');

		$val->set_rules('kredi_karti_no_pesin_1', 'lang:messages_checkout_4_credit_cart_cash_form_credit_cart_text', 'trim|required|min_length[4]|max_length[4]|numeric|xss_clean');
		$val->set_rules('kredi_karti_no_pesin_2', 'lang:messages_checkout_4_credit_cart_cash_form_credit_cart_text', 'trim|required|min_length[4]|max_length[4]|numeric|xss_clean');
		$val->set_rules('kredi_karti_no_pesin_3', 'lang:messages_checkout_4_credit_cart_cash_form_credit_cart_text', 'trim|required|min_length[4]|max_length[4]|numeric|xss_clean');
		$val->set_rules('kredi_karti_no_pesin_4', 'lang:messages_checkout_4_credit_cart_cash_form_credit_cart_text', 'trim|required|min_length[4]|max_length[4]|numeric|xss_clean');

		$val->set_rules('kredi_karti_ay_pesin', 'lang:messages_checkout_4_credit_cart_cash_form_month_text', 'trim|required|is_numeric|xss_clean');
		$val->set_rules('kredi_karti_yil_pesin', 'lang:messages_checkout_4_credit_cart_cash_form_year_text', 'trim|required|is_numeric|xss_clean');
		$val->set_rules('kredi_karti_ccv_pesin', 'lang:messages_checkout_4_credit_cart_cash_form_ccv_text', 'trim|required|is_numeric|min_length[3]|max_length[3]|xss_clean');
	} elseif($kredi_karti_odeme_tipi == 'pesin') {
		$val->set_rules('kredi_karti_adi_pesin', 'lang:messages_checkout_4_credit_cart_cash_form_name_text', 'trim|required|min_length[3]|xss_clean');

		$val->set_rules('kredi_karti_no_pesin_1', 'lang:messages_checkout_4_credit_cart_cash_form_credit_cart_text', 'trim|required|min_length[4]|max_length[4]|numeric|xss_clean');
		$val->set_rules('kredi_karti_no_pesin_2', 'lang:messages_checkout_4_credit_cart_cash_form_credit_cart_text', 'trim|required|min_length[4]|max_length[4]|numeric|xss_clean');
		$val->set_rules('kredi_karti_no_pesin_3', 'lang:messages_checkout_4_credit_cart_cash_form_credit_cart_text', 'trim|required|min_length[4]|max_length[4]|numeric|xss_clean');
		$val->set_rules('kredi_karti_no_pesin_4', 'lang:messages_checkout_4_credit_cart_cash_form_credit_cart_text', 'trim|required|min_length[4]|max_length[4]|numeric|xss_clean');

		$val->set_rules('kredi_karti_ay_pesin', 'lang:messages_checkout_4_credit_cart_cash_form_month_text', 'trim|required|is_numeric|xss_clean');
		$val->set_rules('kredi_karti_yil_pesin', 'lang:messages_checkout_4_credit_cart_cash_form_year_text', 'trim|required|is_numeric|xss_clean');
		$val->set_rules('kredi_karti_ccv_pesin', 'lang:messages_checkout_4_credit_cart_cash_form_ccv_text', 'trim|required|is_numeric|min_length[3]|max_length[3]|xss_clean');
	} elseif($kredi_karti_odeme_tipi == 'taksit') {
		$val->set_rules('kredi_karti_adi_taksit', 'lang:messages_checkout_4_credit_cart_installment_form_name_text', 'trim|required|min_length[3]|xss_clean');

		$val->set_rules('kredi_karti_no_taksit_1', 'lang:messages_checkout_4_credit_cart_installment_form_credit_cart_text', 'trim|required|min_length[4]|max_length[4]|numeric|xss_clean');
		$val->set_rules('kredi_karti_no_taksit_2', 'lang:messages_checkout_4_credit_cart_installment_form_credit_cart_text', 'trim|required|min_length[4]|max_length[4]|numeric|xss_clean');
		$val->set_rules('kredi_karti_no_taksit_3', 'lang:messages_checkout_4_credit_cart_installment_form_credit_cart_text', 'trim|required|min_length[4]|max_length[4]|numeric|xss_clean');
		$val->set_rules('kredi_karti_no_taksit_4', 'lang:messages_checkout_4_credit_cart_installment_form_credit_cart_text', 'trim|required|min_length[4]|max_length[4]|numeric|xss_clean');

		$val->set_rules('kredi_karti_ay_taksit', 'lang:messages_checkout_4_credit_cart_installment_form_month_text', 'trim|required|is_numeric|xss_clean');
		$val->set_rules('kredi_karti_yil_taksit', 'lang:messages_checkout_4_credit_cart_installment_form_year_text', 'trim|required|is_numeric|xss_clean');
		$val->set_rules('kredi_karti_ccv_taksit', 'lang:messages_checkout_4_credit_cart_installment_form_ccv_text', 'trim|required|is_numeric|min_length[3]|max_length[3]|xss_clean');

		$val->set_rules('kredi_karti_taksit_sayisi', 'lang:messages_checkout_4_credit_cart_installment_form_installment_number_text', 'trim|required|xss_clean');
		$val->set_rules('kredi_karti_taksit_banka', 'lang:messages_checkout_4_credit_cart_installment_form_installment_bank_text', 'trim|required|xss_clean');
		$val->set_rules('kredi_karti_kart_tipi_taksit', 'lang:messages_checkout_4_credit_cart_installment_form_type_text', 'trim|required|xss_clean');
	} else {
		$val->set_rules('kredi_karti_adi_pesin', 'lang:messages_checkout_4_credit_cart_cash_form_name_text', 'trim|required|min_length[3]|xss_clean');

		$val->set_rules('kredi_karti_no_pesin_1', 'lang:messages_checkout_4_credit_cart_cash_form_credit_cart_text', 'trim|required|min_length[4]|max_length[4]|numeric|xss_clean');
		$val->set_rules('kredi_karti_no_pesin_2', 'lang:messages_checkout_4_credit_cart_cash_form_credit_cart_text', 'trim|required|min_length[4]|max_length[4]|numeric|xss_clean');
		$val->set_rules('kredi_karti_no_pesin_3', 'lang:messages_checkout_4_credit_cart_cash_form_credit_cart_text', 'trim|required|min_length[4]|max_length[4]|numeric|xss_clean');
		$val->set_rules('kredi_karti_no_pesin_4', 'lang:messages_checkout_4_credit_cart_cash_form_credit_cart_text', 'trim|required|min_length[4]|max_length[4]|numeric|xss_clean');

		$val->set_rules('kredi_karti_ay_pesin', 'lang:messages_checkout_4_credit_cart_cash_form_month_text', 'trim|required|is_numeric|xss_clean');
		$val->set_rules('kredi_karti_yil_pesin', 'lang:messages_checkout_4_credit_cart_cash_form_year_text', 'trim|required|is_numeric|xss_clean');
		$val->set_rules('kredi_karti_ccv_pesin', 'lang:messages_checkout_4_credit_cart_cash_form_ccv_text', 'trim|required|is_numeric|min_length[3]|max_length[3]|xss_clean');
		$val->set_rules('kredi_karti_kart_tipi_pesin', 'lang:messages_checkout_4_credit_cart_cash_form_type_text', 'trim|required|xss_clean');
	}

	$val->set_error_delimiters('', '');

	$kredi_karti_odeme_tipi = $this->input->post('kredi_karti_odeme_tipi');
	if($kredi_karti_odeme_tipi) {
		if($kredi_karti_odeme_tipi == 'pesin') {
			$kredi_karti_odeme_tipi_pesin_radio = 'checked="checked"';
			$kredi_karti_odeme_tipi_taksit_radio = '';
			$kredi_karti_odeme_tipi_pesin_div = 'display:block;';
			$kredi_karti_odeme_tipi_taksit_div = 'display:none;';
			$kredi_karti_odeme_tipi_pesin_aktif = ' k_aktif';
			$kredi_karti_odeme_tipi_taksit_aktif = '';
		} elseif($kredi_karti_odeme_tipi = 'taksit') {
			$kredi_karti_odeme_tipi_pesin_radio = '';
			$kredi_karti_odeme_tipi_taksit_radio = 'checked="checked"';
			$kredi_karti_odeme_tipi_pesin_div = 'display:none;';
			$kredi_karti_odeme_tipi_taksit_div = 'display:block;';
			$kredi_karti_odeme_tipi_pesin_aktif = '';
			$kredi_karti_odeme_tipi_taksit_aktif = ' k_aktif';
		} else {
			$kredi_karti_odeme_tipi_pesin_radio = 'checked="checked"';
			$kredi_karti_odeme_tipi_taksit_radio = '';
			$kredi_karti_odeme_tipi_pesin_div = 'display:block;';
			$kredi_karti_odeme_tipi_taksit_div = 'display:none;';
			$kredi_karti_odeme_tipi_pesin_aktif = ' k_aktif';
			$kredi_karti_odeme_tipi_taksit_aktif = '';
		}
	} else {
		$kredi_karti_odeme_tipi_pesin_radio = 'checked="checked"';
		$kredi_karti_odeme_tipi_taksit_radio = '';
		$kredi_karti_odeme_tipi_pesin_div = 'display:block;';
		$kredi_karti_odeme_tipi_taksit_div = 'display:none;';
		$kredi_karti_odeme_tipi_pesin_aktif = ' k_aktif';
		$kredi_karti_odeme_tipi_taksit_aktif = '';
	}

	$durum								= FALSE;

	if($this->input->post()) {
		if($kredi_karti_odeme_tipi == 'pesin') {
			$kredi_kart_adi				= $this->input->post('kredi_karti_adi_pesin');
			$kredi_kart_no_1			= $this->input->post('kredi_karti_no_pesin_1');
			$kredi_kart_no_2			= $this->input->post('kredi_karti_no_pesin_2');
			$kredi_kart_no_3			= $this->input->post('kredi_karti_no_pesin_3');
			$kredi_kart_no_4			= $this->input->post('kredi_karti_no_pesin_4');
			$kredi_kart_ccv				= $this->input->post('kredi_karti_ccv_pesin');
			$kredi_kart_ay				= $this->input->post('kredi_karti_ay_pesin');
			$kredi_kart_yil				= $this->input->post('kredi_karti_yil_pesin');
			$kredi_kart_tip				= $this->input->post('kredi_karti_kart_tipi_pesin');
			$kredi_kart_taksit			= 0;
			$banka_kontrol_no			= FALSE;
		} elseif($this->input->post('kredi_karti_odeme_tipi') == 'taksit') {
			$kredi_kart_adi				= $this->input->post('kredi_karti_adi_taksit');
			$kredi_kart_no_1			= $this->input->post('kredi_karti_no_taksit_1');
			$kredi_kart_no_2			= $this->input->post('kredi_karti_no_taksit_2');
			$kredi_kart_no_3			= $this->input->post('kredi_karti_no_taksit_3');
			$kredi_kart_no_4			= $this->input->post('kredi_karti_no_taksit_4');
			$kredi_kart_ccv				= $this->input->post('kredi_karti_ccv_taksit');
			$kredi_kart_ay				= $this->input->post('kredi_karti_ay_taksit');
			$kredi_kart_yil				= $this->input->post('kredi_karti_yil_taksit');
			$kredi_kart_tip				= $this->input->post('kredi_karti_kart_tipi_taksit');
			if($this->input->post('kredi_karti_taksit_sayisi'))
			{
				$taksit_kontrol			= explode('_', $this->input->post('kredi_karti_taksit_sayisi'));
				$kredi_kart_taksit		= $taksit_kontrol[1];
				$banka_kontrol_no		= $taksit_kontrol[0];
			} else {
				$kredi_kart_taksit		= 0;
				$banka_kontrol_no		= FALSE;
			}
		} else {
			$kredi_kart_adi				= $this->input->post('kredi_karti_adi_pesin');
			$kredi_kart_no_1			= $this->input->post('kredi_karti_no_pesin_1');
			$kredi_kart_no_2			= $this->input->post('kredi_karti_no_pesin_2');
			$kredi_kart_no_3			= $this->input->post('kredi_karti_no_pesin_3');
			$kredi_kart_no_4			= $this->input->post('kredi_karti_no_pesin_4');
			$kredi_kart_ccv				= $this->input->post('kredi_karti_ccv_pesin');
			$kredi_kart_ay				= $this->input->post('kredi_karti_ay_pesin');
			$kredi_kart_yil				= $this->input->post('kredi_karti_yil_pesin');
			$kredi_kart_tip				= $this->input->post('kredi_karti_kart_tipi_pesin');
			$kredi_kart_taksit			= 0;
			$banka_kontrol_no			= FALSE;
		}
	} else {
		$kredi_kart_adi					= NULL;
		$kredi_kart_no_1				= NULL;
		$kredi_kart_no_2				= NULL;
		$kredi_kart_no_3				= NULL;
		$kredi_kart_no_4				= NULL;
		$kredi_kart_ccv					= NULL;
		$kredi_kart_ay					= NULL;
		$kredi_kart_yil					= NULL;
		$kredi_kart_taksit				= NULL;
		$banka_kontrol_no				= FALSE;
		$kredi_kart_tip					= NULL;
	}

	$siparis_id 						= $siparis_bilgi->siparis_id;
	$user_id							= $this->dx_auth->get_user_id();
	$siparis_no_bankaya_gore			= md5($user_id . $siparis_id . time());

	$toplam_ucret = 0;
	$toplam_ucret += $stok_toplam_fiyat;
	if(config('site_ayar_kdv_goster') == '1') {
		$toplam_ucret += $toplam_kdv_fiyati;
	}
	if($kargo_ucret > 0) {
		$toplam_ucret += $kargo_ucret;
	}
	if($kupon_ucret > 0) {
		$toplam_ucret -= $kupon_ucret;
	}
	if($toplam_ucret <= 0) {
		$toplam_ucret = 0.01;
	}

	$kredi_kart_no						= trim($kredi_kart_no_1) . trim($kredi_kart_no_2) . trim($kredi_kart_no_3) . trim($kredi_kart_no_4);
	$kart_bin_no						= substr($kredi_kart_no, 0, 6);

	$this->db->select(
		get_fields_from_table('odeme_secenek_kredi_karti_bin_numaralari', 'oskkbn.', array(), ', ') .
		get_fields_from_table('odeme_secenek_kredi_karti', 'oskk.', array(), ', ')
	);
	$this->db->from('odeme_secenek_kredi_karti_bin_numaralari oskkbn');
	$this->db->join('odeme_secenek_kredi_karti oskk', 'oskkbn.kkbn_kk_id = oskk.kk_id', 'left');
	$this->db->where('oskkbn.kkbn_bin_no', $kart_bin_no);
	$this->db->where('oskkbn.kkbn_durum', '1');
	$this->db->where('oskkbn.kkbn_kk_id >', '0');
	$this->db->where('oskk.kk_banka_durum', '1');
	$this->db->limit('1');
	$bin_no_kontrol						= $this->db->get();

	if($banka_kontrol_no) {
		$banka_sec_b_id = $banka_kontrol_no;
	} else {
		if($bin_no_kontrol->num_rows()) {
			$bin_bilgi					= $bin_no_kontrol->row();
			$banka_sec_b_id				= $bin_bilgi->kkbn_kk_id;
		} else {
			$standart_banka_kontrol		= $this->db->get_where('odeme_secenek_kredi_karti', array('kk_banka_standart' => '1', 'kk_banka_durum' => '1'), 1);
			if($standart_banka_kontrol->num_rows()) {
				$standart_banka_bilgi	= $standart_banka_kontrol->row();
				$banka_sec_b_id			= $standart_banka_bilgi->kk_id;
			} else {
				$banka_sec_b_id			= 0;
			}
		}
	}

	$this->db->order_by('kk_id', 'desc');
	$odeme_secenek_kredi_karti_sorgu	= $this->db->get_where('odeme_secenek_kredi_karti', array('kk_banka_durum' => '1', 'kk_banka_taksit' => '1'));

	if ($val->run() == TRUE) {
		$extra_degerler = array(
			'siparis_id' => $siparis_id,
			'fatura_id' => $fatura_id
		);

		$sablon = '
			<div id="odeme_secimi">
				<div class="odeme_baslik">'. lang('messages_checkout_title_payment_details') .'</div>
				<div id="k_yonlendir">{mesaj} <img src="'. site_resim() .'yonlendir.gif" alt="yonlendir" /></div>
			</div>
		';

		$banka_pos_pesin_mesaji = strtr($sablon, array('{mesaj}' => lang('messages_checkout_4_credit_cart_check_data')));
		$banka_pos_3d_mesaji = strtr($sablon, array('{mesaj}' => lang('messages_checkout_4_credit_cart_check_redirect_bank')));

		$this->config->set_item('banka_pos_pesin_mesaji', $banka_pos_pesin_mesaji);
		$this->config->set_item('banka_pos_3d_mesaji', $banka_pos_3d_mesaji);

		if($sanal_pos = $this->sanal_pos->banka_sec_baglan($banka_sec_b_id, $extra_degerler) AND $sanal_pos->durum) {
			if($this->input->post('kredi_karti_odeme_tipi') == 'taksit') {
				$taksit_kontrol				= explode('_', $this->input->post('kredi_karti_taksit_sayisi'));
				$kredi_kart_taksit			= $taksit_kontrol[1];
				$banka_kontrol_no			= $taksit_kontrol[0];

				$this->db->order_by('kkts_taksit_sayisi', 'asc');
				$taksit_sayisi_sorgu = $this->db->get_where('odeme_secenek_kredi_karti_taksit_secenekleri', array('kk_id' => $banka_kontrol_no, 'kkts_taksit_sayisi' => $kredi_kart_taksit, 'kkts_durum' => '1'), 1);
				if($taksit_sayisi_sorgu->num_rows() > 0)
				{
					$taksit_sayisi_bilgi = $taksit_sayisi_sorgu->row();
					$komisyon_ucreti_hesapla = (floatval('1.' . $taksit_sayisi_bilgi->kkts_komisyon)) ? floatval('1.' . $taksit_sayisi_bilgi->kkts_komisyon):'0.00';
					$toplam_ucret = ($toplam_ucret * $komisyon_ucreti_hesapla);
				}
			}

			//Örnek Gidecek Veriler
			//$siparis_bilgi_gonder->siparis_id = '1';
			//$siparis_bilgi_gonder->fatura_id = '2';
			//$siparis_bilgi_gonder->ip_adres = '127.0.0.1';
			//$siparis_bilgi_gonder->email_adres = 'etinali@gmail.com';
			//$siparis_bilgi_gonder->user_id = '31';
			//$siparis_bilgi_gonder->fiyat = '5.50';
			//$siparis_bilgi_gonder->taksit = 0;
			//$siparis_bilgi_gonder->kart_numarasi = '1111111111111111';
			//$siparis_bilgi_gonder->kart_numarasi_ay = '11';
			//$siparis_bilgi_gonder->kart_numarasi_yil = '11';
			//$siparis_bilgi_gonder->kart_numarasi_guvenlik_kodu = '111';
			//$siparis_bilgi_gonder->kart_tipi = $kredi_kart_tip;
			//$siparis_bilgi_gonder->fatura_bilgi = $fatura_bilgi;
			//$siparis_bilgi_gonder->teslimat_bilgi = $teslimat_bilgi;
			//$siparis_bilgi_gonder->uye_bilgi = get_usr_ide_inf($user_id);

			$_siparis_detay = $this->session->userdata('siparis_detay');
			$_siparis_detay['kredi_kart']['kart_numarasi'] = $kredi_kart_no;
			$_siparis_detay['kredi_kart']['taksit_sayisi'] = $kredi_kart_taksit;
			$_siparis_detay['kredi_kart']['toplam_ucret'] = number_format($toplam_ucret, 2, '.', '');
			$this->session->set_userdata('siparis_detay', $_siparis_detay);

			$form_deger_yolla->siparis_id							= $siparis_id;
			$form_deger_yolla->fatura_id							= $fatura_id;
			$form_deger_yolla->ip_adres								= $this->input->ip_address();
			$form_deger_yolla->email_adres							= $this->dx_auth->get_username();
			$form_deger_yolla->user_id								= $user_id;
			$form_deger_yolla->fiyat								= number_format($toplam_ucret, 2, '.', '');
			$form_deger_yolla->taksit								= $kredi_kart_taksit;
			$form_deger_yolla->kart_numarasi						= $kredi_kart_no;
			$form_deger_yolla->kart_numarasi_ay						= trim($kredi_kart_ay);
			$form_deger_yolla->kart_numarasi_yil					= trim($kredi_kart_yil);
			$form_deger_yolla->kart_numarasi_guvenlik_kodu			= trim($kredi_kart_ccv);
			$form_deger_yolla->kart_tipi							= $kredi_kart_tip;
			$form_deger_yolla->fatura_bilgi							= $fatura_bilgi;
			$form_deger_yolla->teslimat_bilgi						= $teslimat_bilgi;
			$form_deger_yolla->uye_bilgi							= get_usr_ide_inf($user_id);
			$form_gonder											= $sanal_pos->class->form_gonder($form_deger_yolla);
			echo $form_gonder->veri;
		} else {
			echo $sanal_pos->mesaj;
		}
	} else {
?>

	<?php echo form_open_ssl('odeme/adim_4/kredi_karti/'. $siparis_id .'/'. $fatura_id, array('name' => 'form_devam_et', 'id' => 'form_devam_et')); ?>
	<?php
		echo form_hidden('siparis_id', $siparis_id);
		echo form_hidden('fatura_id', $fatura_id);
		$aylar['01'] 					= '01';
		$aylar['02'] 					= '02';
		$aylar['03'] 					= '03';
		$aylar['04'] 					= '04';
		$aylar['05'] 					= '05';
		$aylar['06'] 					= '06';
		$aylar['07'] 					= '07';
		$aylar['08'] 					= '08';
		$aylar['09'] 					= '09';
		$aylar['10'] 					= '10';
		$aylar['11'] 					= '11';
		$aylar['12'] 					= '12';

		$kart_tipi['visa'] 				= lang('messages_checkout_cart_type_visa');
		$kart_tipi['master'] 			= lang('messages_checkout_cart_type_master');

		$yillar = array();
		for($i = date('Y'); $i <= date('Y') + 15; $i++) {
			$yillar[$i]					= $i;
		}
	?>
	<div id="odeme_secimi">
		<div class="odeme_baslik"><?php echo lang('messages_checkout_title_payment_details'); ?></div>
		<div id="o_sol" class="sola">
			<div id="os_bilgi_bg">
				<div id="os_bilgi">
					<div id="os_rak">
						<i><?php echo lang('messages_checkout_4_credit_cart_sub_total'); ?></i>
						<b><?php echo format_number($stok_toplam_fiyat); ?> TL</b>
					<?php if($kupon_ucret > 0) { ?>
						<i class="s_yesil"><?php echo lang('messages_checkout_4_credit_cart_coupon_total'); ?></i>
						<b class="s_yesil">-<?php echo format_number($kupon_ucret); ?> TL</b>
					<?php } ?>
						<i><?php echo lang('messages_checkout_4_credit_cart_shipping_total'); ?></i>
						<b><?php echo format_number($kargo_ucret); ?> TL</b>
						<?php $hidden = (config('site_ayar_kdv_goster') == '0') ? ' style="visibility:hidden;"':NULL; ?>
						<i<?php echo $hidden; ?>><?php echo lang('messages_checkout_4_credit_cart_vat_total'); ?></i>
						<b<?php echo $hidden; ?>><?php echo format_number($toplam_kdv_fiyati); ?> TL</b>
						<div class="clear"></div>
					</div>
					<div id="os_toplam">
						<i><?php echo lang('messages_checkout_4_credit_cart_total'); ?></i>
						<b>
							<?php
								$total_price = 0;
								$total_price += $stok_toplam_fiyat;
								if(config('site_ayar_kdv_goster') == '1') {
									$total_price += $toplam_kdv_fiyati;
								}
								if($kargo_ucret > 0) {
									$total_price += $kargo_ucret;
								}
								if($kupon_ucret > 0) {
									$total_price -= $kupon_ucret;
								}
								if($total_price <= 0) {
									$total_price = 0.01;
								}
								echo format_number($total_price) . ' TL';
							?>
						</b>
						<div class="clear"></div>
					</div>
				</div>
			</div>
		</div>
		<div id="o_sag" class="saga">
			<div class="adim_info adim_gri"><?php echo lang('messages_checkout_4_credit_cart_information'); ?></div>
		</div>
		<div class="clear"></div>
	</div>
	<div id="os">
		<div id="os_kredi">
			<b class="os_baslik siterenk"><?php echo lang('messages_checkout_4_credit_cart_payment_title'); ?></b>
			<div id="os_kredi_linkler">
				<a id="k_tek_cekim" class="sola<?php echo $kredi_karti_odeme_tipi_pesin_aktif; ?>" href="javascript:;"><?php echo lang('messages_checkout_4_credit_cart_cash_title'); ?></a>
				<?php if($odeme_secenek_kredi_karti_sorgu->num_rows()) { ?>
				<a id="k_taksitli_cekim" class="sola<?php echo $kredi_karti_odeme_tipi_taksit_aktif; ?>" href="javascript:;"><?php echo lang('messages_checkout_4_credit_cart_installment_title'); ?></a>
				<?php } ?>
			</div>
			<div class="clear"></div>
			<div id="os_k_tablar">
				<div id="k_tek_cekim_tab" style="<?php echo $kredi_karti_odeme_tipi_pesin_div; ?>">
					<div class="k_baslik k_tek">
						<input type="radio" name="kredi_karti_odeme_tipi" value="pesin" <?php echo $kredi_karti_odeme_tipi_pesin_radio; ?> id="k_tek_cekim_tip" class="k_tip_secim" />
						<b><?php echo lang('messages_checkout_4_credit_cart_cash_form_title'); ?></b>
						<br />
						<?php echo lang('messages_checkout_4_credit_cart_cash_form_information'); ?>
					</div>
					<div class="k_form">
						<b><?php echo lang('messages_checkout_4_credit_cart_cash_form_name_text'); ?></b>
						<p><input type="text" name="kredi_karti_adi_pesin" id="kredi_karti_adi_pesin" onkeydown="ctrlCEngelle(event);" style="font-weight:bold;text-align:center;" /></p>
						<?php
							if(form_error('kredi_karti_adi_pesin')) {
								echo '<i>' . form_error('kredi_karti_adi_pesin') . '</i>';
							}
						?>
						<div class="clear"></div>
						<b><?php echo lang('messages_checkout_4_credit_cart_cash_form_credit_cart_text'); ?></b>
						<u id="pesin_kart_no_keydown">
							<input type="text" name="kredi_karti_no_pesin_1" onkeypress="return SadeceRakam(event);" onblur="SadeceRakamBlur(event,false);" onkeydown="ctrlCEngelle(event);" maxlength="4" class="kart_no" id="kredi_karti_no_pesin_1" style="text-align:center;font-weight:bold;" />
							<input type="text" name="kredi_karti_no_pesin_2" onkeypress="return SadeceRakam(event);" onblur="SadeceRakamBlur(event,false);" onkeydown="ctrlCEngelle(event);" maxlength="4" class="kart_no" id="kredi_karti_no_pesin_2" style="text-align:center;font-weight:bold;" />
							<input type="text" name="kredi_karti_no_pesin_3" onkeypress="return SadeceRakam(event);" onblur="SadeceRakamBlur(event,false);" onkeydown="ctrlCEngelle(event);" maxlength="4" class="kart_no" id="kredi_karti_no_pesin_3" style="text-align:center;font-weight:bold;" />
							<input type="text" name="kredi_karti_no_pesin_4" onkeypress="return SadeceRakam(event);" onblur="SadeceRakamBlur(event,false);" onkeydown="ctrlCEngelle(event);" maxlength="4" class="kart_no" id="kredi_karti_no_pesin_4" style="text-align:center;font-weight:bold;" />
						</u>
						<?php if(form_error('kredi_karti_no_pesin_1') OR form_error('kredi_karti_no_pesin_2') OR form_error('kredi_karti_no_pesin_3') OR form_error('kredi_karti_no_pesin_4')) { ?>
						<i>
							<?php
								if(form_error('kredi_karti_no_pesin_1')) {
									echo form_error('kredi_karti_no_pesin_1');
								} elseif(form_error('kredi_karti_no_pesin_2')) {
									echo form_error('kredi_karti_no_pesin_2');
								} elseif(form_error('kredi_karti_no_pesin_3')) {
									echo form_error('kredi_karti_no_pesin_3');
								} elseif(form_error('kredi_karti_no_pesin_4')) {
									echo form_error('kredi_karti_no_pesin_4');
								}
							?>
						</i>
						<?php } ?>
						<div class="clear"></div>
						<b><?php echo lang('messages_checkout_4_credit_cart_cash_form_type_text'); ?></b>
						<p>
							<?php echo form_dropdown('kredi_karti_kart_tipi_pesin', $kart_tipi, '', 'class="k_orta"'); ?>
						</p>
						<?php
							if(form_error('kredi_karti_kart_tipi_pesin')) {
								echo '<i>' . form_error('kredi_karti_kart_tipi_pesin') . '</i>';
							}
						?>
						<div class="clear"></div>
						<b><?php echo lang('messages_checkout_4_credit_cart_cash_form_expiration_date_text'); ?></b>
						<p>
							<?php echo form_dropdown('kredi_karti_ay_pesin', $aylar, '', 'class="k_kisa"'); ?>
							<?php echo form_dropdown('kredi_karti_yil_pesin', $yillar, '', 'class="k_orta"'); ?>
						</p>
						<?php if(form_error('kredi_karti_ay_pesin') OR form_error('kredi_karti_yil_pesin')) { ?>
						<i>
							<?php
								$_error = array();
								if(form_error('kredi_karti_ay_pesin')) {
									$_error[] = form_error('kredi_karti_ay_pesin');
								}
								if(form_error('kredi_karti_yil_pesin')) {
									$_error[] = form_error('kredi_karti_yil_pesin');
								}
								echo implode('<br />', $_error);
								unset($_error);
							?>
						</i>
						<?php } ?>
						<div class="clear"></div>
						<b><?php echo lang('messages_checkout_4_credit_cart_cash_form_ccv_text'); ?></b>
						<u><input type="text" name="kredi_karti_ccv_pesin" id="kredi_karti_ccv_pesin" class="sanal_klavye_ccv" onkeypress="return SadeceRakam(event);" onblur="SadeceRakamBlur(event,false);" onkeydown="ctrlCEngelle(event);" maxlength="3" style="font-weight:bold;text-align:center;" /></u>
						<?php
							if(form_error('kredi_karti_ccv_pesin')) {
								echo '<i>' . form_error('kredi_karti_ccv_pesin') . '</i>';
							}
						?>
						<div class="clear"></div>
					</div>
				</div>
				<?php if($odeme_secenek_kredi_karti_sorgu->num_rows()) { ?>
				<div id="k_taksitli_cekim_tab" style="<?php echo $kredi_karti_odeme_tipi_taksit_div; ?>">
					<div class="k_baslik k_taksit">
						<input type="radio" name="kredi_karti_odeme_tipi" value="taksit" <?php echo $kredi_karti_odeme_tipi_taksit_radio; ?> id="k_taksitli_cekim_tip" class="k_tip_secim" />
						<b><?php echo lang('messages_checkout_4_credit_cart_installment_form_title'); ?></b>
						<br />
						<?php echo lang('messages_checkout_4_credit_cart_installment_form_information'); ?>
					</div>
					<div id="k_banka_secim" class="k_form">
						<b><?php echo lang('messages_checkout_4_credit_cart_installment_form_choose_bank'); ?></b>
						<p>
						<?php
							if($odeme_secenek_kredi_karti_sorgu->num_rows()) {
								echo '<select name="kredi_karti_taksit_banka" id="kredi_karti_taksit_banka" onchange="taksit_banka_degistir($(this).val());">';
								echo '<option value=""> - '. lang('messages_checkout_4_credit_cart_installment_form_choose_bank_card') .' - </option>';
								foreach($odeme_secenek_kredi_karti_sorgu->result() as $bankalar) {
									$this->db->order_by('kkts_taksit_sayisi', 'asc');
									$taksit_sayisi_sorgu = $this->db->get_where('odeme_secenek_kredi_karti_taksit_secenekleri', array('kk_id' => $bankalar->kk_id, 'kkts_durum' => '1'));
									if($taksit_sayisi_sorgu->num_rows()) {
										echo '<option value="'. $bankalar->kk_id .'"> - '. $bankalar->kk_banka_adi .' - </option>';
									}
								}
								echo '</select>';
								if(form_error('kredi_karti_taksit_banka')) {
									echo '<i style="display:inline;">' . form_error('kredi_karti_taksit_banka') . '</i>';
								}
							}
						?>
						</p>
					</div>
					<?php
						foreach($odeme_secenek_kredi_karti_sorgu->result() as $bankalar) {
							$this->db->order_by('kkts_taksit_sayisi', 'asc');
							$taksit_sayisi_sorgu = $this->db->get_where('odeme_secenek_kredi_karti_taksit_secenekleri', array('kk_id' => $bankalar->kk_id, 'kkts_durum' => '1'));
							if($taksit_sayisi_sorgu->num_rows()) {
					?>
					<div id="taksit_banka_div_<?php echo $bankalar->kk_id; ?>" style="display:none;" class="k_taksit_form banka_input_disable">
						<div class="k_tf_sol sola">
							<img src="<?php echo site_resim() . $bankalar->kk_banka_resim; ?>" alt="" />
						</div>
						<div class="k_tf_sag saga">
							<div class="k_tf_oge k_tf_baslik">
								<u>&nbsp;</u>
								<b><?php echo lang('messages_checkout_4_credit_cart_installment_form_commission_rate'); ?></b>
								<i><?php echo lang('messages_checkout_4_credit_cart_installment_form_installment_total'); ?></i>
								<b><?php echo lang('messages_checkout_4_credit_cart_installment_form_installment_number'); ?></b>
								<b><?php echo lang('messages_checkout_4_credit_cart_installment_form_total'); ?></b>
								<div class="clear"></div>
							</div>
							<?php
								$i = 0;
								foreach($taksit_sayisi_sorgu->result() as $taksit) {
									$komisyon_ucreti_hesapla_sade = (floatval('0.' . $taksit->kkts_komisyon)) ? floatval('0.' . $taksit->kkts_komisyon) : '0.00';
									$komisyon_ucreti_hesapla = (floatval('1.' . $taksit->kkts_komisyon)) ? floatval('1.' . $taksit->kkts_komisyon) : '0.00';
									if($i == 0) {
										$check = 'checked="checked"';
									} else {
										$check = NULL;
									}
									if($taksit->kkts_komisyon[0] == 0) {
										$komisyon = $taksit->kkts_komisyon[1];
									} else {
										$komisyon = $taksit->kkts_komisyon;
									}

									echo '
										<div class="k_tf_oge">
											<u><input type="radio" name="kredi_karti_taksit_sayisi" value="'. $bankalar->kk_id . '_' . $taksit->kkts_taksit_sayisi .'" disabled="disabled" /></u>
											<b>%'. $komisyon .'</b>
											<i>'. number_format((($toplam_ucret * $komisyon_ucreti_hesapla) / $taksit->kkts_taksit_sayisi), 2, '.', '') .' TL</i>
											<b>'. $taksit->kkts_taksit_sayisi .' '. lang('messages_checkout_4_credit_cart_installment_form_month') .'</b>
											<b>'. number_format($toplam_ucret * $komisyon_ucreti_hesapla, 2, '.', '') .' TL</b>
											<div class="clear"></div>
										</div>
									';

									$i++;
								}
							?>
						</div>
						<div class="clear"></div>
					</div>
						<?php } ?>
					<?php } ?>
					<div id="div_id_kredi_taksit_box" style="display:none;" class="k_form">
						<b><?php echo lang('messages_checkout_4_credit_cart_installment_form_name_text'); ?></b>
						<p><input type="text" name="kredi_karti_adi_taksit" id="kredi_karti_adi_taksit" onkeydown="ctrlCEngelle(event);" style="font-weight:bold;text-align:center;" /></p>
						<?php
							if(form_error('kredi_karti_adi_taksit')) {
								echo '<i>' . form_error('kredi_karti_adi_taksit') . '</i>';
							}
						?>
						<div class="clear"></div>
						<div class="clear"></div>
						<b><?php echo lang('messages_checkout_4_credit_cart_cash_form_credit_cart_text'); ?></b>
						<u id="taksit_kart_no_keydown">
							<input type="text" name="kredi_karti_no_taksit_1" onkeypress="return SadeceRakam(event);" onblur="SadeceRakamBlur(event,false);" onkeydown="ctrlCEngelle(event);" maxlength="4" class="kart_no" id="kredi_karti_no_taksit_1" style="text-align:center;font-weight:bold;" />
							<input type="text" name="kredi_karti_no_taksit_2" onkeypress="return SadeceRakam(event);" onblur="SadeceRakamBlur(event,false);" onkeydown="ctrlCEngelle(event);" maxlength="4" class="kart_no" id="kredi_karti_no_taksit_2" style="text-align:center;font-weight:bold;" />
							<input type="text" name="kredi_karti_no_taksit_3" onkeypress="return SadeceRakam(event);" onblur="SadeceRakamBlur(event,false);" onkeydown="ctrlCEngelle(event);" maxlength="4" class="kart_no" id="kredi_karti_no_taksit_3" style="text-align:center;font-weight:bold;" />
							<input type="text" name="kredi_karti_no_taksit_4" onkeypress="return SadeceRakam(event);" onblur="SadeceRakamBlur(event,false);" onkeydown="ctrlCEngelle(event);" maxlength="4" class="kart_no" id="kredi_karti_no_taksit_4" style="text-align:center;font-weight:bold;" />
						</u>
						<i>
							<?php
								if(form_error('kredi_karti_no_taksit_1')) {
									echo form_error('kredi_karti_no_taksit_1');
								} elseif(form_error('kredi_karti_no_taksit_2')) {
									echo form_error('kredi_karti_no_taksit_2');
								} elseif(form_error('kredi_karti_no_taksit_3')) {
									echo form_error('kredi_karti_no_taksit_3');
								} elseif(form_error('kredi_karti_no_taksit_4')) {
									echo form_error('kredi_karti_no_taksit_4');
								}
							?>
						</i>
						<div class="clear"></div>
						<b><?php echo lang('messages_checkout_4_credit_cart_cash_form_type_text'); ?></b>
						<p>
							<?php echo form_dropdown('kredi_karti_kart_tipi_taksit', $kart_tipi, '', 'class="k_orta"'); ?>
						</p>
						<?php
							if(form_error('kredi_karti_kart_tipi_taksit')) {
								echo '<i>' . form_error('kredi_karti_kart_tipi_taksit') . '</i>';
							}
						?>
						<div class="clear"></div>
						<b><?php echo lang('messages_checkout_4_credit_cart_cash_form_expiration_date_text'); ?></b>
						<p>
							<?php echo form_dropdown('kredi_karti_ay_taksit', $aylar, '', 'class="k_kisa"'); ?>
							<?php echo form_dropdown('kredi_karti_yil_taksit', $yillar, '', 'class="k_orta"'); ?>
						</p>
						<?php if(form_error('kredi_karti_ay_taksit') OR form_error('kredi_karti_yil_taksit')) { ?>
						<i>
							<?php
								$_error = array();
								if(form_error('kredi_karti_ay_taksit')) {
									$_error[] = form_error('kredi_karti_ay_taksit');
								}
								if(form_error('kredi_karti_yil_taksit')) {
									$_error[] = form_error('kredi_karti_yil_taksit');
								}
								echo implode('<br />', $_error);
								unset($_error);
							?>
						</i>
						<?php } ?>
						<div class="clear"></div>
						<b><?php echo lang('messages_checkout_4_credit_cart_cash_form_ccv_text'); ?></b>
						<u><input type="text" name="kredi_karti_ccv_taksit" id="kredi_karti_ccv_taksit" class="sanal_klavye_ccv" onkeypress="return SadeceRakam(event);" onblur="SadeceRakamBlur(event,false);" onkeydown="ctrlCEngelle(event);" maxlength="3" style="font-weight:bold;text-align:center;" /></u>
						<?php
							if(form_error('kredi_karti_ccv_taksit')) {
								echo '<i>' . form_error('kredi_karti_ccv_taksit') . '</i>';
							}
						?>
						<div class="clear"></div>
					</div>
				</div>
				<?php } ?>
			</div>
			<div id="k_tablar_alt"></div>
		</div>
		<div class="clear"></div>
		<div style="margin-left:10px;">
		<a class="sitelink2" id="os_diger" href="javascript:;" onclick="redirect('<?php echo ssl_url('odeme/adim_3/'. $siparis_id .'/'. $fatura_id); ?>');" class="info" title="<?php echo lang('messages_checkout_4_credit_cart_other_payment_options_title'); ?>">
			<?php echo lang('messages_checkout_4_credit_cart_other_payment_options'); ?>
		</a>
		</div>
		<div class="clear"></div>
		<div id="os_buton">
			<a href="javascript:;" onclick="$('#form_devam_et').submit();" class="butonum">
				<span class="butsol"></span>
				<span class="butor"><?php echo lang('messages_checkout_4_credit_cart_form_button_text'); ?></span>
				<span class="butsag"></span>
			</a>
			
		</div>
	</div>
	<?php echo form_close(); ?>

	<script type="text/javascript" charset="utf-8">

		$(document).ready(function(){
			<?php if($kredi_karti_odeme_tipi == 'taksit') { ?>
				<?php if($this->input->post('kredi_karti_taksit_banka') != '') { ?>
				$('#kredi_karti_taksit_banka option[value="<?php echo $this->input->post('kredi_karti_taksit_banka'); ?>"]').attr('selected', 'selected');
				taksit_banka_degistir('<?php echo $this->input->post('kredi_karti_taksit_banka'); ?>');
				<?php } ?>
			<?php } ?>
		});

		$('#pesin_kart_no_keydown input').keydown(function(e) {
			var val = $(this).val();
			if(val.length == 4) {
				if(e.keyCode != 8 && e.keyCode != 46) {
					$(this).next().focus();
				}
			} else if(val.length == 0) {
				if(e.keyCode == 8) {
					$(this).prev().focus();
				}
			}
		});

		$('#pesin_kart_no_keydown input').keyup(function(e) {
			var val = $(this).val();
			if(val.length == 4) {
				if(e.keyCode != 8 && e.keyCode != 46) {
					$(this).next().focus();
				}
			} else if(val.length == 0) {
				if(e.keyCode == 8) {
					$(this).prev().focus();
				}
			}
		});

		$('#taksit_kart_no_keydown input').keydown(function(e) {
			var val = $(this).val();
			if(val.length == 4) {
				if(e.keyCode != 8 && e.keyCode != 46) {
					$(this).next().focus();
				}
			} else if(val.length == 0) {
				if(e.keyCode == 8) {
					$(this).prev().focus();
				}
			}
		});

		$('#taksit_kart_no_keydown input').keyup(function(e) {
			var val = $(this).val();
			if(val.length == 4) {
				if(e.keyCode != 8 && e.keyCode != 46) {
					$(this).next().focus();
				}
			} else if(val.length == 0) {
				if(e.keyCode == 8) {
					$(this).prev().focus();
				}
			}
		});

		$(function() {
			$(this).bind("contextmenu", function(e) {
				e.preventDefault();
			});
		});

		function ctrlCEngelle(e) {
			olay = document.all ? window.event : e;
			tus = document.all ? olay.keyCode : olay.which;
			if(olay.ctrlKey && (tus==99 || tus==67 || tus==118 || tus==86)) {
				if(document.all) {
					olay.returnValue = false;
				} else {
					olay.preventDefault();
				}
			}
		}

		function SadeceRakam(e, allowedchars) {
			var key = e.charCode == undefined ? e.keyCode : e.charCode;
			if ( (/^[0-9]+$/.test(String.fromCharCode(key))) || key==0 || key==13 || isPassKey(key,allowedchars) ) {
				return true;
			} else {
				return false;
			}
		}

		function isPassKey(key,allowedchars) {
			if (allowedchars != null) {
				for (var i = 0; i < allowedchars.length; i++) {
					if (allowedchars[i]  == String.fromCharCode(key)) {
						return true;
					}
				}
			}
			return false;
		}

		function SadeceRakamBlur(e,clear) {
			var nesne = e.target ? e.target : e.srcElement;
			var val = nesne.value;
			val = val.replace(/^\s+|\s+$/g, "");
			if (clear) {
				val = val.replace(/\s{2,}/g, " ");
			}
			nesne.value = val;
		}

		function taksit_banka_degistir(banka_id) {
			$('.banka_input_disable input').attr('disabled', 'disabled');
			$('.banka_input_disable input').attr('checked', false);
			$('.banka_input_disable').hide();
			$('#div_id_kredi_taksit_box').hide();
			$('#div_id_kredi_karti_taksit_baslik').hide();

			$('#taksit_banka_div_' + banka_id + ' input').removeAttr('disabled');
			$('#taksit_banka_div_' + banka_id + ' input').first().attr('checked', true);
			$('#taksit_banka_div_' + banka_id).show();
			if(banka_id != '') {
				$('#div_id_kredi_taksit_box').show();
				$('#div_id_kredi_karti_taksit_baslik').show();
			}
		}

		$('.sanal_klavye').keypad(
			{
				showOn: 'button', 
				buttonImageOnly: true,
				buttonImage: resim_url_n + 'keypad.png',
				keypadOnly: false,
				showAnim: 'fadeIn',
				showOptions: {direction: 'up'},
				duration: 'fast',
				randomiseNumeric: true
			}
		);

		$('.sanal_klavye_ccv').keypad(
			{
				showOn: 'both', 
				buttonImageOnly: true,
				buttonImage: resim_url_n + 'keypad.png',
				showAnim: 'fadeIn',
				showOptions: {direction: 'up'},
				duration: 'fast',
				randomiseNumeric: true
			}
		);

		$.keypad.regional['tr'] = {
			buttonText: '...',
			buttonStatus: 'Aç',
			closeText: 'Kapat',
			closeStatus: 'Klavyeyi Kapatır',
			clearText: 'Sil',
			clearStatus: 'İçerisini Temizler',
			backText: 'Geri Al',
			backStatus: 'Son Karakteri Siler.',
			shiftText: 'Büyüt',
			shiftStatus: 'Büyük Harfle Yazmak İçin Seçiniz.',
			spacebarText: '&nbsp;',
			spacebarStatus: '',
			enterText: 'Enter',
			enterStatus: '',
			tabText: '→',
			tabStatus: '',
			alphabeticLayout: $.keypad.qwertyAlphabetic,
			fullLayout: $.keypad.qwertyLayout,
			isAlphabetic: $.keypad.isAlphabetic,
			isNumeric: $.keypad.isNumeric,
			isRTL: false
		};

		$.keypad.setDefaults($.keypad.regional['<?php echo get_language('code'); ?>']);

	</script>

<?php } ?>

<?php $this->load->view(tema() . 'odeme/footer'); ?>