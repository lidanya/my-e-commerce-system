<?php 

if (! defined('BASEPATH')) exit('No direct script access');

class review extends Admin_Controller {

	var $izin_linki;

	function __construct() 
	{
		parent::__construct();
		$this->load->model('yonetim/urunler/review_review_model');
		$this->load->model('yonetim/urunler/product_product_model');
		$this->izin_linki = 'product/review';

		$this->load->library('form_validation');
	}

	public function index()
	{
		redirect(yonetim_url('urunler/review/lists'));
	}

	public function lists($sort_link = 'r.author-desc', $filter = 'r.status|]', $page = 0)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/urunler/review/lists/' . $sort_link . '/' . $filter . '/' . $page);

		$sort_link_e			= explode('-', $sort_link);
		$sort					= $sort_link_e[0];
		$order					= $sort_link_e[1];

		$data					= array();
		$typ_title				= 'Yorum';
		$data['title']			= ucwords($typ_title);
		$data['delete_url']		= 'urunler/review/delete';
		$data['reviews']	= array();
		$reviews			= $this->review_review_model->get_review_by_all($page, $sort, $order, $filter, $sort_link);
		if($reviews) {
			foreach ($reviews->result() as $result) {
				$action = array();
				$action[] = array(
					'text' => 'Düzenle',
					'href' => 'yonetim/urunler/review/edit/' . $result->review_id
				);
				$action[] = array(
					'text' => 'Sil',
					'href' => 'yonetim/urunler/review/delete_one/' . $result->review_id
				);
				$data['reviews'][] = array(
					'review_id'					=> $result->review_id,
					'product_id'				=> $result->product_id,
					'user_id'					=> $result->user_id,
					'email'						=> $result->email,
					'author'					=> $result->author,
					'text'						=> $result->text,
					'rating'					=> $result->rating,
					'status'					=> $result->status,
					'date_added' 				=> $result->date_added,
					'date_modified' 			=> $result->date_modified,
					'selected'					=> ($this->input->post('selected') && in_array($result->review_id, $this->input->post('selected'))),
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

		$_c_array = explode(', ', get_fields_from_table('review', 'r.'));
		$_filter_allowed = $_c_array;

		if ($filter != 'r.status|]') {
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

		$this->load->view('yonetim/urunler/review_list_view' , $data);
	}

	function delete()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/urunler/review/delete');

		$val = $this->validation;

		$rules['selected']	= "trim|xss_clean|required";
		$fields['selected'] = "Yorum No";

		$val->set_fields($fields);
		$val->set_rules($rules);

		if ($val->run() == TRUE)
		{
			$kontrol = $this->review_review_model->review_delete_by_id($val->selected);
			if($kontrol)
			{
				$yonetim_mesaj				= array();
				$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Yorum silme işlemi tamamlandı.');	
			} else {
				$yonetim_mesaj				= array();
				$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Yorum silme işlemi tamamlanamadı.');
			}
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/urunler/review/lists');
		} else {
			$yonetim_mesaj				= array();
			$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Herhangi bir yorum seçilemediği için yorum silme işlemi tamamlanamadı.');
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/urunler/review/lists');
		}
	}

	function delete_one($review_id)
	{
		$check = $this->review_review_model->review_delete_by_id($review_id);
		if($check)
		{
			$yonetim_mesaj				= array();
			$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Yorum silme işlemi tamamlandı.');	
		} else {
			$yonetim_mesaj				= array();
			$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Yorum silme işlemi tamamlanamadı.');
		}
		$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
		redirect('yonetim/urunler/review/lists');
	}

	function add()
	{
		$form_type = array('type' => 'add');
		$this->get_form($form_type);
	}

	function edit($review_id)
	{
		$form_type = array('type' => 'edit', 'review_id' => $review_id);
		$this->get_form($form_type);
	}

	private function get_form($get_values)
	{
		if($get_values['type'] == 'edit') {
			$review_id = $get_values['review_id'];
			$data['review_id'] = $review_id;
			$review_info = $this->review_review_model->get_review_by_id($review_id);
		}

		$language_id				= get_language('language_id', config('site_ayar_yonetim_dil'));
		$typ_title					= 'Yorum';
		$data['title']				= ucwords($typ_title);
		$data['cancel_url']			= 'urunler/review/lists';
		if(isset($review_info)) {
			$data['action_url']		= 'urunler/review/edit/' . $review_id;
		}

		$val = $this->form_validation;

		/* Genel */
		$val->set_rules('product_id', 'Ürün No', 'trim|numeric|required|xss_clean');
		$val->set_rules('user_id', 'Üye No', 'trim|numeric|xss_clean');
		$val->set_rules('email', 'E-Posta', 'trim|valid_email|xss_clean');
		$val->set_rules('author', 'Yazar', 'trim|required|xss_clean');
		$val->set_rules('text', 'Yorum', 'trim|required|xss_clean');
		$val->set_rules('rating', 'Oy', 'trim|required|numeric|xss_clean');
		$val->set_rules('status', 'Durum', 'trim|required|numeric|xss_clean');
		/* Genel */

		if ($val->run() == FALSE) {
			$data['errors'] = validation_errors();
		} else {
			if(isset($review_info)) {
				$check = $this->review_review_model->update_review($review_id, $this->input->post());
				if(!$check) {
					$yonetim_mesaj				= array();
					$yonetim_mesaj['durum'] 	= '2';	// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('Düzenlemek istediğiniz yorumda sorun oluştu! Herhangi bir değişiklik yapmamış olabilirsiniz!');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/urunler/review/lists');
				} else {
					$yonetim_mesaj				= array();
					$yonetim_mesaj['durum'] 	= '1';	// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('Yorum düzenleme işleminiz başarılı bir şekilde gerçekleşti.');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/urunler/review/lists');
				}
			}
		}

		/* Genel Tab */

		if($this->input->post('product_id')) {
			$data['product_id'] = $this->input->post('product_id');
		} elseif(isset($review_info)) {
			$data['product_id'] = $review_info->product_id;
		} else {
			$data['product_id'] = '';
		}

		$product_info = $this->product_product_model->get_product_and_desc_by_id($data['product_id']);
		$data['product_info'] = $product_info;

		$get_products = $this->product_product_model->get_product_by_all_s_name_and_id();
		$data['products'] = $get_products;

		if($this->input->post('user_id')) {
			$data['user_id'] = $this->input->post('user_id');
		} elseif(isset($review_info)) {
			$data['user_id'] = $review_info->user_id;
		} else {
			$data['user_id'] = '';
		}

		if($this->input->post('email')) {
			$data['email'] = $this->input->post('email');
		} elseif(isset($review_info)) {
			$data['email'] = $review_info->email;
		} else {
			$data['email'] = '';
		}

		if($this->input->post('author')) {
			$data['author'] = $this->input->post('author');
		} elseif(isset($review_info)) {
			$data['author'] = $review_info->author;
		} else {
			$data['author'] = '';
		}

		if($this->input->post('text')) {
			$data['text'] = $this->input->post('text');
		} elseif(isset($review_info)) {
			$data['text'] = $review_info->text;
		} else {
			$data['text'] = '';
		}

		if($this->input->post('rating')) {
			$data['rating'] = $this->input->post('rating');
		} elseif(isset($review_info)) {
			$data['rating'] = $review_info->rating;
		} else {
			$data['rating'] = '';
		}

		if($this->input->post('status')) {
			$data['status'] = $this->input->post('status');
		} elseif(isset($review_info)) {
			$data['status'] = $review_info->status;
		} else {
			$data['status'] = '1';
		}

		if(isset($review_info)) {
			$data['date_added'] = standard_date('DATE_TR', mysql_to_unix($review_info->date_added), 'tr');
		} else {
			$data['date_added'] = standard_date('DATE_TR', time(), 'tr');
		}

		if(isset($review_info)) {
			$data['date_modified'] = standard_date('DATE_TR', mysql_to_unix($review_info->date_modified), 'tr');
		} else {
			$data['date_modified'] = standard_date('DATE_TR', time(), 'tr');
		}

		/* Genel Tab */

		$this->load->view('yonetim/urunler/review_form_view' , $data);
	}
}

/* End of file class_name.php */
/* Location: ./application/controllers/class_name.php */