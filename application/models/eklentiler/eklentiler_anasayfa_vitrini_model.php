<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 **/

class eklentiler_anasayfa_vitrini_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Ana Sayfa Vitrini Model Yüklendi');
	}

	function anasayfa_vitrin_listele()
	{
		if(eklenti_ayar('anasayfa_vitrini', 'siralama_sekli') != NULL)
		{
			$siralama = eklenti_ayar('anasayfa_vitrini', 'siralama_sekli');
		} else {
			$siralama = 'desc';
		}
		if(eklenti_ayar('anasayfa_vitrini', 'siralama_limit') != NULL)
		{
			$siralama_limit = eklenti_ayar('anasayfa_vitrini', 'siralama_limit');
		} else {
			$siralama_limit = 18;
		}

		$language_id = get_language('language_id');
		$this->db->distinct();
		$this->db->select('pd.name AS name, p.image AS image, m.name AS manufacturer, ' . 
			get_fields_from_table('product', 'p.', array(), ', ') . 
			get_fields_from_table('product_description', 'pd.', array(), '')
			, FALSE);
		$this->db->from('product p');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->join('manufacturer m', 'p.manufacturer_id = m.manufacturer_id', 'left');
		$this->db->where('pd.language_id', (int) $language_id);
		$this->db->where('p.date_available >= UNIX_TIMESTAMP()');
		$this->db->where('p.status', '1');
		$this->db->where('p.show_homepage >', '0');
		$this->db->order_by('p.show_homepage', $siralama);
		$this->db->limit($siralama_limit ,$siralama);
		$sorgu = $this->db->get();
		$sorgu_say = $this->db->select('FOUND_ROWS() as toplam')->get()->row()->toplam;
		$gonder = array('sorgu' => $sorgu, 'toplam' => $sorgu_say);
		return $gonder;
	}

	function kontrol()
	{
		if(eklenti_ayar('anasayfa_vitrini', 'siralama_sekli') != NULL)
		{
			$siralama = eklenti_ayar('anasayfa_vitrini', 'siralama_sekli');
		} else {
			$siralama = 'desc';
		}
		if(eklenti_ayar('anasayfa_vitrini', 'siralama_limit') != NULL)
		{
			$siralama_limit = eklenti_ayar('anasayfa_vitrini', 'siralama_limit');
		} else {
			$siralama_limit = 18;
		}

		$language_id = get_language('language_id');
		$this->db->distinct();
		$this->db->select('pd.name AS name, p.image AS image, m.name AS manufacturer, ' . 
			get_fields_from_table('product', 'p.', array(), ', ') . 
			get_fields_from_table('product_description', 'pd.', array(), '')
			, FALSE);
		$this->db->from('product p');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->join('manufacturer m', 'p.manufacturer_id = m.manufacturer_id', 'left');
		$this->db->where('pd.language_id', (int) $language_id);
		$this->db->where('p.date_available >= UNIX_TIMESTAMP()');
		$this->db->where('p.status', '1');
		$this->db->where('p.show_homepage >', '0');
		$this->db->order_by('p.product_id', $siralama);
		$this->db->limit($siralama_limit ,$siralama);
		$check = $this->db->count_all_results();

		if($check) {
			return true;
		} else {
			return false;
		}
	}
}