<?php
	/* tasarımcıların değiştireceği alanlar */
	$kdv_tanimi									= ' + ' . lang('messages_product_shema_sliding_vat_text');

	// Kampanya ve İndirim Tanımlamaları
	$kampanyali_urun_adet_yazisi_tanimi			= lang('messages_product_shema_sliding_campaign_product_piece');
	$indirimli_urun_adet_yazisi_tanimi			= '<div class="k_fiyat saga"><em>{fiyat_gercek}</em><big> {fiyat}</big> <small>{tur}{kdv}</small></div>';
	$urun_adet_yazisi_tanimi					= '<div class="k_fiyat saga"><big>{fiyat}</big> <small>{tur}{kdv}</small></div>';

	// Buton tanımlamaları
	$fiyat_gorunum_uye_ol_yazisi_tanimi			= '<div class="urun_liste_fiyat saga" style="font-size: 12px;margin-top: 10px;">'. lang('messages_product_shema_sliding_product_price_reg') .'</div>';

	// Ürün detayı
	$urun_liste_detay_tanimi					= '
<a href="{urun_liste_resim_a}" class="y_u_liste_image sola"><img src="{urun_liste_resim_img_src}" alt="{urun_liste_resim_img_alt}" title="{urun_liste_resim_img_title}" /></a>
<a href="{urun_liste_resim_a}" title="{urun_liste_resim_img_title}" class="y_u_liste_urun_adi" style="color:##FF6C00;">{urun_liste_baslik_deger}</a>
{fiyat}
<div class="clear"></div>
';
	/* tasarımcıların değiştireceği alanlar */

	$resim = show_image($degerler->image, $w = 210, $h = 160);
	$secenek_kontrol = $this->product_model->get_product_option($degerler->product_id);

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
		} else {
			if($kampanya_kontrol['user_group_id'] == $user_group_id) {
				$kampanya_bilgi = $kampanya_kontrol;
				$kampanya_kontrol_if = true;
			}
		}
	}

	$kdv_goster = (config('site_ayar_kdv_goster') == '1') ? $kdv_tanimi : null;

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
		} else {
			$fiyat = $fiyat_gorunum_uye_ol_yazisi_tanimi;
		}
	} else if(config('site_ayar_fiyat_goster') == '0') {
		$fiyat = $k_fiyat;
	} else {
		$fiyat = $fiyat_gorunum_uye_ol_yazisi_tanimi;
	}

	echo strtr($urun_liste_detay_tanimi, array('{urun_liste_resim_a}' => site_url($degerler->seo . '--product'), '{urun_liste_resim_img_src}' => $resim, '{urun_liste_resim_img_alt}' => $degerler->name, '{urun_liste_resim_img_title}' => $degerler->name, '{urun_liste_baslik_a}' => site_url($degerler->seo . '--product'), '{urun_liste_baslik_deger}' => character_limiter($degerler->name, 50), '{fiyat}' => $fiyat));
	?>