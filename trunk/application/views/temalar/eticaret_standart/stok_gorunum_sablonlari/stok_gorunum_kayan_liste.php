<?php
  /*  if ( config('site_ayar_urun_tarih_goster') == '1'  and $degerler->durum == 'modul-kampanya' ) {

 // İptal edildi
 $dt = date('Y-m-d H:i:s',$degerler->date_end);
 
 $dt = explode(" ",$dt);
 $trh = $dt[0];
 $zmn = $dt[1];
 
 $trh = explode("-",$trh);
 $zmn = explode(":",$zmn);
 
 $yil= $trh[0];
 $ay = $trh[1]-1;
 $gun = $trh[2];
 
 $saat = $zmn[0];
 $dakika = $zmn[1];
 $saniye = $zmn[2];
 
 $sayac  									= '
	<span id="CountDownModul'.$degerler->product_id.'"></span>
	<script language="javascript" type="text/javascript"> 
	var date = new Date('.$yil.','.$ay.','.$gun.','.$saat.','.$dakika.','.$saniye.');
	
	$("#CountDownModul'.$degerler->product_id.'").countdown({until: date, compact: true, 
    description: "", format:"dHMS"});
	
 </script>';
 
 
 
}
elseif ( config('site_ayar_urun_kalansure_goster') == '1'  and $degerler->durum == 'modul-indirimli' ) 
{

 
 $dt = date('Y-m-d H:i:s',$degerler->date_end);
 
 $dt = explode(" ",$dt);
 $trh = $dt[0];
 $zmn = $dt[1];
 
 $trh = explode("-",$trh);
 $zmn = explode(":",$zmn);
 
 $yil= $trh[0];
 $ay = $trh[1]-1;
 $gun = $trh[2];
 
 $saat = $zmn[0];
 $dakika = $zmn[1];
 $saniye = $zmn[2];
 
 $sayac  									= '
	<span id="CountDown'.$degerler->product_id.'"></span>
	<script language="javascript" type="text/javascript"> 
	var date = new Date('.$yil.','.$ay.','.$gun.','.$saat.','.$dakika.','.$saniye.');
	
	$("#CountDown'.$degerler->product_id.'").countdown({until: date, compact: true, 
    description: "", format:"dHMS"});
	
 </script>';
 
 
 
} else {
	$sayac = '';
}*/
    
	
	/* tasarımcıların değiştireceği alanlar */
	$kampanyali_urun_yazisi_tanimi				= '<div class="kampanya">'. lang('messages_product_shema_sliding_campaign_text') .'</div>';
	$new_product_yazisi_tanimi					= '<div class="yeni">'. lang('messages_product_shema_sliding_new_product_text') .'</div>';

	$kdv_tanimi									= ' + ' . lang('messages_product_shema_sliding_vat_text');

	// Kampanya ve İndirim Tanımlamaları
	$kampanyali_urun_adet_yazisi_tanimi			= lang('messages_product_shema_sliding_campaign_product_piece');
	$indirimli_urun_adet_yazisi_tanimi			= '<div class="k_fiyat saga"><em>{fiyat_gercek}</em><big> {fiyat}</big> <small>{tur}{kdv}</small></div>';
	$urun_adet_yazisi_tanimi					= '<div class="k_fiyat saga"><big>{fiyat}</big> <small>{tur}{kdv}</small></div>';

	// Buton tanımlamaları
	$hizli_al_buton_tanimi						= '<a href="javascript:;" rel="nofollow" onclick="{url}" class="buton_hizli_al " style="width:100px;"><b class="urhizlial">'. lang('messages_product_shema_sliding_fast_buy_button') .'</b></a>';
	$sepete_at_buton_tanimi						= '<a href="javascript:;" rel="nofollow" onclick="{url}" class="buton_sepete_ekle " style="width:60px;"><b class="ursepetat">'. lang('messages_product_shema_sliding_cart_add_button2') .'</b></a>';
	$detaylar_buton_tanimi						= '<a href="{url}" style="width:100px;" class="buton_detay "><b class="urmercek">'. lang('messages_product_shema_sliding_details_button') .'</b></a>';
	$stokta_yok_buton_tanimi					= '<a href="{url}" style="width:100px;" class="buton_stok_yok "><b class="urstokyok">'. lang('messages_product_shema_sliding_no_stock_button') .'</b></a>';
	$fiyat_gorunum_uye_ol_yazisi_tanimi			= '<div class="urun_liste_fiyat saga" style="font-size: 12px;margin-top: 10px;">'. lang('messages_product_shema_sliding_product_price_reg') .'</div>';
	$secenek_var_buton_tanimi					= '<a href="{url}" class="buton_detay" style="width:60px;"><b class="urmercek">'. lang('messages_product_shema_sliding_detail_button') .'</b></a>';

	// Ürün detayı
	
	if(config('site_ayar_sepete_git')=='1'):
	$urun_liste_detay_tanimi					= '
