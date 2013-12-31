<?php

	/* Oluşum */
	//	$db_array = array();
	//	$yaz = '';
	//	foreach($this->db->list_tables() as $tables) {
	//		$_table = strtr($tables, array('daynex_' => ''));
	//		$fields = $this->db->list_fields($tables);
	//		$_fields = array();
	//		foreach($fields as $field) {
	//			$_fields[] = strtr($field, array('daynex_' => ''));
	//		}
	//		$yaz .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/* '. ucwords(strtr($_table, array('_' => ' '))) .' */' . "<br />";
	//		$yaz .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\''. $_table .'\' => \''. implode(', ', $_fields) .'\',' . "<br />";
	//	}

	//	exit($yaz);
	// find /	
	// repl /	
	/* Oluşum */

	$config['sql_table_fields'] = array(
        /* Ayarlar */
            'ayarlar' => 'ayar_adi, ayar_deger',
        /* Bayiler */
            'bayiler' => 'bayi_id, bayi_adi, bayi_eposta, bayi_adres, bayi_maps_flag, bayi_maps_kodu, bayi_tel, bayi_tel_p, bayi_tel2, bayi_tel2_p, bayi_tel3, bayi_tel3_p, bayi_tel4, bayi_tel4_p, bayi_tel5, bayi_tel5_p, bayi_fax, bayi_fax_p, bayi_fax2, bayi_fax2_p, bayi_fax3, bayi_fax3_p, bayi_flag, bayi_ektar',
        /* Category */
            'category' => 'category_id, image, parent_id, top, column, sort_order, date_added, date_modified, status',
        /* Category Description */
            'category_description' => 'category_id, language_id, name, meta_keywords, meta_description, description, seo',
        /* Category Features */
            'category_features' => 'feature_id, category_id, date_added, date_modified, status',
        /* Category Features Description */
            'category_features_description' => 'feature_id, language_id, name',
        /* Coupon */
            'coupon' => 'id, title, description, code, type, value, date_start, date_end, status, date_add',
        /* Eklentiler */
            'eklentiler' => 'eklenti_id, eklenti_yer, eklenti_baslik, eklenti_baslik_goster, eklenti_ascii, eklenti_durum, eklenti_sira',
        /* Eklentiler Ayalar */
            'eklentiler_ayalar' => 'eklenti_ascii, ayar_adi, ayar_deger',
        /* Information */
            'information' => 'information_id, sort_order, category_id, type, status, delete, image, date_added, date_modified',
        /* Information Category */
            'information_category' => 'information_category_id, sort_order, parent_id, type, status, image, date_added, date_modified',
        /* Information Category Description */
            'information_category_description' => 'information_category_id, language_id, title, description, meta_keywords, meta_description, seo',
        /* Information Description */
            'information_description' => 'information_id, language_id, title, description, meta_keywords, meta_description, seo',
        /* Istatistik */
            'istatistik' => 'istatistik_id, istatistik_ip, istatistik_son_sayfa, istatistik_tarih, istatistik_uye_id, istatistik_tarayici_bilgisi, istatistik_tip',
        /* Kargo */
            'kargo' => 'kargo_id, kargo_adi, kargo_logo, kargo_parca, kargo_ucret_tip, kargo_sira, kargo_flag',
        /* Kargo Ucret */
            'kargo_ucret' => 'kargo_ucret_id, kargo_ucret_tip, kargo_ucret_ucret, kargo_ucret_flag, kargo_id',
        /* Kurlar */
            'kurlar' => 'kur_id, kur_adi, kur_alis, kur_alis_eski, kur_satis, kur_satis_eski, kur_tipi, kur_alis_manuel, kur_satis_manuel, kur_guncelleme_zamani',
        /* Language */
            'language' => 'language_id, name, code, locale, image, directory, filename, sort_order, status',
        /* Login Attempts */
            'login_attempts' => 'id, ip_address, time',
        /* Mail Reklamlar */
            'mail_reklamlar' => 'reklam_id, reklam_flag, reklam_adi, reklam_link, reklam_icerik',
        /* Manufacturer */
            'manufacturer' => 'manufacturer_id, name, image, seo, meta_description, meta_keywords, description, status, sort_order, date_added, date_modified',
        /* Odeme Secenek Havale */
            'odeme_secenek_havale' => 'havale_id, havale_banka_baslik, havale_banka_ascii, havale_durum, havale_sira, havale_banka_resim',
        /* Odeme Secenek Havale Detay */
            'odeme_secenek_havale_detay' => 'havale_detay_id, banka_id, hesap_no, hesap_sahip, sube, iban_no, tur, hesap_durum',
        /* Odeme Secenek Kredi Karti */
            'odeme_secenek_kredi_karti' => 'kk_id, kk_odeme_id, kk_banka_adi, kk_banka_adi_ascii, kk_banka_resim, kk_banka_durum, kk_banka_pos_tipi, kk_banka_test_tipi, kk_banka_standart, kk_banka_taksit, kk_pesin_komisyon, kk_banka_bilgi, kk_banka_secilebilir_pos_tipleri, kk_banka_secilebilir_test_tipleri',
        /* Odeme Secenek Kredi Karti Bin Numaralari */
            'odeme_secenek_kredi_karti_bin_numaralari' => 'kkbn_id, kkbn_kk_id, kkbn_bin_no, kkbn_aciklama, kkbn_durum',
        /* Odeme Secenek Kredi Karti Hata Kodlari */
            'odeme_secenek_kredi_karti_hata_kodlari' => 'kkhk_id, kkhk_hata_kodu, kkhk_hata_mesaj, kkhk_hata_aciklama, kkhk_hata_durum',
        /* Odeme Secenek Kredi Karti Taksit Secenekleri */
            'odeme_secenek_kredi_karti_taksit_secenekleri' => 'kkts_id, kk_id, kkts_taksit_sayisi, kkts_durum, kkts_komisyon',
        /* Odeme Secenekleri */
            'odeme_secenekleri' => 'odeme_id, odeme_durum, odeme_baslik, odeme_aciklama, odeme_sira, odeme_resim, odeme_model, odeme_kurulum, odeme_siparis_durum, odeme_indirim_orani, odeme_indirim_tipi',
        /* Option */
            'option' => 'option_id, type, sort_order',
        /* Option Description */
            'option_description' => 'option_id, language_id, name',
        /* Option Value */
            'option_value' => 'option_value_id, option_id, sort_order',
        /* Option Value Description */
            'option_value_description' => 'option_value_id, language_id, option_id, name',
        /* Permissions */
            'permissions' => 'id, role_id, data',
        /* Product */
            'product' => 'product_id, model, quantity, stock_status_id, image, manufacturer_id, price, price_type, stock_type, tax, date_available, status, show_homepage, new_product, feature_status, cargo_required, cargo_multiply_required, date_added, date_modified, viewed, sort_order, minimum, cost, length, width, height, length_class_id, weight, weight_class_id, subtract, hizli_gonder',
        /* Product Description */
            'product_description' => 'product_id, language_id, seo, name, info, meta_keywords, meta_description, description, video',
        /* Product Discount */
            'product_discount' => 'product_discount_id, product_id, user_group_id, quantity, priority, price, date_start, date_end',
        /* Product Featured */
            'product_featured' => 'product_id, feature_id, language_id, value',
        /* Product Follow */
            'product_follow' => 'follow_id, product_id, user_id',
        /* Product Image */
            'product_image' => 'product_image_id, product_id, image',
        /* Product Option */
            'product_option' => 'product_option_id, product_id, option_id, option_value, required, character_limit',
        /* Product Option Value */
            'product_option_value' => 'product_option_value_id, product_option_id, product_id, option_id, option_value_id, quantity, subtract, price, price_prefix',
        /* Product Related */
            'product_related' => 'product_id, related_id',
        /* Product Special */
            'product_special' => 'product_special_id, product_id, user_group_id, quantity, priority, price, date_start, date_end',
        /* Product To Category */
            'product_to_category' => 'product_id, category_id',
        /* Review */
            'review' => 'review_id, product_id, user_id, email, author, text, rating, status, date_added, date_modified',
        /* Roles */
            'roles' => 'id, parent_id, name, fiyat_orani, flag, fiyat_tip, yetki',
        /* Sessions */
            'sessions' => 'session_auto_id, session_id, ip_address, user_agent, last_activity, user_data',
        /* Siparis */
            'siparis' => 'siparis_id, siparis_tar, irsaliye_tar, irsaliye_no, usr_inv_id, odeme_tip, kayit_tar, siparis_flag, siparis_flag_data, user_id, siparis_data, siparis_islem_tarihi',
        /* Siparis Detay */
            'siparis_detay' => 'siparis_det_id, siparis_id, stok_kodu, stok_aciklama, stok_bfiyat, stok_tfiyat, stok_kdv_orani, kayit_tar, stok_miktar, stokdan_dus_durum, stok_tip, siparis_det_flag, siparis_det_data',
        /* Siparis Durum */
            'siparis_durum' => 'siparis_durum_id, siparis_durum_baslik, siparis_durum_tanim_id, siparis_durum_silme',
        /* Tanimlar */
            'tanimlar' => 'tanimlar_id, tanimlar_adi, tanimlar_kod, tanimlar_tip',
        /* Ticket */
            'ticket' => 'ticket_id, ticket_kodu, ticket_kime, ticket_konu, ticket_icerik, ticket_tarih, ticket_tip, ticket_flag, ticket_uye_durum, ticket_adm_durum, ticket_prm_id, user_id',
        /* Tool Slider */
            'tool_slider' => 'slider_id, slider_link, slider_img, slider_flag, slider_sira',
        /* Ulke Bolgeleri */
            'ulke_bolgeleri' => 'bolge_id, ulke_id, kod, bolge_adi',
        /* Ulkeler */
            'ulkeler' => 'ulke_id, ulke_adi, iso_code_2, iso_code_3, iso_num_3, adres_formati',
        /* User Autologin */
            'user_autologin' => 'key_id, user_id, user_agent, last_ip, last_login',
        /* User Profile */
            'user_profile' => 'id, user_id, country, website',
        /* User Temp */
            'user_temp' => 'id, username, password, email, activation_key, last_ip, created, role_id',
        /* Users */
            'users' => 'id, role_id, username, password, email, banned, ban_reason, newpass, newpass_key, newpass_time, last_ip, last_login, created, modified, durum',
        /* Users Facebook */
            'users_facebook' => 'user_id, facebook_id',
        /* Usr Adr Inf */
            'usr_adr_inf' => 'adr_id, adr_is_adr_id, adr_is_ack, adr_is_tel1, adr_is_tel2, adr_is_fax, adr_tip, adr_flag, user_id',
        /* Usr Ide Inf */
            'usr_ide_inf' => 'ide_id, ide_adi, ide_soy, ide_dogtar, ide_dogyer, ide_cins, ide_cep, ide_flag, ide_unv, ide_tckimlik, ide_alternatif_mail, ide_web_site, user_id',
        /* Usr Inv Inf */
            'usr_inv_inf' => 'inv_id, inv_adr_id, inv_name, inv_username, inv_usersurname, inv_tckimlik, inv_ack, inv_firma, inv_vno, inv_vda, inv_tel, inv_fax, inv_flag, inv_pkodu, inv_ulke, inv_sehir, inv_ilce, user_id',
        /* Usr Sec Inf */
            'usr_sec_inf' => 'sec_id, sec_save_date, sec_last_date, sec_aktivasyon, sec_ques, sec_answer, sec_active, sec_last_act, sec_flag, user_id',
	);

?>