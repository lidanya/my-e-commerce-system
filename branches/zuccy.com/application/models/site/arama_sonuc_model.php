<?php if (! defined('BASEPATH')) exit('No direct script access');

/**
 * -------------------------------------------------------------------------- 
 * ÜRÜN ARAMA MODEL DOSYASI
 * --------------------------------------------------------------------------
 *
 * @package ürünler
 * @subpackage ürünler / arama
 **/
class Arama_sonuc_model extends CI_Model 
{
	
	/**
	 * Bu olay ile alakalı tablo isimleri bu dizi değişkende tutulur.
	 *
	 * @var array
	 **/
	private $_table = array(
		'product', 
		'product_description', 
		'product_discount', 
		'product_to_category', 
		'product_special', 
		'manufacturer'
		);
	
	/**
	 * Veritabanından sıralama bilgileri bu değişkende tutulur.
	 *
	 * @var array
	 **/
	private $order_by = array(
		"t1.price DESC",
		"t1.price ASC",
		"t2.name DESC",
		"t2.name ASC",
		"t1.product_id DESC",
		"t1.product_id ASC"
		);
	
	/**
	 * Mevcut dilin ID 'si.
	 *
	 * @var int
	 **/
	private $cur_lang;
	
	
	public function __construct() {
		
		parent::__construct();
		
		// INIT
		$this->cur_lang = get_language('language_id');
	}
		
