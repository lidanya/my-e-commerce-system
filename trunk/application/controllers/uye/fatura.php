<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class fatura extends Public_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('fatura_model','faturaModel');
	}
	
	function index()
	{
		$this->template->set_master_template(tema() . 'uye/fatura/index');
		$this->template->add_region('baslik');
		$this->template->write('baslik', lang('messages_member_billing_title'));
		$this->template->add_region('content');
		$content_data['faturalarim'] = $this->faturaModel->fatura_getir($this->dx_auth->get_user_id());
		$this->template->write_view('content', tema() . 'uye/fatura/content',$content_data);
		$this->template->add_css(APPPATH. 'views/' . tema_asset() . 'css/uyeislem.css');
			//SKOCH
		$this->template->add_css(APPPATH . 'views/' . tema() . 'css/jquery.countdown.css');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown.js');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown-tr.js');
	    $this->output->enable_profiler(false);
		//SKOCH
		
		$this->template->render();
	}

	function goruntule($inv_id = 0)
	{
		if( is_numeric($inv_id) )
		{
			$this->db->where('inv_id',$inv_id);
			$fatura = $this->db->get('usr_inv_inf');
			$this->template->set_master_template(tema() . 'uye/fatura/goruntule');
			$this->template->add_region('baslik');
			$this->template->write('baslik', lang('messages_member_billing_detail_title'));
			$this->template->add_region('content');
			$content_data['fatura'] = $fatura;
			$this->template->write_view('content', tema() . 'uye/fatura/goruntule_content',$content_data);
			$this->template->add_css(APPPATH. 'views/' . tema_asset() . 'css/uyeislem.css');
				//SKOCH
		$this->template->add_css(APPPATH . 'views/' . tema() . 'css/jquery.countdown.css');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown.js');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown-tr.js');
	    $this->output->enable_profiler(false);
		//SKOCH
			
			$this->template->render();
		} else {
			redirect('uye/fatura');
		}
	}
	
	/*function duzenle($inv_id = 0)
	{
		$val = $this->validation;
		$rules['fatura_adi'] 	= 'trim|required|xss_clean'; // Fatura NO
		
		if( $this->input->post('firmaadi') == '' )
		{
			$rules['adi'] 			= 'trim|required|xss_clean'; // Adı
			$rules['soyad']			= 'trim|required|xss_clean'; // Soyadı
			$rules['tckimlik']		= "trim|numeric|required|min_length[11]|max_length[11]|xss_clean"; // Tc Kimlik No
		} else if( $this->input->post('firmaadi') != '' ) {
			$rules['adi'] 			= 'trim|xss_clean'; // Adı
			$rules['soyad']			= 'trim|xss_clean'; // Soyadı
			$rules['tckimlik']		= "trim|numeric|min_length[11]|max_length[11]|xss_clean"; // Tc Kimlik No
		}
		
		if( $this->input->post('adi') == '' || $this->input->post('soyad') == '' )
		{
			$rules['firmaadi']		= 'trim|required|xss_clean'; // Firma Adı	
			$rules['vergid']		= 'trim|required|xss_clean'; // Veri Dairesi
			$rules['vergin']		= 'trim|required|xss_clean'; // Veri Numarası
		} else if(  $this->input->post('adi') != '' || $this->input->post('soyad') != ''  ) {
			$rules['firmaadi']		= 'trim|xss_clean'; // Firma Adı	
			$rules['vergid']		= 'trim|xss_clean'; // Veri Dairesi
			$rules['vergin']		= 'trim|xss_clean'; // Veri Numarası			
		}

		$rules['adres']				= 'trim|required|xss_clean'; // Adres
		$rules['ulke']				= "trim|required|xss_clean"; // Ulke
		$rules['sehir']				= "trim|required|xss_clean"; // Şehir
		$rules['ilce']				= 'trim|xss_clean'; 		// İlçe
		$rules['postak']			= 'trim|required|numeric|min_length[5]|max_length[5]|xss_clean'; // Posta Kodu
		$rules['tel']				= 'trim|required|xss_clean'; // Telefon
		$rules['fax']				= 'trim|min_length[11]|xss_clean'; // Fax

		
		$fields['fatura_adi'] 		= 'Fatura Adı';
		$fields['adi'] 				= 'Adınız';
		$fields['soyad'] 			= 'Soyadınız';
		$fields['tckimlik']			= 'Tc Kimlik No';
		$fields['firmaadi'] 		= 'Firmanızın Adınız';
		$fields['adres'] 			= 'Adresiniz';
		$fields['ulke'] 			= 'Ülke';
		$fields['sehir'] 			= 'Şehir';
		$fields['ilce'] 			= 'İlçe';
		$fields['postak'] 			= 'Posta Kodu';
		$fields['tel'] 				= 'Telefon';
		$fields['fax'] 				= 'Faks';
		$fields['vergid']			= 'Vergi Dairesi';
		$fields['vergin']			= 'Vergi Numarası';
	
		$val->set_rules($rules);
		$val->set_fields($fields);
		if($val->run() == FALSE)
		{
			if(!is_numeric($inv_id)){ redirect('uye/fatura'); }
			
			$this->template->set_master_template(tema() . 'uye/fatura/duzelt');
			$this->template->add_region('content');
			$content_data['val'] = $val;
			$content_data['fatura'] = $this->faturaModel->fatura_duzenle($inv_id);
			$this->template->write_view('content', tema() . 'uye/fatura/duzelt_content', $content_data);
			$this->template->add_css(APPPATH. 'views/' . tema_asset() . 'css/uyeislem.css');
			$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.maskedinput-1.2.2.min.js');
			// $this->output->enable_profiler(FALSE);
			$this->template->render();
		} else {
			$result = $this->faturaModel->fatura_duzelt($val,$inv_id);
			if($result)
			{
				$mesajlar['baslik'] = 'Yeni Fatura Bilginiz Başarıyla Güncellendi.';
				$mesajlar['icerik'] = 'Faturalarınızı <a href="uye/fatura">fatura bilgilerinden</a> yönetebilirsiniz.';
				$this->session->set_flashdata('mesajlar', $mesajlar);
				redirect('site/mesaj?tip=1&gd=false');
			} else {
				$mesajlar['baslik'] = 'Fatura Bilgisi Güncellenemiyor.';
				$mesajlar['icerik'] = 'Daha fazla bilgi almak için <a href="site/iletisim">buradan</a> yadrım alabilirsiniz.';
				$this->session->set_flashdata('mesajlar', $mesajlar);
				redirect('site/mesaj?tip=2&gd=false');				
			}
		}
	}*/

	/*
	function ekle()
	{
		$val = $this->validation;
		$rules['fatura_adi'] 	= 'trim|required|xss_clean'; // Fatura NO
		
		if( $this->input->post('firmaadi') == '' )
		{
			$rules['adi'] 			= 'trim|required|xss_clean'; // Adı
			$rules['soyad']			= 'trim|required|xss_clean'; // Soyadı
			$rules['tckimlik']		= "trim|numeric|required|min_length[11]|max_length[11]|xss_clean"; // Tc Kimlik No
		} else if( $this->input->post('firmaadi') != '' ) {
			$rules['adi'] 			= 'trim|xss_clean'; // Adı
			$rules['soyad']			= 'trim|xss_clean'; // Soyadı
			$rules['tckimlik']		= "trim|numeric|min_length[11]|max_length[11]|xss_clean"; // Tc Kimlik No
		}
		
		if( $this->input->post('adi') == '' || $this->input->post('soyad') == '' )
		{
			$rules['firmaadi']		= 'trim|required|xss_clean'; // Firma Adı	
			$rules['vergid']		= 'trim|required|xss_clean'; // Veri Dairesi
			$rules['vergin']		= 'trim|required|xss_clean'; // Veri Numarası
		} else if(  $this->input->post('adi') != '' || $this->input->post('soyad') != ''  ) {
			$rules['firmaadi']		= 'trim|xss_clean'; // Firma Adı	
			$rules['vergid']		= 'trim|xss_clean'; // Veri Dairesi
			$rules['vergin']		= 'trim|xss_clean'; // Veri Numarası			
		}

		$rules['adres']				= 'trim|required|xss_clean'; // Adres
		$rules['ulke']				= "trim|required|xss_clean"; // Ulke
		$rules['sehir']				= "trim|required|xss_clean"; // Şehir
		$rules['ilce']				= 'trim|xss_clean'; 		// İlçe
		$rules['postak']			= 'trim|required|numeric|min_length[5]|max_length[5]|xss_clean'; // Posta Kodu
		$rules['tel']				= 'trim|required|xss_clean'; // Telefon
		$rules['fax']				= 'trim|min_length[11]|xss_clean'; // Fax

		
		$fields['fatura_adi'] 		= 'Fatura Adı';
		$fields['adi'] 				= 'Adınız';
		$fields['soyad'] 			= 'Soyadınız';
		$fields['tckimlik']			= 'Tc Kimlik No';
		$fields['firmaadi'] 		= 'Firmanızın Adınız';
		$fields['adres'] 			= 'Adresiniz';
		$fields['ulke'] 			= 'Ülke';
		$fields['sehir'] 			= 'Şehir';
		$fields['ilce'] 			= 'İlçe';
		$fields['postak'] 			= 'Posta Kodu';
		$fields['tel'] 				= 'Telefon';
		$fields['fax'] 				= 'Faks';
		$fields['vergid']			= 'Vergi Dairesi';
		$fields['vergin']			= 'Vergi Numarası';
	
		$val->set_rules($rules);
		$val->set_fields($fields);
		if($val->run() == FALSE)
		{
			$this->template->set_master_template(tema() . 'uye/fatura/ekle');
			$this->template->add_region('content');
			$content_data['val'] = $val;
			$this->template->write_view('content', tema() . 'uye/fatura/ekle_content', $content_data);
			$this->template->add_css(APPPATH. 'views/' . tema_asset() . 'css/uyeislem.css');
			$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.maskedinput-1.2.2.min.js');
			// $this->output->enable_profiler(FALSE);
			$this->template->render();
		} else {
			$result = $this->faturaModel->fatura_ekle($val);
			if($result)
			{
				$mesajlar['baslik'] = 'Yeni Fatura Bilginiz Başarıyla Eklendi.';
				$mesajlar['icerik'] = 'Artık satın alma işlemlerinde bu faturayı kullanabilirsiniz. İşlem görmeden önce faturanızı dilediğiniz gibi düzenleyip silebilirsiniz.Faturalarınızı <a href="uye/fatura">fatura bilgilerinden</a> yönetebilirsiniz.';
				$this->session->set_flashdata('mesajlar', $mesajlar);
				redirect('site/mesaj?tip=1&gd=false');
			} else {
				$mesajlar['baslik'] = 'Fatura Bilgisi Eklenemiyor.';
				$mesajlar['icerik'] = 'Daha fazla bilgi almak için <a href="site/iletisim">buradan</a> yadrım alabilirsiniz.';
				$this->session->set_flashdata('mesajlar', $mesajlar);
				redirect('site/mesaj?tip=2&gd=false');									
			}
		}
	}

	function varsayilan($inv_id = 0)
	{
		if( is_numeric($inv_id) )
		{
			$this->db->where('inv_id',$inv_id);
			$sonuc = $this->db->get('usr_inv_inf',1)->row();
			
			if($sonuc->inv_flag == 3)
			{
				$this->db->update('usr_inv_inf',array('inv_flag'=>'1'));

				// Durumu Varsayılan Yapma
				$this->db->where('inv_id',$inv_id);
				$this->db->update('usr_inv_inf',array('inv_flag'=>'4'));

			} else {
				
				$this->db->where_not_in('inv_flag',array('4','3'));
				$this->db->update('usr_inv_inf',array('inv_flag'=>'1'));
				
				$this->db->where('inv_flag',4);
				$this->db->update('usr_inv_inf',array('inv_flag'=>'3'));
				
				$this->db->where('inv_id',$inv_id);
				$this->db->update('usr_inv_inf',array('inv_flag'=>'2'));				
			}
			
		}
		redirect('uye/fatura');
	}

	function sil($inv_id = 0)
	{
		if( is_numeric($inv_id) )
		{
			$this->db->delete('usr_inv_inf',array('inv_id'=>$inv_id, 'inv_flag'=>1));
		}
		redirect('uye/fatura');
	}
	*/

	function ajax_bolge_getir()
	{
		$val = $this->validation;
		$rules['ulke_id']	= 'trim|required|xss_clean';
		$fields['ulke_id']	= 'Ükle ID leri';
		
		$val->set_rules($rules);
		$val->set_fields($fields);

		if($val->run() == TRUE)
		{
			$bolgeler = $this->faturaModel->bolge_getir($val);
			$data['bolge']	= $bolgeler;
			$data['onay']	= true;
		}else{
			$data['onay'] = false;
		}
		exit(json_encode($data));
	}

}
?>