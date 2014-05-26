<?php if (! defined('BASEPATH')) exit('No direct script access');

/**
 * --------------------------------------------------------------------------
 * KUPON KODLARI MODEL DOSYASI
 * --------------------------------------------------------------------------
 *
 * @package coupon codes
 * @author  (Serkan Koch)
 **/
class Coupon_model extends CI_Model 
{
	
	/**
	 * Bu olay ile alakalı tablo isimleri.
	 *
	 * @var array
	 **/
	private $_table = array('coupon');
	
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * İlgili tablolaya kayıtlar ekler (bulk)
	 * 
	 * @param array
	 * @return int eklenen kayıt sayısını döndürür.
	 **/
	public function add($data) {
		
		if(!count($data)) return 0;
		
		$this->db->insert_batch($this->_table[0], $data);
		
		return $this->db->affected_rows();
	}
	
	/**
	 * İlgili tablodan kayıt(lar) çeker
	 * 
	 * @param array şartlar
	 * @param int limit (limit yoksa 0 girilmelidir)
	 * @param int başlangıç satırı
	 * @return array
	 **/
	public function gets($options = array(), $limit = 0, $offset = 0) {
		
		// süresi dolmuş varsa süresi doldu olarak ayarla.
		$this->_check_expired();
		
		$this->db
			->from($this->_table[0]);
			
			
		// WHERE
		
		// by code
		if(isset($options['code']) && $options['code']) {
			$this->db->where('code', $options['code']);
		}
		
		// by start date
		if(isset($options['date_start']) && $options['date_start']) {
			$this->db->where('date_start', $options['date_start']);
		}
		
		// by end date
		if(isset($options['date_end']) && $options['date_end']) {
			$this->db->where('date_end', $options['date_end']);
		}
		
		// by date add
		if(isset($options['date_add']) && $options['date_add']) {
			$this->db->where('date_add', $options['date_add']);
		}
		
		// by status
		if(isset($options['status']) && $options['status'] != 'all') {
			$this->db->where('status', $options['status']);			
		}
		
		// LIMIT
		if($limit > 0) {
			$this->db->limit($limit, $offset);
		}

		$this->db->order_by('date_add', 'desc');

		$q = $this->db->get();
		
		if(!$q->num_rows()) {
			return array('rows' => false, 'total_rows' => 0);
		}
		
		$data = array(
			'rows' => $q->result(),
			'total_rows' => $this->db->query("SELECT FOUND_ROWS() as total_rows")->row()->total_rows
			);
			
		return $data;	
	}
	
	/**
	 * İlgili tablodan kayıtları siler (bulk)
	 * 
	 * @param array silinecek kayıtların ID leri
	 * @return int silinen kayıt sayısını döndürür
	 **/
	public function delete($id) {
		
		if(!is_array($id)) $id = (array)$id;
		
		$this->db
			->where('status <>', '1')
			->where_in('id', $id)
			->delete($this->_table[0]);
			
		return $this->db->affected_rows();	
	}
	
	// HELPER
	
	/**
	 * Süresi dolmuş kodların durumunu süresi doldu olarak günceller.
	 * bu metod sınıf yüklenirken tetiklenir.
	 * 
	 * @return void
	 **/
	private function _check_expired() { 
		
		$this->db->update($this->_table[0], array('status' => '2'), "status = '0' AND date_end < CURDATE()");
	}
	
	/**
	 * Kupon durumuna ait icon döndürür.
	 * 
	 * @param int durum
	 * @return string
	 **/
	public function get_icon($status) {
		
		$icons = array(
			'<img src="' . yonetim_resim() . 'status/gray.png" alt="" style="vertical-align: middle" /> Henüz Kullanılmadı',
			'<img src="' . yonetim_resim() . 'status/green.png" alt="" style="vertical-align: middle" /> Kullanıldı',
			'<img src="' . yonetim_resim() . 'status/red.png" alt="" style="vertical-align: middle" /> Süresi Doldu'
			);
			
		return $icons[$status];
		
	}
}

/* End of file Coupon_model.php */