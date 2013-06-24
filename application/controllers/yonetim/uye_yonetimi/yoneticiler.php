<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class yoneticiler extends Admin_Controller {

	var $izin_linki;

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Üyeler Controller Yüklendi');

		$this->load->model('yonetim/yoneticiler_model');
		$this->izin_linki = 'uye_yonetimi/yoneticiler';
	}

	function index()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/uye_yonetimi/yoneticiler');
		redirect('yonetim/uye_yonetimi/yoneticiler/listele');
	}
	
	function listele($sort_lnk = 'username_asc', $filter = 'username|]', $page = 0)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/uye_yonetimi/yoneticiler/listele/' . $sort_lnk . '/' . $filter . '/' . $page);

		$this->output->enable_profiler(false);
		$data = array();
		$data['yonetici_gruplari'] = $this->yoneticiler_model->yonetici_grup_listele()->result();
		
		$data['yoneticiler'] = array();
		foreach($this->yoneticiler_model->listesi($sort_lnk, $filter, $page)->result() as $result)
		{
			$action = array();
			$action[] = array(
				'text' => 'Düzenle',
				'href' => 'yonetim/uye_yonetimi/yoneticiler/duzenle/' . $result->user_id
			);

			$data['yoneticiler'][] = array(
				'user_id'			=> $result->user_id,
				'id'				=> $result->id,
				'role_id'			=> $result->role_id,
				'username'			=> $result->username,
				'email'				=> $result->email,
				'adddate'			=> $result->created,
				'ide_id'			=> $result->ide_id,
				'ide_adi'			=> $result->ide_adi,
				'ide_soy'			=> $result->ide_soy,
				'parent_id'			=> $result->parent_id,
				'name'				=> $result->name,
				'role_name'			=> $result->role_name,
				'action'			=> $action
			);
		}

		$this->load->view('yonetim/uyeyonetimi/yoneticiler/yonetici_listesi_view', $data);
	}
	
	function kullanici_adi_kontrol($username)
	{
		$result = $this->dx_auth->is_username_available($username);
		if ( ! $result)
		{
			$this->validation->set_message('kullanici_adi_kontrol', 'Email adresi kullanılıyor lütfen başka deneyiniz.');
		}
		return $result;
	}
	
	function email_adresi_kontrol($email)
	{
		$result = $this->dx_auth->is_email_available($email);
		if ( ! $result)
		{
			$this->validation->set_message('email_adresi_kontrol', 'Email adresi kullanılıyor lütfen başka deneyiniz.');
		}
		return $result;
	}
	
	function duzenle($user_id)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/uye_yonetimi/yoneticiler/duzenle/' . $user_id);

		if(!$this->yoneticiler_model->yonetici_kontrol($user_id))
		{
			redirect('yonetim/uye_yonetimi/yoneticiler/listele');
		}

		$data['customer_inf'] 		= $this->yoneticiler_model->yonetici_bilgi($user_id);
		$data['customer_inv_inf'] 	= $this->yoneticiler_model->yonetici_inv_inf($user_id);
		$data['customer_groups'] 	= $this->yoneticiler_model->yonetici_grup_listele()->result();

		$this->load->library('validation');
		$val = $this->validation;

		$email = $this->input->post('email');

		if($_POST)
		{
			if($data['customer_inf']->username != $email && $data['customer_inf']->email != $email)
			{
				$rules['email']						= "trim|required|valid_email|callback_email_adresi_kontrol|callback_kullanici_adi_kontrol|xss_clean";
				$fields['email']					= "E-Posta";
			} else {
				$rules['email']						= "trim|required|valid_email|xss_clean";
				$fields['email']					= "E-Posta";
			}
		} else {
			$rules['email']							= "trim|required|valid_email|callback_email_adresi_kontrol|callback_kullanici_adi_kontrol|xss_clean";
			$fields['email']						= "E-Posta";
		}

		//başla form değerleri
		$rules['musteri_id']				= "trim|numeric|required|xss_clean";
		//tab_kimlik
		$rules['ide_adi']					= "trim|required|xss_clean";
		$rules['ide_soy']					= "trim|required|xss_clean";
		$rules['ide_cins']					= "trim|xss_clean";
//		$rules['ide_alternatif_mail']		= "trim|valid_email|xss_clean";
		$rules['ide_tckimlik']				= "trim|min_length[11]|min_length[11]|xss_clean";
//		$rules['ide_unv']					= "trim|xss_clean";
//		$rules['ide_dogtar']				= "trim|xss_clean";
		$rules['ide_web_site']				= "trim|valid_website|xss_clean";
		// $rules['ide_haber']					= "trim|required|xss_clean";
		$rules['role_id']					= "trim|required|xss_clean";
		//tab_iletisim
		$rules['ide_cep']					= "trim|xss_clean";
		$rules['adr_is_tel1']				= "trim|xss_clean";
		$rules['adr_is_fax']				= "trim|xss_clean";
		$rules['adr_is_tel2']				= "trim|xss_clean";
		$rules['adr_is_ack']				= "trim|xss_clean";
		//tab_guvenlik
		$rules['password']					= "trim|min_length[6]|max_length[20]|matches[confirm]|xss_clean";
		$rules['confirm']					= "trim|min_length[6]|max_length[20]|xss_clean";

		$fields['musteri_id']				= "Müşteri Numarası";
		//tab_kimlik
		$fields['ide_adi']					= "Adı";
		$fields['ide_soy']					= "Soyadı";
		$fields['ide_cins']					= "Cinsiyet";
//		$fields['ide_alternatif_mail']		= "Alternatif E-Posta";
		$fields['ide_tckimlik']				= "TC Kimlik No";
//		$fields['ide_unv']					= "Ünvan";
//		$fields['ide_dogtar']				= "Doğum Tarihi";
		$fields['ide_web_site']				= "Web Site";
		// $fields['ide_haber']				= "Ürün Habercisi";
		$fields['role_id']					= "Müşteri Grubu";
		//tab_iletisim
		$fields['ide_cep']					= "Cep Telefonu";
		$fields['adr_is_tel1']				= "İş Telefonu";
		$fields['adr_is_fax']				= "İş Faks";
		$fields['adr_is_tel2']				= "Ev Telefonu";
		$fields['adr_is_ack']				= "Cep Telefonu";
		//tab_guvenlik
		$fields['password']					= "Parola";
		$fields['confirm']					= "Parola(tekrar)";

		$val->set_rules($rules);
		$val->set_fields($fields);
		$val->set_error_delimiters('', '');

		$data['kontrol'] = NULL;
		if ($val->run() == true)
		{
			$kontrol =  $this->yoneticiler_model->yonetici_duzenle($val);
			if($kontrol)
			{
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '1';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarılı: Yönetici Bilgileri Güncellendi.');	
			} else {
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '2';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarısız: Yönetici Bilgileri Güncellenemedi.');
			}
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			
			//redirect('yonetim/uye_yonetimi/yoneticiler/duzenle/'.$user_id);
			redirect('yonetim/uye_yonetimi/yoneticiler/listele');
		} else {
			$this->load->view('yonetim/uyeyonetimi/yoneticiler/yonetici_duzenle_view', $data);
		}
	}
}

/* End of file isimsiz.php */
/*  */

?>