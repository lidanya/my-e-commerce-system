<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class kargo extends Admin_Controller {

	var $izin_linki;

	function __construct()
	{
		parent::__construct();
		$this->load->model('yonetim/kargo_model');

		$this->izin_linki = 'sistem/kargo';
	}

	function index()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/kargo');
		redirect('yonetim/sistem/kargo/listele');
	}	

	function listele($sort_lnk='name_asc')
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/kargo/listele/' . $sort_lnk);

		if ($sort_lnk=='$1'){$sort_lnk='name_asc';}
		$sort_lnk_e=explode('_',$sort_lnk);
		$sort  = $sort_lnk_e[0];
		$order = $sort_lnk_e[1];
		
		if($sort=='name'){$sort='kargo_adi';} else if($sort=='order'){$sort='kargo_sira';} else if($sort=='status'){$sort='kargo_flag';} 
		
		$data = array();
		$data['kargolar']=array();
    	foreach ($this->kargo_model->kargo_listele($sort, $order)->result() as $result)
    	{
			$action = array();
			$action[] = array(
				'text' => 'Düzenle',
				'href' => 'yonetim/sistem/kargo/duzenle/' . $result->kargo_id
			);
			$data['kargolar'][] = array(
				'kargo_id'    	 			=> $result->kargo_id,
				'kargo_adi'  				=> $result->kargo_adi,
				'kargo_logo'  				=> $result->kargo_logo,
				'kargo_parca' 				=> $result->kargo_parca,				
				'kargo_ucret_tip'  			=> $result->kargo_ucret_tip,
				'kargo_sira' 				=> $result->kargo_sira,
				'kargo_flag' 				=> $result->kargo_flag,
				'selected'       			=> ($this->input->post('selected') && in_array($result->kargo_id, $this->input->post('selected'))),
				'action'         			=> $action
			);
		}
		$this->load->view('yonetim/sistem/kargo_listele_view' , $data);
	}		

	function ekle()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/kargo/ekle');

		$val = $this->validation;
		$rules['kargo_adi']    	  = "trim|xss_clean|required";
		$rules['product_image']   = "trim|xss_clean";
		$rules['kargo_durum']     = "trim|xss_clean";
		//$rules['kargo_parca']     = "trim|xss_clean";
		$rules['kargo_ucret_tip'] = "trim|xss_clean";
		$rules['ucret_tip1']  	  = "trim|xss_clean|required";
		$rules['ucret_tip2'] 	  = "trim|xss_clean";
		$rules['ucret_tip3'] 	  = "trim|xss_clean";
		$rules['ucret_tip4']	  = "trim|xss_clean";
		$rules['ucret_tip5']	  = "trim|xss_clean";
		$rules['ucret_tip6']	  = "trim|xss_clean";
		$rules['ucret_tip7']	  = "trim|xss_clean";
		$rules['ucret_tip8']	  = "trim|xss_clean";
		
		$fields['kargo_adi']  	  = "Kargo Adı";
		$fields['product_image']  = "Kargo Logosu";
		$fields['kargo_durum']    = "Kargo Durum";
		//$fields['kargo_parca']    = "Parça Başına ";
		$fields['kargo_ucret_tip']= "Ücret Tipi";
		$fields['ucret_tip1']	  = "Sabti Ücret";
		$fields['ucret_tip2']	  = "Ücret Tipi 2";
		$fields['ucret_tip3']	  = "Ücret Tipi 3";
		$fields['ucret_tip4']	  = "Ücret Tipi 4";
		$fields['ucret_tip5']	  = "Ücret Tipi 5";
		$fields['ucret_tip6']	  = "Ücret Tipi 6";
		$fields['ucret_tip7']	  = "Ücret Tipi 7";
		$fields['ucret_tip8']	  = "Ücret Tipi 8";
		$val->set_fields($fields);
		$val->set_rules($rules);
		if ($val->run() == FALSE)
		{
			$data['kontrol_data'] = false;
			$this->load->view('yonetim/sistem/kargo_ekle_view' , $data);
		} else {
			$kontrol_data=$this->kargo_model->kargo_ekle($val);
			$data['kontrol_data'] = $kontrol_data;
			if ($kontrol_data==true)
			{
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '1';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarılı: Kargo Eklendi.');	
				$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
				redirect('yonetim/sistem/kargo');
			} else {
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '2';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarısız: Kargo Eklenemedi.');	
				$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
				redirect('yonetim/sistem/kargo');
			}
		}
	}		
	
	function duzenle($kargo_id)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/kargo/duzenle/' . $kargo_id);

		$data['kargo_veri'] 	  = $this->kargo_model->kargo_veri($kargo_id);
		$data['kargo_ucret_veri'] = $this->kargo_model->kargo_ucret_veri($kargo_id);

		$val = $this->validation;
		
		$rules['kargo_adi']    	  = "trim|xss_clean|required";
		$rules['kargo_id']    	  = "trim|xss_clean|required";
		$rules['product_image']   = "trim|xss_clean";
		$rules['kargo_durum']     = "trim|xss_clean";
		//$rules['kargo_parca']     = "trim|xss_clean";
		$rules['kargo_ucret_tip'] = "trim|xss_clean";
		$rules['ucret_tip1']  	  = "trim|xss_clean|required";
		$rules['ucret_tip2'] 	  = "trim|xss_clean";
		$rules['ucret_tip3'] 	  = "trim|xss_clean";
		$rules['ucret_tip4']	  = "trim|xss_clean";
		$rules['ucret_tip5']	  = "trim|xss_clean";
		$rules['ucret_tip6']	  = "trim|xss_clean";
		$rules['ucret_tip7']	  = "trim|xss_clean";
		$rules['ucret_tip8']	  = "trim|xss_clean";
		
		$fields['kargo_adi']  	  = "Kargo Adı";
		$fields['kargo_id']  	  = "Kargo Id";
		$fields['product_image']  = "Kargo Logosu";
		$fields['kargo_durum']    = "Kargo Durum";
		//$fields['kargo_parca']    = "Parça Başına ";
		$fields['kargo_ucret_tip']= "Ücret Tipi";
		$fields['ucret_tip1']	  = "Sabti Ücret";
		$fields['ucret_tip2']	  = "Ücret Tipi 2";
		$fields['ucret_tip3']	  = "Ücret Tipi 3";
		$fields['ucret_tip4']	  = "Ücret Tipi 4";
		$fields['ucret_tip5']	  = "Ücret Tipi 5";
		$fields['ucret_tip6']	  = "Ücret Tipi 6";
		$fields['ucret_tip7']	  = "Ücret Tipi 7";
		$fields['ucret_tip8']	  = "Ücret Tipi 8";
		
		$val->set_fields($fields);
		$val->set_rules($rules);
		
		if ($val->run() == FALSE)
		{
			$data['kontrol_data'] = false;
			$this->load->view('yonetim/sistem/kargo_duzenle_view' , $data);
		} else {
			$kontrol_data=$this->kargo_model->kargo_duzenle($val);
			$data['kontrol_data'] = $kontrol_data;
			if ($kontrol_data==TRUE)
			{
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '1';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarılı: Kargo Bilgileri Güncellendi.');	
				$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
				redirect('yonetim/sistem/kargo');
			} else {
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '2';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarısız: Kargo Bilgileri Güncellenmedi.');	
				$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
				redirect('yonetim/sistem/kargo');
			}
		}
	}		

	function durum($kargo_id, $tip)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/kargo/durum/' . $kargo_id . '/' .  $tip);

		$this->kargo_model->kargo_durum($kargo_id, $tip);
		redirect($this->input->get('red'));
	}
	
	function sira($kargo_id, $tip)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/kargo/sira/' . $kargo_id . '/' .  $tip);

		if ($tip=='kargo_enust'){
			$this->kargo_model->kargo_enust($kargo_id);
		} else if ($tip=='kargo_enalt') {
			$this->kargo_model->kargo_enalt($kargo_id);
		}else if ($tip=='kargo_ust') {
			$this->kargo_model->kargo_ust($kargo_id);
		}else if ($tip=='kargo_alt'){
			$this->kargo_model->kargo_alt($kargo_id);
		}

		redirect($this->input->get('red'));
	}
	
	function sil()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/kargo/sil');

		$val = $this->validation;
		
		$rules['selected']					= "trim|xss_clean|required";
		$fields['selected']    				= "Kargo Başlığı";
		
		$val->set_fields($fields);
		$val->set_rules($rules);

		if ($val->run() == TRUE)
		{
			$kontrol = $this->kargo_model->kargo_sil($val);

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
			redirect('yonetim/sistem/kargo/listele');
		} else {
			$yonetim_mesaj 				= array();
			$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Herhangi Bir Kargo Seçilemediği İçin Kargo Silme İşlemi Tamamlanamadı.');
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/sistem/kargo/listele');
		}
	}
}