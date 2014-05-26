<?php

if (!defined('BASEPATH'))
	exit('No direct script access');

class product extends Admin_Controller
{

	var $izin_linki;

	function __construct() {
		parent::__construct();
		$this->load->model('yonetim/urunler/product_product_model');
		$this->load->model('yonetim/urunler/product_category_model');
		$this->load->model('yonetim/urunler/manufacturer_manufacturer_model');
		$this->izin_linki = 'product/product';

		$this->load->library('form_validation');
	}

	public function index() {
		redirect(yonetim_url('urunler/product/lists'));
	}

	public function lists($sort_link = 'pd.name-desc', $filter = 'p.status|]', $page = 0) {
		sayfa_kontrol($this->izin_linki, 'yonetim/urunler/product/lists/' . $sort_link . '/' . $filter . '/' . $page);

		$sort_link_e = explode('-', $sort_link);
		$sort = $sort_link_e[0];
		$order = $sort_link_e[1];

		$data = array();
		$typ_title = 'Ürün';
		$data['title'] = ucwords($typ_title);
		$data['add_url'] = 'urunler/product/add';
		$data['delete_url'] = 'urunler/product/delete';
		$data['products'] = array();
		$products = $this->product_product_model->get_products_by_all($page, $sort, $order, $filter, $sort_link);
		if ($products) {
			foreach ($products->result() as $result) {
				$action = array();
				$action[] = array(
					'text' => 'Düzenle',
					'href' => 'yonetim/urunler/product/edit/' . $result->product_id
				);
				$action[] = array(
					'text' => 'Sil',
					'href' => 'yonetim/urunler/product/delete_one/' . $result->product_id
				);
				$data['products'][] = array(
					'product_id' => $result->product_id,
					'sort_order' => $result->sort_order,
					'status' => $result->status,
					'quantity' => $result->quantity,
					'date_added' => $result->date_added,
					'date_modified' => $result->date_modified,
					'image' => $result->image,
					'name' => $result->name,
					'model' => $result->model,
					'meta_keywords' => $result->meta_keywords,
					'meta_description' => $result->meta_description,
					'seo' => $result->seo,
					'show_homepage' => $result->show_homepage,
					'selected' => ($this->input->post('selected') && in_array($result->product_id, $this->input->post('selected'))),
					'action' => $action
				);
			}
		}

		$data['sort_link'] = $sort_link;
		$data['filt_link'] = $filter;
		$data['page_link'] = $page;

		$sort_lnk_e = explode('-', $sort_link);
		$data['sort'] = $sort_lnk_e[0];
		$data['order'] = $sort_lnk_e[1];

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

		$_c_array = explode(', ', get_fields_from_table('product', 'p.'));
		$_cd_array = explode(', ', get_fields_from_table('product_description', 'pd.'));
		$_filter_allowed = array_merge($_c_array, $_cd_array);

		if ($filter != 'p.status|]') {
			$filter_e = explode(']', $filter);
			foreach ($filter_e as $yaz) {
				if ($yaz != '') {
					if (preg_match('/|/i', $yaz)) {
						$explode = explode('|', $yaz);
						if ((isset($explode[0]) AND $explode[0] != '') AND (isset($explode[1]) AND $explode[1] != '')) {
							if (in_array($explode[0], $_filter_allowed)) {
								$data['filter_' . str_replace('.', '_', $explode[0])] = $explode[1];
							}
						}
					}
				}
			}
		}

		$_stock_types = array('seciniz' => ' - Seçiniz - ');
		$stock_types = $this->yonetim_model->tanimlar_listele('stok_birim', array('tanimlar_adi' => 'asc'));
		foreach ($stock_types->result() as $stock_type) {
			$_stock_types[$stock_type->tanimlar_id] = $stock_type->tanimlar_adi;
		}
		$data['stock_types'] = $_stock_types;

		$taxes = array('seciniz' => ' - Seçiniz - ');
		$taxes['00'] = '0';
		$taxes['01'] = '1';
		$taxes['02'] = '2';
		$taxes['03'] = '3';
		$taxes['04'] = '4';
		$taxes['05'] = '5';
		$taxes['06'] = '6';
		$taxes['07'] = '7';
		$taxes['08'] = '8';
		$taxes['09'] = '9';
		for ($i = 10; $i <= 99; $i++) {
			$taxes[$i] = $i;
		}
		$data['taxes'] = $taxes;

		$_manufacturers = $this->manufacturer_manufacturer_model->get_manufacturer_by_all();
		$manufacturers = array('seciniz' => ' - Seçiniz - ', '0' => ' --- Yok --- ');
		if ($_manufacturers) {
			foreach ($_manufacturers as $_manufacturer) {
				$manufacturers[$_manufacturer->manufacturer_id] = $_manufacturer->name;
			}
		}
		$data['manufacturers'] = $manufacturers;

		$categories = $this->product_category_model->get_category_by_parent(0, '-1');
		$allowed_categories = array();
		if ($categories) {
			foreach ($categories as $category) {
				if ($category['status']) {
					$allowed_categories[] = $category;
				}
			}
		}
		$data['categories'] = $allowed_categories;

		$this->load->view('yonetim/urunler/product_list_view', $data);
	}

