<?php

	/* tasarımcıların değiştireceği alanlar */
	$kampanyali_urun_yazisi_tanimi				= '<div class="kampanya">'. lang('messages_product_shema_sliding_campaign_text') .'</div>';
	$yeni_urun_yazisi_tanimi					= '<div class="yeni">'. lang('messages_product_shema_sliding_new_product_text') .'</div>';

	$kdv_tanimi									= ' + ' . lang('messages_product_shema_sliding_vat_text');

	// Kampanya ve İndirim Tanımlamaları
	$kampanyali_urun_adet_yazisi_tanimi			= lang('messages_product_shema_sliding_campaign_product_piece');
	
	$indirimli_urun_adet_yazisi_tanimi			= '<div class="urun_diger_fiyat"><span class="u_eski"><strike>{fiyat_gercek}</strike></span> {fiyat} <span class="u_birim">{tur}{kdv}</span></div>';
	
	$urun_adet_yazisi_tanimi					= '<div class="urun_diger_fiyat">{fiyat} <span class="u_birim">{tur}{kdv}</span></div>';
	
	// Buton tanımlamaları
	$sepete_at_buton_tanimi						= '<a class="buton_sepete_ekle" href="javascript:;" rel="nofollow" onclick="{url}" style="margin-top:15px;">'. lang('messages_product_shema_sliding_cart_add_button') .'</a><div class="stok_var" style="margin-top:10px;">'. lang('messages_product_shema_sliding_in_stock_button') .'</div>';
	$detaylar_buton_tanimi						= '<a href="{url}" class="buton_detay sola">'. lang('messages_product_shema_sliding_details_button') .'</a>';
	$stokta_yok_buton_tanimi					= '<a href="{url}" class="buton_detay" style="margin-top:15px;">'. lang('messages_product_shema_sliding_detail_button') .'</a><div class="stok_yok" style="margin-top:10px;">'. lang('messages_product_shema_sliding_no_stock_button') .'</div>';
	$fiyat_gorunum_uye_ol_yazisi_tanimi			= '<div class="urun_liste_fiyat" style="font-size: 12px;margin-top: 10px;">'. lang('messages_product_shema_sliding_product_price_reg') .'</div>';
	$secenek_var_buton_tanimi					= '<a href="{url}" class="buton_detay" style="margin-top:15px;">'. lang('messages_product_shema_sliding_detail_button')  .'</a><div class="stok_var" style="margin-top:10px;">'. lang('messages_product_shema_sliding_in_stock_button') .'</div>';

	// Ürün detayı
	$urun_liste_detay_tanimi					= '
<div class="urun_diger_oge{degerler_class}">
<form action="{form_sepet_ekle_url}" name="{form_sepet_ekle_name}" id="{form_sepet_ekle_id}" method="post">
	<input type="hidden" name="stok_kod" id="stok_kod" value="{form_sepet_stok_kod_value}" />
	<input type="hidden" name="stok_id" id="stok_id" value="{form_sepet_stok_id_value}" />
	<input type="hidden" name="stok_adet" id="stok_adet" value="{form_sepet_stok_adet_value}" />
	<input type="hidden" name="basket_image" id="basket_image" value="{urun_liste_resim_img_src}" />
	<input type="hidden" name="redirect_url" id="redirect_url" value="{form_sepet_redirect_url_value}" />
	<div class="urun_diger sola">
		<a href="{urun_liste_resim_a}"><img src="{urun_liste_resim_img_src}" alt="{urun_liste_resim_img_alt}" title="{urun_liste_resim_img_title}" /></a>
		<a class="urun_diger_ad" href="{urun_liste_baslik_a}">{urun_liste_baslik_deger}</a>
		<div class="urun_diger_fiyat">{fiyat}</div>
	</div>
	<div class="urun_diger_buton">
		{stok}
	</div>
	<div class="clear"></div>
