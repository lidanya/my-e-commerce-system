<?php

if (!defined('BASEPATH')) {
	header('Location: http://' . getenv('SERVER_NAME') . '/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 * */
class site extends Public_Controller
{

	function __construct() {
		parent::__construct();
		log_message('debug', 'Site Controller Yüklendi');

		$this->load->library('encrypt');
		$this->load->library('menu');
		$this->load->model('site_model');
	}

	/**
	 * index function
	 *
	 * @return void
	 * */
	function index() {
		$this->template->set_master_template(tema() . 'index/index');
		$this->template->add_region('content');
		$this->template->write_view('content', tema() . 'index/content');
		$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/anasayfa.css');

		//SKOCH
		$this->template->add_css(APPPATH . 'views/' . tema() . 'css/jquery.countdown.css');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown.js');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown-tr.js');
		$this->output->enable_profiler(false);
		//SKOCH

		$this->template->render();
	}

	function page_not_found() {
		$data['baslik'] = lang('messages_page_not_found_title');

		$this->load->view(tema() . 'header', $data);
		$this->load->view(tema() . 'left');
		$this->load->view(tema() . 'index/page_not_found_content');
		$this->load->view(tema() . 'right');
		$this->load->view(tema() . 'footer');
	}

	function img_kontrol($neresi = 'uye_giris') {
		if ($neresi == 'uye_giris') {
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

		ob_start();
		$im = imagecreatefrompng(APPPATH . 'views/' . tema_asset() . 'images/' . 'face.png');
		//echo $im; return false;
		$color = imagecolorallocate($im, 255, 255, 255);
		$font = APPPATH . 'fonts/arialbi.ttf';
		$fontsize = 11;
		imagettftext($im, $fontsize, 0, 17, 20, $color, $font, $str);
		imagepng($im);
		imagedestroy($im);
		exit(header('Content-type: image/png'));
		ob_end_flush();
	}

	function mesaj() {
		$mesajlar = $this->session->flashdata('mesajlar');
		if ($mesajlar) {
			$this->template->set_master_template(tema() . 'mesajlar/index');

			$content_data = array();
			$content_data['baslik'] = $mesajlar['baslik'];
			$content_data['icerik'] = $mesajlar['icerik'];

			$this->template->add_region('content');
			$this->template->write_view('content', tema() . 'mesajlar/content', $content_data);
			$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/uyeislem.css');

			//SKOCH
			$this->template->add_css(APPPATH . 'views/' . tema() . 'css/jquery.countdown.css');
			$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown.js');
			$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown-tr.js');
			$this->output->enable_profiler(false);
			//SKOCH

			$this->output->enable_profiler(false);
			$this->template->render();
		} else {
			redirect('');
		}
	}

	/* Callback function */

	function username_check($username) {
		$result = $this->dx_auth->is_username_available($username);
		if (!$result) {
			$this->validation->set_message('username_check', 'Girilen E-Posta adresi kullanılıyor başka deneyiniz.');
		}

		return $result;
	}

	function email_check($email) {
		$result = $this->dx_auth->is_email_available($email);
		if (!$result) {
			$this->validation->set_message('email_check', 'Girilen E-Posta adresi kullanılıyor başka deneyiniz.');
		}

		return $result;
	}

	function captcha_check_iletisim($code) {
		$this->load->model('uye_model');
		$result = TRUE;
		if (!$this->uye_model->captcha_check($code, 'iletisim')) {
			$this->validation->set_message('captcha_check_iletisim', 'Girdiğiniz doğrulama kodu yanlıştır lütfen tekrar deneyiniz.');
			$result = FALSE;
		}

		return $result;
	}

	function iletisim() {
		$val = $this->validation;

		$rules['eposta'] = 'trim|required|valid_email|xss_clean';
		$rules['TxtAdSoyad'] = 'trim|required|xss_clean';
		$rules['ticket_konu'] = 'trim|required|xss_clean';
		$rules['ticket_mesaj'] = 'trim|required|xss_clean';
		$rules['captcha'] = 'trim|required|xss_clean|callback_captcha_check_iletisim';

		$fields['eposta'] = lang('messages_static_page_contact_form_email_text');
		$fields['TxtAdSoyad'] = lang('messages_static_page_contact_form_name_text');
		$fields['ticket_konu'] = lang('messages_static_page_contact_form_subject_text');
		$fields['ticket_mesaj'] = lang('messages_static_page_contact_form_message_text');
		$fields['captcha'] = lang('messages_static_page_contact_form_security_text');

		$val->set_rules($rules);
		$val->set_fields($fields);
		if ($val->run() == FALSE) {
			$this->template->add_region('baslik');
			$this->template->write('baslik', lang('messages_static_page_contact_title'));

			$this->template->set_master_template(tema() . 'iletisim/index');
			$content_data = array();
			$content_data['bayiler'] = $this->db->get('bayiler');
			$content_data['val'] = $val;
			$content_data['ide_inf'] = "";
			if ($this->dx_auth->is_logged_in()) {
				$content_data['ide_inf'] = get_usr_ide_inf($this->dx_auth->get_user_id());
			}

			$this->template->add_region('content');
			$this->template->write_view('content', tema() . 'iletisim/content', $content_data);
			$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/uyeislem.css');

			//SKOCH
			$this->template->add_css(APPPATH . 'views/' . tema() . 'css/jquery.countdown.css');
			$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown.js');
			$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown-tr.js');
			$this->output->enable_profiler(false);
			//SKOCH
			$this->output->enable_profiler(false);
			$this->template->render();
		} else {
			$this->session->unset_userdata('val');
			if (!$this->dx_auth->is_logged_in()) {
				if (!$this->email_check($val->eposta)) {
					$iletisim = array('email' => $val->eposta, 'adsoyad' => $val->TxtAdSoyad, 'konu' => $val->ticket_konu, 'mesaj' => $val->ticket_mesaj);
					$this->session->set_userdata('val', $iletisim);
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

	function musteri_hizmetleri() {
		$this->template->set_master_template(tema() . 'musteri_hizmetleri/index');
		$this->template->add_region('baslik');
		$this->template->write('baslik', lang('messages_static_page_customer_services_title'));
		$this->template->add_region('content');
		$this->template->write_view('content', tema() . 'musteri_hizmetleri/content');
		$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/uyeislem.css');
		$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/mushiz.css');

		//SKOCH
		$this->template->add_css(APPPATH . 'views/' . tema() . 'css/jquery.countdown.css');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown.js');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown-tr.js');
		$this->output->enable_profiler(false);
		//SKOCH
		$this->output->enable_profiler(false);
		$this->template->render();
	}

	function duyurular($seoname = null) {
		$this->load->model('site/information_model');

		if (!is_null($seoname)) {
			$information_info = $this->information_model->get_information_by_seo($seoname);
			if ($information_info) {
				$this->template->set_master_template(tema() . 'duyurular/index');
				$this->template->add_region('content');

				$content_data['duyuru'] = $information_info;
				$content_data['baslik'] = $information_info->title;
				$content_data['keywords'] = $information_info->meta_keywords;
				$content_data['description'] = $information_info->meta_description;

				$this->template->write_view('content', tema() . 'duyurular/content', $content_data);
				$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/uyeislem.css');
				$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/mushiz.css');

				//SKOCH
				$this->template->add_css(APPPATH . 'views/' . tema() . 'css/jquery.countdown.css');
				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown.js');
				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown-tr.js');
				$this->output->enable_profiler(false);
				//SKOCH

				$this->output->enable_profiler(false);
				$this->template->render();
			} else {
				redirect('');
			}
		} else {
			$type = 'announcement';
			$information_info = $this->information_model->get_information_by_type($type, '-1');
			if ($information_info) {
				$this->template->set_master_template(tema() . 'duyurular/index');
				$this->template->add_region('content');

				$content_data['duyuru'] = $information_info;
				$content_data['baslik'] = lang('messages_extension_announcement_title_all_announcement');
				$content_data['keywords'] = lang('messages_extension_announcement_title_meta_keywords');
				$content_data['description'] = lang('messages_extension_announcement_title_meta_description');

				$this->template->write_view('content', tema() . 'duyurular/tumu_content', $content_data);
				$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/uyeislem.css');
				$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/mushiz.css');

				//SKOCH
				$this->template->add_css(APPPATH . 'views/' . tema() . 'css/jquery.countdown.css');
				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown.js');
				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown-tr.js');
				$this->output->enable_profiler(false);
				//SKOCH

				$this->output->enable_profiler(false);
				$this->template->render();
			} else {
				redirect('');
			}
		}
	}

	function haberler($seoname = null) {
		$this->load->model('site/information_model');

		if (!is_null($seoname)) {
			$information_info = $this->information_model->get_information_by_seo($seoname);
			if ($information_info) {
				$this->template->set_master_template(tema() . 'haberler/index');
				$this->template->add_region('content');

				$content_data['haber'] = $information_info;
				$content_data['baslik'] = $information_info->title;
				$content_data['keywords'] = $information_info->meta_keywords;
				$content_data['description'] = $information_info->meta_description;

				$this->template->write_view('content', tema() . 'haberler/content', $content_data);
				$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/uyeislem.css');
				$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/mushiz.css');

				//SKOCH
				$this->template->add_css(APPPATH . 'views/' . tema() . 'css/jquery.countdown.css');
				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown.js');
				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown-tr.js');
				$this->output->enable_profiler(false);
				//SKOCH

				$this->output->enable_profiler(false);
				$this->template->render();
			} else {
				redirect('');
			}
		} else {
			$type = 'news';
			$information_info = $this->information_model->get_information_by_type($type, '-1');
			if ($information_info) {
				$this->template->set_master_template(tema() . 'haberler/index');
				$this->template->add_region('content');

				$content_data['haber'] = $information_info;
				$content_data['baslik'] = lang('messages_extension_news_title_all_news');
				$content_data['keywords'] = lang('messages_extension_news_title_meta_keywords');
				$content_data['description'] = lang('messages_extension_news_title_meta_description');

				$this->template->write_view('content', tema() . 'haberler/tumu_content', $content_data);
				$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/uyeislem.css');
				$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/mushiz.css');

				//SKOCH
				$this->template->add_css(APPPATH . 'views/' . tema() . 'css/jquery.countdown.css');
				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown.js');
				$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown-tr.js');
				$this->output->enable_profiler(false);
				//SKOCH

				$this->output->enable_profiler(false);
				$this->template->render();
			} else {
				redirect('');
			}
		}
	}

	function banka_bilgileri() {
		$this->template->set_master_template(tema() . 'bilgilendirme/banka_bilgileri_index');

		$this->template->add_region('baslik');
		$this->template->write('baslik', lang('messages_static_page_bank_information_title'));

		$this->template->add_region('content');
		$this->template->write_view('content', tema() . 'bilgilendirme/banka_bilgileri_content');
		$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/bilgi_sayfalari.css');

		//SKOCH
		$this->template->add_css(APPPATH . 'views/' . tema() . 'css/jquery.countdown.css');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown.js');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown-tr.js');
		$this->output->enable_profiler(false);
		//SKOCH

		$this->output->enable_profiler(false);
		$this->template->render();
	}

	function odeme_secenekleri() {
		$this->template->set_master_template(tema() . 'bilgilendirme/odeme_secenekleri_index');

		$this->template->add_region('content');
		$this->template->write_view('content', tema() . 'bilgilendirme/odeme_secenekleri_content');
		$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/bilgi_sayfalari.css');

		//SKOCH
		$this->template->add_css(APPPATH . 'views/' . tema() . 'css/jquery.countdown.css');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown.js');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown-tr.js');
		$this->output->enable_profiler(false);
		//SKOCH

		$this->template->add_region('baslik');
		$this->template->write('baslik', lang('messages_static_page_payment_options_title'));

		$this->output->enable_profiler(false);
		$this->template->render();
	}

	function bakim_modu() {
		if (config('site_ayar_bakim')) {
			$data['baslik'] = 'Sitemiz Yapım Aşamasındadır';
			$this->load->view(tema() . 'bakim_modu_view', $data);
		} else {
			redirect('');
		}
	}

}