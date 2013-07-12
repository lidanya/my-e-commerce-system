<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class detay extends Face_Controller {

	/**
	 * Ürün Detay construct
	 *
	 * @return void
	 * @author Serkan Koch, Serkan Koch -> E-Ticaret Sistemim
	 **/

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Ürün Detay Controller Yüklendi');

		$this->load->model('site/product_model');
		$this->load->model('site/review_model');
		$this->load->library('form_validation');
	}

	function index($product_seo)
	{
		$this->load->library('facebook_lib');
		if ( ! $this->facebook_lib->user) {
			$this->dx_auth->logout();
			$this->session->set_userdata('face_redirect', $product_seo . '--product');
			redirect(face_site_url('uye/giris/facebook'));
		}
		$product_info = $this->product_model->get_product_by_seo($product_seo);
		if(!$product_info)
		{
			redirect(face_site_url('site/index'));
		}

		//exit(var_dump($this->product_model->get_new_product_option($product_info->product_id)));

		$content_data['product_info'] = $product_info;
		$content_data['product_feature'] = $this->product_model->get_feature_by_id($product_info->product_id);
		$content_data['product_review'] = $this->review_model->get_review_by_id($product_info->product_id);
		$content_data['product_review_avg'] = $this->review_model->get_average_rating_by_id($product_info->product_id);
		$content_data['product_images'] = $this->product_model->get_images_by_id($product_info->product_id, 2);
		$content_data['product_images_all'] = $this->product_model->get_images_by_id($product_info->product_id, '-1');
		$content_data['product_related'] = $this->product_model->get_related_by_id($product_info->product_id);
		$content_data['product_option'] = $this->product_model->get_product_option($product_info->product_id);

		$this->product_model->update_view_by_id($product_info->product_id);

		/* Sayfa Tanımlamaları */
		$this->template->add_region('baslik');
		$this->template->add_region('keywords');
		$this->template->add_region('description');
		$this->template->write('baslik', $product_info->name);
		$this->template->write('keywords', $product_info->meta_keywords);
		$this->template->write('description', $product_info->meta_description);

		$this->template->set_master_template(face_tema() . 'urun/index');

		$this->template->add_region('content');
		$this->template->write_view('content', face_tema() . 'urun/content', $content_data);
		$this->template->add_css(APPPATH . 'views/' . face_tema_asset() . 'css/urundetay.css');
		$this->template->add_css(APPPATH . 'views/' . face_tema_asset() . 'css/jqzoom.css');
		$this->template->add_css(APPPATH . 'views/' . face_tema_asset() . 'css/jquery.lightbox-0.5.css');
		$this->template->add_js(APPPATH . 'views/' . face_tema() . 'js/jqzoom.pack.1.0.1.js');
		$this->template->add_js(APPPATH . 'views/' . face_tema() . 'js/tab.js');
		$this->template->add_js(APPPATH . 'views/' . face_tema() . 'js/jquery.lightbox-0.5.js');
		$this->template->add_js(APPPATH . 'views/' . face_tema() . 'js/jsocial.js');

		$this->template->render();
	}

	function yorum_ekle()
	{
		$val = $this->form_validation;

		$val->set_rules('review_product_id', 'lang:messages_product_detail_comments_form_product_number', 'trim|required|numeric|xss_clean');
		$val->set_rules('review_user_id', 'lang:messages_product_detail_comments_form_user_number', 'trim|required|numeric|xss_clean');
		$val->set_rules('review_author', 'lang:messages_product_detail_comments_form_name', 'trim|required|xss_clean');
		$val->set_rules('review_email', 'lang:messages_product_detail_comments_form_email', 'trim|required|valid_email|xss_clean');
		$val->set_rules('review_text', 'lang:messages_product_detail_comments_form_comment', 'trim|required|xss_clean');
		$val->set_rules('review_rating', 'lang:messages_product_detail_comments_form_rate', 'trim|required|numeric|xss_clean');
		$val->set_rules('review_security_code', 'lang:messages_product_detail_comments_form_security_code', 'trim|required|callback_urun_yorum_yaz_guvenlik_kodu_kontrol|xss_clean');

		$sonuc = NULL;
		$val->set_error_delimiters('', '<br>');

		if ($val->run() == FALSE) {
			if (validation_errors()) {
				$sonuc['basarisiz'] = validation_errors();
			}
		} else {
			$this->load->model('site/review_model');
			$kontrol = $this->review_model->add_review($this->input->post());
			if ($kontrol) {
				$sonuc['basarili'] = lang('messages_product_detail_comments_success_message');
			} else {
				$sonuc['basarisiz'] = lang('messages_product_detail_comments_error_message');
			}
		}

		$this->output->set_output(json::encode($sonuc));
	}

	function urun_yorum_yaz_guvenlik_kodu_kontrol($code)
	{
		$val = $this->form_validation;
		$result = TRUE;
		$this->load->model('site/review_model');
		if ( ! $this->review_model->check_review_security_code($code) ) {
			$val->set_message('urun_yorum_yaz_guvenlik_kodu_kontrol', lang('messages_product_detail_comments_error_security_code_message'));			
			$result = FALSE;
		}

		return $result;
	}

	function takip_durum()
	{
		$val = $this->form_validation;

		$val->set_rules('follow_product_id', 'lang:messages_product_detail_follow_product_number', 'trim|required|xss_clean');
		$val->set_rules('follow_user_id', 'lang:messages_product_detail_follow_user_number', 'trim|required|xss_clean');
		$val->set_rules('follow_status', 'lang:messages_product_detail_follow_status', 'trim|required|xss_clean');

		$sonuc = NULL;
		$val->set_error_delimiters('', '<br>');

		if ($val->run() == FALSE) {
			if (validation_errors()) {
				$sonuc['basarisiz'] = validation_errors();
			}
		} else {
			$this->load->model('product_model');
			$kontrol = $this->product_model->follow_status($this->input->post());

			if(set_value('follow_status') == '0')
			{
				$basarili = lang('messages_product_detail_follow_remove_success');
				$basarisiz = lang('messages_product_detail_follow_remove_error');
			} elseif(set_value('follow_status') == '1') {
				$basarili = lang('messages_product_detail_follow_add_success');
				$basarisiz = lang('messages_product_detail_follow_add_error');
			} else {
				$basarili = lang('messages_product_detail_follow_an_error');
				$basarisiz = lang('messages_product_detail_follow_an_error');
			}

			if ($kontrol) {
				$sonuc['basarili'] = $basarili;
			} else {
				$sonuc['basarisiz'] = $basarisiz;
			}
		}

		$this->output->set_output(json::encode($sonuc));
	}

	public function upload()
	{
		$this->delete_files();
		$json = array();
		if(isset($_FILES['file'])) {
			$directory = DIR_DOWNLOAD . 'temp/';

			if (!is_dir($directory)) {
				$json['error'] = 'Uyarı: Lütfen dizin seçiniz!';
			}

			$durum = $this->resim_yukle('file', $directory);
			if(!$durum['durum']) {
				$json['error'] = 'Uyarı: ' . strtr($durum['error'], array('<p>' => '', '</p>' => ''));
			} else {
				$json['file'] = $durum['upload_data']['file_name'];
				$json['success'] = 'Başarılı: Dosyanız yüklendi!';
			}
		} else {
			$json['error'] = 'Uyarı: Lütfen dosya seçiniz!';
		}

		$this->output->set_output(json::encode($json));
	}

	public function resim_yukle($key = 'upload', $kok_dizin)
	{
		$config['upload_path'] = $kok_dizin;
		$config['encrypt_name'] = TRUE;
		$config['allowed_types'] = 'gif|jpg|jpeg|jpe|png';
		$config['max_size']	= '1024';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload($key)) {
			$gonder = array('durum' => false, 'error' => $this->upload->display_errors());
		} else {
			$upload_data = $this->upload->data();
			$gonder = array('durum' => true, 'upload_data' => $this->upload->data());
		}

		return $gonder;
	}

	public function delete_files()
	{
		$this->load->helper('file');
		foreach(glob(DIR_DOWNLOAD . 'temp/*') as $files) {
			$file_info = get_file_info($files);
			$cur_time = time();
			$file_time = ($file_info['date'] + 7200);
			if($file_time < $cur_time) {
				@unlink($file_info['server_path']);
			}
		}
	}

	public function delete()
	{
		$_old_data = $this->input->post('old_data');
		if($_old_data) {
			@unlink(DIR_DOWNLOAD . $_old_data);
		}
	}

	public function sepete_ekle_kontrol()
	{
		$product_id = $this->input->post('stok_id');
		$json['error'] = NULL;
		if($product_id) {
			$post_option = $this->input->post('stok_secenek');
			$options = $this->product_model->get_product_option($product_id);
			if($options) {
				$new_options = array();
				foreach($options as $option) {
					$new_option[$option['product_option_id']] = $option;
				}
				foreach ($new_option as $n_option) {
					if($n_option['required']) {
						if((isset($post_option[$n_option['product_option_id']]) AND $post_option[$n_option['product_option_id']])) {
							if($n_option['character_limit']) {
								$post_strlen = strlen($post_option[$n_option['product_option_id']]);
								if($post_strlen > $n_option['character_limit']) {
									$json['error'][$n_option['product_option_id']] = 'Bu alan '. $n_option['character_limit'] .' karakterden büyük olamaz!';
								}
							}
						} else {
							$json['error'][$n_option['product_option_id']] = 'Bu alan gereklidir!';
						}
					} else {
						if($n_option['character_limit']) {
							$post_strlen = strlen($post_option[$n_option['product_option_id']]);
							if($post_strlen > $n_option['character_limit']) {
								$json['error'][$n_option['product_option_id']] = 'Bu alan '. $n_option['character_limit'] .' karakterden büyük olamaz!';
							}
						}
					}
				}
			}
		}

		$this->output->set_output(json::encode($json));
	}

}
?>