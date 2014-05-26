<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class bayiler extends Admin_Controller {

	var $izin_linki;

	function __construct()
	{
		parent::__construct();
		$this->load->model('yonetim/bayi_model');

		$this->izin_linki = 'sistem/bayiler';
	}

	function index()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/bayiler/');
		redirect('yonetim/sistem/bayiler/listele/');
	}
	
	function listele()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/bayiler/listele');

		$data['bayiler'] = array();
		foreach($this->bayi_model->listele()->result() as $bayi)
		{
			$action = array();
			$action[] = array(
				'text' => 'Düzenle',
				'href' => 'yonetim/sistem/bayiler/duzenle/' . $bayi->bayi_id
			);
			
			$data['bayiler'][] = array(
				'id'	=>$bayi->bayi_id,
				'bayi'	=>$bayi->bayi_adi,
				'eposta'=>$bayi->bayi_eposta,
				'tel'	=>$bayi->bayi_tel,
				'fax'	=>$bayi->bayi_fax,
				'tarih'	=>standard_date('DATE_TR2', $bayi->bayi_ektar, 'tr'),
				'action'=>$action
			);
		}
		
		$this->load->view('yonetim/sistem/bayiler/content_view',$data);	
	}

	function duzenle($bayi_id)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/bayiler/duzenle/' . $bayi_id);

		$val = $this->validation;
		$rules['bayi_id']			= "integer";
		$rules['bayi_adi']			= "trim|xss_clean|required";
		$rules['bayi_adres']		= "trim|xss_clean|required";
		if(!$this->input->post('bayi_eposta'))
		{
			$rules['bayi_eposta']		= "trim|valid_email|xss_clean";
		}else {
			$rules['bayi_eposta']		= "trim|xss_clean";
		}
		
		$rules['bayi_tel']			= "trim|xss_clean|required";
		$rules['bayi_tel2']			= "trim|xss_clean";
		$rules['bayi_tel3']			= "trim|xss_clean";
		$rules['bayi_tel4']			= "trim|xss_clean";
		$rules['bayi_tel5']			= "trim|xss_clean";
		$rules['bayi_tel_p']		= "trim|xss_clean";
		$rules['bayi_tel2_p']		= "trim|xss_clean";
		$rules['bayi_tel3_p']		= "trim|xss_clean";
		$rules['bayi_tel4_p']		= "trim|xss_clean";
		$rules['bayi_tel5_p']		= "trim|xss_clean";
		
		$rules['bayi_fax']			= "trim|xss_clean";
		$rules['bayi_fax2']			= "trim|xss_clean";
		$rules['bayi_fax3']			= "trim|xss_clean";
		$rules['bayi_fax_p']		= "trim|xss_clean";
		$rules['bayi_fax2_p']		= "trim|xss_clean";
		$rules['bayi_fax3_p']		= "trim|xss_clean";
		
		$rules['google_durum']		= "integer";
		if($this->input->post('google_durum') == 1)
		{
			$rules['bayi_maps_kodu']	= "trim|required";
			$fields['bayi_maps_kodu']	= "Google Maps Kodu ( Harita )";
		} else {
			$rules['bayi_maps_kodu']	= "trim";
			$fields['bayi_maps_kodu']	= "Google Maps Kodu ( Harita )";			
		}

		$fields['bayi_id']			= "Bayi ID";
		$fields['bayi_adi']			= "Bayi Adı";
		$fields['bayi_adres']		= "Adres";
		$fields['bayi_eposta']		= "Bayi E-Posta";
		$fields['bayi_tel']			= "Telefon 1";
		$fields['bayi_tel2']		= "Telefon 2";
		$fields['bayi_tel3']		= "Telefon 3";
		$fields['bayi_tel4']		= "Telefon 4";
		$fields['bayi_tel5']		= "Telefon 5";
		
		$fields['bayi_fax']			= "Fax 1";
		$fields['bayi_fax2']		= "Fax 2";
		$fields['bayi_fax3']		= "Fax 3";
		$fields['google_durum']		= "Google Maps Durum";
		
		$val->set_rules($rules);
		$val->set_fields($fields);
		if( $val->run() == false)
		{
			$data['id'] = $bayi_id;
			$data['bayi'] = $this->bayi_model->duzenle($bayi_id);
			$data['val']  = $val;
			$this->load->view('yonetim/sistem/bayiler/duzenle_view',$data);	
		} else {
			$kontrol = $this->bayi_model->duzelt($val);
			if($kontrol)
			{
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '1';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarılı: Bayi Düzenleme İşlemi Tamamlandı.');	
			} else {
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '2';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarısız: Bayi Düzenleme İşlemi Tamamlanamadı.');
			}
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/sistem/bayiler/listele');
		}
	}

	function sil()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/bayiler/sil');

		$val = $this->validation;
		$rules['selected'] 		= "trim|xss_clean|required";
		$fields['selected']		= "Bayiler";
		$val->set_rules($rules);
		$val->set_fields($fields);

		if ($val->run() == TRUE)
		{
			$kontrol = $this->bayi_model->sil($val);

			if($kontrol)
			{
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarılı: Silme İşlemi Tamamlandı.');	
			} else {
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarısız: Silme İşlemi Tamamlanamadı.');
			}
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/sistem/bayiler/listele');
		} else {
			$yonetim_mesaj 				= array();
			$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Herhangi Bir Bayi Seçilemediği İçin Bayi Silme İşlemi Tamamlanamadı.');
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/sistem/bayiler/listele');
		}

	}
	
	function ekle()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/bayiler/ekle');

		$val = $this->validation;
		$rules['bayi_adi']			= "trim|xss_clean|required";
		$rules['bayi_adres']		= "trim|xss_clean|required";
		if(!$this->input->post('bayi_eposta'))
		{
			$rules['bayi_eposta']		= "trim|valid_email|xss_clean";
		}else {
			$rules['bayi_eposta']		= "trim|xss_clean";
		}
		
		$rules['bayi_tel']			= "trim|xss_clean|required";
		$rules['bayi_tel2']			= "trim|xss_clean";
		$rules['bayi_tel3']			= "trim|xss_clean";
		$rules['bayi_tel4']			= "trim|xss_clean";
		$rules['bayi_tel5']			= "trim|xss_clean";
		$rules['bayi_tel_p']		= "trim|xss_clean";
		$rules['bayi_tel2_p']		= "trim|xss_clean";
		$rules['bayi_tel3_p']		= "trim|xss_clean";
		$rules['bayi_tel4_p']		= "trim|xss_clean";
		$rules['bayi_tel5_p']		= "trim|xss_clean";
		
		$rules['bayi_fax']			= "trim|xss_clean";
		$rules['bayi_fax2']			= "trim|xss_clean";
		$rules['bayi_fax3']			= "trim|xss_clean";
		$rules['bayi_fax_p']		= "trim|xss_clean";
		$rules['bayi_fax2_p']		= "trim|xss_clean";
		$rules['bayi_fax3_p']		= "trim|xss_clean";
		
		$rules['google_durum']		= "integer";
		if($this->input->post('google_durum') == 1)
		{
			$rules['bayi_maps_kodu']	= "trim|required";
			$fields['bayi_maps_kodu']	= "Google Maps Kodu ( Harita )";
		} else {
			$rules['bayi_maps_kodu']	= "trim";
			$fields['bayi_maps_kodu']	= "Google Maps Kodu ( Harita )";			
		}

		$fields['bayi_adi']			= "Bayi Adı";
		$fields['bayi_adres']		= "Adres";
		$fields['bayi_eposta']		= "Bayi E-Posta";
		$fields['bayi_tel']			= "Telefon 1";
		$fields['bayi_tel2']		= "Telefon 2";
		$fields['bayi_tel3']		= "Telefon 3";
		$fields['bayi_tel4']		= "Telefon 4";
		$fields['bayi_tel5']		= "Telefon 5";
		
		$fields['bayi_fax']			= "Fax 1";
		$fields['bayi_fax2']		= "Fax 2";
		$fields['bayi_fax3']		= "Fax 3";
		$fields['google_durum']		= "Google Maps Durum";
		
		$val->set_rules($rules);
		$val->set_fields($fields);
		if( $val->run() == false)
		{
			$data['val'] = $val;
			$this->load->view('yonetim/sistem/bayiler/ekle_view',$data);
		} else {
			$kontrol = $this->bayi_model->ekle($val);
			
			if($kontrol)
			{
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '1';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarılı: Bayi Ekeleme İşlemi Tamamlandı.');	
			} else {
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '2';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarısız: Bayi Ekeleme İşlemi Tamamlanamadı.');
			}
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/sistem/bayiler/listele');
		} 
		
	}

}