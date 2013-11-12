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

	public $file = array(
		"categories" => "http://www.technomodel.com/xml/dealer/categories.xml?dId=480&k=6earD4KOoJaQpyzb",
		"products" => "http://www.technomodel.com/xml/dealer/products.xml?dId=480&k=6earD4KOoJaQpyzb"
	);

	public function __construct() {
		parent::__construct();
		log_message('debug', 'api');

		$this->load->model('yonetim/urunler/product_category_model');
		$this->load->model('yonetim/urunler/product_product_model');
	}

	public function addCategories() {

		$categories = @simplexml_load_file($this->file['categories']);
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

		$products = @simplexml_load_file($this->file['products']);
		if ($products) {
			foreach ($products->children() as $product) {
				
				$urun_control = $this->product_product_model->xml_product_control((string) $product->id);

				if (!$urun_control) {

					$ilk_img_path = $product->xpath("images/image[@main = '1']");
					$new_image_name = $this->downloadImages($ilk_img_path[0], $product->name);

					$WHL = pow($product->desi * 3000, 1 / 3); // width, height,length
					$WHL = number_format($WHL, 2, '.', '');

					$price_type = 1;
					if ($product->currency == 'Dolar')
						$price_type = 2;
					else if ($product->currency == 'Euro')
						$price_type = 3;

					$urunData = array(
						"model" => (string) $product->id,
						"quantity" => $product->stock,
						"stock_status_id" => 0,
						"image" => $new_image_name,
						"price" => $product->price,
						"price_type" => $price_type,
						"stock_type" => 12,
						"tax" => $product->kdv,
						"date_available" => time() + 30 * 24 * 60 * 60, // default 1 ay
						"status" => 1,
						"feature_status" => 0,
						"cargo_required" => 1,
						"date_added" => time(),
						"date_modified" => time(),
						"length" => $WHL,
						"width" => $WHL,
						"height" => $WHL,
					);
					$inserted_id = $this->product_product_model->Ekle($urunData, "product");

					$urunDescData = array(
						"product_id" => $inserted_id,
						"language_id" => 1,
						"seo" => url_title($product->name, "dash", TRUE),
						"name" => (string) $product->name,
						"description" => (string) $product->description
					);
					$this->product_product_model->Ekle($urunDescData, "product_description");
					// diğer image işlemleri
					$i = 1;
					$returnNames = array();
					if ($product->images->children()->count() > 0) {
						foreach ($product->images->children() as $image) {
							if ($image->attributes()->main != 1)
								$returnNames[] = $this->downloadImages($image, $product->name . "_" . $i);
							++$i;
						}
					}

					if ($returnNames) {
						foreach ($returnNames as $path) {
							$urunImgData = array(
								"product_id" => $inserted_id,
								"image" => (string) $path
							);
							$this->product_product_model->Ekle($urunImgData, "product_image");
						}
					}
					// diğer image son
					// category işlemleri
					if ($product->categories->children()->count() > 0) {
						// rc model ile ilişkilendirme
						$urunProductToCategoryData = array(
							"product_id" => $inserted_id,
							"category_id" => 177
						);
						$this->product_product_model->Ekle($urunProductToCategoryData, "product_to_category");
						foreach ($product->categories->children() as $category) {
						$parentIDList = kategori_ust_kategori($category,177);
							$urunProductToCategoryData = array(
								"product_id" => $inserted_id,
								"category_id" => (int) $category
							);
							$this->product_product_model->Ekle($urunProductToCategoryData, "product_to_category");
						}
					}
					// category işlemleri son

					echo "başarılı";
					//exit;
				}
			}
		}
	}

	public function downloadImages($_im_url = "http://demo.minikutu.com", $name = "no-name") {
		$headers = @get_headers($_im_url, 1);
		if ($headers["Content-Type"] != "image/jpeg") {
			return "no-image.jpg";
		}
		@file_get_contents($_im_url);
		$new_image_name = "data/xml_resimleri/" . url_title($name) . ".jpg";
		@file_put_contents(DIR_IMAGE . $new_image_name, $img);

		return $new_image_name;
	}

}