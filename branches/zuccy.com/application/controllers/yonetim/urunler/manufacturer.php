<?php 

if (! defined('BASEPATH')) exit('No direct script access');

class manufacturer extends Admin_Controller {

	var $izin_linki;

	function __construct() 
	{
		parent::__construct();
		$this->load->model('yonetim/urunler/manufacturer_manufacturer_model');
		$this->izin_linki = 'product/manufacturer';

		$this->load->library('form_validation');
	}

	public function index()
	{
		redirect(yonetim_url('urunler/manufacturer/lists'));
	}

	public function lists($sort_link = 'm.name-desc', $filter = 'm.status|]', $page = 0)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/urunler/manufacturer/lists/' . $sort_link . '/' . $filter . '/' . $page);

		$sort_link_e			= explode('-', $sort_link);
		$sort					= $sort_link_e[0];
		$order					= $sort_link_e[1];

		$data					= array();
		$typ_title				= 'Marka';
		$data['title']			= ucwords($typ_title);
		$data['add_url']		= 'urunler/manufacturer/add';
		$data['delete_url']		= 'urunler/manufacturer/delete';
		$data['manufacturers']	= array();
		$manufacturers			= $this->manufacturer_manufacturer_model->get_manufacturers_by_all($page, $sort, $order, $filter, $sort_link);
		if($manufacturers) {
			foreach ($manufacturers->result() as $result) {
				$action = array();
				$action[] = array(
					'text' => 'Düzenle',
					'href' => 'yonetim/urunler/manufacturer/edit/' . $result->manufacturer_id
				);
				$action[] = array(
					'text' => 'Sil',
					'href' => 'yonetim/urunler/manufacturer/delete_one/' . $result->manufacturer_id
				);
				$data['manufacturers'][] = array(
					'manufacturer_id'			=> $result->manufacturer_id,
					'name'						=> $result->name,
					'image'						=> $result->image,
					'seo'						=> $result->seo,
					'meta_description'			=> $result->meta_description,
					'meta_keywords'				=> $result->meta_keywords,
					'status'					=> $result->status,
					'sort_order'				=> $result->sort_order,
					'date_added' 				=> $result->date_added,
					'date_modified' 			=> $result->date_modified,
					'selected'					=> ($this->input->post('selected') && in_array($result->manufacturer_id, $this->input->post('selected'))),
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

		$_c_array = explode(', ', get_fields_from_table('manufacturer', 'm.'));
		$_filter_allowed = $_c_array;

		if ($filter != 'm.status|]') {
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

		$this->load->view('yonetim/urunler/manufacturer_list_view' , $data);
	}

	function delete()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/urunler/manufacturer/delete');

		$val = $this->validation;

		$rules['selected']	= "trim|xss_clean|required";
		$fields['selected'] = "Marka No";

		$val->set_fields($fields);
		$val->set_rules($rules);

		if ($val->run() == TRUE)
		{
			$kontrol = $this->manufacturer_manufacturer_model->manufacturer_delete_by_id($val->selected);
			if($kontrol)
			{
				$yonetim_mesaj				= array();
				$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Marka silme işlemi tamamlandı.');	
			} else {
				$yonetim_mesaj				= array();
				$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Marka silme işlemi tamamlanamadı.');
			}
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/urunler/manufacturer/lists');
		} else {
			$yonetim_mesaj				= array();
			$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Herhangi bir marka seçilemediği için marka silme işlemi tamamlanamadı.');
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/urunler/manufacturer/lists');
		}
	}

	function delete_one($manufacturer_id)
	{
		$check = $this->manufacturer_manufacturer_model->manufacturer_delete_by_id($manufacturer_id);
		if($check)
		{
			$yonetim_mesaj				= array();
			$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Marka silme işlemi tamamlandı.');	
		} else {
			$yonetim_mesaj				= array();
			$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Marka silme işlemi tamamlanamadı.');
		}
		$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
		redirect('yonetim/urunler/manufacturer/lists');
	}

	function add()
	{
		$form_type = array('type' => 'add');
		$this->get_form($form_type);
	}

	function edit($manufacturer_id)
	{
		$form_type = array('type' => 'edit', 'manufacturer_id' => $manufacturer_id);
		$this->get_form($form_type);
	}

	private function get_form($get_values)
	{
		if($get_values['type'] == 'edit') {
			$manufacturer_id = $get_values['manufacturer_id'];
			$data['manufacturer_id'] = $manufacturer_id;
			$manufacturer_info = $this->manufacturer_manufacturer_model->get_manufacturer_by_id($manufacturer_id);
		}

		$language_id				= get_language('language_id', config('site_ayar_yonetim_dil'));
		$typ_title					= 'Marka';
		$data['title']				= ucwords($typ_title);
		$data['cancel_url']			= 'urunler/manufacturer/lists';
		if(isset($manufacturer_info)) {
			$data['action_url']		= 'urunler/manufacturer/edit/' . $manufacturer_id;
		} else {
			$data['action_url']		= 'urunler/manufacturer/add/';
		}

		$val = $this->form_validation;

		/* Genel */
		$val->set_rules('name', 'Başlık', 'trim|required|xss_clean');
		$val->set_rules('image', 'Resim', 'trim|xss_clean');
		$val->set_rules('description', 'Marka Açıklama', 'trim|xss_clean');
		$val->set_rules('seo', 'Seo Adresi', 'trim|xss_clean');
		$val->set_rules('meta_description', 'Meta Description', 'trim|xss_clean');
		$val->set_rules('meta_keywords', 'Meta Keywords', 'trim|xss_clean');
		$val->set_rules('sort_order', 'Sıralama', 'trim|required|is_natural_no_zero|xss_clean');
		$val->set_rules('status', 'Durum', 'trim|required|numeric|xss_clean');
		/* Genel */

		if ($val->run() == FALSE) {
			$data['errors'] = validation_errors();
		} else {
			if(isset($manufacturer_info)) {
				$check = $this->manufacturer_manufacturer_model->update_manufacturer($manufacturer_id, $this->input->post());
				if(!$check) {
					$yonetim_mesaj				= array();
					$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('Düzenlemek istediğiniz markada sorun oluştu! Herhangi bir değişiklik yapmamış olabilirsiniz!');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/urunler/manufacturer/lists');
				} else {
					$yonetim_mesaj				= array();
					$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('Marka düzenleme işleminiz başarılı bir şekilde gerçekleşti.');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/urunler/manufacturer/lists');
				}
			} else {
				$check = $this->manufacturer_manufacturer_model->add_manufacturer($this->input->post());
				if(!$check) {
					$yonetim_mesaj				= array();
					$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('Eklemek istediğiniz markada sorun oluştu!');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/urunler/manufacturer/lists');
				} else {
					$yonetim_mesaj				= array();
					$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('Marka ekleme işleminiz başarılı bir şekilde gerçekleşti.');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/urunler/manufacturer/lists');
				}
			}
		}

		/* Genel Tab */

		if($this->input->post('name')) {
			$data['name'] = $this->input->post('name');
		} elseif(isset($manufacturer_info)) {
			$data['name'] = $manufacturer_info->name;
		} else {
			$data['name'] = '';
		}

		if($this->input->post('image')) {
			$data['preview_input'] = $this->input->post('image');
			$data['preview'] = $this->image_model->resize($this->input->post('image'), 100, 100);
		} elseif(isset($manufacturer_info)) {
			$data['preview_input'] = $manufacturer_info->image;
			$data['preview'] = $this->image_model->resize($manufacturer_info->image, 100, 100);
		} else {
			$data['preview_input'] = 'resim_ekle.jpg';
			$data['preview'] = $this->image_model->resize('resim_ekle.jpg', 100, 100);
		}

		if($this->input->post('description')) {
			$data['description'] = $this->input->post('description');
		} elseif(isset($manufacturer_info)) {
			$data['description'] = $manufacturer_info->description;
		} else {
			$data['description'] = '';
		}

		if($this->input->post('seo')) {
			$data['seo'] = $this->input->post('seo');
		} elseif(isset($manufacturer_info)) {
			$data['seo'] = $manufacturer_info->seo;
		} else {
			$data['seo'] = '';
		}

		if($this->input->post('meta_description')) {
			$data['meta_description'] = $this->input->post('meta_description');
		} elseif(isset($manufacturer_info)) {
			$data['meta_description'] = $manufacturer_info->meta_description;
		} else {
			$data['meta_description'] = '';
		}

		if($this->input->post('meta_keywords')) {
			$data['meta_keywords'] = $this->input->post('meta_keywords');
		} elseif(isset($manufacturer_info)) {
			$data['meta_keywords'] = $manufacturer_info->meta_keywords;
		} else {
			$data['meta_keywords'] = '';
		}

		if($this->input->post('status')) {
			$data['status'] = $this->input->post('status');
		} elseif(isset($manufacturer_info)) {
			$data['status'] = $manufacturer_info->status;
		} else {
			$data['status'] = '1';
		}

		if($this->input->post('sort_order')) {
			$data['sort_order'] = $this->input->post('sort_order');
		} elseif(isset($manufacturer_info)) {
			$data['sort_order'] = $manufacturer_info->sort_order;
		} else {
			$data['sort_order'] = '1';
		}

		/* Genel Tab */

		$this->load->view('yonetim/urunler/manufacturer_form_view' , $data);
	}
}

/* End of file class_name.php */
/* Location: ./application/controllers/class_name.php */