<?php 

if (! defined('BASEPATH')) exit('No direct script access');

class site extends Face_Controller 
{

	function __construct() 
	{
		parent::__construct();
	}

	function index() 
	{
		$this->template->set_master_template(face_tema() . 'index/index');
		$this->template->add_region('content');
		$this->template->write_view('content', face_tema() . 'index/content');
		$this->template->add_css(APPPATH . 'views/' . face_tema_asset() . 'css/anasayfa.css');

		$this->template->render();
	}

	function page_not_found()
	{
		$data['baslik'] = lang('messages_page_not_found_title');

		$this->load->view(face_tema() . 'header', $data);
		//$this->load->view(tema() . 'left');
		$this->load->view(face_tema() . 'index/page_not_found_content');
		//$this->load->view(tema() . 'right');
		$this->load->view(face_tema() . 'footer');
	}

	function img_kontrol($neresi = 'uye_giris')
	{
		if($neresi == 'uye_giris')
		{
			$random_key = $this->site_model->random_key('6'); // Kaç haneli Oluşturacağı
			$key = $random_key;
			$this->session->set_userdata('uye_giris', $key);
			$str = $key;
		} elseif ($neresi == 'uye_kayit') {
			$random_key = $this->site_model->random_key('6'); // Kaç haneli Oluşturacağı
			$key = $random_key;
			$this->session->set_userdata('uye_kayit', $key);
			$str = $key;
		} elseif ($neresi == 'urun_yorum_yaz') {
			$random_key = $this->site_model->random_key('6'); // Kaç haneli Oluşturacağı
			$key = $random_key;
			$this->session->set_userdata('urun_yorum_yaz', $key);
			$str = $key;
		} elseif ($neresi == 'iletisim') {
			$random_key = $this->site_model->random_key('6'); // Kaç haneli Oluşturacağı
			$key = $random_key;
			$this->session->set_userdata('iletisim', $key);
			$str = $key;
		} elseif ($neresi == 'sifre_hatirlat') {
			$random_key = $this->site_model->random_key('6'); // Kaç haneli Oluşturacağı
			$key = $random_key;
			$this->session->set_userdata('sifre_hatirlat', $key);
			$str = $key;
		}
		
		$im = imagecreatefrompng(APPPATH . 'views/' . face_tema_asset() .  'images/' . 'chapta.png');
		$color = imagecolorallocate($im, 255, 255, 255);
		$font = APPPATH . 'fonts/arialbi.ttf';
		$fontsize = 11;
		imagettftext($im, $fontsize, 0, 17, 20, $color, $font, $str);
		imagepng($im);
		imagedestroy($im);
		exit(header('Content-type: image/png'));
	}

	function mesaj()
	{
		$mesajlar = $this->session->flashdata('mesajlar');
		if($mesajlar)
		{
			$this->template->set_master_template(face_tema() . 'mesajlar/index');

			$content_data = array();
			$content_data['baslik'] = $mesajlar['baslik'];
			$content_data['icerik']	= $mesajlar['icerik'];

			$this->template->add_region('content');
			$this->template->write_view('content', face_tema() . 'mesajlar/content', $content_data);
			$this->template->add_css(APPPATH . 'views/' . face_tema_asset() . 'css/uyeislem.css');

			$this->output->enable_profiler(false);
			$this->template->render();
		} else {
			redirect('');
		}
	}

	/* Callback function */
	function username_check($username)
	{
		$result = $this->dx_auth->is_username_available($username);
		if ( ! $result)
		{
			$this->validation->set_message('username_check', 'Girilen E-Posta adresi kullanılıyor başka deneyiniz.');
		}
				
		return $result;
	}

	function email_check($email)
	{
		$result = $this->dx_auth->is_email_available($email);
		if ( ! $result)
		{
			$this->validation->set_message('email_check', 'Girilen E-Posta adresi kullanılıyor başka deneyiniz.');
		}
				
		return $result;
	}

	function captcha_check_iletisim($code)
	{
		$this->load->model('uye_model');
		$result = TRUE;
		if ( ! $this->uye_model->captcha_check($code, 'iletisim') )
		{
			$this->validation->set_message('captcha_check_iletisim', 'Girdiğiniz doğrulama kodu yanlıştır lütfen tekrar deneyiniz.');
			$result = FALSE;
		}

		return $result;
	}

