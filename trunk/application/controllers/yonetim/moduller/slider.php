<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class slider extends Admin_Controller {

	var $izin_linki;

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Slider Controller Yüklendi');
		
		$this->load->model('yonetim/slider_model');
		$this->load->model('yonetim/genel_ayarlar_model');

		$this->izin_linki = 'moduller/slider';
	}

	function index()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/moduller/slider');
		redirect('yonetim/moduller/slider/listele');
	}

	function listele($sayfa = 0)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/moduller/slider/listele/' . $sayfa);

		$data = array();
		$data['sliderlar'] = array();
		foreach($this->slider_model->listele($sayfa)->result() as $result)
		{
			$action = array();
			$action[] = array(
				'text' => 'Düzenle',
				'href' => 'yonetim/moduller/slider/duzenle/' . $result->slider_id
			);
			$action[] = array(
				'text' => 'Sil',
				'href' => 'yonetim/moduller/slider/sil_t/' . $result->slider_id
			);

			$data['sliderlar'][] = array(
				'slider_id'			=> $result->slider_id,
				'slider_link'		=> $result->slider_link,
				'slider_img'		=> $result->slider_img,
				'slider_flag'		=> $result->slider_flag,
				'slider_sira'		=> $result->slider_sira,
				'selected'			=> ($this->input->post('selected') && in_array($result->slider_id, $this->input->post('selected'))),
				'action'			=> $action
			);
		}

		$this->load->view('yonetim/moduller/slider/slider_listele_view' , $data);
	}

	function sil()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/moduller/slider/sil');
		$this->output->enable_profiler(false);

		if (!empty($_POST['selected']))
		{
			$secim_sayisi = count($_POST['selected']);
			$cogul_ek	  = ($secim_sayisi > 1) ? 'ler':NULL;
			$kontrol_data = isset($_POST['selected']) ? $this->slider_model->sil($_POST['selected']):$this->slider_model->sil($slider_id);
			if($kontrol_data)
			{
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '1';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Seçilen Slider'. $cogul_ek .' Başarılı Bir Şekilde Silinmiştir.');
				$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
				redirect('yonetim/moduller/slider/listele');
			} else {
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '2';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Herhangi Bir Slider'. $cogul_ek .' Seçilmediği İçin Slider'. $cogul_ek .' Silinemedi.');
				$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
				redirect('yonetim/moduller/slider/listele');
			}
		} else {
			$yonetim_mesaj 				= array();
			$yonetim_mesaj['durum'] 	= '2';// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Herhangi Bir Slider Seçilmediği İçin Ürün Silinemedi.');
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/moduller/slider/listele');
		}
	}

	function sil_t($slider_id)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/moduller/slider/sil_t/' . $slider_id);
		$this->output->enable_profiler(false);

		$kontrol_data = $this->slider_model->sil($slider_id);
		if($kontrol_data)
		{
			$yonetim_mesaj 				= array();
			$yonetim_mesaj['durum'] 	= '1';// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Seçilen Slider Başarılı Bir Şekilde Silinmiştir.');
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/moduller/slider/listele');
		} else {
			$yonetim_mesaj 				= array();
			$yonetim_mesaj['durum'] 	= '2';// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Herhangi Bir Slider Seçilmediği İçin Slider Silinemedi.');
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/moduller/slider/listele');
		}
	}

	function duzenle($slider_id)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/moduller/slider/duzenle/' . $slider_id);

		$slider_kontrol = $this->slider_model->slider_kontrol($slider_id);
		if(!$slider_kontrol)
		{
			$yonetim_mesaj 				= array();
			$yonetim_mesaj['durum'] 	= '2';// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Düzenlemek İstediğiniz Slider Bulunamadı.');
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/moduller/slider/listele');
		}

		$val = $this->validation;
		
		$data['slider_veri']		= $slider_kontrol;

		$rules['slider_id']			= "trim|xss_clean|required|numeric";
		$rules['image']    			= "trim|xss_clean|required";
		$rules['slider_link']    	= "trim|xss_clean";
		$rules['slider_sira']    	= "trim|numeric|xss_clean";
		$rules['slider_flag']		= "trim|numeric|xss_clean|required";

		$fields['slider_id']		= "Slider No";
		$fields['slider_flag']		= "Slider Durum";
		$fields['image']    		= "Slider Resim";
		$fields['slider_link'] 	   	= "Slider Link";
		$fields['slider_sira']		= "Slider Sırası";

		$val->set_fields($fields);
		$val->set_rules($rules);

		if ($val->run() == FALSE)
		{
			$this->load->view('yonetim/moduller/slider/slider_duzenle_view' , $data);
		} else {
			$kontrol_data = $this->slider_model->duzenle($val);
			if ($kontrol_data)
			{
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '1';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Slider Başarılı Bir Şekilde Düzenlenmiştir.');
				$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
				redirect('yonetim/moduller/slider/listele');
			} else {
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '1';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Slider Düzenlemede Hata Oluştu.');
				$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
				redirect('yonetim/moduller/slider/duzenle/' . $slider_id);
			}
		}
	}

	function ekle()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/moduller/slider/ekle');

		$val = $this->validation;

		$rules['image']    			= "trim|xss_clean|required";
		$rules['slider_link']    	= "trim|xss_clean";
		$rules['slider_sira']    	= "trim|numeric|xss_clean";

		$fields['image']    		= "Slider Resim";
		$fields['slider_link'] 	   	= "Slider Link";
		$fields['slider_sira']		= "Slider Sırası";

		$val->set_fields($fields);
		$val->set_rules($rules);

		if ($val->run() == FALSE)
		{
			$this->load->view('yonetim/moduller/slider/slider_ekle_view');
		} else {
			$kontrol_data = $this->slider_model->ekle($val);
			if ($kontrol_data)
			{
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '1';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Slider Başarılı Bir Şekilde Eklenmiştir.');
				$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
				redirect('yonetim/moduller/slider/listele');
			} else {
				redirect('yonetim/moduller/slider/ekle');
			}
		}
	}
	
}
?>