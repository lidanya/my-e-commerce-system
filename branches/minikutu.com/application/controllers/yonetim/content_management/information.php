<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class information extends Admin_Controller {

	var $izin_linki;
	var $type_title;
	var $information_type;
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('yonetim/content_management/information_model');
		$this->izin_linki = 'content_management';

		$this->information_type = $this->config->item('information_types');
		$this->load->library('form_validation');
	}

	function type_control($type)
	{
		if(!isset($this->information_type[$type])) {
			redirect(yonetim_url());
		}
	}

	function lists($type = 'information', $sort_link = 'id.title-desc', $filter = 'i.status|]', $page = 0)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/content_management/information/lists/' . $type . '/' . $sort_link . '/' . $filter . '/' . $page);
		$this->type_control($type);

		$sort_link_e			= explode('-', $sort_link);
		$sort					= $sort_link_e[0];
		$order					= $sort_link_e[1];

		$data					= array();
		$typ_title				= isset($this->information_type[$type]['title']) ? $this->information_type[$type]['title'] : 'Tanımsız';
		$data['title']			= ucwords($typ_title);
		$data['add_url']		= 'content_management/information/add/' . $type;
		$data['delete_url']		= 'content_management/information/delete/' . $type;
		$data['informations']	= array();
		$informations			= $this->information_model->get_information_by_type($type, $page, $sort, $order, $filter, $sort_link);
		if($informations) {
			foreach ($informations->result() as $result) {
				$action = array();
				$action[] = array(
					'text' => 'Düzenle',
					'href' => 'yonetim/content_management/information/edit/' . $type . '/' . $result->information_id
				);
				$action[] = array(
					'text' => 'Sil',
					'href' => 'yonetim/content_management/information/delete_one/' . $type . '/' . $result->information_id
				);
				$data['informations'][] = array(
					'information_id'    	=> $result->information_id,
					'type'					=> $result->type,
					'date_added' 			=> $result->date_added,				
					'date_modified'  		=> $result->date_modified,
					'description'			=> $result->description,
					'seo'					=> $result->seo,
					'status'				=> $result->status,
					'title'					=> $result->title,
					'meta_keywords' 		=> $result->meta_keywords,
					'meta_description' 		=> $result->meta_description,
					'selected'       		=> ($this->input->post('selected') && in_array($result->information_id, $this->input->post('selected'))),
					'action'         		=> $action
				);
			}
		}

		$data['sort_link']		= $sort_link;
		$data['filt_link']		= $filter;
		$data['page_link']		= $page;

		$sort_lnk_e				= explode('-', $sort_link);
		$data['sort']			= $sort_lnk_e[0];
		$data['order']			= $sort_lnk_e[1];

		if ($order) {
			if ($order == 'asc') {
				$data['order_link'] = 'desc';
			} else if ($order == 'desc') {
				$data['order_link'] = 'asc';
			} else {
				$data['order_link'] = 'desc';
			}
		} else {
			$data['order_link'] = 'asc';		
			$data['order'] = 'desc';		
		}

		$_i_array = explode(', ', get_fields_from_table('information', 'i.'));
		$_id_array = explode(', ', get_fields_from_table('information_description', 'id.'));
		$_filter_allowed = array_merge($_i_array, $_id_array);

		if ($filter != 'i.status|]') {
			$filter_e = explode(']', $filter);
			foreach($filter_e as $yaz) {
				if($yaz != '') {
					if(preg_match('/|/i', $yaz)) {
						$explode = explode('|', $yaz);
						if((isset($explode[0]) AND $explode[0] != '') AND (isset($explode[1]) AND $explode[1] != '')) {
							if(in_array($explode[0], $_filter_allowed)) {
								$data['filter_' . str_replace('.', '_', $explode[0])] = $explode[1];
							}
						}
					}
				}
			}
		}

		$data['information_type'] = $type;

		$this->load->view('yonetim/content_management/information_list_view' , $data);
	}

	function delete($type = 'information')
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/content_management/information/delete');
		$this->type_control($type);

		$val = $this->validation;

		$rules['selected']	= "trim|xss_clean|required";
		$fields['selected'] = "Duyuru Başlığı";

		$val->set_fields($fields);
		$val->set_rules($rules);

		if ($val->run() == TRUE)
		{
			$kontrol = $this->information_model->information_delete_by_type($type, $val->selected);
			if($kontrol)
			{
				$yonetim_mesaj				= array();
				$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('İçerik silme işlemi tamamlandı.');	
			} else {
				$yonetim_mesaj				= array();
				$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('İçerik silme işlemi tamamlanamadı.');
			}
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/content_management/information/lists/' . $type);
		} else {
			$yonetim_mesaj				= array();
			$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Herhangi bir içerik seçilemediği için içerik silme işlemi tamamlanamadı.');
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/content_management/information/lists/' . $type);
		}
	}

	function delete_one($type = 'information', $information_id = 0)
	{
		$this->type_control($type);
		$kontrol = $this->information_model->information_delete_by_type($type, $information_id);
		if($kontrol)
		{
			$yonetim_mesaj				= array();
			$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('İçerik silme işlemi tamamlandı.');	
		} else {
			$yonetim_mesaj				= array();
			$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('İçerik silme işlemi tamamlanamadı.');
		}
		$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
		redirect('yonetim/content_management/information/lists/' . $type);
	}

	function edit($type = 'information', $information_id = 0)
	{
		$this->type_control($type);
		$check = $this->information_model->count_information_by_id_type($type, $information_id);
		if(!$check) {
			$yonetim_mesaj				= array();
			$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Düzenlemek istediğiniz içerik bulunamadı!');
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/content_management/information/lists/' . $type);
		}

		$form_type = array('information_type' => $type, 'type' => 'edit', 'information_id' => $information_id);
		$this->get_form($form_type);
	}

	function add($type = 'information')
	{
		$this->type_control($type);
		$form_type = array('information_type' => $type, 'type' => 'add');
		$this->get_form($form_type);
	}

	private function get_form($get_values)
	{
		if($get_values['type'] == 'edit') {
			$information_id = $get_values['information_id'];
			$information_info = $this->information_model->get_information_by_id($information_id);
		}

		$information_type			= $get_values['information_type'];
		$typ_title					= isset($this->information_type[$information_type]['title']) ? $this->information_type[$information_type]['title'] : 'Tanımsız';
		$data['title']				= ucwords($typ_title);
		$data['cancel_url']			= 'content_management/information/lists/' . $information_type;
		if(isset($information_info)) {
			$data['action_url']		= 'content_management/information/edit/' . $information_type . '/' . $information_id;
		} else {
			$data['action_url']		= 'content_management/information/add/' . $information_type;
		}

		/* languages */
		$languages					= $this->fonksiyonlar->get_languages();
		$allowed_languages			= array();
		foreach($languages as $language) {
			if($language['status']) {
				$send_language['language_id']	= $language['language_id'];
				$send_language['name']			= $language['name'];
				$send_language['code']			= $language['code'];
				$send_language['locale']		= $language['locale'];
				$send_language['image']			= $language['image'];
				$send_language['directory']		= $language['directory'];
				$send_language['sort_order']	= $language['sort_order'];
				$send_language['status']		= $language['status'];
				$allowed_languages[$language['language_id']] = $send_language;
			}
		}
		$data['languages']			= $allowed_languages;
		/* languages */

		/* categories */
		$categories					= $this->information_model->get_information_category_by_type_parent($information_type, 0, '-1');
		$allowed_categories			= array();
		if($categories) {
			foreach($categories as $category) {
				if($category['status']) {
					$allowed_categories[$category['information_category_id']] = $category['title'];
				}
			}
		}
		$data['categories']			= $allowed_categories;
		/* categories */

		$val = $this->form_validation;

		/* Genel */
		$lang_array_fields = array(
			'title'				=> array('title' => 'Başlık', 'rules' => 'required|max_length[255]|xss_clean'),
			'description'		=> array('title' => 'Açıklama', 'rules' => 'trim'),
			'meta_keywords'		=> array('title' => 'Meta Keywords', 'rules' => 'max_length[255]|xss_clean'),
			'meta_description'	=> array('title' => 'Meta Description',	'rules'	=> 'max_length[255]|xss_clean'),
			'seo'				=> array('title' => 'Seo Adresi', 'rules' => 'max_length[255]|xss_clean'),
		);
		foreach ($data['languages'] as $_lang) {
			foreach($lang_array_fields as $key => $value) {
				$val->set_rules('information_description['. $_lang['language_id'] .']['. $key .']', $_lang['name'] . ' ' . $value['title'], $value['rules']);
			}
		}
		$val->set_rules('category_id', 'Kategori', 'trim|required|is_natural_no_zero|xss_clean');
		$val->set_rules('status', 'Durumu', 'trim|required|numeric|xss_clean');
		$val->set_rules('sort_order', 'Sıralama', 'trim|required|is_natural_no_zero|xss_clean');
		$val->set_rules('image', 'Resmi', 'trim|xss_clean');
		/* Genel */

		if ($val->run() == FALSE) {
			$data['errors'] = validation_errors();
		} else {
			if(isset($information_info)) {
				$check = $this->information_model->update_information($information_id, $information_type, $this->input->post());
				if(!$check) {
					$yonetim_mesaj				= array();
					$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('Düzenlemek istediğiniz içerikte sorun oluştu! Herhangi bir değişiklik yapmamış olabilirsiniz!');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/content_management/information/lists/' . $information_type);
				} else {
					$yonetim_mesaj				= array();
					$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('İçerik düzenleme işleminiz başarılı bir şekilde gerçekleşti.');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/content_management/information/lists/' . $information_type);
				}
			} else {
				$check = $this->information_model->add_information($information_type, $this->input->post());
				if(!$check) {
					$yonetim_mesaj				= array();
					$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('Eklemek istediğiniz içerikte sorun oluştu!');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/content_management/information/lists/' . $information_type);
				} else {
					$yonetim_mesaj				= array();
					$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('İçerik ekleme işleminiz başarılı bir şekilde gerçekleşti.');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/content_management/information/lists/' . $information_type);
				}
			}
		}

		/* Genel Tab */
		if($this->input->post('category_id')) {
			$data['category_id'] = $this->input->post('category_id');
		} elseif(isset($information_info)) {
			$data['category_id'] = $information_info->category_id;
		} else {
			$data['category_id'] = '0';
		}

		if($this->input->post('status')) {
			$data['status'] = $this->input->post('status');
		} elseif(isset($information_info)) {
			$data['status'] = $information_info->status;
		} else {
			$data['status'] = '1';
		}
	
		if($this->input->post('image')) {
			$data['preview_input'] = $this->input->post('image');
			$data['preview'] = $this->image_model->resize($this->input->post('image'), 100, 100);
		} elseif(isset($information_info)) {
			$data['preview_input'] = $information_info->image;
			$data['preview'] = $this->image_model->resize($information_info->image, 100, 100);
		} else {
			$data['preview_input'] = 'resim_ekle.jpg';
			$data['preview'] = $this->image_model->resize('resim_ekle.jpg', 100, 100);
		}

		if($this->input->post('sort_order')) {
			$data['sort_order'] = $this->input->post('sort_order');
		} elseif(isset($information_info)) {
			$data['sort_order'] = $information_info->sort_order;
		} else {
			$data['sort_order'] = '0';
		}

		if($this->input->post('information_description')) {
			$data['information_description'] = $this->input->post('information_description');
		} elseif(isset($information_info)) {
			$data['information_description'] = $this->information_model->get_information_description_by_id($information_id);
		} else {
			$data['information_description'] = array();
		}
		/* Genel Tab */

		$this->load->view('yonetim/content_management/information_form_view' , $data);
	}
}