	public function ara($options = array(), $limit = 0, $offset = 0) {
		
		$this->db
			->distinct()
			->select('SQL_CALC_FOUND_ROWS ' .
			get_fields_from_table($this->_table[0], 't1.', array(), ', ') . 
			get_fields_from_table($this->_table[1], 't2.', array(), ', ') 
			, false)
			->from($this->_table[0] . ' AS t1') // stok tablosu
			->join($this->_table[1] . ' AS t2', 't1.product_id = t2.product_id') // stok detay tablosu
			->where('t1.date_available >= UNIX_TIMESTAMP()')
			->where('t1.status', '1')
			->where('t2.language_id', $this->cur_lang);
			
		// WHERE
		
		// kategori
		// fixed by serkan koch. bu kısım hatalı
		
        //var_export($options['category']); return;
		
		//$i = 0;
		/*$child = array();
		if(isset($options['category']) && count($options['category'])>0):
		
		foreach($options['category'] as $kat )
		{
			
			$child[] = get_child($kat);
		    
			//++$i;
		}
		foreach($child as $kat )
		{
			if(is_array($kat))
			{
				foreach($kat as $k)
				{
				  $child2[] = $k;
				}
			}
			else
			{
				$child2[] = $kat;
			 	
			
			}	
		    
			//++$i;
		}
		endif;
		
		//print_r($child2); print_r($options['category']);  return; */
		
		
		if (isset($options['category']) && count($options['category']) > 0) {
			
			$this->db
				->join($this->_table[3] . ' AS t4', 't1.product_id = t4.product_id')
				->where_in('t4.category_id', $options['category']);
				//->or_where_in('t4.category_id',$child2);
		} 
		
		// marka
		if (isset($options['manufacturer']) && count($options['manufacturer']) > 0) {
			
			$this->db
				->join($this->_table[5] . ' AS t5', 't1.manufacturer_id = t5.manufacturer_id')
				->where_in('t5.manufacturer_id', $options['manufacturer']);
		} 
		
		// aranan kelime
		if(isset($options['aranan']) && $options['aranan']) {
			$tag = $options['aranan'];
			//return $tag;
			$this->db->where("(
				t2.name LIKE '%{$this->db->escape_like_str($tag)}%' 
				OR t2.description LIKE '%{$this->db->escape_like_str($tag)}%' 
				OR t1.model LIKE '%{$this->db->escape_like_str($tag)}%'
				)");
		//return $q;
		}
		
		// Fiyat aralığı
		if(isset($options['max_fiyat']) && $options['max_fiyat']) {
			//return $options['min_fiyat'];
			$this->db->where(array('t1.price >=' => (float) $options['min_fiyat'], 't1.price <=' => (float) $options['max_fiyat']));
		}
		
		// kampanyalı ürünler
		if(isset($options['tip']) && $options['tip'] == '1') {
			$this->db
				->join($this->_table[4] . ' AS t3', 't1.product_id = t3.product_id') // stok indirim kampanya tablosu
				//->where('t3.indirim_flag', '1')
				->where('t3.date_start <= UNIX_TIMESTAMP()')
				->where('t3.date_end >= UNIX_TIMESTAMP()');
		}
		
		// indirimli ürünler
		if(isset($options['tip']) && $options['tip'] == '2') {
			$this->db
				->join($this->_table[2] . ' AS t3', 't1.product_id = t3.product_id') // stok indirim kampanya tablosu
				//->where('t3.indirim_flag', '1')
				->where('t3.date_start <= UNIX_TIMESTAMP()')
				->where('t3.date_end >= UNIX_TIMESTAMP()');
		}
		
		// ORDER BY
		if(isset($options['sort_by']) && isset($this->order_by[$options['sort_by']])) {
			$this->db->order_by($this->order_by[$options['sort_by']]);
		}
			
	
		// LIMIT
		if($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		
		$q = $this->db->get();
		
		//return $q; 
		
		$result = array(
			'sorgu' => $q,
			'toplam' => $this->db->query("SELECT FOUND_ROWS() AS total_rows")->row()->total_rows
			);
			
		return $result;
	}
	
	/**
	 * Ürünlerdeki minumum ve maksimum fiyatı döndürür.
	 * 
	 * @return object
	 **/
	public function min_max_fiyat($options = array()) {
			
		$this->db
			->select('MIN(t1.price) AS min_fiyat, MAX(t1.price) AS max_fiyat')
			->from($this->_table[0] . ' AS t1') // stok tablosu
			->join($this->_table[1] . ' AS t2', 't1.product_id = t2.product_id') // stok detay tablosu
			->where('t1.date_available >= UNIX_TIMESTAMP()')
			->where('t1.status', '1')
			->where('t2.language_id', $this->cur_lang);
			
		// WHERE
		
		// kategori
		if (isset($options['category']) && count($options['category']) > 0) {
			
			$this->db
				->join($this->_table[3] . ' AS t4', 't1.product_id = t4.product_id') 
				->where_in('t4.category_id', $options['category']);
		 } 
		 
		// marka
		if (isset($options['manufacturer']) && count($options['manufacturer']) > 0) {
			
			$this->db
				->join($this->_table[5] . ' AS t5', 't1.manufacturer_id = t5.manufacturer_id')
				->where_in('t5.manufacturer_id', $options['manufacturer']);
		} 
		
		// aranan kelime
		if(isset($options['aranan']) && $options['aranan']) {
			$tag = $options['aranan'];
			$this->db->where("(
				t2.name LIKE '%{$this->db->escape_like_str($tag)}%' 
				OR t2.description LIKE '%{$this->db->escape_like_str($tag)}%' 
				OR t1.model LIKE '%{$this->db->escape_like_str($tag)}%'
				)");
		}
		
		// kampanyalı ürünler
		if(isset($options['tip']) && $options['tip'] == '1') {
			$this->db
				->join($this->_table[4] . ' AS t3', 't1.product_id = t3.product_id') // stok indirim kampanya tablosu
				//->where('t3.indirim_flag', '1')
				->where('t3.date_start <= UNIX_TIMESTAMP()')
				->where('t3.date_end >= UNIX_TIMESTAMP()');
		}
		
		// indirimli ürünler
		if(isset($options['tip']) && $options['tip'] == '2') {
			$this->db
				->join($this->_table[2] . ' AS t3', 't1.product_id = t3.product_id') // stok indirim kampanya tablosu
				//->where('t3.indirim_flag', '1')
				->where('t3.date_start <= UNIX_TIMESTAMP()')
				->where('t3.date_end >= UNIX_TIMESTAMP()');
		}
		
		$q = $this->db->get();
		
		if($q->num_rows()) {
			return $q->row();
		} else {
			(object) array('min_fiyat' => 0, 'max_fiyat' => 0);
		}
	}
}

/* End of file Arama_sonuc_model.php */