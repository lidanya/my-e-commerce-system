<?php

if (!defined('BASEPATH')) {
	header('Location: http://' . getenv('SERVER_NAME') . '/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 * */
class api extends Admin_Controller
{

	public function __construct() {
		parent::__construct();
		log_message('debug', 'api');

		$this->load->model('yonetim/urunler/product_category_model');
		$this->load->model('yonetim/urunler/product_product_model');
	}

	public function addCategories() {
		$file = "http://www.technomodel.com/xml/dealer/categories.xml?dId=56&k=EgytqyRd5REodUht";
		$categories = simplexml_load_file($file);

		if ($categories->children()->count() > 0) {
			foreach ($categories->children() as $category) {
				$this->get_nodes($category); // <category></category>
			}
		}
	}

	/**
	 * 
	 * @param type $category
	 * @param type $parent_id default RC Model kategori ID'si 177
	 */
	public function get_nodes($category, $parent_id = 177) {
		$id = (int) $category->attributes()->id;
		$name = $category->attributes()->name;
		$this->product_category_model->addCat($id, trim($name), $parent_id);
		if ($category->children()->count() > 0) {
			foreach ($category->children() as $category_child) {
				$this->get_nodes($category_child, $id);
			}
		}
	}

	public function addProducts() {
		$file = "http://www.technomodel.com/xml/dealer/products.xml?dId=56&k=EgytqyRd5REodUht";
		$urunList = @simplexml_load_file($file);
		if ($urunList) {
			foreach ($urunList->children() as $urun) {
				// donwload main image
				$img = @file_get_contents($urun->resim);
				$new_image_name = "data/xml_resimleri/" . url_title($urun->isim) . "-22.jpg";
				@file_put_contents(DIR_IMAGE . $new_image_name, $img);
				
				//price type
				$price_type = 1;
				if ($urun->kur == 'USD')
					$price_type = 2;
				else if ($urun->kur == 'EUR')
					$price_type = 3;
					
				$urunData = array("model" => $urun->id,
					"quantity" => $urun->Stok,
					"stock_status_id" => 0,
					"image" => $new_image_name,
					"price" => $urun->satis,
					"price_type" => $price_type,
					"stock_type"=>12,
					"tax"=>$urun->KDV,
					"date_available"=>time()+30*24*60*60, // default 1 ay
					"feature_status"=>0,
					
					
					
				);
			}
		}
	}

}