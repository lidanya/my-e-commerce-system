<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class genel_ayarlar extends Admin_Controller {

	var $izin_linki;

	function __construct()
	{
		parent::__construct();

		$this->load->model('yonetim/yonetim_model');
		$this->load->model('yonetim/genel_ayarlar_model');

		$this->izin_linki = 'sistem/genel_ayarlar';
	}

	function index()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/genel_ayarlar');
		
		//$this->load->helper('sitemap_generator');
		//generate();
		$this->genel_ayarlar_main();
	}	
	
	function genel_ayarlar_main()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/genel_ayarlar/genel_ayarlar_main');

		$this->output->enable_profiler(false);
		$data['musgrup_listele']  = $this->yonetim_model->musgrup_listele(1);
		$data['uzunluk_birimi']   = $this->yonetim_model->tanimlar_listele('uzunluk');
		$data['agirlik_birimi']   = $this->yonetim_model->tanimlar_listele('agirlik');
		$data['stok_durumu']  	  = $this->yonetim_model->tanimlar_listele('stok_durumu');
		$data['kur_usd'] 	 	  = $this->yonetim_model->kur_bilgi('USD');
		$data['kur_eur'] 	 	  = $this->yonetim_model->kur_bilgi('EUR');
		
		$val = $this->validation;
		
		//form bilgileri yükleniyor	
		
		$rules['config_telephone']    					= "trim|xss_clean|required";
		$rules['config_telephone2']    					= "trim|xss_clean";
		$rules['config_telephone3']    					= "trim|xss_clean";
		$rules['config_telephone4']    					= "trim|xss_clean";
		$rules['config_telephone5']    					= "trim|xss_clean";
		$rules['config_telephone_p']    				= "trim|xss_clean";
		$rules['config_telephone2_p']    				= "trim|xss_clean";
		$rules['config_telephone3_p']    				= "trim|xss_clean";
		$rules['config_telephone4_p']    				= "trim|xss_clean";
		$rules['config_telephone5_p']    				= "trim|xss_clean";

		$rules['config_fax']    						= "trim|xss_clean";
		$rules['config_fax2']    						= "trim|xss_clean";
		$rules['config_fax3']    						= "trim|xss_clean";
		$rules['config_fax_p']    						= "trim|xss_clean";
		$rules['config_fax2_p']    						= "trim|xss_clean";
		$rules['config_fax3_p']    						= "trim|xss_clean";

		$rules['config_address']    					= "trim|xss_clean|required";
		$rules['config_name']    						= "trim|xss_clean|required";
		$rules['config_owner']    						= "trim|xss_clean|required";
		$rules['config_email']    						= "trim|xss_clean|required|valid_email";
		$rules['config_email_admin']    				= "trim|xss_clean|valid_email";
		$rules['config_email_cevapsiz']    				= "trim|xss_clean|required|valid_email";
		$rules['config_email_title']    				= "trim|xss_clean|required";
		$rules['config_email_destek']    				= "trim|xss_clean|required|valid_email";
		$rules['config_title']    						= "trim|xss_clean|required";
		$rules['config_meta_description']    			= "trim|xss_clean";
		$rules['config_meta_keywords']    				= "trim|xss_clean";
		$rules['config_copyright']    					= "trim|xss_clean|required";
		$rules['config_template']    					= "trim|xss_clean";
		$rules['config_admin_limit']    				= "trim|xss_clean|required|numeric";
		$rules['config_catalog_limit'] 	   				= "trim|xss_clean|required|numeric";
		$rules['config_invoice_id']    					= "trim|xss_clean";
		$rules['config_invoice_prefix']    				= "trim|xss_clean";
		$rules['config_customer_group_id']    			= "trim|xss_clean";
		$rules['config_customer_price']    				= "trim|xss_clean|numeric";
		
		$rules['config_basket_view']    				= "trim|xss_clean|numeric";
		$rules['config_basket_go']    				= "trim|xss_clean|numeric";
		
		$rules['config_stock_display']    				= "trim|xss_clean|numeric";
		$rules['config_kdv_display']					= "trim|xss_clean|numeric";
		$rules['site_ayar_varsayilan_siparis_durumu']   = "trim|xss_clean";
		$rules['config_review']    						= "trim|xss_clean";
		$rules['config_ssl']    						= "trim|xss_clean";
		$rules['config_ssl_code']    					= "trim";
		$rules['config_maintenance']    				= "trim|xss_clean";
		
		$rules['image'] 	   							= "trim|xss_clean";
		$rules['imageFav'] 	   							= "trim|xss_clean";
		
		
		$rules['config_standart_musteri_grup']			= "trim|xss_clean|required";

		$rules['config_kargo_indirimi_durum']			= "trim|xss_clean";
		$fields['config_kargo_indirimi_durum']			= "Kargo İndirimi Durum ?";

		$kargo_indirim_gerekli = ($this->input->post('config_kargo_indirimi_durum') == '1') ? '|required':NULL;
		$rules['config_kargo_indirim_fiyat']			= "trim|xss_clean". $kargo_indirim_gerekli ."";
		$fields['config_kargo_indirim_fiyat']			= "Kargo İndirimi Fiyatı";

		$rules['config_google_maps_durum'] 	   			= "trim";
		$fields['config_google_maps_durum']    			= "Google Maps Durum";

		$rules['config_google_maps_kodu']  				= "trim";
		$fields['config_google_maps_kodu']    			= "Google Maps Kodu";
				
		$rules['config_google_kodu'] 	   				= "trim";
		$fields['config_google_kodu']    				= "Google Analytics Kodu";
		
		$rules['config_google_durum'] 	   				= "trim|integer|xss_clean";
		$fields['config_google_durum']    				= "Google Analytics Durum";
				
		$rules['config_site_ayar_kur'] 	   				= "trim|xss_clean|required|numeric";
		$rules['config_site_ayar_kur_yuzde']			= "trim|xss_clean|numeric";
		if($this->input->post('config_site_ayar_kur') == 3)
		{
			$rules['config_kur_dolara'] 				= "trim|xss_clean|required";
			$rules['config_kur_dolars'] 				= "trim|xss_clean|required";
			$rules['config_kur_euroa'] 					= "trim|xss_clean|required";
			$rules['config_kur_euros'] 					= "trim|xss_clean|required";
		} else {
			$rules['config_kur_dolara'] 				= "trim|xss_clean";
			$rules['config_kur_dolars'] 				= "trim|xss_clean";
			$rules['config_kur_euroa'] 					= "trim|xss_clean";
			$rules['config_kur_euros'] 					= "trim|xss_clean";
		}

		if($this->input->post('config_maintenance') == 1)
		{
			$rules['config_bakim_detay'] 		   		= "trim|xss_clean|required";
			$fields['config_bakim_detay']    			= "Bakım Modu Detay Sayfası";
		} else {
			$rules['config_bakim_detay'] 		   		= "trim|xss_clean";
			$fields['config_bakim_detay']    			= "Bakım Modu Detay Sayfası";
		}

		$rules['config_tema']							= "trim|required|xss_clean";
		$rules['config_tema_renkler']					= "trim|required|xss_clean";
		
		$rules['config_urun_kodu_goster']				= "trim|required|xss_clean";
		$rules['config_begeni_durumu_goster']			= "trim|required|xss_clean";
		$rules['config_stok_durumu_goster']				= "trim|required|xss_clean";
		$rules['config_urun_info_goster']				= "trim|required|xss_clean";
		$rules['config_urun_tarih_goster']				= "trim|required|xss_clean";
		$rules['config_urun_kalansure_goster']			= "trim|required|xss_clean";

		$fields['config_telephone']    					= "Telefon 1";
		$fields['config_telephone2']					= "Telefon 2";
		$fields['config_telephone3']					= "Telefon 3";
		$fields['config_telephone4']					= "Telefon 4";
		$fields['config_telephone5']					= "Telefon 5";
		$fields['config_telephone_p']    				= "Telefon 1 Pbx";
		$fields['config_telephone2_p']					= "Telefon 2 Pbx";
		$fields['config_telephone3_p']					= "Telefon 3 Pbx";
		$fields['config_telephone4_p']					= "Telefon 4 Pbx";
		$fields['config_telephone5_p']					= "Telefon 5 Pbx";

		$fields['config_fax']    						= "Fax 1";
		$fields['config_fax2']    						= "Fax 2";
		$fields['config_fax3']    						= "Fax 3";
		$fields['config_fax_p']    						= "Fax 1 Pbx";
		$fields['config_fax2_p']    					= "Fax 2 Pbx";
		$fields['config_fax3_p']    					= "Fax 3 Pbx";

		$fields['config_address']    					= "Adres";
		$fields['config_name']    						= "Firma Adı";
		$fields['config_owner']    						= "Firma Sahibi";
		$fields['config_email']    						= "E-posta Adresi";
		$fields['config_email_cevapsiz']    			= "Cevapsız E-Posta Adresi";
		$fields['config_email_title']    				= "E-Posta Başlığı";
		$fields['config_email_admin']    				= "Admin E-Posta Adresi";
		$fields['config_email_destek']    				= "Destek E-Posta Adresi";
		$fields['config_title']    						= "Site Başlığı";
		$fields['config_meta_description']    			= "Meta Description";
		$fields['config_meta_keywords']    				= "Meta Keywords";
		$fields['config_copyright']    					= "CopyRight";
		$fields['config_template']    					= "Tema Seçeneği";
		$fields['config_admin_limit']    				= "Admin Paneldeki Görünen Ürün Adedi";
		$fields['config_catalog_limit'] 	   			= "Mağazadaki Görünen Ürün Adedi";
		$fields['config_invoice_id']    				= "Fatura Başlangıç Numarası";
		$fields['config_invoice_prefix']    			= "Fatura Öneki";
		$fields['config_customer_group_id']    			= "Varsayılan Kullanıcı Grubu";
		$fields['config_customer_price']    			= "Fiyatlar Görünecek mi?";
		
		$fields['config_basket_view']    			= "Sepette ürün resmi görünsün mü?";
		
		$fields['config_basket_go']    			= "Sepete Gitsin mi?";
		
		$fields['config_stock_display']    				= "Stok Miktarı Görünecek mi?";
		$fields['config_kdv_display']					= "Kdv Fiyatları Gösterimi?";
		$fields['site_ayar_varsayilan_siparis_durumu']  = "Varsayılan Sipariş Durumu";
		$fields['config_review']    					= "Yorumlar";
		$fields['config_ssl']    						= "SSL Aktif mi?";
		$fields['config_ssl_code']    					= "SSL Kodu";
		$fields['config_maintenance']    				= "Bakım Modu";
		
		$fields['image'] 	   							= "Firma Logosu";
		$fields['imageFav'] 	   							= "Firma Favicon";
		
		$fields['config_standart_musteri_grup']			= "Standart Müşteri Grubu";

		$fields['config_site_ayar_kur'] 	   			= "Kur Ayarı";
		$fields['config_site_ayar_kur_yuzde']			= "Kur Ayar Yüzde";
		$fields['config_kur_dolara'] 	   				= "Dolar Alış";
		$fields['config_kur_dolars'] 	   				= "Dolar Satış";
		$fields['config_kur_euroa'] 		   			= "Euro Alış";
		$fields['config_kur_euros'] 		   			= "Euro Satış";

		$fields['config_tema']							= "Tema";
		$fields['config_tema_renkler']					= "Tema Renkler";
		
		$fields['config_urun_kodu_goster']				= "Ürün Detay Ürün Kodu";
		$fields['config_begeni_durumu_goster']			= "Ürün Detay Beğeni Durumu";
		$fields['config_stok_durumu_goster']			= "Ürün Detay Ürün Stok Durumu";
		$fields['config_urun_info_goster']				= "Ürün Detay Ürün info";
		$fields['config_urun_tarih_goster']				= "Ürün Detay kampanyalı tarih";
		$fields['config_urun_kalansure_goster']			= "Ürün Detay indirim kalan süre";
		$fields['config_urundetay_urungrubu']			= "Ürün Detaydaki listelenen ürün gurubu";

		// facebook
		$fields['config_facebook_status']				= "Facebook Giriş Durumu";
		$rules['config_facebook_status']				= "trim|required|numeric";
		$fields['config_facebook_app_id']				= "Facebook Uygulama Numarası";
		$rules['config_facebook_app_id']				= "trim|xss_clean";
		$fields['config_facebook_secret']				= "Facebook Uygulama Şifresi";
		$rules['config_facebook_secret']				= "trim|xss_clean";
		if (config('facebook_app_status')) {
			$fields['config_facebook_url']				= "Facebook Uygulama Adresi";
			$rules['config_facebook_url']				= "trim|xss_clean";
			$fields['config_facebook_tema']				= "Facebook Temaları";
			$rules['config_facebook_tema']				= "trim|xss_clean";
			$fields['config_facebook_tema_asset']		= "Facebook Tema Renkleri";
			$rules['config_facebook_tema_asset']		= "trim|xss_clean";
		}

		// kupon
		$rules['config_coupon_status']					= "trim|required|numeric";
		$fileds['config_coupon_status']					= "Kupon Uygulaması";		
		$rules['config_coupon_limit']					= "trim|required|numeric";
		$fileds['config_coupon_limit']					= "Günlük Kupon Kullanım Limiti";

		$data['temalar'] = array();
		$klasorler = glob(APPPATH . 'views/temalar/*', GLOB_ONLYDIR);
		foreach ($klasorler as $klasor) {
			$data['temalar'][basename($klasor)] = basename($klasor);
		}

		$data['renkler'] = array();
		$klasorler = glob(APPPATH . 'views/temalar/'. config('site_ayar_tema') .'/tema_asset/*', GLOB_ONLYDIR);
		foreach ($klasorler as $klasor) {
			$data['renkler'][basename($klasor)] = basename($klasor);
		}

		if (config('facebook_app_status')) {
			$data['face_temalar'] = array();
			$klasorler = glob(APPPATH . 'views/face_temalar/*', GLOB_ONLYDIR);
			foreach ($klasorler as $klasor) {
				$data['face_temalar'][basename($klasor)] = basename($klasor);
			}

			$data['face_renkler'] = array();
			$klasorler = glob(APPPATH . 'views/face_temalar/'. config('site_ayar_facebook_tema') .'/tema_asset/*', GLOB_ONLYDIR);
			foreach ($klasorler as $klasor) {
				$data['face_renkler'][basename($klasor)] = basename($klasor);
			}
		}

		$val->set_fields($fields);
		$val->set_rules($rules);
		
		if ($val->run() == FALSE)
		{
			$data['kontrol_data'] = false;
			$this->load->view('yonetim/sistem/genel_ayarlar_view', $data);
		} else {
			$kontrol_data = $this->genel_ayarlar_model->genel_ayarlar_kaydet($val);
			if ($kontrol_data)
			{
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '1'; // 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Ayarlarınız başarılı bir şekilde düzenlenmiştir.');
				$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
				redirect('yonetim/sistem/genel_ayarlar');
			} else {
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '2'; // 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Ayarlarınız kaydedilemedi.');
				$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
				redirect('yonetim/sistem/genel_ayarlar');
			}
		}
		
	}
	
}