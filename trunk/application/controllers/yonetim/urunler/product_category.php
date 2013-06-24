<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class product_category extends Admin_Controller {

	var $izin_linki;
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('yonetim/urunler/product_category_model');
		$this->izin_linki = 'product/category';

		$this->load->library('form_validation');
	}

	public function index()
	{
		redirect(yonetim_url('urunler/product_category/lists'));
	}

	function lists($sort_link = 'cd.name-desc', $filter = 'c.status|]', $page = 0)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/urunler/product_category/lists/' . $sort_link . '/' . $filter . '/' . $page);

		$sort_link_e			= explode('-', $sort_link);
		$sort					= $sort_link_e[0];
		$order					= $sort_link_e[1];

		$data					= array();
		$typ_title				= 'Ürün Kategorileri';
		$data['title']			= ucwords($typ_title);
		$data['add_url']		= 'urunler/product_category/add';
		$data['delete_url']		= 'urunler/product_category/delete';
		$data['product_categories']	= array();
		$categories				= $this->product_category_model->get_categories_by_all($page, $sort, $order, $filter, $sort_link);
		if($categories) {
			foreach ($categories->result() as $result) {
				$action = array();
				$action[] = array(
					'text' => 'Düzenle',
					'href' => 'yonetim/urunler/product_category/edit/' . $result->category_id
				);
				$action[] = array(
					'text' => 'Sil',
					'href' => 'yonetim/urunler/product_category/delete_one/' . $result->category_id
				);
				$data['product_categories'][] = array(
					'category_id'				=> $result->category_id,
					'sort_order'				=> $result->sort_order,
					'parent_id'					=> $result->parent_id,
					'status'					=> $result->status,
					'date_added'				=> $result->date_added,
					'date_modified'				=> $result->date_modified,
					'name'						=> $this->product_category_model->get_category_path_by_id($result->category_id),
					'meta_keywords' 			=> $result->meta_keywords,
					'meta_description' 			=> $result->meta_description,
					'seo'						=> $result->seo,
					'selected'					=> ($this->input->post('selected') && in_array($result->category_id, $this->input->post('selected'))),
					'action'					=> $action
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

		$_c_array = explode(', ', get_fields_from_table('category', 'c.'));
		$_cd_array = explode(', ', get_fields_from_table('category_description', 'cd.'));
		$_filter_allowed = array_merge($_c_array, $_cd_array);

		if ($filter != 'c.status|]') {
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

		$this->load->view('yonetim/urunler/product_category_list_view' , $data);
	}

	function delete()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/urunler/product_category/delete');

		$val = $this->validation;

		$rules['selected']	= "trim|xss_clean|required";
		$fields['selected'] = "Kategori";

		$val->set_fields($fields);
		$val->set_rules($rules);

		if ($val->run() == TRUE)
		{
			$kontrol = $this->product_category_model->category_delete_by_id($val->selected);
			if($kontrol)
			{
				$yonetim_mesaj				= array();
				$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Kategori silme işlemi tamamlandı.');	
			} else {
				$yonetim_mesaj				= array();
				$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Kategori silme işlemi tamamlanamadı.');
			}
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/urunler/product_category/lists');
		} else {
			$yonetim_mesaj				= array();
			$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Herhangi bir kategori seçilemediği için kategori silme işlemi tamamlanamadı.');
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/urunler/product_category/lists');
		}
	}

	function delete_one($category_id)
	{
		$kontrol = $this->product_category_model->category_delete_by_id($category_id);
		if($kontrol)
		{
			$yonetim_mesaj				= array();
			$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Kategori silme işlemi tamamlandı.');	
		} else {
			$yonetim_mesaj				= array();
			$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Kategori silme işlemi tamamlanamadı.');
		}
		$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
		redirect('yonetim/urunler/product_category/lists');
	}

	public function feature_delete()
	{
		$json = NULL;
		$json['success'] = '';
		$json['error'] = '';

		$feature_id = $this->input->post('feature_id');
		if($feature_id) {

			$feature_check = $this->product_category_model->feature_delete_by_id($feature_id);
			if($feature_check) {
				$json['success'] = 'İşlem Başarılı';
			} else {
				$json['error'] = 'İşlem Başarısız';
			}
		} else {
			$json['error'] = 'Özellik No Gerekli';
		}

		exit(json::encode($json));
	}

	function edit($category_id)
	{
		$check = $this->product_category_model->count_category_by_id($category_id);
		if(!$check) {
			$yonetim_mesaj				= array();
			$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Düzenlemek istediğiniz kategori bulunamadı!');
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/urunler/product_category/lists');
		}

		$form_type = array('type' => 'edit', 'category_id' => $category_id);
		$this->get_form($form_type);
	}

	function add()
	{
		$form_type = array('type' => 'add');
		$this->get_form($form_type);
	}

	private function get_form($get_values)
	{
		if($get_values['type'] == 'edit') {
			$category_id = $get_values['category_id'];
			$category_info = $this->product_category_model->get_category_by_id($category_id);
		}

		$typ_title					= 'Ürün Kategorileri';
		$data['title']				= ucwords($typ_title);
		$data['cancel_url']			= 'urunler/product_category/lists';
		if(isset($category_info)) {
			$data['action_url']		= 'urunler/product_category/edit/' . $category_id;
		} else {
			$data['action_url']		= 'urunler/product_category/add/';
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
		$_categories				= $this->product_category_model->get_category_by_parent(0, '-1');
		$allowed_categories			= array('0' => ' - Ana Kategori - ');
		if($_categories) {
			foreach($_categories as $_category) {
				if($_category['status']) {
					if(isset($category_info) AND $_category['category_id'] == $category_id) {
						continue;
					} else {
						$allowed_categories[$_category['category_id']] = $_category['name'];
					}
				}
			}
		}
		$data['allowed_categories']	= $allowed_categories;
		/* categories */

		$val = $this->form_validation;

		$lang_array_fields = array(
			'name'				=> array('title' => 'Kategori Başlık', 'rules' => 'required|max_length[255]|xss_clean'),
			'meta_keywords'		=> array('title' => 'Meta Keywords', 'rules' => 'max_length[255]|xss_clean'),
			'meta_description'	=> array('title' => 'Meta Description',	'rules'	=> 'max_length[255]|xss_clean'),
			'seo'				=> array('title' => 'Seo Adresi', 'rules' => 'max_length[255]|xss_clean'),
			'description'		=> array('title' => 'Kategori Açıklama', 'rules' => 'trim')
		);
		foreach ($data['languages'] as $_lang) {
			foreach($lang_array_fields as $key => $value) {
				$val->set_rules('category_description['. $_lang['language_id'] .']['. $key .']', $_lang['name'] . ' ' . $value['title'], $value['rules']);
			}
		}

		$category_features = $this->input->post('category_features');
		if($category_features) {
			foreach($category_features as $cf_key => $category_feature) {
				foreach ($data['languages'] as $_lang) {
					foreach($category_feature as $key => $value) {
						$val->set_rules('category_features['. $cf_key .']['. $_lang['language_id'] .'][name]', $_lang['name'] . ' Özellik Adı', 'required|max_length[255]|xss_clean');
					}
				}
			}
		} else {
			foreach ($data['languages'] as $_lang) {
				$val->set_rules('category_features['. $_lang['language_id'] .'][name]', $_lang['name'] . ' Özellik Adı', 'max_length[255]|xss_clean');
			}
		}

		//$val->set_rules('top', 'Üst Menüde Göster', 'trim|required|numeric|xss_clean');
		//$val->set_rules('column', 'Üst Menüdeki Alt Kategori Sütun Sayısı', 'trim|required|numeric|xss_clean');
		$val->set_rules('parent_id', 'Üst Kategori', 'trim|required|numeric|xss_clean');
		$val->set_rules('status', 'Kategori Durumu', 'trim|required|numeric|xss_clean');
		$val->set_rules('sort_order', 'Sıralama', 'trim|is_natural_no_zero|xss_clean');
		$val->set_rules('image', 'Kategori Resmi', 'trim|xss_clean');

		if ($val->run() == FALSE) {
			$data['errors'] = validation_errors();
		} else {
			if(isset($category_info)) {
				$check = $this->product_category_model->update_category($category_id, $this->input->post());
				if(!$check) {
					$yonetim_mesaj				= array();
					$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('Düzenlemek istediğiniz içerikte sorun oluştu! Herhangi bir değişiklik yapmamış olabilirsiniz!');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/urunler/product_category/lists');
				} else {
					$yonetim_mesaj				= array();
					$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('İçerik düzenleme işleminiz başarılı bir şekilde gerçekleşti.');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/urunler/product_category/lists');
				}
			} else {
				$check = $this->product_category_model->add_category($this->input->post());
				if(!$check) {
					$yonetim_mesaj				= array();
					$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('Eklemek istediğiniz içerikte sorun oluştu!');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/urunler/product_category/lists');
				} else {
					$yonetim_mesaj				= array();
					$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('İçerik ekleme işleminiz başarılı bir şekilde gerçekleşti.');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/urunler/product_category/lists');
				}
			}
		}

		/* Genel Tab */
		if($this->input->post('parent_id')) {
			$data['parent_id'] = $this->input->post('parent_id');
		} elseif(isset($category_info)) {
			$data['parent_id'] = $category_info->parent_id;
		} else {
			$data['parent_id'] = '0';
		}

		if($this->input->post('status')) {
			$data['status'] = $this->input->post('status');
		} elseif(isset($category_info)) {
			$data['status'] = $category_info->status;
		} else {
			$data['status'] = '1';
		}

		if($this->input->post('sort_order')) {
			$data['sort_order'] = $this->input->post('sort_order');
		} elseif(isset($category_info)) {
			$data['sort_order'] = $category_info->sort_order;
		} else {
			$data['sort_order'] = '0';
		}

		if($this->input->post('top')) {
			$data['top'] = $this->input->post('top');
		} elseif(isset($category_info)) {
			$data['top'] = $category_info->top;
		} else {
			$data['top'] = '0';
		}

		if($this->input->post('column')) {
			$data['column'] = $this->input->post('column');
		} elseif(isset($category_info)) {
			$data['column'] = $category_info->column;
		} else {
			$data['column'] = '1';
		}

		if($this->input->post('image')) {
			$data['preview_input'] = $this->input->post('image');
			$data['preview'] = $this->image_model->resize($this->input->post('image'), 100, 100);
		} elseif(isset($category_info)) {
			$data['preview_input'] = $category_info->image;
			$data['preview'] = $this->image_model->resize($category_info->image, 100, 100);
		} else {
			$data['preview_input'] = 'resim_ekle.jpg';
			$data['preview'] = $this->image_model->resize('resim_ekle.jpg', 100, 100);
		}

		if($this->input->post('category_description')) {
			$data['category_description'] = $this->input->post('category_description');
		} elseif(isset($category_info)) {
			$data['category_description'] = $this->product_category_model->get_category_description_by_id($category_id);
		} else {
			$data['category_description'] = array();
		}
		/* Genel Tab */

		/* Özellik Tab */
		if($this->input->post('category_features')) {
			$data['category_features'] = $this->input->post('category_features');
		} elseif(isset($category_info)) {
			$data['category_features'] = $this->product_category_model->get_features_by_category_id($category_id);
		} else {
			$data['category_features'] = array();
		}
		/* Özellik Tab */

		$this->load->view('yonetim/urunler/product_category_form_view' , $data);
	}
}