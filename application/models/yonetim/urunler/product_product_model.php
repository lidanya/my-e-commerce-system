<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class product_product_model extends CI_Model
{

	protected $product_options = array();

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Category Model Yüklendi');
	}
	
	//SKOCH
	public function Ekle($data, $table)
	{
		if(isset($data) && isset($table))
		{
			$this->db->insert($table,$data);
			$result = $this->db->insert_id();
			
			return $result;
		}
	}
	
	public function Guncelle($data,$table,$column,$where)
	{
		$this->db->where($column,$where);
		$result = $this->db->update($table,$data);
		
		if($result)
		return TRUE;
		else
		return FALSE;
		 
	}
	
	public function sil($table,$column,$id)
	{
		$this->db->where($column,$id);
		$result = $this->db->delete($table);
		if($result)
		return TRUE;
		else
		return FALSE;
		
	}
	
	function get_campaign_products()
	{
		$this->db->select("*");
		$this->db->from('e_product_special');
		$q = $this->db->get();
		
		if($q->num_rows() >0)
		return $q->result();
		else
		return false;
	}
	
	function get_discount_products()
	{
		$this->db->select("*");
		$this->db->from('e_product_discount');
		$q = $this->db->get();
		
		if($q->num_rows() >0)
		return $q->result();
		else
		return false;
	}

	public function add_product($get_values)
	{
		$check_model = url_title($get_values['model'], 'dash', TRUE);
		$model = $this->check_model($check_model);

		$product_insert_data = array(
			'model'						=> $model,
			'quantity'					=> $get_values['quantity'],
			'stock_status_id'			=> 0,
			'image'						=> $get_values['image'],
			'manufacturer_id'			=> $get_values['manufacturer_id'],
			'price'						=> $get_values['price'],
			'price_type'				=> $get_values['price_type'],
			'stock_type'				=> $get_values['stock_type'],
			'tax'						=> $get_values['tax'],
			'date_available'			=> strtotime($get_values['date_available']),
			'status'					=> $get_values['status'],
			'show_homepage'				=> $get_values['show_homepage'],
			'new_product'				=> $get_values['new_product'],
			'feature_status'			=> $get_values['feature_status'],
			'cargo_required'			=> $get_values['cargo_required'],
			'cargo_multiply_required'	=> $get_values['cargo_multiply_required'],
			'date_added'				=> time(),
			'date_modified'				=> time(),
			'sort_order'				=> $get_values['sort_order'],
			'length'					=> $get_values['length'],
			'width'						=> $get_values['width'],
			'height'					=> $get_values['height'],
			'length_class_id'			=> $get_values['length_class_id'],
			'weight'					=> $get_values['weight'],
			'weight_class_id'			=> $get_values['weight_class_id'],
			'subtract'					=> $get_values['subtract']
		);
		$this->db->insert('product', $product_insert_data);
		$product_id = $this->db->insert_id();

		foreach($get_values['product_description'] as $language_id => $value) {

			$check_seo = ($value['seo'] AND $value['seo'] != '') ? url_title($value['seo'], 'dash', TRUE) : url_title($value['name'], 'dash', TRUE);
			$seo = $this->check_seo($check_seo, $language_id);

			$product_description_insert = array(
				'product_id'		=> (int) $product_id,
				'language_id'		=> (int) $language_id,
				'seo'				=> $seo,
				'name'				=> $value['name'],
				'meta_keywords'		=> $value['meta_keywords'],
				'meta_description'	=> $value['meta_description'],
				'description'		=> $value['description'],
				'info'				=> $value['info'],
				'video'				=> $value['video']
			);
			$this->db->insert('product_description', $product_description_insert);
		}

		if (isset($get_values['product_option'])) {
			foreach ($get_values['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' OR $product_option['type'] == 'radio' OR $product_option['type'] == 'checkbox') {
					$_product_option_insert_data = array(
						'product_id'			=> (int) $product_id,
						'option_id'				=> (int) $product_option['option_id'],
						'required'				=> (int) $product_option['required'],
						'character_limit'		=> (int) isset($product_option['character_limit']) ? $product_option['character_limit'] : 0
					);
					$this->db->insert('product_option', $_product_option_insert_data);
					$product_option_id = $this->db->insert_id();

					if (isset($product_option['product_option_value'])) {
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$_product_option_value_insert_data = array(
								'product_option_id'		=> (int) $product_option_id,
								'product_id'			=> (int) $product_id,
								'option_id'				=> (int) $product_option['option_id'],
								'option_value_id'		=> $product_option_value['option_value_id'],
								'quantity'				=> (int) $product_option_value['quantity'],
								'subtract'				=> (int) $product_option_value['subtract'],
								'price'					=> (float) $product_option_value['price'],
								'price_prefix'			=> $product_option_value['price_prefix']
							);
							$this->db->insert('product_option_value', $_product_option_value_insert_data);
						}
					}
				} else { 
					$_product_option_insert_data = array(
						'product_id'			=> (int) $product_id,
						'option_id'				=> (int) $product_option['option_id'],
						'option_value'			=> $product_option['option_value'],
						'required'				=> (int) $product_option['required'],
						'character_limit'		=> (int) isset($product_option['character_limit']) ? $product_option['character_limit'] : 0
					);
					$this->db->insert('product_option', $_product_option_insert_data);
				}
			}
		}

		if (isset($get_values['product_discount'])) {
			foreach ($get_values['product_discount'] as $value) {
				$product_discount_insert = array(
					'product_id'		=> (int) $product_id,
					'user_group_id'		=> (int) $value['user_group_id'],
					'priority'			=> (int) $value['priority'],
					'price'				=> (float) $value['price'],
					'date_start'		=> strtotime($value['date_start']),
					'date_end'			=> strtotime($value['date_end'])
				);
				$this->db->insert('product_discount', $product_discount_insert);
			}
		}

		if (isset($get_values['product_special'])) {
			foreach ($get_values['product_special'] as $value) {
				$product_special = array(
					'product_id'		=> (int) $product_id,
					'user_group_id'		=> (int) $value['user_group_id'],
					'quantity'			=> (int) $value['quantity'],
					'priority'			=> (int) $value['priority'],
					'price'				=> (float) $value['price'],
					'date_start'		=> strtotime($value['date_start']),
					'date_end'			=> strtotime($value['date_end'])
				);
				$this->db->insert('product_special', $product_special);
			}
		}

		if (isset($get_values['product_images'])) {
			foreach ($get_values['product_images'] as $image) {
				$product_image_insert = array(
					'product_id'		=> (int) $product_id,
					'image'				=> $image
				);
        		$this->db->insert('product_image', $product_image_insert);
			}
		}

		if (isset($get_values['product_category'])) {
			foreach ($get_values['product_category'] as $category_id) {
				$product_category_insert = array(
					'product_id'		=> (int) $product_id,
					'category_id'		=> (int) $category_id
				);
				$this->db->insert('product_to_category', $product_category_insert);
			}
		}

		if (isset($get_values['product_related'])) {
			foreach ($get_values['product_related'] as $related_id) {
				$product_related_insert = array(
					'product_id'		=> (int) $product_id,
					'related_id'		=> (int) $related_id
				);
				$this->db->insert('product_related', $product_related_insert);
			}
		}

		if (isset($get_values['product_features'])) {
			foreach ($get_values['product_features'] as $pf_key => $pf_value) {
				foreach ($pf_value as $language_id => $value) {
					$_product_features_insert_data = array(
						'product_id'	=> $product_id,
						'feature_id'	=> $pf_key,
						'language_id'	=> $language_id,
						'value'			=> $value['name']
					);
				}
				$this->db->insert('product_featured', $_product_features_insert_data);
			}
		}

		return TRUE;
	}

	public function add_product_by_xml($add_datas)
	{
		/*
			- definitions -
			- required
				model string
				name array
				price float
				price_type int
			- optional
				description array
				tax int
				quantity int
				category string
				sub_category string
				manufacturer string
				image string
		*/

		$check_model = url_title($add_datas->model, 'dash', TRUE);
		$model = $this->check_model($check_model);

		$_product_insert_data = array(
			'model'			 		=> $model,
			'quantity'				=> (int) $add_datas->quantity,
			'stock_status_id'		=> 0,
			'image'			 		=> $add_datas->image,
			'manufacturer_id'		=> 0,
			'price'		 			=> $add_datas->price,
			'price_type'			=> $add_datas->price_type,
			'tax'			 		=> $add_datas->tax,
			'date_available'		=> strtotime('+5 years'),
			'status'				=> 1,
			'show_homepage'			=> 0,
			'new_product'			=> 0,
			'feature_status'		=> 0,
			'cargo_required'		=> 0,
			'date_added'			=> time(),
			'date_modified'			=> time(),
			'sort_order'			=> 1,
			'length'				=> 0,
			'width'					=> 0,
			'height'				=> 0,
			'length_class_id'		=> 0,
			'weight'				=> 0,
			'weight_class_id'		=> 0,
			'subtract'				=> 0
		);
		$this->db->insert('product', $_product_insert_data);

		if ($this->db->affected_rows()) {
			$product_id = $this->db->insert_id();

			$_product_update_data = array(
				'date_modified'	=> time()
			);

			/* Check Product Name */
			foreach ($add_datas->name as $k_name => $v_name) {
				$language_id = $k_name;
				$name = $v_name;

				if($v_name == '') {
					$name = 'İsim Yok';
				}

				$check_seo = url_title($name, 'dash', TRUE);
				$seo = $this->check_seo($check_seo, $language_id);

				$product_description_insert_data = array(
					'product_id'		=> (int) $product_id,
					'language_id'		=> (int) $language_id,
					'seo'				=> $seo,
					'name'				=> $name,
					'meta_keywords'		=> '',
					'meta_description'	=> '',
					'description'		=> '',
					'video'				=> ''
				);
				$this->db->insert('product_description', $product_description_insert_data);
			}
			/* Check Product Name */

			/* Check Product Description */
			foreach ($add_datas->description as $k_description => $v_description) {
				$language_id = $k_description;
				$description = $v_description;

				if($v_description == '') {
					$description = '';
				}

				$product_description_update_data = array(
					'description' => $description
				);

				$this->db->where('product_id', (int) $product_id);
				$this->db->where('language_id', (int) $language_id);
				$this->db->update('product_description', $product_description_update_data);
			}
			/* Check Product Description */

			/* Check Image */
			if ($add_datas->image != '') {
				$_product_update_data['image'] = $add_datas->image;
			}
			/* Check Image */

			/* Check Manufacturer */
			if ($add_datas->manufacturer != '') {
				$this->load->model('yonetim/urunler/manufacturer_manufacturer_model');
				$manufacturer = $this->manufacturer_manufacturer_model->get_manufacturer_by_name($add_datas->manufacturer);
				if($manufacturer) {
					$manufacturer_id = $manufacturer->manufacturer_id;
				} else {
					$check_seo = url_title($add_datas->manufacturer, 'dash', TRUE);
					$seo = $this->manufacturer_manufacturer_model->check_seo($check_seo);

					$_manufacturer_insert_data = array(
						'name'					=> $add_datas->manufacturer,
						'image'					=> '',
						'seo'					=> $seo,
						'meta_description'		=> '',
						'meta_keywords'			=> '',
						'description'			=> '',
						'sort_order'			=> 1,
						'status'				=> 1,
						'image'					=> '',
						'date_added'			=> standard_date('DATE_MYSQL', time(), 'tr'),
						'date_modified'			=> standard_date('DATE_MYSQL', time(), 'tr')
					);
					$this->db->insert('manufacturer', $_manufacturer_insert_data);
					if($this->db->affected_rows()) {
						$manufacturer_id = $this->db->insert_id();
					} else {
						$manufacturer_id = 0;
					}
				}

				$_product_update_data['manufacturer_id'] = (int) $manufacturer_id;
			}
			/* Check Manufacturer */

			/* Check Category */
			$category_id = FALSE;
			if ($add_datas->category != '') {
				$this->load->model('yonetim/urunler/product_category_model');

				$category = $this->product_category_model->get_category_by_name_and_parent_id($add_datas->category, 0);
				if($category) {
					$category_id = $category->category_id;
				} else {
					$_category_insert_data = array(
						'sort_order'	=> 1,
						'parent_id'		=> 0,
						'status'		=> 1,
						'image'			=> '',
						'date_added'	=> standard_date('DATE_MYSQL', time(), 'tr'),
						'date_modified'	=> standard_date('DATE_MYSQL', time(), 'tr')
					);
					$this->db->insert('category', $_category_insert_data);
					if($this->db->affected_rows()) {
						$category_id = $this->db->insert_id();

						$languages = get_languages();
						foreach ($languages	as $language) {
							$check_seo = url_title($add_datas->category, 'dash', TRUE);
							$seo = $this->product_category_model->check_seo($check_seo, $language['language_id']);

							$_category_description_insert_data = array(
								'category_id'				=> $category_id,
								'language_id'				=> $language['language_id'],
								'name'						=> $add_datas->category,
								'description'				=> '',
								'meta_keywords'				=> '',
								'meta_description'			=> '',
								'seo'						=> $seo,
							);
							$this->db->insert('category_description', $_category_description_insert_data);
						}
					}
				}
			}
			/* Check Category */

			/* Check Sub Category */
			if ($category_id AND $add_datas->sub_category != '') {
				$this->load->model('yonetim/urunler/product_category_model');

				$category = $this->product_category_model->get_category_by_name_and_parent_id($add_datas->sub_category, $category_id);
				if($category) {
					$category_id = $category->category_id;
				} else {
					$_category_insert_data = array(
						'sort_order'	=> 1,
						'parent_id'		=> (int) $category_id,
						'status'		=> 1,
						'image'			=> '',
						'date_added'	=> standard_date('DATE_MYSQL', time(), 'tr'),
						'date_modified'	=> standard_date('DATE_MYSQL', time(), 'tr')
					);
					$this->db->insert('category', $_category_insert_data);
					if($this->db->affected_rows()) {
						$category_id = $this->db->insert_id();

						$languages = get_languages();
						foreach ($languages	as $language) {
							$check_seo = url_title($add_datas->sub_category, 'dash', TRUE);
							$seo = $this->product_category_model->check_seo($check_seo, $language['language_id']);

							$_category_description_insert_data = array(
								'category_id'				=> $category_id,
								'language_id'				=> $language['language_id'],
								'name'						=> $add_datas->sub_category,
								'description'				=> '',
								'meta_keywords'				=> '',
								'meta_description'			=> '',
								'seo'						=> $seo,
							);
							$this->db->insert('category_description', $_category_description_insert_data);
						}
					}
				}
			}
			/* Check Sub Category */

			/* Check Category Insert */
			if($category_id) {
				$this->db->insert('product_to_category', array('product_id' => (int) $product_id, 'category_id' => (int) $category_id));
			}
			/* Check Category Insert */

			$this->db->update('product', $_product_update_data, array('product_id' => (int) $product_id));

			return TRUE;
		}

		return FALSE;
	}

	public function update_product($product_id, $get_values, $product_data)
	{
		$check_model = url_title($get_values['model'], 'dash', TRUE);
		$model = $this->check_model($check_model, $product_id);

		$product_insert_data = array(
			'model'						=> $model,
			'quantity'					=> $get_values['quantity'],
			'stock_status_id'			=> 0,
			'image'						=> $get_values['image'],
			'manufacturer_id'			=> $get_values['manufacturer_id'],
			'price'						=> $get_values['price'],
			'price_type'				=> $get_values['price_type'],
			'stock_type'				=> $get_values['stock_type'],
			'tax'						=> $get_values['tax'],
			'date_available'			=> strtotime($get_values['date_available']),
			'status'					=> $get_values['status'],
			'show_homepage'				=> $get_values['show_homepage'],
			'new_product'				=> $get_values['new_product'],
			'feature_status'			=> $get_values['feature_status'],
			'cargo_required'			=> $get_values['cargo_required'],
			'cargo_multiply_required'	=> $get_values['cargo_multiply_required'],
			'date_modified'				=> time(),
			'sort_order'				=> $get_values['sort_order'],
			'length'					=> $get_values['length'],
			'width'						=> $get_values['width'],
			'height'					=> $get_values['height'],
			'length_class_id'			=> $get_values['length_class_id'],
			'weight'					=> $get_values['weight'],
			'weight_class_id'			=> $get_values['weight_class_id'],
			'subtract'					=> $get_values['subtract']
		);
		$this->db->update('product', $product_insert_data, array('product_id' => (int) $product_id));
		
		$this->db->delete('product_description', array('product_id' => (int) $product_id));
		foreach($get_values['product_description'] as $language_id => $value) {

			$check_seo = ($value['seo'] AND $value['seo'] != '') ? url_title($value['seo'], 'dash', TRUE) : url_title($value['name'], 'dash', TRUE);
			$seo = $this->check_seo($check_seo, $language_id, $product_id);

			$product_description_insert = array(
				'product_id'		=> (int) $product_id,
				'language_id'		=> (int) $language_id,
				'seo'				=> $seo,
				'name'				=> $value['name'],
				'meta_keywords'		=> $value['meta_keywords'],
				'meta_description'	=> $value['meta_description'],
				'info		'		=> $value['info'],
				'description'		=> $value['description'],
				'video'				=> $value['video']
			);
			$this->db->insert('product_description', $product_description_insert);
		}

		$this->db->delete('product_option', array('product_id' => (int) $product_id));
		$this->db->delete('product_option_value', array('product_id' => (int) $product_id));
		if (isset($get_values['product_option'])) {
			foreach ($get_values['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' OR $product_option['type'] == 'radio' OR $product_option['type'] == 'checkbox') {
					$_product_option_insert_data = array(
						'product_id'			=> (int) $product_id,
						'option_id'				=> (int) $product_option['option_id'],
						'required'				=> (int) $product_option['required'],
						'character_limit'		=> (int) isset($product_option['character_limit']) ? $product_option['character_limit'] : 0
					);
					$this->db->insert('product_option', $_product_option_insert_data);
					$product_option_id = $this->db->insert_id();

					if (isset($product_option['product_option_value'])) {
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$_product_option_value_insert_data = array(
								'product_option_id'		=> (int) $product_option_id,
								'product_id'			=> (int) $product_id,
								'option_id'				=> (int) $product_option['option_id'],
								'option_value_id'		=> $product_option_value['option_value_id'],
								'quantity'				=> (int) $product_option_value['quantity'],
								'subtract'				=> (int) $product_option_value['subtract'],
								'price'					=> (float) $product_option_value['price'],
								'price_prefix'			=> $product_option_value['price_prefix']
							);
							$this->db->insert('product_option_value', $_product_option_value_insert_data);
						}
					}
				} else { 
					$_product_option_insert_data = array(
						'product_id'			=> (int) $product_id,
						'option_id'				=> (int) $product_option['option_id'],
						'option_value'			=> $product_option['option_value'],
						'required'				=> (int) $product_option['required'],
						'character_limit'		=> (int) isset($product_option['character_limit']) ? $product_option['character_limit'] : 0
					);
					$this->db->insert('product_option', $_product_option_insert_data);
				}
			}
		}

		$this->db->delete('product_discount', array('product_id' => (int) $product_id));
		if (isset($get_values['product_discount'])) {
			foreach ($get_values['product_discount'] as $product_discount_value) {
				$product_discount_insert = array(
					'product_id'		=> (int) $product_id,
					'user_group_id'		=> (int) $product_discount_value['user_group_id'],
					'priority'			=> (int) $product_discount_value['priority'],
					'price'				=> (float) $product_discount_value['price'],
					'date_start'		=> strtotime($product_discount_value['date_start']),
					'date_end'			=> strtotime($product_discount_value['date_end'])
				);
				$this->db->insert('product_discount', $product_discount_insert);
			}
		}

		$this->db->delete('product_special', array('product_id' => (int) $product_id));
		if (isset($get_values['product_special'])) {
			foreach ($get_values['product_special'] as $product_special_value) {
				$product_special = array(
					'product_id'		=> (int) $product_id,
					'user_group_id'		=> (int) $product_special_value['user_group_id'],
					'quantity'			=> (int) $product_special_value['quantity'],
					'priority'			=> (int) $product_special_value['priority'],
					'price'				=> (float) $product_special_value['price'],
					'date_start'		=> strtotime($product_special_value['date_start']),
					'date_end'			=> strtotime($product_special_value['date_end'])
				);
				$this->db->insert('product_special', $product_special);
			}
		}

		$this->db->delete('product_image', array('product_id' => (int) $product_id));
		if (isset($get_values['product_images'])) {
			foreach ($get_values['product_images'] as $image) {
				$product_image_insert = array(
					'product_id'		=> (int) $product_id,
					'image'				=> $image
				);
        		$this->db->insert('product_image', $product_image_insert);
			}
		}

		$this->db->delete('product_to_category', array('product_id' => (int) $product_id));
		if (isset($get_values['product_category'])) {
			foreach ($get_values['product_category'] as $category_id) {
				$product_category_insert = array(
					'product_id'		=> (int) $product_id,
					'category_id'		=> (int) $category_id
				);
				$this->db->insert('product_to_category', $product_category_insert);
			}
		}

		$this->db->delete('product_related', array('product_id' => (int) $product_id));
		if (isset($get_values['product_related'])) {
			foreach ($get_values['product_related'] as $related_id) {
				$this->db->delete('product_related', array('product_id' => (int) $related_id, 'related_id' => (int) $product_id));

				$product_related_insert = array(
					'product_id'		=> (int) $product_id,
					'related_id'		=> (int) $related_id
				);
				$this->db->insert('product_related', $product_related_insert);
			}
		}

		$this->db->delete('product_featured', array('product_id' => $product_id));
		if (isset($get_values['product_features'])) {
			foreach ($get_values['product_features'] as $pf_key => $pf_value) {
				foreach ($pf_value as $language_id => $value) {
					$_product_features_insert_data = array(
						'product_id'	=> $product_id,
						'feature_id'	=> $pf_key,
						'language_id'	=> $language_id,
						'value'			=> $value['name']
					);
					$this->db->insert('product_featured', $_product_features_insert_data);
				}
			}
		}

		if (($product_data->price != $get_values['price']) OR ($product_data->quantity != $get_values['quantity'])) {
			$takip_mail_kont = TRUE; 
		} else {
			$takip_mail_kont = FALSE;
		}

		$follow_users = $this->get_product_follow_by_id($product_id);
		if ($takip_mail_kont AND $follow_users) {
			foreach($follow_users as $follow_user) {
				$from = config('site_ayar_email_cevapsiz');
				$subject = $product_data->name . ' ürünü düzenlenmiştir';

				$mail_data['adsoyad'] 		= $follow_user['ide_adi'] . ' ' . $follow_user['ide_soy'];
				$mail_data['stok_adi']		= $product_data->name;
				$mail_data['seo_url']		= $product_data->seo;

				$message = $this->load->view(tema() . 'mail_sablon/stok_duzenleme_takip', $mail_data, true);
				$this->dx_auth->_email($follow_user['email'], $from, $subject, $message);
			}
		}

		return TRUE;
	}

	public function update_product_by_xml($update_datas)
	{
		/*
			- definitions -
			- required
				model string
				name array
				price float
				price_type int
			- optional
				description array
				tax int
				quantity int
				category string
				sub_category string
				manufacturer string
				image string
		*/

		$product_detail = $update_datas->product_detail;
		$product_id		= $product_detail->product_id;

		$check_model	= url_title($update_datas->model, 'dash', TRUE);
		$model			= $this->check_model($check_model, (int) $product_id);

		$_product_update_data = array(
			'model'			 		=> $model,
			'quantity'				=> (int) $update_datas->quantity,
			'stock_status_id'		=> 0,
			'image'			 		=> $update_datas->image,
			'manufacturer_id'		=> 0,
			'price'		 			=> $update_datas->price,
			'price_type'			=> $update_datas->price_type,
			'tax'			 		=> $update_datas->tax,
			'date_available'		=> strtotime('+5 years'),
			'status'				=> 1,
			'show_homepage'			=> 0,
			'new_product'			=> 0,
			'feature_status'		=> 0,
			'cargo_required'		=> 0,
			'date_added'			=> time(),
			'date_modified'			=> time(),
			'sort_order'			=> 1,
			'length'				=> 0,
			'width'					=> 0,
			'height'				=> 0,
			'length_class_id'		=> 0,
			'weight'				=> 0,
			'weight_class_id'		=> 0,
			'subtract'				=> 0
		);
		$this->db->update('product', $_product_update_data, array('product_id' => $product_id));

		if ($this->db->affected_rows()) {
			/* Check Product Name */
			if ($update_datas->name) {
				$this->db->delete('product_description', array('product_id' => (int) $product_id));
				foreach ($update_datas->name as $k_name => $v_name) {
					$language_id = $k_name;
					$name = $v_name;

					if ($v_name == '') {
						$name = 'İsim Yok';
					}

					$check_seo = url_title($name, 'dash', TRUE);
					$seo = $this->check_seo($check_seo, $language_id);

					$product_description_insert_data = array(
						'product_id'		=> (int) $product_id,
						'language_id'		=> (int) $language_id,
						'seo'				=> $seo,
						'name'				=> $name,
						'meta_keywords'		=> '',
						'meta_description'	=> '',
						'description'		=> '',
						'video'				=> ''
					);
					$this->db->insert('product_description', $product_description_insert_data);
				}
			}
			/* Check Product Name */

			/* Check Product Description */
			foreach ($update_datas->description as $k_description => $v_description) {
				$language_id = $k_description;
				$description = $v_description;

				if($v_description == '') {
					$description = '';
				}

				$product_description_update_data = array(
					'description' => $description
				);

				$this->db->where('product_id', (int) $product_id);
				$this->db->where('language_id', (int) $language_id);
				$this->db->update('product_description', $product_description_update_data);
			}
			/* Check Product Description */

			/* Check Image */
			if ($update_datas->image != '') {
				$_product_update_data_['image'] = $update_datas->image;
			}
			/* Check Image */

			/* Check Manufacturer */
			if ($update_datas->manufacturer != '') {
				$this->load->model('yonetim/urunler/manufacturer_manufacturer_model');
				$manufacturer = $this->manufacturer_manufacturer_model->get_manufacturer_by_name($update_datas->manufacturer);
				if($manufacturer) {
					$manufacturer_id = $manufacturer->manufacturer_id;
				} else {
					$check_seo = url_title($update_datas->manufacturer, 'dash', TRUE);
					$seo = $this->manufacturer_manufacturer_model->check_seo($check_seo);

					$_manufacturer_insert_data = array(
						'name'					=> $update_datas->manufacturer,
						'image'					=> '',
						'seo'					=> $seo,
						'meta_description'		=> '',
						'meta_keywords'			=> '',
						'description'			=> '',
						'sort_order'			=> 1,
						'status'				=> 1,
						'image'					=> '',
						'date_added'			=> standard_date('DATE_MYSQL', time(), 'tr'),
						'date_modified'			=> standard_date('DATE_MYSQL', time(), 'tr')
					);
					$this->db->insert('manufacturer', $_manufacturer_insert_data);
					if($this->db->affected_rows()) {
						$manufacturer_id = $this->db->insert_id();
					} else {
						$manufacturer_id = 0;
					}
				}

				$_product_update_data_['manufacturer_id'] = (int) $manufacturer_id;
			}
			/* Check Manufacturer */

			/* Check Category */
			$category_id = FALSE;
			if ($update_datas->category != '') {
				$this->load->model('yonetim/urunler/product_category_model');

				$category = $this->product_category_model->get_category_by_name_and_parent_id($update_datas->category, 0);
				if($category) {
					$category_id = $category->category_id;
				} else {
					$_category_insert_data = array(
						'sort_order'	=> 1,
						'parent_id'		=> 0,
						'status'		=> 1,
						'image'			=> '',
						'date_added'	=> standard_date('DATE_MYSQL', time(), 'tr'),
						'date_modified'	=> standard_date('DATE_MYSQL', time(), 'tr')
					);
					$this->db->insert('category', $_category_insert_data);
					if($this->db->affected_rows()) {
						$category_id = $this->db->insert_id();

						$languages = get_languages();
						foreach ($languages	as $language) {
							$check_seo = url_title($update_datas->category, 'dash', TRUE);
							$seo = $this->product_category_model->check_seo($check_seo, $language['language_id']);

							$_category_description_insert_data = array(
								'category_id'				=> $category_id,
								'language_id'				=> $language['language_id'],
								'name'						=> $update_datas->category,
								'description'				=> '',
								'meta_keywords'				=> '',
								'meta_description'			=> '',
								'seo'						=> $seo,
							);
							$this->db->insert('category_description', $_category_description_insert_data);
						}
					}
				}
			}
			/* Check Category */

			/* Check Sub Category */
			if ($category_id AND $update_datas->sub_category != '') {
				$this->load->model('yonetim/urunler/product_category_model');

				$category = $this->product_category_model->get_category_by_name_and_parent_id($update_datas->sub_category, $category_id);
				if($category) {
					$category_id = $category->category_id;
				} else {
					$_category_insert_data = array(
						'sort_order'	=> 1,
						'parent_id'		=> (int) $category_id,
						'status'		=> 1,
						'image'			=> '',
						'date_added'	=> standard_date('DATE_MYSQL', time(), 'tr'),
						'date_modified'	=> standard_date('DATE_MYSQL', time(), 'tr')
					);
					$this->db->insert('category', $_category_insert_data);
					if($this->db->affected_rows()) {
						$category_id = $this->db->insert_id();

						$languages = get_languages();
						foreach ($languages	as $language) {
							$check_seo = url_title($update_datas->sub_category, 'dash', TRUE);
							$seo = $this->product_category_model->check_seo($check_seo, $language['language_id']);

							$_category_description_insert_data = array(
								'category_id'				=> $category_id,
								'language_id'				=> $language['language_id'],
								'name'						=> $update_datas->sub_category,
								'description'				=> '',
								'meta_keywords'				=> '',
								'meta_description'			=> '',
								'seo'						=> $seo,
							);
							$this->db->insert('category_description', $_category_description_insert_data);
						}
					}
				}
			}
			/* Check Sub Category */

			/* Check Category Insert */
			if($category_id) {
				$this->db->delete('product_to_category', array('product_id' => (int) $product_id, 'category_id' => (int) $category_id));
				$this->db->insert('product_to_category', array('product_id' => (int) $product_id, 'category_id' => (int) $category_id));
			}
			/* Check Category Insert */

			$this->db->update('product', $_product_update_data_, array('product_id' => (int) $product_id));

			return TRUE;
		}

		return FALSE;
	}

	public function bacth_update_product($get_values)
	{
		$price					= isset($get_values['price']) ? $get_values['price'] : 'seciniz';
		$price_type				= isset($get_values['price_type']) ? $get_values['price_type'] : 'seciniz';
		$quantity				= isset($get_values['quantity']) ? $get_values['quantity'] : 'seciniz';
		$stock_type				= isset($get_values['stock_type']) ? $get_values['stock_type'] : 'seciniz';
		$tax					= isset($get_values['tax']) ? $get_values['tax'] : 'seciniz';
		$status					= isset($get_values['status']) ? $get_values['status'] : 'seciniz';
		$subtract				= isset($get_values['subtract']) ? $get_values['subtract'] : 'seciniz';
		$manufacturer_id		= isset($get_values['manufacturer_id']) ? $get_values['manufacturer_id'] : 'seciniz';
		$feature_status			= isset($get_values['feature_status']) ? $get_values['feature_status'] : 'seciniz';
		$product_category		= isset($get_values['product_category']) ? $get_values['product_category'] : 'seciniz';

		$sayi					= 0;

		if (isset($get_values['selected'])) {
			foreach($get_values['selected'] as $product_id) {
				$product_info = FALSE;
				$product_info = $this->get_product_by_id($product_id);

				$product_update_data = array();
				$_mail_kontrol_stok_fiyat = FALSE;
				if($price !== 'seciniz' AND !is_null($price) AND is_numeric($price)) {
					$product_update_data['price']					= $price;
					$_mail_kontrol_stok_fiyat						= $price;
				}
	
				if($price_type !== 'seciniz' AND !is_null($price_type) AND is_numeric($price_type)) {
					$product_update_data['price_type']				= $price_type;
				}
	
				if($tax !== 'seciniz' AND !is_null($tax) AND is_numeric($tax)) {
					$product_update_data['tax']				= $tax;
				}

				if($quantity !== 'seciniz' AND !is_null($quantity) AND is_numeric($quantity)) {
					$product_update_data['quantity']		= $quantity;
				}

				if($stock_type !== 'seciniz' AND !is_null($stock_type) AND is_numeric($stock_type)) {
					$product_update_data['stock_type']				= $stock_type;
				}
	
				if($status !== 'seciniz' AND !is_null($status) AND is_numeric($status)) {
					$product_update_data['status']					= $status;
				}

				if($subtract !== 'seciniz' AND !is_null($subtract) AND is_numeric($subtract)) {
					$product_update_data['subtract']				= $subtract;
				}

				if($manufacturer_id !== 'seciniz' AND !is_null($manufacturer_id) AND is_numeric($manufacturer_id)) {
					$product_update_data['manufacturer_id']			= $manufacturer_id;
				}

				if($feature_status !== 'seciniz' AND !is_null($feature_status) AND is_numeric($feature_status)) {
					$product_update_data['feature_status']			= $feature_status;
				}

				if(count($product_update_data) > 0) {
					$this->db->where('product_id', $product_id);
					$kontrol = $this->db->update('product', $product_update_data);
					$sayi += 1;
				}

				if($product_category !== 'seciniz' AND !is_null($product_category) AND is_array($product_category)) {
					$this->db->delete('product_to_category', array('product_id' => $product_id));
					if ($product_category) {
						foreach($product_category as $category) {
							$this->db->insert('product_to_category', array('product_id' => $product_id, 'category_id' => $category));
							$sayi += 1;
						}
					}
				}

				if (($product_info->price != $get_values['price']) OR ($product_info->quantity != $get_values['quantity'])) {
					$takip_mail_kont = TRUE; 
				} else {
					$takip_mail_kont = FALSE;
				}

				$follow_users = $this->get_product_follow_by_id($product_id);
				if ($takip_mail_kont AND $follow_users) {
					foreach($follow_users as $follow_user) {
						$from = config('site_ayar_email_cevapsiz');
						$subject = $product_info->name . ' ürünü düzenlenmiştir';

						$mail_data['adsoyad'] 		= $follow_user['ide_adi'] . ' ' . $follow_user['ide_soy'];
						$mail_data['stok_adi']		= $product_info->name;
						$mail_data['seo_url']		= $product_info->seo;

						$message = $this->load->view(tema() . 'mail_sablon/stok_duzenleme_takip', $mail_data, true);
						$this->dx_auth->_email($follow_user['email'], $from, $subject, $message);
					}
				}
			}
		}

		if($sayi > 0) {
			$gonder['durum'] = TRUE;
			$gonder['mesaj'] = 'Düzenleme işleminiz başarılı bir şekilde gerçekleşti.';
		} else {
			$gonder['durum'] = FALSE;
			$gonder['mesaj'] = 'Düzenleme işleminiz başarısız.';
		}

		return $gonder;
	}


	public function get_product_follow_by_id($product_id)
	{
		$this->db->select(
			get_fields_from_table('product_follow', 'pf.', array(), ', ') . 
			get_fields_from_table('users', 'u.', array('email'), ', ') .
			get_fields_from_table('usr_ide_inf', 'uii.', array('ide_adi', 'ide_soy'), '')
		);
		$this->db->from('product_follow pf');
		$this->db->join('users u', 'pf.user_id = u.id', 'left');
		$this->db->join('usr_ide_inf uii', 'u.id = uii.user_id', 'left');
		$this->db->where('pf.product_id', (int) $product_id);
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}

	public function check_model($model, $product_id = FALSE)
	{
		$this->db->from('product p');
		$this->db->where('model', $model);
		if($product_id) {
			$this->db->where_not_in('product_id', (int) $product_id);
		}
		$count = $this->db->count_all_results();

		if($count) {
			$this->load->helper('string');
			$_model = $model . '-' . mb_strtolower(random_string('alnum', 4));
			return $this->check_model($_model, $product_id);
		} else {
			return $model;
		}
	}

	public function check_seo($seo, $language_id, $product_id = FALSE)
	{
		$this->db->from('product_description pd');
		$this->db->where('seo', $seo);
		$this->db->where('language_id', $language_id);
		if($product_id) {
			$this->db->where_not_in('product_id', (int) $product_id);
		}
		$count = $this->db->count_all_results();

		if($count) {
			$this->load->helper('string');
			$_seo = $seo . '-' . mb_strtolower(random_string('alnum', 4));
			return $this->check_seo($_seo, $language_id, $product_id);
		} else {
			return $seo;
		}
	}

	public function get_products_by_all($page, $sort = 'p.product_id', $order = 'desc', $filter = 'p.status|]', $sort_link)
	{
		$_array = explode(', ', get_fields_from_table('product', 'p.'));
		$_d_array = explode(', ', get_fields_from_table('product_description', 'pd.'));
		$_filter_allowed = array_merge($_array, $_d_array);
		$_sort_allowed = array_merge($_array, $_d_array);

		$per_page = (config('site_ayar_urun_yonetim_sayfa')) ? config('site_ayar_urun_yonetim_sayfa') : 20;

		if( ! in_array($sort, $_sort_allowed)) {
			$sort = 'p.product_id';
		}

		$_order_allowed = array('asc', 'desc', 'random');

		if( ! in_array($order, $_order_allowed)) {
			$order = 'desc';
		}

		$language_id = get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS ' . 
			get_fields_from_table('product', 'p.', array(), ', ') . 
			get_fields_from_table('product_description', 'pd.', array(), '')
		, FALSE);
		$this->db->from('product p');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->where('pd.language_id', $language_id);

		if ($filter != 'p.status|]') {
			$filter_e = explode(']', $filter);
			foreach($filter_e as $yaz) {
				if($yaz != '') {
					if(preg_match('/|/i', $yaz)) {
						$explode = explode('|', $yaz);
						if((isset($explode[0]) AND $explode[0] != '') AND (isset($explode[1]) AND $explode[1] != '')) {
							if(in_array($explode[0], $_filter_allowed)) {
								$this->db->like($explode[0], $explode[1]);
							}
						}
					}
				}
			}
		}

		$this->db->order_by($sort, $order);
		$this->db->order_by('pd.name', 'asc');
		$this->db->limit($per_page, $page);
		$query = $this->db->get();
		$query_count = $this->db->select('FOUND_ROWS() as count')->get()->row()->count;

		$config['base_url'] 		= base_url() . 'yonetim/urunler/product/lists/' . $sort_link . '/' . $filter;
		$config['uri_segment']		= 7;
		$config['per_page'] 	  	= $per_page;
		$config['total_rows'] 	  	= $query_count;
		$config['full_tag_open']  	= 'Sayfa : ';
		$config['full_tag_close'] 	= '';
		$config['num_links'] 	  	= 6;

		$mevcut_sayfa = floor(($page / $per_page) + 1);
		$toplam_sayisi = $query_count;
		$toplam_sayfa = ceil($toplam_sayisi / $per_page);

		$config['full_tag_open'] = '<div class="pagination"><div class="links">';
		$config['full_tag_close'] = '</div><div class="results">
