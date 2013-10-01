<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class sabit_tanimlar extends Admin_Controller {

	var $izin_linki;

	function __construct()
	{
		parent::__construct();
		$this->load->model('yonetim/sabittanimlar_model');

		$this->izin_linki = 'sistem/sabit_tanimlar';
	}

	function uzunluk_listele($kontrol='')
	{

		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/uzunluk_tanimlari/uzunluk_listele/' . $kontrol);

		$data = array();
		$tanim_tip='uzunluk';
		$data['tanim_tip']='uzunluk';
		$data['tanimlar_listele']=array();
		$data['kontrol_data']=FALSE;		
		if ($kontrol=='ok'){$data['kontrol_data']=TRUE;}
		
    	foreach ($this->sabittanimlar_model->listele($tanim_tip)->result() as $result)
    	{
			$action = array();
			
			$action[] = array(
				'text' => 'Düzenle',
				'href' => 'yonetim/sistem/uzunluk_tanimlari/duzenle/'.$result->tanimlar_id
			);
			
			$data['tanimlar_listele'][] = array(
				'tanimlar_id'  		=> $result->tanimlar_id,
				'tanimlar_adi' 		=> $result->tanimlar_adi,
				'selected'      	=> ($this->input->post('selected') && in_array($result->tanimlar_id, $this->input->post('selected'))),
				'action'        	=> $action
			);
		}
		
		$this->load->view('yonetim/sistem/sabit_tanimlar_view' , $data);
	}		

	function agirlik_listele($kontrol='')
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/uzunluk_tanimlari/agirlik_listele/' . $kontrol);

		$data = array();
		$tanim_tip='agirlik';
		$data['tanim_tip']='agirlik';
		$data['tanimlar_listele']=array();
		$data['kontrol_data']=FALSE;		
		if ($kontrol=='ok'){$data['kontrol_data']=TRUE;}
		
    	foreach ($this->sabittanimlar_model->listele($tanim_tip)->result() as $result)
    	{
			$action = array();
			
			$action[] = array(
				'text' => 'Düzenle',
				'href' => 'yonetim/sistem/agirlik_tanimlari/duzenle/'.$result->tanimlar_id
			);
			
			$data['tanimlar_listele'][] = array(
				'tanimlar_id'  		=> $result->tanimlar_id,
				'tanimlar_adi' 		=> $result->tanimlar_adi,
				'selected'      	=> ($this->input->post('selected') && in_array($result->tanimlar_id, $this->input->post('selected'))),
				'action'        	=> $action
			);
		}
		
		$this->load->view('yonetim/sistem/sabit_tanimlar_view' , $data);
	}		

	function stok_durum_listele($kontrol='')
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/uzunluk_tanimlari/stok_durum_listele/' . $kontrol);

		$data = array();
		$tanim_tip='stok_durumu';
		$data['tanim_tip']='stok_durumu';
		$data['tanimlar_listele']=array();
		$data['kontrol_data']=FALSE;		
		if ($kontrol=='ok'){$data['kontrol_data']=TRUE;}
		
    	foreach ($this->sabittanimlar_model->listele($tanim_tip)->result() as $result)
    	{
			$action = array();
			
			$action[] = array(
				'text' => 'Düzenle',
				'href' => 'yonetim/sistem/stok_tanimlari/duzenle/'.$result->tanimlar_id
			);
			
			$data['tanimlar_listele'][] = array(
				'tanimlar_id'  		=> $result->tanimlar_id,
				'tanimlar_adi' 		=> $result->tanimlar_adi,
				'selected'      	=> ($this->input->post('selected') && in_array($result->tanimlar_id, $this->input->post('selected'))),
				'action'        	=> $action
			);
		}
		
		$this->load->view('yonetim/sistem/sabit_tanimlar_view' , $data);
	}		

	function uzunluk_ekle()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/uzunluk_tanimlari/uzunluk_ekle');

		$val = $this->validation;		
		$rules['tanimlar_adi']    = "trim|xss_clean|required";
		$rules['tanim_tip']    	  = "trim|xss_clean|required";
		$fields['tanimlar_adi']   = "Adı";
		$fields['tanim_tip']   	  = "Tip";
		$val->set_fields($fields);
		$val->set_rules($rules);
		$data['tanim_tip'] = 'uzunluk';
		if ($val->run() == FALSE)
		{
			$data['kontrol_data'] = false;
			$this->load->view('yonetim/sistem/sabit_tanimlar_ekle_view' , $data);
		} else {
			$kontrol_data=$this->sabittanimlar_model->ekle($val);
			$data['kontrol_data'] = $kontrol_data;
			if ($kontrol_data==TRUE)
			{
				redirect('yonetim/sistem/uzunluk_tanimlari/ok');
			} else {
				$this->load->view('yonetim/sistem/sabit_tanimlar_ekle_view' , $data);
			}
		}
	}

	function uzunluk_duzenle($tanimlar_id)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/uzunluk_tanimlari/uzunluk_duzenle/' . $tanimlar_id);

		$data['sabittanimlar_veri']=$this->sabittanimlar_model->veri($tanimlar_id);
		$val = $this->validation;		
		$rules['tanimlar_adi']    = "trim|xss_clean|required";
		$rules['tanimlar_id']     = "trim|xss_clean|required";
		$fields['tanimlar_adi']   = "Adı";
		$fields['tanimlar_id']    = "Id";		
		$val->set_fields($fields);
		$val->set_rules($rules);
		$data['tanim_tip'] = 'uzunluk';
		if ($val->run() == FALSE)
		{
			$data['kontrol_data'] = false;
			$this->load->view('yonetim/sistem/sabit_tanimlar_duzenle_view' , $data);
		} else {
			$kontrol_data=$this->sabittanimlar_model->duzenle($val);
			$data['kontrol_data'] = $kontrol_data;
			if ($kontrol_data==TRUE)
			{
				redirect('yonetim/sistem/uzunluk_tanimlari/ok');
			} else {
				$this->load->view('yonetim/sistem/sabit_tanimlar_duzenle_view' , $data);
			}
		}
	}

	function uzunluk_sil()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/uzunluk_tanimlari/uzunluk_sil');

		$val = $this->validation;		
		$rules['selected']		= "trim|xss_clean|required";
		$fields['selected'] 	= "Başlık";		
		$val->set_fields($fields);
		$val->set_rules($rules);
		if ($val->run() == TRUE)
		{
			$kontrol_data = $this->sabittanimlar_model->sil($val);
			$data['kontrol_data'] = $kontrol_data;
			redirect('yonetim/sistem/uzunluk_tanimlari/ok');
		}
	}

	function agirlik_ekle()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/uzunluk_tanimlari/agirlik_ekle');

		$val = $this->validation;		
		$rules['tanimlar_adi']    = "trim|xss_clean|required";
		$rules['tanim_tip']    	  = "trim|xss_clean|required";
		$fields['tanimlar_adi']   = "Adı";
		$fields['tanim_tip']   	  = "Tip";
		$val->set_fields($fields);
		$val->set_rules($rules);
		$data['tanim_tip'] = 'agirlik';
		if ($val->run() == FALSE)
		{
			$data['kontrol_data'] = false;
			$this->load->view('yonetim/sistem/sabit_tanimlar_ekle_view' , $data);
		} else {
			$kontrol_data=$this->sabittanimlar_model->ekle($val);
			$data['kontrol_data'] = $kontrol_data;
			if ($kontrol_data==TRUE)
			{
				redirect('yonetim/sistem/agirlik_tanimlari/ok');
			} else {
				$this->load->view('yonetim/sistem/sabit_tanimlar_ekle_view' , $data);
			}
		}
	}

	function agirlik_duzenle($tanimlar_id)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/uzunluk_tanimlari/agirlik_duzenle/' . $tanimlar_id);

		$data['sabittanimlar_veri']=$this->sabittanimlar_model->veri($tanimlar_id);
		$val = $this->validation;		
		$rules['tanimlar_adi']    = "trim|xss_clean|required";
		$rules['tanimlar_id']     = "trim|xss_clean|required";
		$fields['tanimlar_adi']   = "Adı";
		$fields['tanimlar_id']    = "Id";		
		$val->set_fields($fields);
		$val->set_rules($rules);
		$data['tanim_tip'] = 'agirlik';
		if ($val->run() == FALSE)
		{
			$data['kontrol_data'] = false;
			$this->load->view('yonetim/sistem/sabit_tanimlar_duzenle_view' , $data);
		} else {
			$kontrol_data=$this->sabittanimlar_model->duzenle($val);
			$data['kontrol_data'] = $kontrol_data;
			if ($kontrol_data==TRUE)
			{
				redirect('yonetim/sistem/agirlik_tanimlari/ok');
			} else {
				$this->load->view('yonetim/sistem/sabit_tanimlar_duzenle_view' , $data);
			}
		}
	}

	function agirlik_sil()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/uzunluk_tanimlari/agirlik_sil');

		$val = $this->validation;		
		$rules['selected']		= "trim|xss_clean|required";
		$fields['selected'] 	= "Başlık";		
		$val->set_fields($fields);
		$val->set_rules($rules);
		if ($val->run() == TRUE)
		{
			$kontrol_data = $this->sabittanimlar_model->sil($val);
			$data['kontrol_data'] = $kontrol_data;
			redirect('yonetim/sistem/agirlik_tanimlari/ok');
		}
	}

	function stok_durum_ekle()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/uzunluk_tanimlari/stok_durum_ekle');

		$val = $this->validation;		
		$rules['tanimlar_adi']    = "trim|xss_clean|required";
		$rules['tanim_tip']    	  = "trim|xss_clean|required";
		$fields['tanimlar_adi']   = "Adı";
		$fields['tanim_tip']   	  = "Tip";
		$val->set_fields($fields);
		$val->set_rules($rules);
		$data['tanim_tip'] = 'stok_durumu';
		if ($val->run() == FALSE)
		{
			$data['kontrol_data'] = false;
			$this->load->view('yonetim/sistem/sabit_tanimlar_ekle_view' , $data);
		} else {
			$kontrol_data=$this->sabittanimlar_model->ekle($val);
			$data['kontrol_data'] = $kontrol_data;
			if ($kontrol_data==TRUE)
			{
				redirect('yonetim/sistem/stok_tanimlari/ok');
			} else {
				$this->load->view('yonetim/sistem/sabit_tanimlar_ekle_view' , $data);
			}
		}
	}

	function stok_durum_duzenle($tanimlar_id)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/uzunluk_tanimlari/stok_durum_duzenle/' . $tanimlar_id);

		$data['sabittanimlar_veri']=$this->sabittanimlar_model->veri($tanimlar_id);
		$val = $this->validation;		
		$rules['tanimlar_adi']    = "trim|xss_clean|required";
		$rules['tanimlar_id']     = "trim|xss_clean|required";
		$fields['tanimlar_adi']   = "Adı";
		$fields['tanimlar_id']    = "Id";		
		$val->set_fields($fields);
		$val->set_rules($rules);
		$data['tanim_tip'] = 'stok_durumu';
		if ($val->run() == FALSE)
		{
			$data['kontrol_data'] = false;
			$this->load->view('yonetim/sistem/sabit_tanimlar_duzenle_view' , $data);
		} else {
			$kontrol_data=$this->sabittanimlar_model->duzenle($val);
			$data['kontrol_data'] = $kontrol_data;
			if ($kontrol_data==TRUE)
			{
				redirect('yonetim/sistem/stok_tanimlari/ok');
			} else {
				$this->load->view('yonetim/sistem/sabit_tanimlar_duzenle_view' , $data);
			}
		}
	}

	function stok_durum_sil()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/uzunluk_tanimlari/stok_durum_sil');

		$val = $this->validation;		
		$rules['selected']		= "trim|xss_clean|required";
		$fields['selected'] 	= "Başlık";		
		$val->set_fields($fields);
		$val->set_rules($rules);
		if ($val->run() == TRUE)
		{
			$kontrol_data = $this->sabittanimlar_model->sil($val);
			$data['kontrol_data'] = $kontrol_data;
			redirect('yonetim/sistem/stok_tanimlari/ok');
		}
	}
}