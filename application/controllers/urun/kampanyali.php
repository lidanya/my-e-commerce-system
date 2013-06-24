<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class kampanyali extends Public_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('site/product_model');
	}
	
	function index($sort_link = 'price-asc', $page = 0)
	{
		$this->template->add_region('baslik');
		$this->template->write('baslik', lang('messages_campaign_products_title'));

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
		$uri_segment = 6;
		$new_products = $this->product_model->get_campaign_product($sort, $order, $page, $limit);

		$content_data['urunler'] = $new_products;
		if($new_products) {
			$content_data['pagination'] = create_pagination(site_url('urun/kampanyali/index/' . $sort_link), $new_products['total'], $limit, $uri_segment);
		} else {
			$content_data['pagination'] = FALSE;
		}

		$this->template->set_master_template(tema() . 'urun/kampanyali');
		$this->template->add_region('content');
		$this->template->write_view('content', tema() . 'urun/kampanyali_content',$content_data);
		$this->template->add_css(APPPATH . 'views/' . tema_asset() . 'css/anasayfa.css');
		//$this->template->add_js(APPPATH . 'views/' . tema() . 'js/countdown.js');
		
		//SKOCH
		$this->template->add_css(APPPATH . 'views/' . tema() . 'css/jquery.countdown.css');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown.js');
		$this->template->add_js(APPPATH . 'views/' . tema() . 'js/jquery.countdown-tr.js');
	    $this->output->enable_profiler(false);
		//SKOCH
		$this->template->render();
	}
	
}
?>