	function delete() {
		sayfa_kontrol($this->izin_linki, 'yonetim/urunler/product/delete');

		$val = $this->validation;


		$rules['selected'] = "trim|xss_clean|required";
		$fields['selected'] = "Ürün No";

		$val->set_fields($fields);
		$val->set_rules($rules);

		if ($val->run() == TRUE) {
			$kontrol = $this->product_product_model->product_delete_by_id($val->selected);
			if ($kontrol) {
				$yonetim_mesaj = array();
				$yonetim_mesaj['durum'] = '1'; // 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj'] = array('Ürün silme işlemi tamamlandı.');
			} else {
				$yonetim_mesaj = array();
				$yonetim_mesaj['durum'] = '2'; // 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj'] = array('Ürün silme işlemi tamamlanamadı.');
			}
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/urunler/product/lists');
		} else {
			$yonetim_mesaj = array();
			$yonetim_mesaj['durum'] = '2'; // 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj'] = array('Herhangi bir ürün seçilemediği için ürün silme işlemi tamamlanamadı.');
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/urunler/product/lists');
		}
	}

	function delete_one($product_id) {
		$check = $this->product_product_model->product_delete_by_id($product_id);
		if ($check) {
			$yonetim_mesaj = array();
			$yonetim_mesaj['durum'] = '1'; // 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj'] = array('Ürün silme işlemi tamamlandı.');
		} else {
			$yonetim_mesaj = array();
			$yonetim_mesaj['durum'] = '2'; // 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj'] = array('Ürün silme işlemi tamamlanamadı.');
		}
		$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
		redirect('yonetim/urunler/product/lists');
	}

	function add() {
		$form_type = array('type' => 'add');
		$this->get_form($form_type);
	}

	function edit($product_id) {
		$form_type = array('type' => 'edit', 'product_id' => $product_id);
		$this->get_form($form_type);
	}

