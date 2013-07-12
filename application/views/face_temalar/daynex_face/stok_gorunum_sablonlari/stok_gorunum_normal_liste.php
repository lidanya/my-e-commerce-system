<?php
	/* tasarımcıların değiştireceği alanlar */
	$kampanyali_urun_yazisi_tanimi				= '<div class="kampanya">'. lang('messages_product_shema_normal_campaign_text') .'</div>';
	$yeni_urun_yazisi_tanimi					= '<div class="yeni">'. lang('messages_product_shema_normal_new_product_text') .'</div>';

	$kdv_tanimi									= ' + ' . lang('messages_product_shema_normal_vat_text');

	// Kampanya ve İndirim Tanımlamaları
	$kampanyali_urun_adet_yazisi_tanimi			= lang('messages_product_shema_normal_campaign_product_piece');
	
	$indirimli_urun_adet_yazisi_tanimi			= '<div class="urun_liste_fiyat saga"><em>{fiyat_gercek}</em><big> {fiyat}</big> <small>{tur}{kdv}</small></div>';
	$urun_adet_yazisi_tanimi					= '<div class="urun_liste_fiyat saga"><big>{fiyat}</big> <small>{tur}{kdv}</small></div>';

	// Buton tanımlamaları
	$hizli_al_buton_tanimi						= '<a href="javascript:;" rel="nofollow" onclick="{url}" class="buton_hizli_al " style="" target="_top">'. lang('messages_product_shema_normal_fast_buy_button') .'</a>';
	$sepete_at_buton_tanimi						= '<a href="javascript:;" rel="nofollow" onclick="{url}" class="buton_sepete_ekle " style="" target="_top">'. lang('messages_product_shema_normal_cart_add_button') .'</a>';
	$secenek_var_buton_tanimi						= '<a href="{url}" class="buton_detay " target="_top">'. lang('messages_product_shema_normal_details_button') .'</a>';
	$stokta_yok_buton_tanimi					= '<a href="{url}" class="buton_stok_yok " target="_top">'. lang('messages_product_shema_normal_no_stock_button') .'</a>';
	$fiyat_gorunum_uye_ol_yazisi_tanimi			= '<div class="urun_liste_fiyat saga" style="font-size: 12px;margin-top: 10px;">'. lang('messages_product_shema_normal_product_price_reg') .'</div>';
	$secenek_var_buton_tanimi					= '<a href="{url}" class="buton_detay" style="margin:auto" target="_top">'. lang('messages_product_shema_normal_detail_button') .'</a>';

	// Ürün detayı
	$urun_liste_detay_tanimi					= '
<form action="{form_sepet_ekle_url}" name="{form_sepet_ekle_name}" id="{form_sepet_ekle_id}" method="post">
	<input type="hidden" name="stok_kod" id="model" value="{form_sepet_model_value}" />
	<input type="hidden" name="stok_id" id="stok_id" value="{form_sepet_stok_id_value}" />
	<input type="hidden" name="stok_adet" id="stok_adet" value="{form_sepet_stok_adet_value}" />
	<input type="hidden" name="redirect_url" id="redirect_url" value="{form_sepet_redirect_url_value}" />
	<div class="urun_liste_oge sola">
		{yeni_urun}
		{kampanya}
		<div class="urun_liste_resim"><a href="{urun_liste_resim_a}" target="_top"><img src="{urun_liste_resim_img_src}" alt="{urun_liste_resim_img_alt}" title="{urun_liste_resim_img_title}" /></a></div>
		<a class="urun_liste_baslik" href="{urun_liste_baslik_a}">{urun_liste_baslik_deger}</a>
		{fiyat}
		<div class="urun_liste_butonlar sola">
			{hizli_al}
			{stok}
			<div class="clear"></div>
		</div>
	</div>
