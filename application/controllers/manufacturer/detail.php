<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class detail extends Public_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('site/manufacturer_model');
		$this->load->model('site/product_model');
	}

	function index($seo, $sort_link = 'price-asc', $page = 0)
	{
		$check = $this->manufacturer_model->get_manufacturer_by_seo($seo);
		if($check) {
			$manifacturer_info = $check;

			$_izin_verilenler = array('product_id-desc', 'product_id-asc', 'price-desc', 'price-asc', 'name-desc', 'name-asc', 'viewed-desc', 'viewed-asc');

			if(in_array($sort_link, $_izin_verilenler)) {
				$_sort_link = $sort_link;
			} else {
				$_sort_link = 'price-asc';
			}

			$sort_link_e = explode('-', $_sort_link);
			$sort  = $sort_link_e[0];
			$order = $sort_link_e[1];
			$content_data['sort_link'] = $sort_link;

			$limit = (config('site_ayar_urun_site_sayfa')) ? config('site_ayar_urun_site_sayfa') : 9;
			$uri_segment = 4;
			$products = $this->product_model->get_manufacturer_product_by_id($manifacturer_info->manufacturer_id, $sort, $order, $page);

			$content_data['manufacturer_info'] = $manifacturer_info;
			$content_data['urunler'] = $products;
			if($products) {
				$content_data['pagination'] = create_pagination(site_url($seo . '--manufacturer/' . $sort_link), $products['total'], $limit, $uri_segment);
			} else {
				$content_data['pagination'] = FALSE;
			}

			$this->template->set_master_template(tema() . 'manufacturer/detail');

			$content_data['keywords'] = $manifacturer_info->meta_keywords;
			$content_data['description'] = $manifacturer_info->meta_description;

			$this->template->add_region('content');
			$this->template->write_view('content', tema() . 'manufacturer/detail_content', $content_data);

			$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/anasayfa.css');
			$this->template->render();
		} else {
			redirect('');
		}
	}
	
}
?>