	public function batch_edit() {
		$sonuc = NULL;
		$sonuc['success'] = '';
		$sonuc['error'] = '';

		$val = $this->form_validation;

		if ($this->input->post('price')) {
			$val->set_rules('price', 'Satış Fiyatı', 'trim|required|xss_clean');
			$val->set_rules('price_type', 'Fiyat Tipi', 'trim|required|xss_clean');
		} else {
			$val->set_rules('price', 'Satış Fiyatı', 'trim|xss_clean');
			$val->set_rules('price_type', 'Fiyat Tipi', 'trim|xss_clean');
		}

		if ($this->input->post('quantity')) {
			$val->set_rules('quantity', 'Miktar', 'trim|required|xss_clean');
			$val->set_rules('stock_type', 'Miktar Tipi', 'trim|required|xss_clean');
		} else {
			$val->set_rules('quantity', 'Miktar', 'trim|xss_clean');
			$val->set_rules('stock_type', 'Miktar Tipi', 'trim|xss_clean');
		}

		$val->set_rules('tax', 'Vergi Oranı', 'trim|xss_clean');
		$val->set_rules('selected', 'Seçili Ürünler', 'required|xss_clean');

		$val->set_rules('feature_status', 'Özellik Tabını Göster', 'trim|xss_clean');
		$val->set_rules('status', 'Durum', 'trim|xss_clean');
		$val->set_rules('subtract', 'Stoktan Düş', 'trim|xss_clean');
		$val->set_rules('manufacturer_id', 'Marka', 'trim|xss_clean');
		$val->set_rules('product_category', 'Kategoriler', 'xss_clean');

		$val->set_error_delimiters('', '');

		if ($val->run() === FALSE) {
			$sonuc['error'] = validation_errors();
		} else {
			$kontrol = $this->product_product_model->bacth_update_product($this->input->post());
			if ($kontrol['durum'] === TRUE) {
				$sonuc['success'] = $kontrol['mesaj'];
			} else {
				$sonuc['error'] = $kontrol['mesaj'];
			}
		}

		exit(json::encode($sonuc));
	}

