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
	}

	public function addCategories() {
		$file = "http://www.technomodel.com/xml/dealer/categories.xml?dId=56&k=EgytqyRd5REodUht";
		$xml = simplexml_load_file($file);

		if ($xml->children()->count() > 0) {
			foreach ($xml->children() as $category) {
				$this->get_nodes($category); // <category></category>
			}
		}
	}

	public function get_nodes($category, $parent_id = 0) {
		$id = (int) $category->attributes()->id;
		$name = $category->attributes()->name;
		$this->product_category_model->addCat($id, trim($name), $parent_id);
		if ($category->children()->count() > 0) {
			foreach ($category->children() as $category_child) {
				$this->get_nodes($category_child, $id);
			}
		}
	}

}