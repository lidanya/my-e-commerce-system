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

	public function aktar() {
		//echo "adad"; exit;
		$livedb = $this->load->database("live", TRUE);
		//p($livedb); exit;
		$query = $this->db->select("*")->from("product_to_category")->where("category_id", 167)->get();
		foreach ($query->result() as $p) {
			$productIDList[] = $p->product_id;
		}

		foreach ($productIDList as $pa) {
			$q = $livedb->select("product_id")->from("product")->where("product_id", $pa)->get();

			if ($q->row()) {
				// pd
				$q = $livedb->select("*")->from("product_to_category")->where("product_id", $pa)->where("category_id", 167)->get();
				//$query = $this->db->select("category_id")->from("product_to_category")->where("product_id",$pa)->get();
				if (!$q->row()) {
					$arr = array("product_id" => $pa, "category_id" => 167);
					//$livedb->where('product_id', $pa);
					$livedb->insert('product_to_category', $arr);
					echo "3-";
				}
				// 
				//p($query->row_array());
			}
		}
		//p($productIDList); 
	}

	public function addCategories() {

		$categories = @simplexml_load_file($this->file['categories']);
		if ($categories->children()->count() > 0) {
			foreach ($categories->children() as $category) {
				$this->get_nodes($category); // <category></category>
			}
		}
	}

	public function clearImgTags() {
		$query = $this->db->select("pd.description,p.product_id")->from("product_description pd")
				->join("product p", "p.product_id = pd.product_id")
				->where("p.status", 1)
				->get();
		foreach ($query->result() as $key => $row) {
			//$description = '<img src="http://www.technomodel.com/data/images/7/F/7f526647b71c3c5be32892bb9bc9ebb3l.jpg" alt="" /> <p><img src="http://www.technomodel.com/data/images/7/E/7ebafb8d85a015730dd1203377338b59l.jpg" alt="" /></p>';
			$result = $this->getDescriptionImages($row->description);

			if ($result) {
				$data = array("description" => $result);
				$this->product_product_model->Guncelle($data, "product_description", 'product_id', $row->product_id);
				echo "succeed---";
			}
			//if($key == 1) exit;
			//p($result); exit;
			//$row->description;
			//$row->product_id;
		}
	}

	public function available_two_months() {
		$query = $this->db->select("p.product_id")->from("product p")
				->get();
		foreach ($query->result() as $key => $row) {
			$data = array("date_available" => time() + 60 * 24 * 60 * 60);
			$this->product_product_model->Guncelle($data, "product", 'product_id', $row->product_id);
			echo "success---";
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
				//p($parentIDList = kategori_ust_kategori(316, 177)); exit;
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
						"seo" => $this->product_product_model->check_seo(url_title($product->name, "dash", TRUE), 1, TRUE),
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
//							$parentIDList = kategori_ust_kategori($category, 177);
//							if ($parentIDList) {
//								foreach ($parentIDList as $parent_id) {
//									$cQuery = $this->db->select("category_id")
//											->from("product_to_category")
//											->where("category_id", $parent_id)
//											->where("product_id", $inserted_id)
//											->limit(1)
//											->get();
//									if ($cQuery->num_rows() < 1) {
//										$urunProductToCategoryData = array(
//											"product_id" => $inserted_id,
//											"category_id" => (int) $parent_id
//										);
//										$this->product_product_model->Ekle($urunProductToCategoryData, "product_to_category");
//									}
//								}
//							}
							$urunProductToCategoryData = array(
								"product_id" => $inserted_id,
								"category_id" => (int) $category
							);
							$this->product_product_model->Ekle($urunProductToCategoryData, "product_to_category");
						}
					}
					// category işlemleri son

					echo "basarili<br/>";
					//exit;
				} else {
					echo "urun onceden eklenmis <br/>";
				}
			}
		}
	}

	public function downloadImages($_im_url = "http://www.technomodel.com/data/images/4/5/45dcfaf60f0418b39e94ac58da80b9f7s.png", $name = "no-name", $path = "xml_resimleri") {
		$headers = @get_headers($_im_url, 1);
		if ($headers["Content-Type"] != "image/jpeg" && $headers["Content-Type"] != "image/png" && $headers["Content-Type"] != "image/gif") {
			return "no-image.jpg";
		}
		$ext = ".jpg";
		if ($headers["Content-Type"] == "image/png")
			$ext = ".png";
		else if ($headers["Content-Type"] == "image/gif")
			$ext = ".gif";

		$img = @file_get_contents($_im_url);
		$new_image_name = "data/" . $path . "/" . url_title($name) . $ext;
		@file_put_contents(DIR_IMAGE . $new_image_name, $img);

		return $new_image_name;
	}

	public function getDescriptionImages($description = "") {
		//$description = '<img src="http://www.technomodel.com/data/images/7/F/7f526647b71c3c5be32892bb9bc9ebb3l.jpg" alt="" /> <p><img src="http://www.technomodel.com/data/images/7/E/7ebafb8d85a015730dd1203377338b59l.jpg" alt="" /></p>';
		$doc = new DOMDocument();
		@$doc->loadHTML($description);

		$images = $doc->getElementsByTagName("img");
		//p($images);
		//$a = &$description;
		if ($images->length > 0) {
			for ($i = 0; $i < $images->length; $i++) {
				$src = $images->item($i)->attributes->getNamedItem('src')->nodeValue;
				$name = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
				$newImage = $this->downloadImages($src, $name, "description");
				// Outputs: foo.jpg bar.png
				$description = str_replace($src, show_image($newImage,100,100), $description);
			}
			return $description;
		}
		return FALSE;
		//echo $description;
	}

	public function gd() {
		
	}

	public function relateParents() {
		$query = $this->db->select("product_id")->from("product")->where("status", 1)->get();
		if ($query->result()) {
			foreach ($query->result() as $product) {
				//$product->product_id = 909;
				$query = $this->db->select("category_id")->from("product_to_category")->where("product_id", $product->product_id)->get();
				if ($query->num_rows()) {
					//foreach($r)
					$rows = $query->result();
					//p($rows); exit;
					foreach ($rows as $row) {
						$parentIDList = array();
						//echo $row->category_id;
						$parentIDList = kategori_ust_kategori($row->category_id);
						//p($parentIDList); continue; exit;
						if ($parentIDList) {
							foreach ($parentIDList as $p) {
								if ($p != 0) {
									$query = $this->db->select("*")->from("product_to_category")->where("product_id", $product->product_id)->where("category_id", $p)->get();
									if ($query->num_rows() < 1) {
										echo "yok";
										$arr = array("product_id" => $product->product_id, "category_id" => $p);
										$this->db->insert("product_to_category", $arr);
									}
									//echo $p."-".$product->product_id;
								}
							}
						}
						//p($parentIDList); 
					}
				}
				//exit;
			}
		}
	}

	//$parentIDList = kategori_ust_kategori($category, 177);
}