Toplam '. $toplam_sayfa .' sayfa içinde '. $mevcut_sayfa .'. sayfadasın, toplam ürün sayısı '. $query_count .'</div></div>';

		$config['first_link'] = '|&lt;';
		$config['first_tag_open'] = '';
		$config['first_tag_close'] = '';

		$config['last_link'] = '&gt;|';
		$config['last_tag_open'] = '';
		$config['last_tag_close'] = '';

		$config['next_link'] = '&gt;';
		$config['next_tag_open'] = '';
		$config['next_tag_close'] = '';

		$config['prev_link'] = '&lt;';
		$config['prev_tag_open'] = '';
		$config['prev_tag_close'] = '';

		$config['cur_tag_open'] = '<b>';
		$config['cur_tag_close'] = '</b>';

		$config['num_tag_open'] = '';
		$config['num_tag_close'] = '';

		$this->pagination->initialize($config);
		return $query;
	}

	public function get_products_by_no_pag_all($page, $sort = 'p.product_id', $order = 'desc', $filter = 'p.status|]')
	{
		$_array = explode(', ', get_fields_from_table('product', 'p.'));
		$_d_array = explode(', ', get_fields_from_table('product_description', 'pd.'));
		$_filter_allowed = array_merge($_array, $_d_array);
		$_sort_allowed = array_merge($_array, $_d_array);

		$per_page = (config('site_ayar_urun_yonetim_sayfa')) ? config('site_ayar_urun_yonetim_sayfa') : 20;

		if( ! in_array($sort, $_sort_allowed)) {
			$sort = 'p.product_id';
		}

		$_order_allowed = array('asc', 'desc', 'random');

		if( ! in_array($order, $_order_allowed)) {
			$order = 'desc';
		}

		$language_id = get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS ' . 
			get_fields_from_table('product', 'p.', array(), ', ') . 
			get_fields_from_table('product_description', 'pd.', array(), '')
		, FALSE);
		$this->db->from('product p');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->where('pd.language_id', $language_id);

		if ($filter != 'p.status|]') {
			$filter_e = explode(']', $filter);
			foreach($filter_e as $yaz) {
				if($yaz != '') {
					if(preg_match('/|/i', $yaz)) {
						$explode = explode('|', $yaz);
						if((isset($explode[0]) AND $explode[0] != '') AND (isset($explode[1]) AND $explode[1] != '')) {
							if(in_array($explode[0], $_filter_allowed)) {
								$this->db->like($explode[0], $explode[1]);
							}
						}
					}
				}
			}
		}

		$this->db->order_by($sort, $order);
		$this->db->order_by('pd.name', 'asc');
		$this->db->limit($per_page, $page);
		$query = $this->db->get();

		return $query->result_array();
	}

	public function get_product_by_id($product_id)
	{
		$this->db->select(get_fields_from_table('product', 'p.', array(), ''));
		$this->db->from('product p');
		$this->db->where('p.product_id', $product_id);
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->row();
		} else {
			return false;
		}
	}

	public function get_product_by_model($model)
	{
		$this->db->select(get_fields_from_table('product', 'p.', array(), ''));
		$this->db->from('product p');
		$this->db->where('p.model', $model);
		$this->db->limit(1);
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->row();
		} else {
			return false;
		}
	}

	public function get_product_by_all_s_name_and_id()
	{
		$language_id = get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->distinct();
		$this->db->select('pd.name AS name, p.image AS image, m.name AS manufacturer, ' . 
			get_fields_from_table('product', 'p.', array('product_id', 'model', 'image'), ', ') . 
			get_fields_from_table('product_description', 'pd.', array('name'), '')
			, FALSE);
		$this->db->from('product p');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->join('manufacturer m', 'p.manufacturer_id = m.manufacturer_id', 'left');
		$this->db->where('pd.language_id', (int) $language_id);
		$this->db->where('p.date_available >= UNIX_TIMESTAMP()');
		$this->db->where('p.status', '1');
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	public function get_product_and_desc_by_id($product_id)
	{
		$language_id = get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->distinct();
		$this->db->select('pd.name AS name, p.image AS image, ' . 
			get_fields_from_table('product', 'p.', array(), ', ') . 
			get_fields_from_table('product_description', 'pd.', array(), '')
			, FALSE);
		$this->db->from('product p');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->where('p.product_id', (int) $product_id);
		$this->db->where('pd.language_id', (int) $language_id);
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->row();
		} else {
			return FALSE;
		}
	}

	public function get_product_description_by_id($product_id)
	{
		$product_description_data = array();

		$this->db->select(get_fields_from_table('product_description', 'pd.', array(), ''));
		$this->db->from('product_description pd');
		$this->db->where('pd.product_id', (int) $product_id);
		$query = $this->db->get();
		//$query = $this->db->query("Select * From e_product_description Where product_id = '$product_id'");
		//var_dump($query->result());
		//die();

		foreach ($query->result() as $result) {
			$product_description_data[$result->language_id] = array(
				'seo'				=> $result->seo,
				'name'				=> $result->name,
				'meta_keywords'		=> $result->meta_keywords,
				'meta_description'	=> $result->meta_description,
				'info'				=> $result->info,
				'description'		=> $result->description,
				'video'				=> $result->video,
			);
		}

		return $product_description_data;
	}

	public function product_delete_by_id($product_id)
	{
		if(is_array($product_id)) {
			foreach($product_id as $product) {
				$this->db->delete('product', array('product_id' => (int) $product));
				$this->db->delete('product_description', array('product_id' => (int) $product));
				$this->db->delete('product_option', array('product_id' => (int) $product));
				$this->db->delete('product_option_value', array('product_id' => (int) $product));
				$this->db->delete('product_discount', array('product_id' => (int) $product));
				$this->db->delete('product_image', array('product_id' => (int) $product));
				$this->db->delete('product_related', array('product_id' => (int) $product));
				$this->db->delete('product_to_category', array('product_id' => (int) $product));
				$this->db->delete('review', array('product_id' => (int) $product));
			}
		} else {
			$this->db->delete('product', array('product_id' => (int) $product_id));
			$this->db->delete('product_description', array('product_id' => (int) $product_id));
			$this->db->delete('product_option', array('product_id' => (int) $product_id));
			$this->db->delete('product_option_value', array('product_id' => (int) $product_id));
			$this->db->delete('product_discount', array('product_id' => (int) $product_id));
			$this->db->delete('product_image', array('product_id' => (int) $product_id));
			$this->db->delete('product_related', array('product_id' => (int) $product_id));
			$this->db->delete('product_to_category', array('product_id' => (int) $product_id));
			$this->db->delete('review', array('product_id' => (int) $product_id));
		}

		return TRUE;
	}
	
	public function xml_product_control($model) {
		$query = $this->db->select("model")
				->from("product p")
				->where("p.model",$model)
				->limit(1)
				->get();
		return $query->row() ? true : false;
	}

	public function get_product_uuid($prefix = '')
	{
		$chars = md5(uniqid(mt_rand(), true));
		$uuid  = substr($chars,0,8) . '-';
		$uuid .= substr($chars,8,4) . '-';
		$uuid .= substr($chars,12,4) . '-';
		$uuid .= substr($chars,16,4) . '-';
		$uuid .= substr($chars,20,12);
		return $prefix . $uuid;
	}

	public function get_product_categories_by_id($product_id)
	{
		$product_category_data = array();
		$this->db->select(get_fields_from_table('product_to_category', 'p2c.', array(), ''));
		$this->db->from('product_to_category p2c');
		$this->db->where('p2c.product_id', (int) $product_id);
		$query = $this->db->get();

		foreach($query->result() as $result) {
			$product_category_data[] = $result->category_id;
		}

		return $product_category_data;
	}

	public function get_products_by_category_id($category_id)
	{
		$language_id = get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->select(
			get_fields_from_table('product', 'p.', array(), ', ') .
			get_fields_from_table('product_description', 'pd.', array(), ', ') .
			get_fields_from_table('product_to_category', 'p2c.', array(), '')
		);
		$this->db->from('product_to_category p2c');
		$this->db->join('product p', 'p2c.product_id = p.product_id', 'left');
		$this->db->join('product_description pd', 'pd.product_id = p.product_id', 'left');
		$this->db->where('p.status', '1');
		$this->db->where('pd.language_id', (int) $language_id);
		$this->db->where('p2c.category_id', (int) $category_id);
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	public function get_product_related_by_id($product_id)
	{
		$product_related_data = array();
		$this->db->select(get_fields_from_table('product_related', 'pr.', array(), ''));
		$this->db->from('product_related pr');
		$this->db->where('product_id', (int) $product_id);
		$query = $this->db->get();

		foreach ($query->result() as $result) {
			$product_related_data[] = $result->related_id;
		}

		return $product_related_data;
	}

	public function get_product_images_by_id($product_id)
	{
		$this->db->select(get_fields_from_table('product_image', 'pi.', array(), ''));
		$this->db->from('product_image pi');
		$this->db->where('product_id', (int) $product_id);
		$query = $this->db->get();		

		if($query->num_rows()) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	public function get_product_options($product_id)
	{
		$product_option_data = array();

		$language_id = get_language('language_id', config('site_ayar_yonetim_dil'));
		$this->db->select(
			get_fields_from_table('product_option', 'po.', array(), ', ') .
			get_fields_from_table('option', 'o.', array(), ', ') .
			get_fields_from_table('option_description', 'od.', array(), '')
		);
		$this->db->from('product_option po');
		$this->db->join('option o', 'po.option_id = o.option_id', 'left');
		$this->db->join('option_description od', 'o.option_id = od.option_id', 'left');
		$this->db->where('po.product_id', (int) $product_id);
		$this->db->where('od.language_id', (int) $language_id);
		$product_option_query = $this->db->get();

		foreach ($product_option_query->result_array() as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox') {
				$product_option_value_data = array();	

				$this->db->select(
					get_fields_from_table('product_option_value', 'pov.', array(), ', ') .
					get_fields_from_table('option_value', 'ov.', array(), '')
				);
				$this->db->from('product_option_value pov');
				$this->db->join('option_value ov', 'pov.option_value_id = ov.option_value_id', 'left');
				$this->db->where('pov.product_option_id', (int) $product_option['product_option_id']);
				$product_option_value_query = $this->db->get();

				foreach ($product_option_value_query->result_array() as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id'	=> $product_option_value['product_option_value_id'],
						'option_value_id'			=> $product_option_value['option_value_id'],
						'quantity'					=> $product_option_value['quantity'],
						'subtract'					=> $product_option_value['subtract'],
						'price'						=> $product_option_value['price'],
						'price_prefix'				=> $product_option_value['price_prefix'],
					);
				}

				$product_option_data[] = array(
					'product_option_id'				=> $product_option['product_option_id'],
					'option_id'						=> $product_option['option_id'],
					'name'							=> $product_option['name'],
					'type'							=> $product_option['type'],
					'product_option_value'			=> $product_option_value_data,
					'required'						=> $product_option['required'],
					'character_limit'				=> $product_option['character_limit']
				);				
			} else {
				$product_option_data[] = array(
					'product_option_id'				=> $product_option['product_option_id'],
					'option_id'						=> $product_option['option_id'],
					'name'							=> $product_option['name'],
					'type'							=> $product_option['type'],
					'option_value'					=> $product_option['option_value'],
					'required'						=> $product_option['required'],
					'character_limit'				=> $product_option['character_limit']
				);				
			}
		}	

		return $product_option_data;
	}

	public function get_product_discounts_by_id($product_id)
	{
		$this->db->select(get_fields_from_table('product_discount', 'pd.', array(), ''));
		$this->db->from('product_discount pd');
		$this->db->where('product_id', (int) $product_id);
		$this->db->order_by('pd.quantity, pd.priority, pd.price');
		$query = $this->db->get();		

		if($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}

	public function get_product_specials_by_id($product_id)
	{
		$this->db->select(get_fields_from_table('product_special', 'ps.', array(), ''));
		$this->db->from('product_special ps');
		$this->db->where('product_id', (int) $product_id);
		$this->db->order_by('ps.quantity, ps.priority, ps.price');
		$query = $this->db->get();		

		if($query->num_rows()) {
			return $query->result_array();
		} else {
			return FALSE;
		}
	}

	public function get_features_by_category_ids_content($category_ids = array(), $product_id = FALSE)
	{
		$this->db->select(
			get_fields_from_table('category_features', 'cf.', array(), '')
		);
		$this->db->from('category_features cf');
		$this->db->where_in('cf.category_id', $category_ids);
		$this->db->where('cf.status', '1');
		$query = $this->db->get();

		$return_data = array();
		foreach($query->result() as $result) {

			$this->db->select(
				get_fields_from_table('category_features_description', 'cfd.', array(), ', ') .
				get_fields_from_table('product_featured', 'pf.', array('value'), '')
			);
			$this->db->from('category_features_description cfd');
			if($product_id) {
				$this->db->join('product_featured pf', 'cfd.feature_id = pf.feature_id AND cfd.language_id = pf.language_id AND pf.product_id = '. (int) $product_id .'', 'left');
			} else {
				$this->db->join('product_featured pf', 'cfd.feature_id = pf.feature_id AND cfd.language_id = pf.language_id AND pf.product_id = NULL', 'left');
			}
			$this->db->where('cfd.feature_id', (int) $result->feature_id);
			$query = $this->db->get();

			$description_data = array();
			foreach($query->result() as $result_r) {
				$key = $result_r->language_id;
				$send_data['feature_id']	= $result_r->feature_id;
				$send_data['name']			= $result_r->name;
				$send_data['value']			= ($result_r->value != '') ? $result_r->value : '';
				$send_data['lang_data']		= get_language_v2(NULL, $result_r->language_id);
				$description_data[$key]		= $send_data;
			}

			$this->load->model('yonetim/urunler/product_category_model');

			$key = $result->feature_id;
			$return_data[$key] = array(
				'name' => $this->product_category_model->get_category_path_by_id($result->category_id),
				'data' => $description_data
			);
		}

		return $return_data;
	}

	public function get_features_by_product_id($product_id)
	{
		return array();
	}

	public function change_homepage_position($product_id, $status)
	{
		switch ($status) {
			case 'hide':
				$this->db->update('product', array('show_homepage' => (int) '0'), array('product_id' => $product_id));
				break;
			case 'show':
				$this->db->select('product_id, show_homepage');
				$this->db->where('show_homepage >', '0');
				$query = $this->db->get('product');
				$show_row = $query->last_row();
				if($show_row) {
					$this->db->update('product', array('show_homepage' => (int) ($show_row->show_homepage + 1)), array('product_id' => $product_id));
				} else {
					$this->db->update('product', array('show_homepage' => (int) '1'), array('product_id' => $product_id));
				}
				break;
			case 'first':
				$this->db->update('product', array('show_homepage' => (int) '1'), array('product_id' => $product_id));
				break;
			case 'last':
				$this->db->select('product_id, show_homepage');
				$this->db->where('show_homepage >', '0');
				$query = $this->db->get('product');
				$last_row = $query->last_row();
				if($last_row) {
					$this->db->update('product', array('show_homepage' => (int) ($last_row->show_homepage + 1)), array('product_id' => $product_id));
				} else {
					$this->db->update('product', array('show_homepage' => (int) '1'), array('product_id' => $product_id));
				}
				break;
			case 'next':
				$this->db->select('product_id, show_homepage');
				$this->db->where('show_homepage >', '0');
				$this->db->where('product_id', (int) $product_id);
				$query = $this->db->get('product');
				$next_row = $query->next_row();
				if($next_row) {
					$this->db->update('product', array('show_homepage' => (int) ($next_row->show_homepage + 1)), array('product_id' => $product_id));
				} else {
					$this->db->update('product', array('show_homepage' => (int) '1'), array('product_id' => $product_id));
				}
				break;
			case 'previous':
				$this->db->select('product_id, show_homepage');
				$this->db->where('show_homepage >', '0');
				$this->db->where('product_id', (int) $product_id);
				$query = $this->db->get('product');
				$previous_row = $query->previous_row();
				if($previous_row) {
					if($previous_row->show_homepage > 2) {
						$this->db->update('product', array('show_homepage' => (int) ($previous_row->show_homepage - 1)), array('product_id' => $product_id));
					}
				} else {
					$this->db->update('product', array('show_homepage' => (int) '1'), array('product_id' => $product_id));
				}
				break;
			default:
				break;
		}
	}

}

?>