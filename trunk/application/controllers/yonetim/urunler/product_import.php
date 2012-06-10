<?php 

if (! defined('BASEPATH')) exit('No direct script access');

class product_import extends Admin_Controller {

	var $izin_linki;
	protected $variables = array();

	function __construct() 
	{
		parent::__construct();
		$this->load->model('yonetim/urunler/product_product_model');
		$this->izin_linki = 'product/product_import';

		$this->load->library('form_validation');
		$this->load->helper('file');

		$this->_set_variable();
		$this->_get_required_variables();
	}

	public function index()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/urunler/product_import');

		if($_FILES OR $_POST) {
			$check = $this->import_csv_file('csv_file');
			if($check['status'] === TRUE) {
				redirect('yonetim/urunler/product_import/select_column');
			} else {
				$yonetim_mesaj 			= array();
				$yonetim_mesaj['durum'] = '2';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']	= array($check['message']);
				$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
				redirect('yonetim/urunler/product_import');
			}
		} else {
			$this->load->view('yonetim/urunler/product_import_view');
		}
	}

	protected function import_csv_file($filename = 'userfile')
	{
		$confpath = 'upload/csv/';
		$config['upload_path'] = './' . $confpath;
		$config['allowed_types'] = 'csv';
		$config['max_size']	= '51200';
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload($filename)) {
			$send['status'] = false;
			$send['message'] = $this->upload->display_errors('', '');
		} else {
			$upload_data = $this->upload->data();
			$file = $upload_data['file_name'];
			$this->session->set_userdata('csv_file_url', $confpath . $file);

			foreach(glob($config['upload_path'] . '*.csv') as $deneme) {
				if($deneme != $config['upload_path'] . $file) {
					@unlink($deneme);
				}
			}

			$send['status']		= TRUE;
			$send['message']	= NULL;
		}
		return $send;
	}

	function convert_csv_to_mysql()
	{
		$send_datas				= array();
		$errors					= '';
		$success				= '';

		$line					= $this->input->post('line');
		$column					= $this->input->post('column');

		$model					= isset($column['model']) ? $column['model'] : FALSE;
		$languages				= get_languages();
		$name					= array();
		$description			= array();
		foreach($languages as $language) {
			$name[$language['language_id']] = isset($column['name_' . $language['language_id']]) ? $column['name_' . $language['language_id']] : FALSE;
			$description[$language['language_id']] = isset($column['description_' . $language['language_id']]) ? $column['description_' . $language['language_id']] : FALSE;
		}

		$name					= $name;
		$description			= $description;
		$price_types			= array('1' => 'TL', '2' => '$', '3' => '€');
		$_current_price_type	= array();
		foreach ($price_types as $type_key => $type_value) {
			if(isset($column['price_' . $type_key])) {
				$_current_price_type[$type_key]	= isset($column['price_' . $type_key]) ? $column['price_' . $type_key] : FALSE;
			}
		}

		if($_current_price_type) {
			$price				= current($_current_price_type);
			$price_type			= key($_current_price_type);
		} else {
			$price				= '0.01';
			$price_type			= '1';
		}

		$tax					= isset($column['tax']) ? $column['tax'] : FALSE;
		$quantity				= isset($column['quantity']) ? $column['quantity'] : FALSE;
		$category				= isset($column['category']) ? $column['category'] : FALSE;
		$sub_category			= isset($column['sub_category']) ? $column['sub_category'] : FALSE;
		$manufacturer			= isset($column['manufacturer']) ? $column['manufacturer'] : FALSE;
		$image					= isset($column['image']) ? $column['image'] : FALSE;

		// Gerekliler
		if(($model AND $model != '') AND (is_array($name) AND count($name) > 0) AND ($price AND $price != ''))
		{
			$price												= $this->float($price);

			/* İnsert Verileri */
			$insert_datas->model								= $model;
			$insert_datas->name									= $name;
			$insert_datas->description							= $description;
			$insert_datas->price								= $price;
			$insert_datas->price_type							= $price_type;

			if($tax AND $tax != '') {
				$insert_datas->tax								= $tax;
			} else {
				$insert_datas->tax								= '18';
			}

			if($quantity AND $quantity != '') {
				$insert_datas->quantity							= $quantity;
			} else {
				$insert_datas->quantity							= '0';
			}

			if($category AND $category != '') {
				$insert_datas->category							= $category;
			} else {
				$insert_datas->category							= '';
			}

			if($sub_category AND $sub_category != '') {
				$insert_datas->sub_category						= $sub_category;
			} else {
				$insert_datas->sub_category						= '';
			}

			if($manufacturer AND $manufacturer != '') {
				$insert_datas->manufacturer						= $manufacturer;
			} else {
				$insert_datas->manufacturer						= '';
			}

			if($image AND $image != '') {
				$insert_datas->image							= $image;
			} else {
				$insert_datas->image							= '';
			}

			/* İnsert Verileri */

			$product_check										= $this->product_product_model->get_product_by_model($model);
			if($product_check) {
				$insert_datas->product_detail					= $product_check;
				$check											= $this->product_product_model->update_product_by_xml($insert_datas);
				if(!$check)
				{
					$errors										= $line . '. satırdaki veriler ile ürün arasında fark olmadığından düzenlenmedi.';
				} else {
					$success									= $line . '. satırdaki veriler ile ürün düzenlendi.';
				}
			} else {
				$check											= $this->product_product_model->add_product_by_xml($insert_datas);
				if(!$check)
				{
					$errors										= $line . '. satırdaki veriler ile ürün eklenemedi.';
				} else {
					$success									= $line . '. satırdaki veriler ile ürün eklendi.';
				}
			}
		} else {
			$errors												= $line . '. satırdaki verilerde ürün kodu, ürün adı veya ürün fiyatına erişilemedi yada boş!';
		}

		$result	= array(
			'error_msg' => $errors,
			'success_msg' => $success
		);

		exit(json::encode($result));
	}

	protected function check_csv()
	{
		$csv_yolu = $this->session->userdata('csv_file_url');
		if($csv_yolu) {
			if(file_exists($csv_yolu)) {
				$send['status']		= TRUE;
				$send['file_url']	= $csv_yolu;
			} else {
				$send['status']		= FALSE;
				$send['file_url']	= NULL;
			}
		} else {
			$send['status']			= FALSE;
			$send['file_url']		= NULL;
		}

		return $send;
	}

	public function select_column()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/urunler/product_import/select_column');

		$check_file = $this->check_csv();
		$data['select_variables'] = $this->_get_all_variables();
		if($check_file['status'] === TRUE) {
			$val = $this->form_validation;

			$val->set_rules('column', 'Sütunlar', 'reqired|xss_clean|callback__check_array');

			$this->load->library('parser_csv');
			$csv = $this->parser_csv;

			$setting = array(
				'eol' => ';',
				'newline' => "\r\n",
				'limit' => null
			);
			$csv->load_file($check_file['file_url']);
			$csv->set_setting($setting);
			$csv_array_rows = $csv->parse();
			$csv_array_row = $csv->parse(0);
			$data['csv_datas'] = $csv_array_row;

			if($csv_array_row) {
				if ($val->run() === FALSE) {
					$errors	= NULL;
					$vall	= '';
					$i		= 0;
					if($this->input->post()) {
						foreach($this->input->post('column') as $key => $value) {
							$vall = $this->array_search_i($value, $this->input->post('column'));
							if($vall > 1) {
								$errors[$i] = TRUE;
							}
							$i++;
						}
					} else {
						$errors = array();
					}
					$data['errors'] = $errors;
					$this->load->view('yonetim/urunler/product_import_select_column_view', $data);
				} else {
					$errors	= array();
					$vall	= '';
					$i		= 0;
					foreach($this->input->post('column') as $key => $value) { 
						$vall = $this->array_search_i($value, $this->input->post('column'));
						if($vall > 1) {
							$errors[$i] = TRUE;				
						}
						$i++;
					}
					$data['errors'] = $errors;

					if($errors) {
						$this->load->view('yonetim/urunler/product_import_select_column_view', $data);
					} else {
						//$this->_sql_yedek_al();
						$check_file = $this->check_csv();
						if($check_file['status'] === TRUE)
						{
							@unlink($check_file['file_url']);
						}
						$send_datas = array();
						foreach($csv_array_rows as $csv_array_rows_key => $csv_array_rows_value) {
							foreach($csv_array_rows_value as $csv_array_rows_value_key => $csv_array_rows_value_value) {
								$post_data = $this->input->post('column');
								if(isset($post_data[$csv_array_rows_value_key]) AND $post_data[$csv_array_rows_value_key] != '') {
									$check_key = $post_data[$csv_array_rows_value_key];
								} else {
									$check_key = $csv_array_rows_value_key;
								}
								$send_datas[$csv_array_rows_key][$check_key] = mb_convert_encoding($csv_array_rows_value_value, 'UTF-8', 'auto');
							}
						}
						$data_iceri_aktar_sonuc['column'] = $send_datas;
						$data_iceri_aktar_sonuc['error_msg'] = array();
						$this->load->view('yonetim/urunler/product_import_column_status_view', $data_iceri_aktar_sonuc);
					}
				}
			} else {
				$yonetim_mesaj 			= array();
				$yonetim_mesaj['durum'] = '2';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']	= array('Lütfen farklı bir csv dosyası seçiniz en az 1 satır veri olması gerekiyor.');
				$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
				redirect('yonetim/urunler/product_import');
			}
		} else {
			redirect('yonetim/urunler/product_import');
		}
	}

	protected function _set_variable()
	{
		$this->variables[]	= array(
			'name'				=> 'model',
			'desc'				=> 'Ürün Kodu',
			'required'			=> TRUE,
		);

		$languages			= get_languages();
		foreach($languages as $language) {
			$this->variables[]	= array(
				'name'				=> 'name_' . $language['language_id'],
				'desc'				=> $language['name'] . ' Ürün Adı',
				'required'			=> TRUE,
			);
		}

		foreach($languages as $language) {
			$this->variables[]	= array(
				'name'				=> 'description_' . $language['language_id'],
				'desc'				=> $language['name'] . ' Ürün Açıklaması',
				'required'			=> FALSE,
			);
		}

		// price type = 1 tl 2 $ 3 €
		$price_types			= array('1' => 'TL', '2' => '$', '3' => '€');
		foreach ($price_types as $type_key => $type_value) {
			$this->variables[]	= array(
				'name'				=> 'price_' . $type_key,
				'desc'				=> 'Ürün Fiyatı ' . $type_value,
				'required'			=> FALSE,
			);
		}

		$this->variables[]	= array(
			'name'				=> 'tax',
			'desc'				=> 'Ürün Kdv Oranı',
			'required'			=> FALSE,
		);

		$this->variables[]	= array(
			'name'				=> 'quantity',
			'desc'				=> 'Ürün Miktarı',
			'required'			=> FALSE,
		);

		$this->variables[]	= array(
			'name'				=> 'category',
			'desc'				=> 'Ürün Kategori',
			'required'			=> FALSE,
		);

		$this->variables[]	= array(
			'name'				=> 'sub_category',
			'desc'				=> 'Ürün Alt Kategori',
			'required'			=> FALSE,
		);

		$this->variables[]	= array(
			'name'				=> 'manufacturer',
			'desc'				=> 'Ürün Marka',
			'required'			=> FALSE,
		);

		$this->variables[]	= array(
			'name'				=> 'image',
			'desc'				=> 'Ürün Resim Yolu',
			'required'			=> FALSE,
		);
	}

	protected function _get_required_variables()
	{
		$required_variables = array();
		$extend_required	= array();
		foreach($this->variables as $field) {
			if($field['required']) {
				$required_variables[$field['name']] = $field['desc'];
			}
		}
		return $required_variables;
	}

	protected function _get_all_variables()
	{
		$all_variables = array();
		foreach($this->variables as $field)
		{
			$all_variables[$field['name']] = $field['desc'];
		}
		return $all_variables;
	}

	public function _check_array($str)
	{
		$val		= $this->form_validation;
		$variables	= $this->_get_required_variables();
		$i			= 0;
		$text		= '';
		foreach($variables as $key => $value) { 
			$vall	= $this->array_search_i($key, $this->input->post('column'));
			if($vall > 0) {
				$i++;
			} else {
				$text .= $value . ', ';
			}
		}

		if($i == count($variables)) {
			return TRUE;
		} else{
			$val->set_message('_check_array', $text . ' alanlarını veriniz ile eşleştirmek zorundasınız.');
			return FALSE;
		}
	}

	private function array_search_i($str, $array)
	{
		$i = 0;
		foreach($array as $key => $value) {
			if($str == $value AND $value != '') {
				$i++;
			}
		}
		return $i; 
	}

	protected function float($str, $set = FALSE)
	{
		if(preg_match("/([0-9\.,-]+)/", $str, $match))
		{
			// Found number in $str, so set $str that number
			$str = $match[0];

			if(strstr($str, ','))
			{
				// A comma exists, that makes it easy, cos we assume it separates the decimal part.
				$str = str_replace('.', '', $str); // Erase thousand seps
				$str = str_replace(',', '.', $str); // Convert , to . for floatval command
				return floatval($str);
			}
			else
			{
				// No comma exists, so we have to decide, how a single dot shall be treated
				if(preg_match("/^[0-9]*[\.]{1}[0-9-]+$/", $str) == TRUE && $set['single_dot_as_decimal'] == TRUE)
				{
					// Treat single dot as decimal separator
					return floatval($str);
				}
				else
				{
					// Else, treat all dots as thousand seps
					$str = str_replace('.', '', $str); // Erase thousand seps
					return floatval($str);
				}
			}
		}
		else
		{
			// No number found, return zero
			return 0;
		}
	}

}