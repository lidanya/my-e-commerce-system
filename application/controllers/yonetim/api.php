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
		$file = "http://www.technomodel.com/xml/dealer/categories.xml?dId=480&k=6earD4KOoJaQpyzb";
		$categories = @simplexml_load_file($file);
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
		$file = "http://www.technomodel.com/xml/dealer/products.xml?dId=480&k=6earD4KOoJaQpyzb";
		$products = @simplexml_load_file($file);
		if ($products) {
			foreach ($products->children() as $urun) {


				//p($returnNames);
				//exit;
				//exit;
				// download main image
				//echo $urun->images->image[0]; exit;
				$new_image_name = $this->downloadImages($urun->images->image[0], $urun->name);

				//echo $urun->desi; exit;
				$olcu = pow($urun->desi * 3000,1/3); // width, height,length
				$olcu = number_format($olcu, 2, '.', '');
				//echo $olcu; 
				//exit;
				//price type
				$price_type = 1;
				if ($urun->currency == 'Dolar')
					$price_type = 2;
				else if ($urun->currency == 'Euro')
					$price_type = 3;

				$urunData = array(
					"model" => (string) $urun->id,
					"quantity" => $urun->stock,
					"stock_status_id" => 0,
					"image" => $new_image_name,
					"price" => $urun->price,
					"price_type" => $price_type,
					"stock_type" => 12,
					"tax" => $urun->kdv,
					"date_available" => time() + 30 * 24 * 60 * 60, // default 1 ay
					"status" => 1,
					"feature_status" => 0,
					"cargo_required" => 1,
					"date_added" => time(),
					"date_modified" => time(),
					"length" => $olcu,
					"width" => $olcu,
					"height" => $olcu,
				);
				$inserted_id = $this->product_product_model->Ekle($urunData, "product");

				$urunDesc = array(
					"product_id" => $inserted_id,
					"language_id" => 1,
					"seo" => url_title($urun->name, "dash", TRUE),
					"name" => (string) $urun->name,
					"description" => (string) $urun->description
				);
				$this->product_product_model->Ekle($urunDesc, "product_description");
				// diğer image işlemleri
				$i = 1;
				$returnNames = array();
				if ($urun->images->children()->count() > 0) {
					foreach ($urun->images->children() as $image) {
						if ($i != 1) {
							$returnNames[] = $this->downloadImages($image, $urun->name . "_" . $i);
						}
						++$i;
					}
				}
				// diğer image son

				if ($returnNames) {
					foreach ($returnNames as $path) {
						$urunImgData = array(
							"product_id" => $inserted_id,
							"image" => (string) $path
						);
						$this->product_product_model->Ekle($urunImgData, "product_image");
					}
				}
				// category işlemleri
				if ($urun->categories->children()->count() > 0) {

					// rc model ile ilişkilendirme
					$urunProductToCategory = array(
						"product_id" => $inserted_id,
						"category_id" => 177
					);
					$this->product_product_model->Ekle($urunProductToCategory, "product_to_category");
					foreach ($urun->categories->children() as $category) {
						//echo $category;
						$urunProductToCategory = array(
							"product_id" => $inserted_id,
							"category_id" => (int) $category
						);
						$this->product_product_model->Ekle($urunProductToCategory, "product_to_category");
					}
				}

				echo "başarılı";
				exit;
			}
		}
	}

	public function downloadImages($_im_url, $name) {
		$img = @file_get_contents($_im_url);
		$new_image_name = "data/xml_resimleri/" . url_title($name) . ".jpg";
		@file_put_contents(DIR_IMAGE . $new_image_name, $img);
		return $new_image_name;
	}

}