	public function price_control($price) {
		$val = $this->form_validation;
		if ($price == '0') {
			$val->set_message('price_control', '%s alanına sıfırdan büyük sayı girilmelidir.');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	private function get_form($get_values) {
		if ($get_values['type'] == 'edit') {
			$product_id = $get_values['product_id'];
			$data['product_id'] = $product_id;
			$product_info = $this->product_product_model->get_product_by_id($product_id);
		}

		$language_id = get_language('language_id', config('site_ayar_yonetim_dil'));
		$typ_title = 'Ürün';
		$data['title'] = ucwords($typ_title);
		$data['cancel_url'] = 'urunler/product/lists';
		if (isset($product_info)) {
			$data['action_url'] = 'urunler/product/edit/' . $product_id;
		} else {
			$data['action_url'] = 'urunler/product/add/';
		}

		/* languages */
		$languages = $this->fonksiyonlar->get_languages();
		$allowed_languages = array();
		foreach ($languages as $language) {
			if ($language['status']) {
				$send_language['language_id'] = $language['language_id'];
				$send_language['name'] = $language['name'];
				$send_language['code'] = $language['code'];
				$send_language['locale'] = $language['locale'];
				$send_language['image'] = $language['image'];
				$send_language['directory'] = $language['directory'];
				$send_language['sort_order'] = $language['sort_order'];
				$send_language['status'] = $language['status'];
				$allowed_languages[$language['language_id']] = $send_language;
			}
		}
		$data['languages'] = $allowed_languages;
		/* languages */

		$val = $this->form_validation;

		/* Genel */
		$lang_array_fields = array(
			'name' => array('title' => 'Ürün Başlık', 'rules' => 'required|max_length[255]|xss_clean'),
			'meta_keywords' => array('title' => 'Meta Keywords', 'rules' => 'max_length[255]|xss_clean'),
			'meta_description' => array('title' => 'Meta Description', 'rules' => 'max_length[255]|xss_clean'),
			'seo' => array('title' => 'Seo Adresi', 'rules' => 'max_length[255]|xss_clean'),
			'info' => array('title' => 'Ürün Info', 'rules' => 'trim'),
			'description' => array('title' => 'Ürün Açıklama', 'rules' => 'trim'),
			'video' => array('title' => 'Video Embed Kodu', 'rules' => 'trim')
		);
		foreach ($data['languages'] as $_lang) {
			foreach ($lang_array_fields as $key => $value) {
				$val->set_rules('product_description[' . $_lang['language_id'] . '][' . $key . ']', $_lang['name'] . ' ' . $value['title'], $value['rules']);
			}
		}
		/* Genel */

		/* Detaylar */
		$val->set_rules('quantity', 'Miktar', 'trim|required|numeric|xss_clean');
		$val->set_rules('stock_type', 'Miktar Tipi', 'trim|required|numeric|xss_clean');
		$val->set_rules('model', 'Ürün Kodu', 'trim|xss_clean');
		$val->set_rules('price', 'Satış Fiyatı', 'trim|required|callback_price_control|xss_clean');
		$val->set_rules('price_type', 'Fiyat Tipi', 'trim|required|numeric|xss_clean');
		$val->set_rules('date_available', 'Geçerlilik Süresi', 'trim|required|xss_clean');
		$val->set_rules('tax', 'Vergi Oranı', 'trim|required|numeric|xss_clean');
		$val->set_rules('subtract', 'Stoktan Düş', 'trim|required|numeric|xss_clean');
		$val->set_rules('hizli', 'Hizli Gönderi', 'trim|required|numeric|xss_clean');
		$val->set_rules('status', 'Durum', 'trim|required|numeric|xss_clean');
		$val->set_rules('sort_order', 'Sıralama', 'trim|required|numeric|xss_clean');
		$val->set_rules('show_homepage', 'Anasayfada Göster', 'trim|required|numeric|xss_clean');
		$val->set_rules('new_product', 'Yeni Ürün', 'trim|required|numeric|xss_clean');
		/* Detaylar */

		/* Bağlantı Seçimleri */
		$val->set_rules('manufacturer_id', 'Marka', 'trim|required|numeric|xss_clean');
		$val->set_rules('product_category', 'Kategoriler', 'xss_clean');
		$val->set_rules('product_related', 'Benzer Ürünler', 'xss_clean');
		/* Bağlantı Seçimleri */

		/* Resimler */
		$val->set_rules('image', 'Ürün Resmi', 'trim|xss_clean');
		$val->set_rules('product_images', 'Ürün Resimleri', 'xss_clean');
		/* Resimler */

		/* Seçenekler */
		$val->set_rules('product_option', 'Seçenekler', 'xss_clean');
		/* Seçenekler */

		/* İndirim */
		$val->set_rules('product_discount', 'İndirimler', 'xss_clean');
		/* İndirim */

		/* Kampanyalar */
		$val->set_rules('product_special', 'Kampanyalar', 'xss_clean');
		/* Kampanyalar */

		/* Kargo Bilgileri */
		$val->set_rules('cargo_required', 'Kargo Gerekli', 'trim|required|numeric|xss_clean');
		$val->set_rules('cargo_multiply_required', 'Kargo Ücreti Adet Çarpımı', 'trim|required|numeric|xss_clean');
		$val->set_rules('length', 'Uzunluk', 'trim|numeric|xss_clean');
		$val->set_rules('length_class_id', 'Uzunluk', 'trim|required|numeric|xss_clean');
		$val->set_rules('width', 'Genişlik', 'trim|numeric|xss_clean');
		$val->set_rules('height', 'Yükseklik', 'trim|numeric|xss_clean');
		$val->set_rules('weight', 'Ağırlık', 'trim|numeric|xss_clean');
		$val->set_rules('weight_class_id', 'Ağırlık', 'trim|required|numeric|xss_clean');
		/* Kargo Bilgileri */

		if ($val->run() == FALSE) {
			$data['errors'] = validation_errors();
		} else {
			if (isset($product_info)) {
				$_product_info = $this->product_product_model->get_product_and_desc_by_id($product_id);
				$check = $this->product_product_model->update_product($product_id, $this->input->post(), $_product_info);
				if (!$check) {
					$yonetim_mesaj = array();
					$yonetim_mesaj['durum'] = '2'; // 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj'] = array('Düzenlemek istediğiniz üründe sorun oluştu! Herhangi bir değişiklik yapmamış olabilirsiniz!');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/urunler/product/lists');
				} else {
					$yonetim_mesaj = array();
					$yonetim_mesaj['durum'] = '1'; // 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj'] = array('Ürün düzenleme işleminiz başarılı bir şekilde gerçekleşti.');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/urunler/product/lists');
				}
			} else {
				$check = $this->product_product_model->add_product($this->input->post());
				if (!$check) {
					$yonetim_mesaj = array();
					$yonetim_mesaj['durum'] = '2'; // 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj'] = array('Eklemek istediğiniz üründe sorun oluştu!');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/urunler/product/lists');
				} else {
					$yonetim_mesaj = array();
					$yonetim_mesaj['durum'] = '1'; // 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj'] = array('Ürün ekleme işleminiz başarılı bir şekilde gerçekleşti.');
					$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
					redirect('yonetim/urunler/product/lists');
				}
			}
		}

		/* Genel Tab */
		if ($this->input->post('product_description')) {
			$data['product_description'] = $this->input->post('product_description');
		} elseif (isset($product_info)) {
			$data['product_description'] = $this->product_product_model->get_product_description_by_id($product_id);
		} else {
			$data['product_description'] = array();
		}
		/* Genel Tab */

		/* Detaylar Tab */
		if ($this->input->post('quantity')) {
			$data['quantity'] = $this->input->post('quantity');
		} elseif (isset($product_info)) {
			$data['quantity'] = $product_info->quantity;
		} else {
			$data['quantity'] = '0';
		}

		$_stock_types = array();
		$stock_types = $this->yonetim_model->tanimlar_listele('stok_birim', array('tanimlar_adi' => 'asc'));
		foreach ($stock_types->result() as $stock_type) {
			$_stock_types[$stock_type->tanimlar_id] = $stock_type->tanimlar_adi;
		}
		$data['stock_types'] = $_stock_types;

		if ($this->input->post('stock_type')) {
			$data['stock_type'] = $this->input->post('stock_type');
		} elseif (isset($product_info)) {
			$data['stock_type'] = $product_info->stock_type;
		} else {
			$data['stock_type'] = '';
		}

		if ($this->input->post('model')) {
			$data['model'] = $this->input->post('model');
		} elseif (isset($product_info)) {
			$data['model'] = $product_info->model;
		} else {
			$data['model'] = $this->product_product_model->get_product_uuid();
		}

		if ($this->input->post('price')) {
			$data['price'] = $this->input->post('price');
		} elseif (isset($product_info)) {
			$data['price'] = $product_info->price;
		} else {
			$data['price'] = '0';
		}

		if ($this->input->post('price_type')) {
			$data['price_type'] = $this->input->post('price_type');
		} elseif (isset($product_info)) {
			$data['price_type'] = $product_info->price_type;
		} else {
			$data['price_type'] = '';
		}

		if ($this->input->post('date_available')) {
			$data['date_available'] = $this->input->post('date_available');
		} elseif (isset($product_info)) {
			$data['date_available'] = date('Y-m-d', $product_info->date_available);
		} else {
			$data['date_available'] = date('Y-m-d', strtotime("+5 years"));
		}

		$taxes = array();
		$taxes['00'] = '0';
		$taxes['01'] = '1';
		$taxes['02'] = '2';
		$taxes['03'] = '3';
		$taxes['04'] = '4';
		$taxes['05'] = '5';
		$taxes['06'] = '6';
		$taxes['07'] = '7';
		$taxes['08'] = '8';
		$taxes['09'] = '9';
		for ($i = 10; $i <= 99; $i++) {
			$taxes[$i] = $i;
		}
		$data['taxes'] = $taxes;

		if ($this->input->post('tax')) {
			$data['tax'] = $this->input->post('tax');
		} elseif (isset($product_info)) {
			$data['tax'] = $product_info->tax;
		} else {
			$data['tax'] = '18';
		}

		if ($this->input->post('subtract')) {
			$data['subtract'] = $this->input->post('subtract');
		} elseif (isset($product_info)) {
			$data['subtract'] = $product_info->subtract;
		} else {
			$data['subtract'] = '1';
		}

		if ($this->input->post('hizli')) {
			$data['hizli'] = $this->input->post('hizli');
		} elseif (isset($product_info)) {
			$data['hizli'] = $product_info->hizli_gonder;
		} else {
			$data['hizli'] = '1';
		}

		if ($this->input->post('status')) {
			$data['status'] = $this->input->post('status');
		} elseif (isset($product_info)) {
			$data['status'] = $product_info->status;
		} else {
			$data['status'] = '1';
		}

		if ($this->input->post('sort_order')) {
			$data['sort_order'] = $this->input->post('sort_order');
		} elseif (isset($product_info)) {
			$data['sort_order'] = $product_info->sort_order;
		} else {
			$data['sort_order'] = '0';
		}

		if ($this->input->post('feature_status')) {
			$data['feature_status'] = $this->input->post('feature_status');
		} elseif (isset($product_info)) {
			$data['feature_status'] = $product_info->feature_status;
		} else {
			$data['feature_status'] = '1';
		}

		if ($this->input->post('show_homepage')) {
			$data['show_homepage'] = $this->input->post('show_homepage');
		} elseif (isset($product_info)) {
			$data['show_homepage'] = $product_info->show_homepage;
		} else {
			$data['show_homepage'] = '0';
		}

		if ($this->input->post('new_product')) {
			$data['new_product'] = $this->input->post('new_product');
		} elseif (isset($product_info)) {
			$data['new_product'] = $product_info->new_product;
		} else {
			$data['new_product'] = '1';
		}
		/* Detaylar Tab */

		/* Bağlantı Seçimleri */
		$_manufacturers = $this->manufacturer_manufacturer_model->get_manufacturer_by_all();
		$manufacturers = array('0' => ' --- Yok --- ');
		if ($_manufacturers) {
			foreach ($_manufacturers as $_manufacturer) {
				$manufacturers[$_manufacturer->manufacturer_id] = $_manufacturer->name;
			}
		}
		$data['manufacturers'] = $manufacturers;
		if ($this->input->post('manufacturer_id')) {
			$data['manufacturer_id'] = $this->input->post('manufacturer_id');
		} elseif (isset($product_info)) {
			$data['manufacturer_id'] = $product_info->manufacturer_id;
		} else {
			$data['manufacturer_id'] = '0';
		}

		/* categories */
		$categories = $this->product_category_model->get_category_by_parent(0, '-1');
		//$categories					= $this->product_category_model->get_final_child_categories();
		$allowed_categories = array();
		if ($categories) {
			foreach ($categories as $category) {
				if ($category['status']) {
					$allowed_categories[] = $category;
				}
			}
		}
		$data['categories'] = $allowed_categories;
		if ($this->input->post('product_category')) {
			$data['product_category'] = $this->input->post('product_category');
		} elseif (isset($product_info)) {
			$data['product_category'] = $this->product_product_model->get_product_categories_by_id($product_info->product_id);
		} else {
			$data['product_category'] = array();
		}
		/* categories */
		if ($this->input->post('product_related')) {
			$data['product_related'] = $this->input->post('product_related');
		} elseif (isset($product_info)) {
			$data['product_related'] = $this->product_product_model->get_product_related_by_id($product_info->product_id);
		} else {
			$data['product_related'] = array();
		}
		/* Bağlantı Seçimleri */

		/* Resimler */
		if ($this->input->post('image')) {
			$data['image'] = $this->input->post('image');
		} elseif (isset($product_info)) {
			$data['image'] = ($product_info->image != '') ? $product_info->image : 'no-image.jpg';
		} else {
			$data['image'] = 'no-image.jpg';
		}

		if (isset($product_info)) {
			$data['preview'] = show_image($product_info->image, 100, 100);
		} else {
			$data['preview'] = show_image('no-image.jpg', 100, 100);
		}

		$data['no_image'] = show_image('no-image.jpg', 100, 100);

		$data['product_images'] = array();
		if ($this->input->post('product_images')) {
			foreach ($this->input->post('product_images') as $result) {
				if ($result AND file_exists(DIR_IMAGE . $result)) {
					$data['product_images'][] = array(
						'preview' => show_image($result, 100, 100),
						'file' => $result
					);
				} else {
					$data['product_images'][] = array(
						'preview' => show_image('no-image.jpg', 100, 100),
						'file' => $result
					);
				}
			}
		} elseif (isset($product_info)) {
			$results = $this->product_product_model->get_product_images_by_id($product_info->product_id);
			if ($results) {
				foreach ($results as $result) {
					if ($result->image AND file_exists(DIR_IMAGE . $result->image)) {
						$data['product_images'][] = array(
							'preview' => show_image($result->image, 100, 100),
							'file' => $result->image
						);
					} else {
						$data['product_images'][] = array(
							'preview' => show_image('no_image.jpg', 100, 100),
							'file' => $result->image
						);
					}
				}
			}
		}
		/* Resimler */

		/* Seçenekler */
		$data['language_id'] = $language_id;
		if ($this->input->post('product_option')) {
			$product_options = $this->input->post('product_option');
		} elseif (isset($product_info)) {
			$product_options = $this->product_product_model->get_product_options($product_info->product_id);
		} else {
			$product_options = array();
		}

		$data['product_options'] = array();
		foreach ($product_options as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox') {
				$product_option_value_data = array();
				foreach ($product_option['product_option_value'] as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id' => $product_option_value['option_value_id'],
						'quantity' => $product_option_value['quantity'],
						'subtract' => $product_option_value['subtract'],
						'price' => $product_option_value['price'],
						'price_prefix' => $product_option_value['price_prefix']
					);
				}
				$data['product_options'][] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id' => $product_option['option_id'],
					'name' => $product_option['name'],
					'type' => $product_option['type'],
					'product_option_value' => $product_option_value_data,
					'required' => $product_option['required']
				);
			} else {
				$data['product_options'][] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id' => $product_option['option_id'],
					'name' => $product_option['name'],
					'type' => $product_option['type'],
					'option_value' => $product_option['option_value'],
					'required' => $product_option['required'],
					'character_limit' => isset($product_option['character_limit']) ? $product_option['character_limit'] : 0
				);
			}
		}
		/* Seçenekler */

		/* İndirim */
		$this->load->model('yonetim/customer_management/customer_group_model');
		$data['customer_groups'] = $this->customer_group_model->get_groups_by_parent_id(1);
		if ($this->input->post('product_discount')) {
			$data['product_discounts'] = $this->input->post('product_discount');
		} elseif (isset($product_info)) {
			$data['product_discounts'] = array();
			$product_discounts = $this->product_product_model->get_product_discounts_by_id($product_info->product_id);
			if ($product_discounts) {
				$data['product_discounts'] = $product_discounts;
			}
		} else {
			$data['product_discounts'] = array();
		}
		/* İndirim */

		/* Kampanya */
		$this->load->model('yonetim/customer_management/customer_group_model');
		$data['customer_groups'] = $this->customer_group_model->get_groups_by_parent_id(1);
		if ($this->input->post('product_special')) {
			$data['product_specials'] = $this->input->post('product_special');
		} elseif (isset($product_info)) {
			$data['product_specials'] = array();
			$product_specials = $this->product_product_model->get_product_specials_by_id($product_info->product_id);
			if ($product_specials) {
				$data['product_specials'] = $product_specials;
			}
		} else {
			$data['product_specials'] = array();
		}
		/* Kampanya */

		/* Özellik Tab */

		/* Özellik Tab */

		/* Kargo Bilgileri */
		if ($this->input->post('cargo_required')) {
			$data['cargo_required'] = $this->input->post('cargo_required');
		} elseif (isset($product_info)) {
			$data['cargo_required'] = $product_info->cargo_required;
		} else {
			$data['cargo_required'] = '0';
		}

		if ($this->input->post('cargo_multiply_required')) {
			$data['cargo_multiply_required'] = $this->input->post('cargo_multiply_required');
		} elseif (isset($product_info)) {
			$data['cargo_multiply_required'] = $product_info->cargo_multiply_required;
		} else {
			$data['cargo_multiply_required'] = '0';
		}

		if ($this->input->post('length')) {
			$data['length'] = $this->input->post('length');
		} elseif (isset($product_info)) {
			$data['length'] = $product_info->length;
		} else {
			$data['length'] = '0.00';
		}

		if ($this->input->post('width')) {
			$data['width'] = $this->input->post('width');
		} elseif (isset($product_info)) {
			$data['width'] = $product_info->width;
		} else {
			$data['width'] = '0.00';
		}

		if ($this->input->post('height')) {
			$data['height'] = $this->input->post('height');
		} elseif (isset($product_info)) {
			$data['height'] = $product_info->height;
		} else {
			$data['height'] = '0.00';
		}

		if ($this->input->post('weight')) {
			$data['weight'] = $this->input->post('weight');
		} elseif (isset($product_info)) {
			$data['weight'] = $product_info->weight;
		} else {
			$data['weight'] = '0.00';
		}

		$length_class_query = $this->yonetim_model->tanimlar_listele('uzunluk');
		$length_class = array();
		foreach ($length_class_query->result() as $length) {
			$length_class[$length->tanimlar_id] = $length->tanimlar_adi;
		}
		$data['length_class'] = $length_class;

		if ($this->input->post('length_class_id')) {
			$data['length_class_id'] = $this->input->post('length_class_id');
		} elseif (isset($product_info)) {
			$data['length_class_id'] = $product_info->length_class_id;
		} else {
			$data['length_class_id'] = '';
		}

		$weight_class = array();
		$weight_class_query = $this->yonetim_model->tanimlar_listele('agirlik');
		foreach ($weight_class_query->result() as $weight) {
			$weight_class[$weight->tanimlar_id] = $weight->tanimlar_adi;
		}
		$data['weight_class'] = $weight_class;

		if ($this->input->post('weight_class_id')) {
			$data['weight_class_id'] = $this->input->post('weight_class_id');
		} elseif (isset($product_info)) {
			$data['weight_class_id'] = $product_info->weight_class_id;
		} else {
			$data['weight_class_id'] = '';
		}
		/* Kargo Bilgileri */

		$this->load->view('yonetim/urunler/product_form_view', $data);
	}

	public function category($category_id = 0) {
		$product_data = array();
		$results = $this->product_product_model->get_products_by_category_id($category_id);
		if ($results) {
			foreach ($results as $result) {
				$product_data[] = array(
					'product_id' => $result->product_id,
					'name' => $result->name,
					'model' => $result->model
				);
			}
		}
		$this->output->set_output(Json::encode($product_data));
	}

	public function show_homepage($product_id, $status) {
		$this->product_product_model->change_homepage_position($product_id, $status);
		redirect($this->input->get('redirect'));
	}

	public function option() {
		$this->load->model('yonetim/urunler/product_option_model');
		$output = '';
		$results = $this->product_option_model->get_option_values_by_id((int) $this->input->get('option_id'));

		foreach ($results as $result) {
			$output .= '<option value="' . $result['option_value_id'] . '"';

			if ($this->input->get('option_value_id') AND ($this->input->get('option_value_id') == $result['option_value_id'])) {
				$output .= ' selected="selected"';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		$this->output->set_output($output);
	}

	public function get_features() {
		$product_category = array();
		$product_category = $this->input->post('product_category');

		$product_id = $this->input->post('product_id');
		if ($product_id != '') {
			$_product_id = $product_id;
		} else {
			$_product_id = FALSE;
		}

		$json = array();
		$json['error'] = '';
		$json['data'] = '';

		if ($product_category) {
			$features = $this->product_product_model->get_features_by_category_ids_content($product_category, $_product_id);
			$json['data'] = $features;
		} else {
			$json['error'] = 'Kategoriler Gerekli';
		}

		exit(Json::encode($json));
	}

	public function autocomplete() {
		$json = array();
		if ($this->input->post('filter_name')) {
			$page = 0;
			$filter = 'pd.name|' . $this->input->post('filter_name');
			$sort = 'p.product_id';
			$order = 'desc';
			$results = $this->product_product_model->get_products_by_no_pag_all($page, $sort, $order, $filter);

			foreach ($results as $result) {
				$json[] = array(
					'product_id' => $result['product_id'],
					'name' => html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'),
					'model' => $result['model'],
					'price' => $result['price']
				);
			}
		}

		$this->output->set_output(Json::encode($json));
	}

}

/* End of file class_name.php */
/* Location: ./application/controllers/class_name.php */