<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class genel_ayarlar_model extends CI_Model
{
    function __construct()
    {
		parent::__construct();
    }

    function genel_ayarlar_kaydet($val)
    {
		//genel ayarlar
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_telephone), array('ayar_adi' => 'site_ayar_sirket_tel'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_telephone2), array('ayar_adi' => 'site_ayar_sirket_tel2'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_telephone3), array('ayar_adi' => 'site_ayar_sirket_tel3'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_telephone4), array('ayar_adi' => 'site_ayar_sirket_tel4'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_telephone5), array('ayar_adi' => 'site_ayar_sirket_tel5'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_telephone_p), array('ayar_adi' => 'site_ayar_sirket_tel_p'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_telephone2_p), array('ayar_adi' => 'site_ayar_sirket_tel2_p'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_telephone3_p), array('ayar_adi' => 'site_ayar_sirket_tel3_p'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_telephone4_p), array('ayar_adi' => 'site_ayar_sirket_tel4_p'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_telephone5_p), array('ayar_adi' => 'site_ayar_sirket_tel5_p'));
		
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_fax), array('ayar_adi' => 'site_ayar_sirket_fax'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_fax2), array('ayar_adi' => 'site_ayar_sirket_fax2'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_fax2), array('ayar_adi' => 'site_ayar_sirket_fax3'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_fax_p), array('ayar_adi' => 'site_ayar_sirket_fax_p'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_fax2_p), array('ayar_adi' => 'site_ayar_sirket_fax2_p'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_fax2_p), array('ayar_adi' => 'site_ayar_sirket_fax3_p'));
		
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_address), array('ayar_adi' => 'site_ayar_sirket_adres'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_name), array('ayar_adi' => 'firma_adi'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_owner), array('ayar_adi' => 'firma_sahibi'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_email), array('ayar_adi' => 'site_ayar_mail'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_email_cevapsiz), array('ayar_adi' => 'site_ayar_email_cevapsiz'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_email_title), array('ayar_adi' => 'site_ayar_email_baslik'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_email_destek), array('ayar_adi' => 'site_ayar_email_destek'));
		
		$this->db->update('ayarlar', array('ayar_deger' => $val->image), array('ayar_adi' => 'site_ayar_logo'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->imageFav), array('ayar_adi' => 'site_ayar_favicon'));
		
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_tema), array('ayar_adi' => 'site_ayar_tema'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_tema_renkler), array('ayar_adi' => 'site_ayar_tema_asset'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_email_admin), array('ayar_adi' => 'site_ayar_email_admin'));

		//mağaza ayarları
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_title), array('ayar_adi' => 'site_ayar_baslik'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_meta_description), array('ayar_adi' => 'site_ayar_description'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_meta_keywords), array('ayar_adi' => 'site_ayar_keywords'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_copyright), array('ayar_adi' => 'site_ayar_copyright'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_google_durum), array('ayar_adi' => 'site_google_analytics_durum'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_google_kodu), array('ayar_adi' => 'site_google_analytics_kodu'));
		
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_google_maps_durum), array('ayar_adi' => 'site_google_maps_durum'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_google_maps_kodu), array('ayar_adi' => 'site_google_maps_kodu'));

		//ürün detay sayfası ayarları
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_urun_kodu_goster), array('ayar_adi' => 'site_ayar_urun_kodu_goster'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_begeni_durumu_goster), array('ayar_adi' => 'site_ayar_begeni_durumu_goster'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_stok_durumu_goster), array('ayar_adi' => 'site_ayar_stok_durumu_goster'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_urun_info_goster), array('ayar_adi' => 'site_ayar_urun_info_goster'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_urun_tarih_goster), array('ayar_adi' => 'site_ayar_urun_tarih_goster'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_urun_kalansure_goster), array('ayar_adi' => 'site_ayar_urun_kalansure_goster'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_urundetay_urungrubu), array('ayar_adi' => 'site_ayar_urundetay_urungrubu'));
		
		//seçenek ayarları
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_admin_limit), array('ayar_adi' => 'site_ayar_urun_yonetim_sayfa'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_catalog_limit), array('ayar_adi' => 'site_ayar_urun_site_sayfa'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_invoice_id), array('ayar_adi' => 'site_ayar_fatura_bas'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_invoice_prefix), array('ayar_adi' => 'site_ayar_fatura_pre'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_standart_musteri_grup), array('ayar_adi' => 'site_ayar_varsayilan_mus_grub'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_customer_price), array('ayar_adi' => 'site_ayar_fiyat_goster'));
		
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_basket_view), array('ayar_adi' => 'site_ayar_sepet_resim_goster'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_basket_go), array('ayar_adi' => 'site_ayar_sepete_git'));
		
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_stock_display), array('ayar_adi' => 'site_ayar_stok_miktar_goster'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->site_ayar_varsayilan_siparis_durumu), array('ayar_adi' => 'site_ayar_varsayilan_siparis_durumu'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_review), array('ayar_adi' => 'site_ayar_yorum'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_kdv_display), array('ayar_adi' => 'site_ayar_kdv_goster'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_kargo_indirimi_durum), array('ayar_adi' => 'config_kargo_indirimi_durum'));
		$this->db->update('ayarlar', array('ayar_deger' => number_format($val->config_kargo_indirim_fiyat, 2, '.', '')), array('ayar_adi' => 'config_kargo_indirim_fiyat'));
		

		//Sunucu Ayarları
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_ssl), array('ayar_adi' => 'site_ayar_ssl'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_ssl_code), array('ayar_adi' => 'site_ayar_ssl_kod'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_maintenance), array('ayar_adi' => 'site_ayar_bakim'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_bakim_detay), array('ayar_adi' => 'site_ayar_bakim_sayfasi_detay'));
		
		// kupon ayarları
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_coupon_status), array('ayar_adi' => 'site_ayar_coupon_status'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_coupon_limit), array('ayar_adi' => 'site_ayar_coupon_limit'));

		// facebook ayarları
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_facebook_status), array('ayar_adi' => 'site_ayar_facebook_status'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_facebook_app_id), array('ayar_adi' => 'site_ayar_facebook_app_id'));
		$this->db->update('ayarlar', array('ayar_deger' => $val->config_facebook_secret), array('ayar_adi' => 'site_ayar_facebook_secret'));
		if (config('facebook_app_status')) {
			$this->db->update('ayarlar', array('ayar_deger' => $val->config_facebook_url), array('ayar_adi' => 'site_ayar_facebook_url'));
			$this->db->update('ayarlar', array('ayar_deger' => $val->config_facebook_tema), array('ayar_adi' => 'site_ayar_facebook_tema'));
			$this->db->update('ayarlar', array('ayar_deger' => $val->config_facebook_tema_asset), array('ayar_adi' => 'site_ayar_facebook_tema_asset'));
		}

		$this->db->update('ayarlar', array('ayar_deger' => $val->config_site_ayar_kur), array('ayar_adi' => 'site_ayar_kur'));

		if($val->config_site_ayar_kur == 2)
		{
			$this->db->update('ayarlar', array('ayar_deger' => $val->config_site_ayar_kur_yuzde), array('ayar_adi' => 'site_ayar_kur_yuzde'));
		}

		if($val->config_site_ayar_kur == 3)
		{
			$this->db->update('kurlar', array('kur_alis_manuel' => number_format($val->config_kur_dolara, 4, '.', ''), 'kur_satis_manuel' => number_format($val->config_kur_dolars, 4, '.', '')), array('kur_adi' => 'USD'));
			$this->db->update('kurlar', array('kur_alis_manuel' => number_format($val->config_kur_euroa, 4, '.', ''), 'kur_satis_manuel' => number_format($val->config_kur_euros, 4, '.', '')), array('kur_adi' => 'EUR'));
		}

		$kontrol_data = true;
		return $kontrol_data;
    }
}