<form action="{form_sepet_ekle_url}" name="{form_sepet_ekle_name}" id="{form_sepet_ekle_id}" method="post">
	<input type="hidden" name="stok_kod" id="model" value="{form_sepet_model_value}" />
	<input type="hidden" name="stok_id" id="product_id" value="{form_sepet_stok_id_value}" />
	<input type="hidden" name="stok_adet" id="stok_adet" value="{form_sepet_stok_adet_value}" />
	<input type="hidden" name="redirect_url" id="redirect_url" value="{form_sepet_redirect_url_value}" />
	<input type="hidden" name="basket_image" id="basket_image" value="{urun_liste_resim_img_src}" />
	
	<div class="k_image"><a href="{urun_liste_resim_a}"><img src="{urun_liste_resim_img_src}" alt="{urun_liste_resim_img_alt}" title="{urun_liste_resim_img_title}" /></a></div>
	<a class="k_urun_adi sitelink" href="{urun_liste_baslik_a}">{urun_liste_baslik_deger}</a>
		{fiyat}
	<div class="k_butonlar sola">
		{stok}
		<div class="clear"></div>
	</div>
</form>
';
else:
	$urun_liste_detay_tanimi					= '
	<form action="{form_sepet_ekle_url}" name="{form_sepet_ekle_name}" id="{form_sepet_ekle_id}" method="post">
	<input type="hidden" name="stok_kod" id="model" value="{form_sepet_model_value}" />
	<input type="hidden" name="stok_id" id="product_id" value="{form_sepet_stok_id_value}" />
	<input type="hidden" name="stok_adet" id="stok_adet" value="{form_sepet_stok_adet_value}" />
	<input type="hidden" name="redirect_url" id="redirect_url" value="{form_sepet_redirect_url_value}" />
	<input type="hidden" name="basket_image" id="basket_image" value="{urun_liste_resim_img_src}" />
	
	<div class="k_image"><a href="{urun_liste_resim_a}"><img src="{urun_liste_resim_img_src}" alt="{urun_liste_resim_img_alt}" title="{urun_liste_resim_img_title}" /></a></div>
	<a class="k_urun_adi sitelink" href="{urun_liste_baslik_a}">{urun_liste_baslik_deger}</a>
		{fiyat}
	<div class="k_butonlar sola">
		{stok}
		<div class="clear"></div>
	</div>
</form>
<script type="text/javascript">

$(document).ready(function(){
$("#{form_sepet_ekle_id}").submit(function(e){
	
	//return false;
	e.preventDefault();
	
	//alert($("form#{form_sepet_ekle_id}").serialize());
	$.ajax({
			type: "post",
			url: "{form_sepet_ekle_url}" ,
			dataType: "json",
			data: $(this).serialize(),
			success: function (json) {
				$("div.seport").html("");
				$("div.seport").html(json["output"]);
				$("#cart_total").html("<b>"+json["total"]+"</b>");
				$("#{form_sepet_ekle_id}.ursepetat").html("Eklendi");
				$("#ust_hizlisepet").slideDown();
				
				//$("big#crt").html(json["output"]);
			},	
			complete: function () {
				var image = $("#logo").offset();
				var cart  = $("#h_sepet").offset();
				
				$("html, body").animate({ scrollTop: 0 }, "slow");
	
				//$("#urun_liste_resim img").before("<img src=\""+$("#urun_liste_resim img").attr("src")+"\" id="temp" style="position: absolute; top: \"" + image.top +"\"px; left: \"" +image.left+ "\"px;" />");
	
				params = {
					top : cart.top + "px",
					left : cart.left + "px",
					opacity : 0.0,
					width : $("#h_sepet").width(),  
					height : $("#h_sepet").height()
				};		
	
				//$("#temp").animate(params, "slow", false, function () {
					//$("#temp").remove();
				//});		
			}			
		});

});
});

