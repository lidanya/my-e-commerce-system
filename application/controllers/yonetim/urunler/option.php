<?php 

if (! defined('BASEPATH')) exit('No direct script access');

class option extends Admin_Controller {

	var $izin_linki;

	function __construct() 
	{
		parent::__construct();
		$this->load->model('yonetim/urunler/product_option_model');
		$this->izin_linki = 'product/option';

		$this->load->library('form_validation');
	}

	public function index()
	{
		redirect(yonetim_url('urunler/option/lists'));
	}

	public function lists($sort_link = 'od.name-desc', $filter = 'o.option_id|]', $page = 0)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/urunler/option/lists/' . $sort_link . '/' . $filter . '/' . $page);

		$sort_link_e			= explode('-', $sort_link);
		$sort					= $sort_link_e[0];
		$order					= $sort_link_e[1];

		$data					= array();
		$typ_title				= 'Seçenek';
		$data['title']			= ucwords($typ_title);
		$data['add_url']		= 'urunler/option/add';
		$data['delete_url']		= 'urunler/option/delete';
		$data['options']		= array();
		$options				= $this->product_option_model->get_options_by_all($page, $sort, $order, $filter, $sort_link);
		if($options) {
			foreach ($options->result() as $result) {
				$action = array();
				$action[] = array(
					'text' => 'Düzenle',
					'href' => 'yonetim/urunler/option/edit/' . $result->option_id
				);
				$action[] = array(
					'text' => 'Sil',
					'href' => 'yonetim/urunler/option/delete_one/' . $result->option_id
				);
				$data['options'][] = array(
					'option_id'					=> $result->option_id,
					'name'						=> $result->name,
					'sort_order'				=> $result->sort_order,
					'selected'					=> ($this->input->post('selected') && in_array($result->option_id, $this->input->post('selected'))),
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

		$_c_array = explode(', ', get_fields_from_table('option', 'o.'));
		$_cd_array = explode(', ', get_fields_from_table('option_description', 'od.'));
		$_filter_allowed = array_merge($_c_array, $_cd_array);

		if ($filter != 'o.option_id|]') {
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

		$this->load->view('yonetim/urunler/option_list_view' , $data);
	}

	public function delete()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/urunler/option/delete');

		$val = $this->validation;

		$rules['selected']	= "trim|xss_clean|required";
		$fields['selected'] = "Ürün No";

		$val->set_fields($fields);
		$val->set_rules($rules);

		if ($val->run() == TRUE)
		{
			$kontrol = $this->product_option_model->option_delete_by_id($val->selected);
			if($kontrol)
			{
				$yonetim_mesaj				= array();
				$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Seçenek silme işlemi tamamlandı.');	
			} else {
				$yonetim_mesaj				= array();
				$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Seçenek silme işlemi tamamlanamadı.');
			}
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/urunler/option/lists');
		} else {
			$yonetim_mesaj				= array();
			$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Herhangi bir seçenek seçilemediği için seçenek silme işlemi tamamlanamadı.');
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/urunler/option/lists');
		}
	}

	public function delete_one($option_id)
	{
		$check = $this->product_option_model->option_delete_by_id($option_id);
		if($check)
		{
			$yonetim_mesaj				= array();
			$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Seçenek silme işlemi tamamlandı.');	
		} else {
			$yonetim_mesaj				= array();
			$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Seçenek silme işlemi tamamlanamadı.');
		}
		$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
		redirect('yonetim/urunler/option/lists');
	}

	function add()
	{
		$form_type = array('type' => 'add');
		$this->get_form($form_type);
	}

	function edit($option_id)
	{
		$form_type = array('type' => 'edit', 'option_id' => $option_id);
		$this->get_form($form_type);
	}

	private function get_form($get_values)
	{
		if($get_values['type'] == 'edit') {
			$option_id = $get_values['option_id'];
			$data['option_id'] = $option_id;
			$option_info = $this->product_option_model->get_option_by_id($option_id);
		}

		$language_id				= get_language('language_id', config('site_ayar_yonetim_dil'));
		$typ_title					= 'Seçenek';
		$data['title']				= ucwords($typ_title);
		$data['cancel_url']			= 'urunler/option/lists';
		if(isset($option_info)) {
			$data['action_url']		= 'urunler/option/edit/' . $option_id;
		} else {
			$data['action_url']		= 'urunler/option/add/';
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

		$val = $this->form_validation;

		/* Genel */
		$lang_array_fields = array(
			'name' => array('title' => 'Seçenek Adı', 'rules' => 'trim|required|max_length[255]|xss_clean')
		);
		foreach ($data['languages'] as $_lang) {
			foreach($lang_array_fields as $key => $value) {
				$val->set_rules('option_description['. $_lang['language_id'] .']['. $key .']', $_lang['name'] . ' ' . $value['title'], $value['rules']);
			}
		}

		if ($this->input->post('option_value')) {
			foreach ($this->input->post('option_value') as $option_value_id => $option_value) {
				foreach ($option_value['option_value_description'] as $language_id => $option_value_description) {
					$val->set_rules('option_value['. $option_value_id .'][option_value_description]['. $_lang['language_id'] .'][name]', 'Seçenek Değer Adı', 'required|max_length[255]|xss_clean');
				}
				$val->set_rules('option_value['. $option_value_id .'][sort_order]', 'Seçenek Değer Sırası', 'trim|required|is_natural_no_zero|xss_clean');
			}
		}

		$val->set_rules('type', 'Tip', 'trim|required|xss_clean');
		$val->set_rules('sort_order', 'Sıralama', 'trim|required|is_natural_no_zero|xss_clean');
		/* Genel */

		if ($val->run() == FALSE) {
			$data['errors'] = validation_errors();
		} else {
			if(isset($option_info)) {
				$check = $this->product_option_model->update_option($option_id, $this->input->post());
				if(!$check) {
					$yonetim_mesaj				= array();
					$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('Düzenlemek istediğiniz seçenekte sorun oluştu! Herhangi bir değişiklik yapmamış olabilirsiniz!');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/urunler/option/lists');
				} else {
					$yonetim_mesaj				= array();
					$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('Seçenek düzenleme işleminiz başarılı bir şekilde gerçekleşti.');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/urunler/option/lists');
				}
			} else {
				$check = $this->product_option_model->add_option($this->input->post());
				if(!$check) {
					$yonetim_mesaj				= array();
					$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('Eklemek istediğiniz seçenekte sorun oluştu!');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/urunler/option/lists');
				} else {
					$yonetim_mesaj				= array();
					$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('Seçenek ekleme işleminiz başarılı bir şekilde gerçekleşti.');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/urunler/option/lists');
				}
			}
		}

		/* Genel Tab */
		if($this->input->post('option_description')) {
			$data['option_description'] = $this->input->post('option_description');
		} elseif(isset($option_info)) {
			$data['option_description'] = $this->product_option_model->get_option_description_by_id($option_id);
		} else {
			$data['option_description'] = array();
		}

		if ($this->input->post('type')) {
			$data['type'] = $this->input->post('type');
		} elseif (isset($option_info)) {
			$data['type'] = $option_info->type;
		} else {
			$data['type'] = '';
		}

		if ($this->input->post('sort_order')) {
			$data['sort_order'] = $this->input->post('sort_order');
		} elseif (isset($option_info)) {
			$data['sort_order'] = $option_info->sort_order;
		} else {
			$data['sort_order'] = '';
		}

		if ($this->input->post('option_value')) {
			$data['option_values'] = $this->input->post('option_value');
		} elseif (isset($option_info)) {
			$data['option_values'] = $this->product_option_model->get_option_value_description_by_id($option_id);
		} else {
			$data['option_values'] = array();
		}
		/* Genel Tab */

		$this->load->view('yonetim/urunler/option_form_view' , $data);
	}

	public function autocomplete()
	{
		$json = array();
		if ($this->input->post('filter_name')) {
			$page		= 0;
			$filter		= 'od.name|'. $this->input->post('filter_name') .']';
			$sort		= 'od.name';
			$order		= 'asc';
			$options	= $this->product_option_model->get_options_by_no_pag_all($page, $sort, $order, $filter);

			foreach ($options as $option) {
				$option_value_data = array();
				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox') {
					$option_values = $this->product_option_model->get_option_values_by_id($option['option_id']);
					foreach ($option_values as $option_value) {
						$option_value_data[] = array(
							'option_value_id'	=> $option_value['option_value_id'],
							'name'				=> html_entity_decode($option_value['name'], ENT_QUOTES, 'UTF-8')					
						);
					}
					$sort_order = array();
					foreach ($option_value_data as $key => $value) {
						$sort_order[$key] = $value['name'];
					}
					array_multisort($sort_order, SORT_ASC, $option_value_data);					
				}

				$type = '';
				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox') {
					$type = 'Seçim';
				}

				if ($option['type'] == 'text' || $option['type'] == 'textarea') {
					$type = 'Yazı';
				}

				if ($option['type'] == 'file') {
					$type = 'Dosya';
				}

				$json[] = array(
					'option_id'		=> $option['option_id'],
					'name'			=> html_entity_decode($option['name'], ENT_QUOTES, 'UTF-8'),
					'category'		=> $type,
					'type'			=> $option['type'],
					'option_value'	=> $option_value_data
				);
			}
		}
		$sort_order = array();
		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}
		array_multisort($sort_order, SORT_ASC, $json);
		$this->output->set_output(Json::encode($json));
	}

}