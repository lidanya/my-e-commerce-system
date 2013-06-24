<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class Arama extends Face_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('daynex_pagination');
		$this->load->model('site/arama_sonuc_model');
		$this->load->model('site/category_model');
		
		$this->load->helper('array');
	}

	function index() {

		$limit = (config('site_ayar_urun_site_sayfa')) ? config('site_ayar_urun_site_sayfa') : 9;
		$limit = _get('limit', $limit);

		$result = $this->arama_sonuc_model->ara($_GET, $limit, _get('per_page', 0));

		// seçili kategoriler
		$get_categories = array();
		if($this->input->get('category')) {
			$get_categories = $this->input->get('category');
		}

		// ürün kategorileri
		$r = $this->category_model->get_categories_by_parent_id(0, -1);
		$categories = array();

		if($r) {
			foreach($r as $row) {
				$categories[$row->category_id] = $row->name;
			}			
		}

		// seçili markalar
		$get_manufacturers = array();
		if($this->input->get('manufacturer')) {
			$get_manufacturers = $this->input->get('manufacturer');
		}

		// markalar
		$q = $this->db->query("SELECT manufacturer_id, name FROM {$this->db->dbprefix('manufacturer')}");
		$manufacturers = array();
		if($q->num_rows() > 0) {
			$r = $q->result();
			foreach($r as $row) {
				$manufacturers[$row->manufacturer_id] = $row->name;
			}
		}

		// PAGINATION
		$q_string = $this->input->server('QUERY_STRING');
		parse_str(html_entity_decode($q_string), $output);
		unset($output['per_page']);
		$write = http_build_query($output);

		$uri				= face_site_url('urun/arama/index?' . $write);
		$uri_segment 		= NULL;
		$total_rows			= $result['toplam'];
		if($result) {
			$pagination 	= create_pagination($uri, $total_rows, $limit, $uri_segment, 'face', TRUE);
		} else {
			$pagination 	= FALSE;
		}

		// select boxes
		$tip = array(
			'1' => array('Tüm Ürünleri Göster', 'Sadece Kampanyalı Ürünleri', 'Sadece İndirimli Ürünleri'),
			'2' => array('Show All Products', 'Only Campaign Products', 'Only Discount Products')
			);
		$sort_by = array(
			'1' => array('Azalan Fiyata Göre', 'Artan Fiyata Göre', 'Z den A ya Göre', 'A dan Z ye Göre', 'Yeniden Eskiye', 'Eskiden Yeniye'),
			'2' => array('Price High > Low', 'Price Low > High', 'Name Z - A', 'Name A - Z', 'Newest', 'Oldest')
			);
		$per_page = array(
			'1' => array('25' => '25 li Göster', '50' => '50 li Göster', '75' => '75 li Göster', '100' => '100 lü Göster'),
			'2' => array('25' => '25 Per Page', '50' => '50 Per Page', '75' => '75 Per Page', '100' => '100 Per Page')
			);

		$data = array(
			'pagination'		=> $pagination,
			'get_categories'	=> $get_categories,
			'categories'		=> $categories,
			'get_manufacturers' => $get_manufacturers,
			'manufacturers'     => $manufacturers,
			'arama_sonuc'		=> $result,
			'aranan_kelime'		=> $this->input->get('aranan', true), // xss koruma
			'fiyat'				=> $this->arama_sonuc_model->min_max_fiyat($_GET), // minumum ve maksimum fiyat

			// Select Boxes
			'tip' => $tip[get_language('language_id')],
			'sort_by' => $sort_by[get_language('language_id')],
			'per_page' => $per_page[get_language('language_id')]
		);

		$this->template->set_master_template(face_tema() . 'urun/arama_sonuclari');

		$this->template->add_region('baslik');
		$this->template->write('baslik', lang('messages_product_search_title') . ' : ' . $data['aranan_kelime']);

		$this->template->add_region('content');
		$this->template->write_view('content', face_tema() . 'urun/arama_sonuclari_content',$data);
		$this->template->add_css(APPPATH . 'views/' . face_tema_asset() . 'css/listeler.css');
		$this->template->add_js(APPPATH . 'views/' . face_tema() . 'js/arama_sonuc.js');
		$this->template->add_js(APPPATH . 'views/' . face_tema() . 'js/arama_sonuclari.js');
		$this->template->render();
		
	}
}


/* End of file arama.php */