</script>
';
endif;
	
	/* tasarımcıların değiştireceği alanlar */

	$resim = show_image($degerler->image, $w = 180, $h = 140);
	$secenek_kontrol = $this->product_model->get_product_option($degerler->product_id);

	if($degerler->new_product == '1') {
		$new_product = $new_product_yazisi_tanimi;
	} else {
		$new_product = null;
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

	$kdv_goster = (config('site_ayar_kdv_goster') == '1') ? $kdv_tanimi : NULL;
    
	/* SKOCH indirimli ürün kdv fiyat fixes ( 500 üzeri çizili değil 500+KDV üzeri çizili olmalıydı) */
		if(config('site_ayar_kdv_goster') == '0'):
		$fy = format_number($fiyat_bilgi['stok_gercek_fiyat_t']*($fiyat_bilgi['kdv_orani']+1));
		elseif(config('site_ayar_kdv_goster') == '1'):
		$fy = format_number($fiyat_bilgi['stok_gercek_fiyat_t']);
		endif;


	if($kampanya_kontrol_if) {
		$k_fiyat = strtr($kampanyali_urun_adet_yazisi_tanimi, array('{adet}' => $kampanya_bilgi['quantity'], '{fiyat}' => format_number($kampanya_bilgi['price']), '{tur}' => $fiyat_bilgi['fiyat_tur'], '{kdv}' => $kdv_goster));
	} else if ($fiyat_bilgi['stok_indirim']) {
		$k_fiyat = strtr($indirimli_urun_adet_yazisi_tanimi, array('{fiyat_gercek}' => $fy, '{fiyat}' => format_number($fiyat_bilgi['fiyat_t']), '{tur}' => $fiyat_bilgi['fiyat_tur'], '{kdv}' => $kdv_goster));
	} else {
		$k_fiyat = strtr($urun_adet_yazisi_tanimi, array('{fiyat}' => format_number($fiyat_bilgi['fiyat_t']), '{tur}' => $fiyat_bilgi['fiyat_tur'], '{kdv}' => $kdv_goster));
	}

	if(config('site_ayar_fiyat_goster') == '1') {
		if($this->dx_auth->is_logged_in()) {
			$fiyat = $k_fiyat;
			if($degerler->quantity) {
				$hizli_al = strtr($hizli_al_buton_tanimi, array('{url}' => 'location = \''. ssl_url('odeme/adim_1/hizli/'. $degerler->model) .'\';'));
				$stok = strtr($sepete_at_buton_tanimi, array('{url}' => '$(\'#'. $key .'\').submit();'));
			} else {
				$hizli_al = strtr($detaylar_buton_tanimi, array('{url}' => site_url($degerler->seo . '--product')));
				$stok = strtr($stokta_yok_buton_tanimi, array('{url}' => site_url($degerler->seo . '--product')));
			}
		} else {
			$hizli_al = strtr($hizli_al_buton_tanimi, array('{url}' => 'location = \''. ssl_url('uye/giris?ref=' . current_url()) .'\';'));
			$stok = strtr($sepete_at_buton_tanimi,  array('{url}' => 'location = \''. ssl_url('uye/giris?ref=' . current_url()) .'\';'));
			$fiyat = $fiyat_gorunum_uye_ol_yazisi_tanimi;
		}
	} else if(config('site_ayar_fiyat_goster') == '0') {
		$fiyat = $k_fiyat;
		if($degerler->quantity) {
			$hizli_al = strtr($hizli_al_buton_tanimi, array('{url}' => 'location = \''. ssl_url('odeme/adim_1/hizli/'. $degerler->model) .'\';'));
			$stok = strtr($sepete_at_buton_tanimi, array('{url}' => '$(\'#'. $key .'\').submit();'));
		} else {
			$hizli_al = strtr($detaylar_buton_tanimi, array('{url}' => site_url($degerler->seo . '--product')));
			$stok = strtr($stokta_yok_buton_tanimi, array('{url}' => site_url($degerler->seo . '--product')));
		}
	} else {
		$hizli_al = strtr($hizli_al_buton_tanimi, array('{url}' => 'location = \''. ssl_url('uye/giris?ref=' . current_url()) .'\';'));
		$stok = strtr($sepete_at_buton_tanimi, array('{url}' => 'location = \''. ssl_url('uye/giris?ref=' . current_url()) .'\';'));
		$fiyat = $fiyat_gorunum_uye_ol_yazisi_tanimi;
	}

	if($secenek_kontrol) {
		$hizli_al = NULL;
		$stok = strtr($secenek_var_buton_tanimi, array('{url}' => site_url($degerler->seo . '--product')));
	}

	echo strtr($urun_liste_detay_tanimi, array('{new_product}' => $new_product, '{kampanya}' => $kampanya, '{urun_liste_resim_a}' => site_url($degerler->seo . '--product'), '{urun_liste_resim_img_src}' => $resim, '{urun_liste_resim_img_alt}' => $degerler->name, '{urun_liste_resim_img_title}' => $degerler->name, '{urun_liste_baslik_a}' => site_url($degerler->seo . '--product'), '{urun_liste_baslik_deger}' => character_limiter($degerler->name, 50), '{fiyat}' => $fiyat, '{hizli_al}' => $hizli_al, '{stok}' => $stok, '{form_sepet_ekle_url}' => ssl_url('sepet/ekle/urun_ekle'), '{form_sepet_ekle_name}' => $key, '{form_sepet_ekle_id}' => $key, '{form_sepet_model_value}' => $degerler->model, '{form_sepet_stok_id_value}' => $degerler->product_id, '{form_sepet_stok_adet_value}' => '1', '{form_sepet_redirect_url_value}' => current_url()));

?>