	function iletisim()
	{
		$val = $this->validation;
		
		$rules['eposta']		= 'trim|required|valid_email|xss_clean';
		$rules['TxtAdSoyad']	= 'trim|required|xss_clean';
		$rules['ticket_konu']	= 'trim|required|xss_clean';
		$rules['ticket_mesaj']	= 'trim|required|xss_clean';
		$rules['captcha']		= 'trim|required|xss_clean|callback_captcha_check_iletisim';
		
		$fields['eposta']		= lang('messages_static_page_contact_form_email_text');
		$fields['TxtAdSoyad']	= lang('messages_static_page_contact_form_name_text');
		$fields['ticket_konu']	= lang('messages_static_page_contact_form_subject_text');
		$fields['ticket_mesaj']	= lang('messages_static_page_contact_form_message_text');
		$fields['captcha']		= lang('messages_static_page_contact_form_security_text');;
		
		$val->set_rules($rules);
		$val->set_fields($fields);
		if($val->run() == FALSE)
		{
			$this->template->add_region('baslik');
			$this->template->write('baslik', lang('messages_static_page_contact_title'));

			$this->template->set_master_template(face_tema() . 'iletisim/index');
			$content_data = array();
			$content_data['bayiler'] = $this->db->get('bayiler');
			$content_data['val'] = $val;
			$content_data['ide_inf'] = "";
			if($this->dx_auth->is_logged_in())
			{
				$content_data['ide_inf'] =  get_usr_ide_inf($this->dx_auth->get_user_id());
			}

			$this->template->add_region('content');
			$this->template->write_view('content', face_tema() . 'iletisim/content', $content_data);
			$this->template->add_css(APPPATH . 'views/' . face_tema_asset() . 'css/uyeislem.css');
			$this->template->render();
		} else {
			$this->session->unset_userdata('val');
			if(! $this->dx_auth->is_logged_in())
			{
				if(!$this->email_check($val->eposta))
				{
					$iletisim = array('email'=>$val->eposta, 'adsoyad' => $val->TxtAdSoyad, 'konu' => $val->ticket_konu, 'mesaj' => $val->ticket_mesaj);
					$this->session->set_userdata('val',$iletisim);
					redirect('uye/giris');
				} else {
					$this->site_model->ticket_ekle();
					$mesajlar['baslik'] = strtr(lang('messages_static_page_contact_form_success_title'), array('{name}' => $val->TxtAdSoyad));
					$mesajlar['icerik'] = lang('messages_static_page_contact_form_success_message');
					$this->session->set_flashdata('mesajlar', $mesajlar);
					redirect('site/mesaj?tip=1&gd=false');
				}
			} else {
				$this->site_model->ticket_ekle();
				$mesajlar['baslik'] = strtr(lang('messages_static_page_contact_form_success_title'), array('{name}' => $val->TxtAdSoyad));
				$mesajlar['icerik'] = lang('messages_static_page_contact_form_success_message');
				$this->session->set_flashdata('mesajlar', $mesajlar);
				redirect('site/mesaj?tip=1&gd=false');
			}
		}
	}

	function error() 
	{
		$this->template->set_master_template(face_tema() . 'index/error_index');
		$this->template->add_region('content');
		$this->template->write_view('content', face_tema() . 'index/error_content');
		$this->template->add_css(APPPATH . 'views/' . face_tema_asset() . 'css/anasayfa.css');

		$this->template->render();
	}

	function musteri_hizmetleri()
	{
		$this->template->set_master_template(face_tema() . 'musteri_hizmetleri/index');
		$this->template->add_region('content');
		$this->template->write_view('content', face_tema() . 'musteri_hizmetleri/content');
		$this->template->add_css(APPPATH . 'views/' . face_tema_asset() . 'css/uyeislem.css');
		$this->template->add_css(APPPATH . 'views/' . face_tema_asset() . 'css/mushiz.css');
		$this->template->render();
	}

	public function banka_bilgileri()
	{
		$this->template->set_master_template(face_tema() . 'bilgilendirme/banka_bilgileri_index');

		$this->template->add_region('baslik');
		$this->template->write('baslik', lang('messages_static_page_bank_information_title'));

		$this->template->add_region('content');
		$this->template->write_view('content', face_tema() . 'bilgilendirme/banka_bilgileri_content');
		$this->template->add_css(APPPATH . 'views/' . face_tema_asset() . 'css/bilgi_sayfalari.css');

		$this->template->render();
	}

	function odeme_secenekleri()
	{
		$this->template->set_master_template(face_tema() . 'bilgilendirme/odeme_secenekleri_index');

		$this->template->add_region('content');
		$this->template->write_view('content', face_tema() . 'bilgilendirme/odeme_secenekleri_content');
		$this->template->add_css(APPPATH . 'views/' . face_tema_asset() . 'css/bilgi_sayfalari.css');

		$this->template->add_region('baslik');
		$this->template->write('baslik', lang('messages_static_page_payment_options_title'));

		$this->template->render();
	}

	function bakim_modu()
	{
		if(config('site_ayar_bakim'))
		{
			$data['baslik'] = 'Sitemiz Yapım Aşamasındadır';
			$this->load->view(face_tema() . 'bakim_modu_view',$data);
		} else {
			redirect(face_site_url('site/index'));
		}
	}

}

/* End of file site.php */
/* Location: ./application/controllers/site.php */