</form>
';
	/* tasarımcıların değiştireceği alanlar */

	$resim = show_image($degerler->image, $w = 210, $h = 160);
	$secenek_kontrol = $this->product_model->get_product_option($degerler->product_id);

	if($degerler->new_product) {
		$yeni_urun = $yeni_urun_yazisi_tanimi;
	} else {
		$yeni_urun = null;
	}

	$key = md5(time().microtime().$degerler->model.$degerler->product_id);

	$fiyat_bilgi = fiyat_hesapla($degerler->model, 1, kur_oku('usd'), kur_oku('eur'), true);

	if ($this->dx_auth->is_logged_in()) {
		$user_group_id = $this->dx_auth->get_role_id();
	} else {
		$user_group_id = config('site_ayar_varsayilan_mus_grub');
	}

	$kampanya = null;
	$kampanya_bilgi = false;
	$kampanya_kontrol_if = false;
	$kampanya_kontrol = $this->campaign_model->get_campaign($degerler->product_id);
	if($kampanya_kontrol) {
		if ($this->dx_auth->is_role('admin-gruplari')) {
			$kampanya_bilgi = $kampanya_kontrol;
			$kampanya_kontrol_if = true;
			$kampanya = $kampanyali_urun_yazisi_tanimi;
		} else {
			if($kampanya_kontrol['user_group_id'] == $user_group_id) {
				$kampanya_bilgi = $kampanya_kontrol;
				$kampanya_kontrol_if = true;
				$kampanya = $kampanyali_urun_yazisi_tanimi;
			}
		}
	}

	$kdv_goster = (config('site_ayar_kdv_goster') == '1') ? $kdv_tanimi : null;

	if($kampanya_kontrol_if) {
		$k_fiyat = strtr($kampanyali_urun_adet_yazisi_tanimi, array('{adet}' => $kampanya_bilgi['quantity'], '{fiyat}' => format_number($kampanya_bilgi['price']), '{tur}' => $fiyat_bilgi['fiyat_tur'], '{kdv}' => $kdv_goster));
	} else if ($fiyat_bilgi['stok_indirim']) {
		$k_fiyat = strtr($indirimli_urun_adet_yazisi_tanimi, array('{fiyat_gercek}' => format_number($fiyat_bilgi['stok_gercek_fiyat_t']), '{fiyat}' => format_number($fiyat_bilgi['fiyat_t']), '{tur}' => $fiyat_bilgi['fiyat_tur'], '{kdv}' => $kdv_goster));
	} else {
		$k_fiyat = strtr($urun_adet_yazisi_tanimi, array('{fiyat}' => format_number($fiyat_bilgi['fiyat_t']), '{tur}' => $fiyat_bilgi['fiyat_tur'], '{kdv}' => $kdv_goster));
	}

	if(config('site_ayar_fiyat_goster') == '1') {
		if($this->dx_auth->is_logged_in()) {
			$fiyat = $k_fiyat;
			if($degerler->quantity) {
				$hizli_al = strtr($secenek_var_buton_tanimi, array('{url}' => face_site_url($degerler->seo . '--product'))); //strtr($hizli_al_buton_tanimi, array('{url}' => 'redirect(\''. face_ssl_url('odeme/adim_1/hizli/'. $degerler->model) .'\');'));
				$stok = ''; //strtr($sepete_at_buton_tanimi, array('{url}' => '$(\'#'. $key .'\').submit();'));
			} else {
				$hizli_al = strtr($secenek_var_buton_tanimi, array('{url}' => face_site_url($degerler->seo . '--product')));
				$stok = ''; //strtr($stokta_yok_buton_tanimi, array('{url}' => face_site_url($degerler->seo . '--product')));
			}
		} else {
			$hizli_al = strtr($secenek_var_buton_tanimi, array('{url}' => face_site_url($degerler->seo . '--product'))); //strtr($hizli_al_buton_tanimi, array('{url}' => 'redirect(\''. face_ssl_url('uye/giris?ref=' . current_url()) .'\');'));
			$stok = ''; //strtr($sepete_at_buton_tanimi, array('{url}' => 'redirect(\''. face_ssl_url('uye/giris?ref=' . current_url()) .'\');'));
			$fiyat = $fiyat_gorunum_uye_ol_yazisi_tanimi;
		}
	} else if(config('site_ayar_fiyat_goster') == '0') {
		$fiyat = $k_fiyat;
		if($degerler->quantity) {
			$hizli_al = strtr($secenek_var_buton_tanimi, array('{url}' => face_site_url($degerler->seo . '--product'))); //strtr($hizli_al_buton_tanimi, array('{url}' => 'redirect(\''. face_ssl_url('odeme/adim_1/hizli/'. $degerler->model) .'\');'));
			$stok = ''; //strtr($sepete_at_buton_tanimi, array('{url}' => '$(\'#'. $key .'\').submit();'));
		} else {
			$hizli_al = strtr($secenek_var_buton_tanimi, array('{url}' => face_site_url($degerler->seo . '--product'))); //strtr($secenek_var_buton_tanimi, array('{url}' => face_site_url($degerler->seo . '--product')));
			$stok = ''; //strtr($stokta_yok_buton_tanimi, array('{url}' => face_site_url($degerler->seo . '--product')));
		}
	} else {
		$hizli_al = strtr($secenek_var_buton_tanimi, array('{url}' => face_site_url($degerler->seo . '--product'))); //strtr($hizli_al_buton_tanimi, array('{url}' => 'redirect(\''. face_ssl_url('uye/giris?ref=' . current_url()) .'\');'));
		$stok = ''; //strtr($sepete_at_buton_tanimi, array('{url}' => 'redirect(\''. face_ssl_url('uye/giris?ref=' . current_url()) .'\');'));
		$fiyat = $fiyat_gorunum_uye_ol_yazisi_tanimi;
	}

	if($secenek_kontrol) {
		$hizli_al = strtr($secenek_var_buton_tanimi, array('{url}' => face_site_url($degerler->seo . '--product'))); //NULL;
		$stok = ''; //strtr($secenek_var_buton_tanimi, array('{url}' => face_site_url($degerler->seo . '--product')));
	}

	echo strtr($urun_liste_detay_tanimi, array('{yeni_urun}' => $yeni_urun, '{kampanya}' => $kampanya, '{urun_liste_resim_a}' => face_site_url($degerler->seo . '--product'), '{urun_liste_resim_img_src}' => $resim, '{urun_liste_resim_img_alt}' => $degerler->name, '{urun_liste_resim_img_title}' => $degerler->name, '{urun_liste_baslik_a}' => face_site_url($degerler->seo . '--product'), '{urun_liste_baslik_deger}' => character_limiter($degerler->name, 50), '{fiyat}' => $fiyat, '{hizli_al}' => $hizli_al, '{stok}' => $stok, '{form_sepet_ekle_url}' => face_ssl_url('sepet/ekle/urun_ekle/index'), '{form_sepet_ekle_name}' => $key, '{form_sepet_ekle_id}' => $key, '{form_sepet_model_value}' => $degerler->model, '{form_sepet_stok_id_value}' => $degerler->product_id, '{form_sepet_stok_adet_value}' => '1', '{form_sepet_redirect_url_value}' => current_url(ssl_status())));
	?>