</form>
</div>
';
	/* tasarımcıların değiştireceği alanlar */

	$key = md5(time().microtime().$degerler->model.$degerler->product_id);
	$resim = show_image($degerler->image, $w = 70, $h = 66);
	$secenek_kontrol = $this->product_model->get_product_option($degerler->product_id);

	$fiyat_bilgi = fiyat_hesapla($degerler->model, 1, kur_oku('usd'), kur_oku('eur'), true);

	if ($this->dx_auth->is_logged_in()) {
		$user_group_id = $this->dx_auth->get_role_id();
	} else {
		$user_group_id = config('site_ayar_varsayilan_mus_grub');
	}

	$kampanya = NULL;
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

	$kdv_goster = (config('site_ayar_kdv_goster') == '1') ? $kdv_tanimi : NULL;

	if($kampanya_kontrol_if)
	{
		$k_fiyat = strtr($kampanyali_urun_adet_yazisi_tanimi, array('{adet}' => $kampanya_bilgi['quantity'], '{fiyat}' => format_number($kampanya_bilgi['price']), '{tur}' => $fiyat_bilgi['fiyat_tur'], '{kdv}' => $kdv_goster));
	} else if ($fiyat_bilgi['stok_indirim']) {
		$k_fiyat = strtr($indirimli_urun_adet_yazisi_tanimi, array('{fiyat_gercek}' => format_number($fiyat_bilgi['stok_gercek_fiyat_t']), '{fiyat}' => format_number($fiyat_bilgi['fiyat_t']), '{tur}' => $fiyat_bilgi['fiyat_tur'], '{kdv}' => $kdv_goster));
	} else {
		$k_fiyat = strtr($urun_adet_yazisi_tanimi, array('{fiyat}' => format_number($fiyat_bilgi['fiyat_t']), '{tur}' => $fiyat_bilgi['fiyat_tur'], '{kdv}' => $kdv_goster));
	}

	if(config('site_ayar_fiyat_goster') == '1')
	{
		if($this->dx_auth->is_logged_in())
		{
			$fiyat = $k_fiyat;
			if($degerler->quantity)
			{
				$stok = strtr($sepete_at_buton_tanimi, array('{url}' => '$(\'#'. $key .'\').submit();'));
			} else {
				$stok = strtr($stokta_yok_buton_tanimi, array('{url}' => site_url($degerler->seo . '--product')));
			}
		} else {
			$stok = strtr($sepete_at_buton_tanimi, array('{url}' => 'location = \''. ssl_url('uye/giris?ref=' . current_url()) .'\';'));
			$fiyat = $fiyat_gorunum_uye_ol_yazisi_tanimi;
		}
	} else if(config('site_ayar_fiyat_goster') == '0') {
		$fiyat = $k_fiyat;
		if($degerler->quantity)
		{
			$stok = strtr($sepete_at_buton_tanimi, array('{url}' => '$(\'#'. $key .'\').submit();'));
		} else {
			$stok = strtr($stokta_yok_buton_tanimi, array('{url}' => site_url($degerler->seo . '--product')));
		}
	} else {
		$stok = strtr($sepete_at_buton_tanimi, array('{url}' => 'location = \''. ssl_url('uye/giris?ref=' . current_url()) .'\';'));
		$fiyat = $fiyat_gorunum_uye_ol_yazisi_tanimi;
	}

	if($secenek_kontrol)
	{
		$stok = strtr($secenek_var_buton_tanimi, array('{url}' => site_url($degerler->seo . '--product')));
	}

	echo strtr($urun_liste_detay_tanimi, array('{degerler_class}' => $degerler->div_class, '{urun_liste_resim_a}' => site_url($degerler->seo . '--product'), '{urun_liste_resim_img_src}' => $resim, '{urun_liste_resim_img_alt}' => $degerler->name, '{urun_liste_resim_img_title}' => $degerler->name, '{urun_liste_baslik_a}' => site_url($degerler->seo . '--product'), '{urun_liste_baslik_deger}' => character_limiter($degerler->name, 50), '{fiyat}' => $fiyat, '{stok}' => $stok, '{form_sepet_ekle_url}' => ssl_url('sepet/ekle/urun_ekle'), '{form_sepet_ekle_name}' => $key, '{form_sepet_ekle_id}' => $key, '{form_sepet_stok_kod_value}' => $degerler->model, '{form_sepet_stok_id_value}' => $degerler->product_id, '{form_sepet_stok_adet_value}' => '1', '{form_sepet_redirect_url_value}' => current_url()));

?>