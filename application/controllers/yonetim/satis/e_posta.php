<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class e_posta extends Admin_Controller {

	var $izin_linki;

	function __construct()
	{
		parent::__construct();
		$this->load->model('yonetim/reklam_model');

		$this->izin_linki = 'satis/e_posta';
	}

	function index()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/satis/e_posta/reklam');
		redirect('yonetim/satis/e_posta/reklam');
	}
	
	function reklam()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/satis/e_posta/reklam');

		$data=array();
		$data['reklamlar'] =array();
		foreach($this->reklam_model->listesi()->result() as $reklam)
		{
			$data['reklamlar'][] = array(
				'id' 			=>$reklam->reklam_id,
				'adi' 			=>$reklam->reklam_adi,
				'link' 			=>$reklam->reklam_link,
				'icerik'		=>$reklam->reklam_icerik,
				'durum' 		=>$reklam->reklam_flag
				);
		}
		
		
		$this->load->view('yonetim/satis/e_posta/reklam_view', $data);
	}

	function reklam_ekle()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/satis/e_posta/reklam_ekle');

		$val = $this->validation;
		$rules['reklam_adi'] 				= 'trim|required|xss_clean';
		$rules['reklam_link'] 				= 'trim|required|xss_clean';
		$rules['reklam_metni'] 				= 'trim|required|xss_clean';
		$rules['reklam_durum'] 				= 'trim|required|xss_clean';
		
		$fields['reklam_adi'] 				= 'Reklam Adı';
		$fields['reklam_link'] 				= 'Reklam Link';
		$fields['reklam_metni'] 			= 'Reklam Metni';
		$fields['reklam_durum'] 			= 'Reklam Durum';
		
		$val->set_rules($rules);
		$val->set_fields($fields);
		if($val->run())
		{
			$kontrol = $this->reklam_model->ekle($val);
			if($kontrol)
			{
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '1';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarılı: Reklam Ekleme İşlemi Tamamlandı.');	
			} else {
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '2';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarısız: Reklam Ekleme İşlemi Tamamlanamadı.');
			}
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/satis/e_posta/reklam');
			
		} else {
			$data['val'] = $val;
			$this->load->view('yonetim/satis/e_posta/reklam_ekle_view',$data);
		}
		
	}
	
	function reklam_duzenle($id = 0)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/satis/e_posta/reklam_duzenle/' . $id);

		$val = $this->validation;
		$rules['reklam_id'] 				= 'trim|integer|xss_clean';
		$rules['reklam_adi'] 				= 'trim|required|xss_clean';
		$rules['reklam_link'] 				= 'trim|required|xss_clean';
		$rules['reklam_metni'] 				= 'trim|required|xss_clean';
		$rules['reklam_durum'] 				= 'trim|required|xss_clean';
		
		$fields['reklam_id'] 				= 'Reklam ID';
		$fields['reklam_adi'] 				= 'Reklam Adı';
		$fields['reklam_link'] 				= 'Reklam Link';
		$fields['reklam_metni'] 			= 'Reklam Metni';
		$fields['reklam_durum'] 			= 'Reklam Durum';
		
		$val->set_rules($rules);
		$val->set_fields($fields);
		if($val->run())
		{
			$kontrol = $this->reklam_model->duzelt($val);
			if($kontrol)
			{
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '1';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarılı: Reklam Düzenleme İşlemi Tamamlandı.');	
			} else {
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '2';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarısız: Reklam Düzenleme İşlemi Tamamlanamadı.');
			}
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/satis/e_posta/reklam');
			
		} else {
			$data['val'] = $val;
			$data['id'] = $id;
			$data['reklam'] = $this->db->get_where('mail_reklamlar',array('reklam_id'=>$id))->row();
			$this->load->view('yonetim/satis/e_posta/reklam_duzenle_view',$data);
		}
		
	}
	
	function reklam_sil()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/satis/e_posta/reklam_sil');

		$val = $this->validation;
		$rules['selected']	 				= 'trim|requeried|xss_clean';
		$fields['reklam_id'] 				= 'Reklamlar';

		$val->set_rules($rules);
		$val->set_fields($fields);
		if($val->run())
		{
			$kontrol = $this->reklam_model->sil($val);
			if($kontrol)
			{
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarılı: Reklam Silme İşlemi Tamamlandı.');	
			} else {
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarısız: Reklam Silme İşlemi Tamamlanamadı.');
			}
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/satis/e_posta/reklam');
		} else {
			redirect('yonetim/satis/e_posta/reklam');
		}
	}
	
	function reklam_durum($id = 0)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/satis/e_posta/reklam_durum/' . $id);

		$kontrol = $this->reklam_model->durum($id);
		if($kontrol)
		{
			$yonetim_mesaj 				= array();
			$yonetim_mesaj['durum'] 	= '1';// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Başarılı: Reklam Durum Değişikliği İşlemi Tamamlandı.');	
		} else {
			$yonetim_mesaj 				= array();
			$yonetim_mesaj['durum'] 	= '2';// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Başarısız: Reklam Durum Değişikliği İşlemi Tamamlanamadı.');
		}
		$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
		redirect('yonetim/satis/e_posta/reklam');
	}	
}