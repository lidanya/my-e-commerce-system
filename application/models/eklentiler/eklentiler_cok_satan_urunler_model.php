<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class eklentiler_cok_satan_urunler_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Çok Satan Ürünlerimiz Model Yüklendi');
	}

	function yeni_urunler_listele()
	{
		if(eklenti_ayar('cok_satan_urunler', 'siralama_sekli') != NULL)
		{
			$order = eklenti_ayar('cok_satan_urunler', 'siralama_sekli');
		} else {
			$order = 'desc';
		}

		if(eklenti_ayar('cok_satan_urunler', 'siralama_limit') != NULL)
		{
			$limit = eklenti_ayar('cok_satan_urunler', 'siralama_limit');
		} else {
			$limit = 5;
		}

        $sql = "SELECT sd.stok_kodu, SUM(stok_miktar) as totalStock FROM e_siparis_detay sd
        JOIN e_siparis s ON s.siparis_id = sd.siparis_id
        WHERE s.siparis_flag = 2
        GROUP BY sd.stok_kodu
        ORDER BY totalStock ".$order."
        LIMIT ".$limit;


        $query = $this->db->query($sql);
        $prods = $query->result_array();

        $stokKodList = array();
        if($prods)  {
            foreach($prods as $prod) {
                $stokKodList[] = $prod['stok_kodu'];
            }
        }
        if(!$stokKodList)
            return FALSE;
		$language_id = get_language('language_id');
		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS pd.name AS name, p.image AS image, m.name AS manufacturer, ' . 
			get_fields_from_table('product', 'p.', array(), ', ') . 
			get_fields_from_table('product_description', 'pd.', array(), '')
			, FALSE);
		$this->db->from('product p');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->join('manufacturer m', 'p.manufacturer_id = m.manufacturer_id', 'left');
		$this->db->where('pd.language_id', (int) $language_id);
		$this->db->where('p.date_available >= UNIX_TIMESTAMP()');
		$this->db->where('p.status', '1');
		$this->db->where_in('p.model', $stokKodList);
		$this->db->limit($limit);
		$query = $this->db->get();
		$total_row = $this->db->select('FOUND_ROWS() as total')->get()->row()->total;
		if($query->num_rows()) {
			return array(
				'query' => $query->result(),
				'total' => $total_row
			);
		} else {
			return FALSE;
		}
	}

	function kontrol()
	{
		if(eklenti_ayar('cok_satan_urunler', 'siralama_sekli') != NULL)
		{
			$order = eklenti_ayar('cok_satan_urunler', 'siralama_sekli');
		} else {
			$order = 'desc';
		}

		if(eklenti_ayar('cok_satan_urunler', 'siralama_limit') != NULL)
		{
			$limit = eklenti_ayar('cok_satan_urunler', 'siralama_limit');
		} else {
			$limit = 5;
		}

		$language_id = get_language('language_id');
		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS pd.name AS name, p.image AS image, m.name AS manufacturer, ' . 
			get_fields_from_table('product', 'p.', array(), ', ') . 
			get_fields_from_table('product_description', 'pd.', array(), '')
			, FALSE);
		$this->db->from('product p');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->join('manufacturer m', 'p.manufacturer_id = m.manufacturer_id', 'left');
		$this->db->where('pd.language_id', (int) $language_id);
		$this->db->where('p.date_available >= UNIX_TIMESTAMP()');
		$this->db->where('p.status', '1');
		$this->db->order_by('p.viewed', $order);
		$this->db->limit($limit);
		$check = $this->db->count_all_results();

		if($check) {
			return true;
		} else {
			return false;
		}
	}
}