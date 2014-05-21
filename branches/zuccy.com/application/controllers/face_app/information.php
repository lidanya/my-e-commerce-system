<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class information extends Face_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('site/information_model');
	}

	function detail($type, $seo_link)
	{
		$information = $this->information_model->get_information_by_seo_type($seo_link, $type);
		if($information) {
			$content_data['baslik'] = $information->title;
			$content_data['keywords'] = $information->meta_keywords;
			$content_data['description'] = $information->meta_description;
			$content_data['page_detail'] = $information->description;

			$this->template->set_master_template(face_tema() . 'information/index');
			$this->template->add_region('content');
			$this->template->write_view('content', face_tema() . 'information/detail/content' , $content_data);
			$this->template->add_css(APPPATH . 'views/' . face_tema_asset() . 'css/anasayfa.css');

			$this->template->render();
		} else {
			redirect('');
		}
	}

	function all_detail($type)
	{
		$information = $this->information_model->get_information_by_type($type, $limit = '-1');
		if($information) {
			$content_data['information_detail'] = $information;
			$content_data['baslik'] = lang('messages_page_title_' . $type);

			$this->template->set_master_template(face_tema() . 'information/index');
			$this->template->add_region('content');
			$this->template->write_view('content', face_tema() . 'information/all_detail/content' , $content_data);
			$this->template->add_css(APPPATH . 'views/' . face_tema_asset() . 'css/anasayfa.css');

			$this->template->render();
		} else {
			redirect('');
		}
	}
	
}