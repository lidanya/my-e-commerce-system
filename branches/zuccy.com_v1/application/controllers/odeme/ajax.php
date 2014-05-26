<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class ajax extends Public_Controller {

	/**
	 * Ödeme Ajax construct
	 *
	 * @return void
	 **/

	public function __construct()
	{
		parent::__construct();
		log_message('debug', 'Ödeme Ajax Controller Yüklendi');

		$this->load->library('form_validation');
		$this->load->helper('string');
	}

	public function siparis_not_ekle()
	{
		$val = $this->form_validation;
		$sonuc = NULL;

		$val->set_rules('order_note', lang('messages_checkout_1_billing_form_order_note_text'), 'trim|required|xss_clean');
		$val->set_error_delimiters('', '');

		$sonuc['basarili']					= '';
		$sonuc['basarisiz']					= '';

		if($val->run()) {
			$siparis_detay = $this->session->userdata('siparis_detay');

			$siparis_detay['siparis_not'] = nl2br(strip_tags($this->input->post('order_note')));
			$this->session->set_userdata('siparis_detay', $siparis_detay);

			$sonuc['basarili'] = 'İşleminiz başarılı';
		} else {
			if (validation_errors()) {
				$sonuc['basarisiz']	= validation_errors();

				foreach($val->_error_array as $key => $value) {
					$sonuc[$key . '_error'] = $value;
				}
			}
		}

		$this->output->set_output(json::encode($sonuc));
	}

	public function sehir_listele()
	{
		$ulke_id = $this->input->post('ulke_id');
		if($ulke_id)
		{
			$sorgu = $this->db->get_where('ulke_bolgeleri', array('ulke_id' => $ulke_id));
			$sehirler = '';
			if($sorgu->num_rows() > 0)
			{
				//$sehirler .= '<select name="domain_iletisim_sehirler_'. $yer .'_'. $hash_id .'" id="domain_iletisim_sehirler_'. $yer .'_'. $hash_id .'">';
				foreach($sorgu->result() as $ulke)
				{
					$secili = ($ulke->bolge_adi == 'Istanbul') ? 'selected="selected"':NULL;
					$sehirler .= '<option value="'. $ulke->bolge_id .'" '. $secili .'>'. $ulke->bolge_adi .'</option>';
				}
				//$sehirler .= '</select>';
				$this->output->set_output($sehirler);
			} else {
				$sehirler .= '<option value="SehirYok">Şehir Yok</option>';
				//echo '<input name="domain_iletisim_sehirler_'. $yer .'_'. $hash_id .'" id="domain_iletisim_sehirler_'. $yer .'_'. $hash_id .'" value="Şehir Yazınız" />';
				$this->output->set_output($sehirler);
			}
		} else {
			$this->output->set_output('Hata');
		}
	}

	public function firma_form_gonder_v2()
	{
		$this->load->model('fatura_model');

		$val = $this->form_validation;
		$sonuc = NULL;

		$val->set_rules('name', lang('messages_checkout_1_billing_form_name_text'), 'trim|required|xss_clean');
		$val->set_rules('phone', lang('messages_checkout_1_billing_form_phone_text'), 'trim|required|xss_clean');
		$val->set_rules('address', lang('messages_checkout_1_billing_form_address_text'), 'trim|required|xss_clean');

		if($this->input->post('company_name') != '') {
			$val->set_rules('id_number', lang('messages_checkout_1_billing_form_id_number_text'), 'trim|numeric|xss_clean');
			$val->set_rules('company_name', lang('messages_checkout_1_billing_form_company_name_text'), 'trim|required|xss_clean');
			$val->set_rules('tax_office', lang('messages_checkout_1_billing_form_tax_office_text'), 'trim|required|xss_clean');
			$val->set_rules('tax_number', lang('messages_checkout_1_billing_form_tax_number_text'), 'trim|required|xss_clean');
		} else {
			$val->set_rules('id_number', lang('messages_checkout_1_billing_form_id_number_text'), 'trim|numeric|required|xss_clean');
			$val->set_rules('company_name', lang('messages_checkout_1_billing_form_company_name_text'), 'trim|xss_clean');
			$val->set_rules('tax_office', lang('messages_checkout_1_billing_form_tax_office_text'), 'trim|xss_clean');
			$val->set_rules('tax_number', lang('messages_checkout_1_billing_form_tax_number_text'), 'trim|xss_clean');
		}

		$val->set_rules('country', lang('messages_checkout_1_billing_form_country_text'), 'trim|xss_clean');
		$val->set_rules('city', lang('messages_checkout_1_billing_form_city_text'), 'trim|xss_clean');
		$val->set_rules('place', lang('messages_checkout_1_billing_form_place_text'), 'trim|xss_clean');
		$val->set_rules('postal_code', lang('messages_checkout_1_billing_form_postal_code_text'), 'trim|xss_clean');

		$val->set_error_delimiters('', '');

		$sonuc['basarili']					= '';
		$sonuc['basarisiz']					= '';
		$sonuc['name_error']				= '';
		$sonuc['id_number_error']			= '';
		$sonuc['company_name_error']		= '';
		$sonuc['address_error']				= '';
		$sonuc['country_error']				= '';
		$sonuc['city_error']				= '';
		$sonuc['place_error']				= '';
		$sonuc['postal_code_error']			= '';
		$sonuc['tax_number_error']			= '';
		$sonuc['tax_office_error']			= '';
		$sonuc['phone_error']				= '';
		$sonuc['fax_number_error']			= '';
		$sonuc['fatura_id']					= '';

		$name = $this->input->post('name');
		$name_parse = get_username_parse($name, ' ', TRUE);

		$send = new stdClass;
		$send->fatura_adi		= random_string('unique', 16);
		$send->adi				= $name_parse['name'];
		$send->soyad			= $name_parse['surname'];
		$send->tckimlik			= $this->input->post('id_number');
		$send->firmaadi			= $this->input->post('company_name');
		$send->adres			= $this->input->post('address');
		$send->ulke				= $this->input->post('country');
		$send->sehir			= $this->input->post('city');
		$send->ilce				= $this->input->post('place');
		$send->postak			= $this->input->post('postal_code');
		$send->vergin			= $this->input->post('tax_number');
		$send->vergid			= $this->input->post('tax_office');
		$send->tel				= $this->input->post('phone');
		$send->fax				= $this->input->post('fax_number');

		if($val->run() AND $this->fatura_model->fatura_ekle($send)) {
			$sonuc['basarili'] = 'İşleminiz başarılı';
			$sonuc['fatura_id'] = $this->db->insert_id();
		} else {
			if (validation_errors()) {
				$sonuc['basarisiz']	= validation_errors();

				foreach($val->_error_array as $key => $value) {
					$sonuc[$key . '_error'] = $value;
				}
			}
		}

		$this->output->set_output(json::encode($sonuc));
	}

	public function firma_form_gonder()
	{
		$val = $this->validation;
		
		$sonuc = NULL;
		//form bilgileri yükleniyor
		$rules['fatura_adi']			= "trim|required|xss_clean";
		$rules['adiniz']				= "trim|required|xss_clean";
		$rules['soyadiniz']				= "trim|required|xss_clean";

		$firma_adi_zorunlu = (strlen($this->input->post('firma_adiniz')) > 0) ? true:false;
		if($firma_adi_zorunlu)
		{
			$rules['tc_kimlik_no'] = "trim|numeric|min_length[11]|max_length[11]|xss_clean";
			$rules['firma_adiniz'] = "trim|required|xss_clean";
			$rules['vergi_dairesi']	= "trim|required|xss_clean";
			$rules['vergi_numarasi'] = "trim|required|xss_clean";
		} else {
			$rules['tc_kimlik_no'] = "trim|numeric|required|min_length[11]|max_length[11]|xss_clean";
			$rules['firma_adiniz'] = "trim|xss_clean";
			$rules['vergi_dairesi']	= "trim|xss_clean";
			$rules['vergi_numarasi'] = "trim|xss_clean";
		}

		$rules['adresiniz']				= "trim|required|xss_clean";
		$rules['ulke']					= "trim|required|xss_clean";
		$rules['sehir']					= "trim|required|xss_clean";
		$rules['ilce']					= "trim|xss_clean";
		$rules['posta_kodu']			= "trim|required|numeric|min_length[5]|max_length[5]|xss_clean";
		$rules['telefon']				= "trim|required|min_length[14]|max_length[14]|xss_clean";
		$rules['fax']					= "trim|min_length[11]|xss_clean";

		$fields['tc_kimlik_no']			= "TC Kimlik Numarası";
		$fields['fatura_adi']			= "Fatura Adı";
		$fields['adiniz']				= "Adınız";
		$fields['soyadiniz']			= "Soyadınız";
		$fields['firma_adiniz']			= "Firma Adınız";
		$fields['vergi_dairesi']		= "Vergi Dairesi";
		$fields['vergi_numarasi']		= "Vergi Numarası";
		$fields['adresiniz']			= "Adresiniz";
		$fields['ulke']					= "Ülke";
		$fields['sehir']				= "İl";
		$fields['ilce']					= "İlçe";
		$fields['posta_kodu']			= "Posta Kodu";
		$fields['telefon']				= "Telefon Numarası";
		$fields['fax']					= "Faks Numarası";
	
		$val->set_fields($fields);
		$val->set_rules($rules);

		if ($val->run())
		{
			$this->db->where('user_id', $this->dx_auth->get_user_id());
			$this->db->where('inv_flag', '2');
			$standart_varmi = $this->db->count_all_results('usr_inv_inf');
			$standart = ($standart_varmi > 0) ? true:false;
			
			$user_id = $this->dx_auth->get_user_id();
			
			$fatura_data = array(
				'inv_name'				=> $val->fatura_adi,
				'inv_username'			=> $val->adiniz,
				'inv_usersurname'		=> $val->soyadiniz,
				'inv_tckimlik'			=> $val->tc_kimlik_no,
				'inv_firma'				=> $val->firma_adiniz,
				'inv_vda'				=> $val->vergi_dairesi,
				'inv_vno'				=> $val->vergi_numarasi,
				'inv_adr_id'			=> $val->adresiniz,
				'inv_ulke'				=> $val->ulke,
				'inv_sehir'				=> $val->sehir,
				'inv_ilce'				=> $val->ilce,
				'inv_pkodu'				=> $val->posta_kodu,
				'inv_tel'				=> $val->telefon,
				'inv_fax'				=> $val->fax,
				'inv_flag'				=> ($standart) ? '1':'2',
				'user_id'				=> $user_id
			);
			$this->db->insert('usr_inv_inf', $fatura_data);
			$fatura_id = $this->db->insert_id();
	
			/* Üyenin Bilgileri Güncellenmemişse Güncelle */
			$user_profile_query = $this->db->get_where('usr_ide_inf', array('user_id' => $user_id), 1);
			if($user_profile_query->num_rows() > 0)
			{
				$userp_info = $user_profile_query->row();
				$kontrol = 0;
				if($userp_info->ide_adi == '')
				{
					$kontrol += 1;
					$userp_data['ide_adi'] = $val->adiniz;
				}
				
				if($userp_info->ide_soy == '')
				{
					$kontrol += 1;
					$userp_data['ide_soy'] = $val->soyadiniz;
				}
				
				if($userp_info->ide_tckimlik == '')
				{
					$kontrol += 1;
					$userp_data['ide_tckimlik'] = $val->tc_kimlik_no;
				}
				
				if($kontrol > 0)
				{
					$this->db->where('user_id', $userp_info->user_id);
					$this->db->update('usr_ide_inf', $userp_data);
				}
			} else {
				$userp_info = $user_profile_query->row();
				
				$userp_data['ide_adi'] = $val->adiniz;
				$userp_data['ide_soy'] = $val->soyadiniz;
				$userp_data['ide_tckimlik'] = $val->tc_kimlik_no;
				$userp_data['ide_flag'] = '1';
				$userp_data['user_id'] = $user_id;
				$this->db->insert('usr_ide_inf', $userp_data);
			}
			/* Üyenin Bilgileri Güncellenmemişse Güncelle */

			$sonuc['basarili'] = (string)$fatura_id;
		}
		else
		{
			$sonuc['basarisiz']	= $val->error_string;
		}

		$this->output->set_output(json::encode($sonuc));
	}

	public function teslimat_form_gonder()
	{
		$val = $this->validation;

		$sonuc = NULL;
		//form bilgileri yükleniyor
		$rules['ad_soyad']				= "trim|required|xss_clean";
		$rules['adres']					= "trim|required|xss_clean";
		$rules['ulke']					= "trim|required|xss_clean";
		$rules['sehir']					= "trim|required|xss_clean";
		$rules['ilce']					= "trim|xss_clean";
		$rules['posta_kodu']			= "trim|required|numeric|min_length[5]|max_length[5]|xss_clean";
		$rules['telefon']				= "trim|required|min_length[14]|max_length[14]|xss_clean";
		$rules['siparis_id']			= "trim|required|numeric|xss_clean";

		$fields['ad_soyad']				= "Ad Soyad";
		$fields['adres']				= "Adres";
		$fields['ulke']					= "Ülke";
		$fields['sehir']				= "İl";
		$fields['ilce']					= "İlçe";
		$fields['posta_kodu']			= "Posta Kodu";
		$fields['telefon']				= "Telefon Numarası";
		$fields['siparis_id']			= "Sipariş Numarası";

		$val->set_fields($fields);
		$val->set_rules($rules);

		$val->set_fields($fields);
		$val->set_rules($rules);

		if ($val->run())
		{

			$session_data = array('teslimat' => 
								array(
									'ad_soyad' => $val->ad_soyad,
									'adres' => $val->adres,
									'ulke' => $val->ulke,
									'sehir' => $val->sehir,
									'ilce' => $val->ilce,
									'posta_kodu' => $val->posta_kodu,
									'telefon' => $val->telefon
								)
							);

			$this->session->set_userdata('siparis_' . $val->siparis_id, $session_data);
			$sonuc['basarili'] = 'Teslimat bilgileri geçici hafızaya kaydedildi.';
		} else {
			$sonuc['basarisiz'] = $val->error_string;
		}

		$this->output->set_output(json::encode($sonuc));
	}

	public function teslimat_form_gonder_v2()
	{
		$val = $this->form_validation;
		$sonuc = NULL;

		$val->set_rules('name', lang('messages_checkout_1_shipping_form_name_text'), 'trim|required|xss_clean');
		$val->set_rules('phone', lang('messages_checkout_1_shipping_form_phone_text'), 'trim|required|xss_clean');
		$val->set_rules('address', lang('messages_checkout_1_shipping_form_address_text'), 'trim|required|xss_clean');
		$val->set_rules('country', lang('messages_checkout_1_shipping_form_country_text'), 'trim|numeric|xss_clean');
		$val->set_rules('city', lang('messages_checkout_1_shipping_form_city_text'), 'trim|numeric|xss_clean');
		$val->set_rules('place', lang('messages_checkout_1_shipping_form_place_text'), 'trim|xss_clean');
		$val->set_rules('postal_code', lang('messages_checkout_1_shipping_form_postal_code_text'), 'trim|numeric|xss_clean');

		$val->set_error_delimiters('', '');

		$sonuc['basarili']					= '';
		$sonuc['basarisiz']					= '';
		$sonuc['name_error']				= '';
		$sonuc['address_error']				= '';
		$sonuc['country_error']				= '';
		$sonuc['city_error']				= '';
		$sonuc['place_error']				= '';
		$sonuc['postal_code_error']			= '';

		if($val->run()) {
			$siparis_detay = $this->session->userdata('siparis_detay');

			$siparis_detay_teslimat['teslimat']['ad_soyad'] = $this->input->post('name');
			$siparis_detay_teslimat['teslimat']['telefon'] = $this->input->post('phone');
			$siparis_detay_teslimat['teslimat']['adres'] = $this->input->post('address');
			$siparis_detay_teslimat['teslimat']['ulke'] = $this->input->post('country');
			$siparis_detay_teslimat['teslimat']['sehir'] = $this->input->post('city');
			$siparis_detay_teslimat['teslimat']['ilce'] = $this->input->post('place');
			$siparis_detay_teslimat['teslimat']['posta_kodu'] = $this->input->post('postal_code');

			$siparis_detay = array_merge($siparis_detay_teslimat, $siparis_detay);
			$this->session->set_userdata('siparis_detay', $siparis_detay);

			$sonuc['basarili'] = 'İşleminiz başarılı';
		} else {
			if (validation_errors()) {
				$sonuc['basarisiz']	= validation_errors();

				foreach($val->_error_array as $key => $value) {
					$sonuc[$key . '_error'] = $value;
				}
			}
		}

		$this->output->set_output(json::encode($sonuc));
	}
}

/* End of file isimsiz.php */
/*  */

?>