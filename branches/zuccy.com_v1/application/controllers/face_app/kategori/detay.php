<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class detay extends Face_Controller {

	/**
	 * Kategori Detay construct
	 *
	 * @return void
	 * @author Serkan Koch, Serkan Koch -> E-Ticaret Sistemim
	 **/

	function __construct()
	{
		parent::__construct();
		$this->load->model('category_model');
	}

	function index($seo, $sort_link = 'price-asc', $page = 0)
	{
		$content_data['breadcrumbs'] = array();
		$content_data['breadcrumbs'][] = array(
			'href'		=> face_site_url('site/index'),
			'text'		=> lang('messages_breadcrumbs_homepage'),
			'separator'	=> FALSE
		);

		if($seo != 'tum_kategoriler') {
			$path = '';
			$parts = explode('---', $seo);

			foreach($parts as $path_id) {
				$category_info = $this->category_model->get_category_by_seo($path_id);

				if ($category_info) {
					if (!$path) {
						$path = $path_id;
					} else {
						$path .= '---' . $path_id;
					}

					$content_data['breadcrumbs'][] = array(
						'href'		=> face_site_url($path . '--category'),
						'text'		=> $category_info->name,
						'separator'	=> ' &gt; '
					);
				}
			}
		
			$category_id = array_pop($parts);
		} else {
			$category_id = 0;
			$content_data['breadcrumbs'][] = array(
				'href'		=> face_site_url('tum_kategoriler--category'),
				'text'		=> lang('messages_breadcrumbs_all_categories'),
				'separator'	=> ' &gt; '
			);
		}

		if($seo == 'tum_kategoriler') {
			$_order								= (eklenti_ayar('kategori', 'siralama_sekli')) ? eklenti_ayar('kategori', 'siralama_sekli') : 'desc';
			$content_data['sub_category']		= $this->category_model->get_categories_by_parent_id(0, '-1', 'c.category_id', $_order);
			$content_data['sort_link']			= $sort_link;
			$content_data['category_info'] 		= FALSE;
			$content_data['category_products']	= FALSE;
			$content_data['seo']				= $seo;
		} else {
			$category_info = $this->category_model->get_category_by_seo($category_id);
			if(!$category_info) {
				redirect(face_site_url('site/index'));
			}

			$content_data['category_info'] = $category_info;

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
			$category_products = $this->product_model->get_products_by_category_id($category_info->category_id, $sort, $order, $page, $limit);

			$content_data['category_products'] = $category_products;
			if($category_products) {
				$content_data['category_products_pagination'] = create_pagination(face_site_url($seo . '--category/' . $sort_link), $category_products['total'], $limit, $uri_segment, 'face');
			} else {
				$content_data['category_products_pagination'] = FALSE;
			}

			$_order								= (eklenti_ayar('kategori', 'siralama_sekli')) ? eklenti_ayar('kategori', 'siralama_sekli') : 'desc';
			$content_data['sub_category']		= $this->category_model->get_categories_by_parent_id($category_info->category_id, '-1', 'c.category_id', $_order);
			$content_data['sort_link']			= $sort_link;
			$content_data['seo']				= $seo;
		}

		/* Sayfa Tanımlamaları */
		$this->template->add_region('baslik');
		$this->template->add_region('keywords');
		$this->template->add_region('description');
		if($seo != 'tum_kategoriler') {
			$this->template->write('baslik', $category_info->name);
			$this->template->write('keywords', $category_info->meta_keywords);
			$this->template->write('description', $category_info->meta_description);
		}

		$this->template->set_master_template(face_tema() . 'kategori/index');

		$this->template->add_region('content');
		$this->template->write_view('content', face_tema() . 'kategori/content', $content_data);
		$this->template->add_css(APPPATH . 'views/' . face_tema_asset() . 'css/listeler.css');

		$this->template->render();
	}

}
?>