<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class mail_sablon_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function kampanyali_urunler()
	{
		$this->db->group_by('stok.stok_id');
		$this->db->select('stok_indirim_kampanya.*, stok.*, stok_detay.*, stok_kategori.*, stok_stok_kategori.*');
		$this->db->join('stok', 'stok.stok_id = stok_indirim_kampanya.stok_id');
		$this->db->join('stok_detay', 'stok_detay.stok_id = stok.stok_id');
		$this->db->join('stok_stok_kategori', 'stok_stok_kategori.stok_id = stok_indirim_kampanya.stok_id');
		$this->db->join('stok_kategori', 'stok_kategori.stk_kategori_id = stok_stok_kategori.kategori_id');
		$result = $this->db->get_where('stok_indirim_kampanya', array('indirim_flag' => '1','indirim_basla <' => time(), 'indirim_bitir >' => time(), 'stok_kategori.stk_kategori_flag' => '1', 'stok.stok_flag' => '1', 'stok_stok_kategori.stk_kat_flag' => '1','indirim_tip'=>'kampanya'), 16);
		return $result;
	}

	function indirimli_urunler()
	{
		$this->db->group_by('stok.stok_id');
		$this->db->select('stok_indirim_kampanya.*, stok.*, stok_detay.*, stok_kategori.*, stok_stok_kategori.*');
		$this->db->join('stok', 'stok.stok_id = stok_indirim_kampanya.stok_id');
		$this->db->join('stok_detay', 'stok_detay.stok_id = stok.stok_id');
		$this->db->join('stok_stok_kategori', 'stok_stok_kategori.stok_id = stok_indirim_kampanya.stok_id');
		$this->db->join('stok_kategori', 'stok_kategori.stk_kategori_id = stok_stok_kategori.kategori_id');
		$result = $this->db->get_where('stok_indirim_kampanya', array('indirim_flag' => '1','indirim_basla <' => time(), 'indirim_bitir >' => time(), 'stok_kategori.stk_kategori_flag' => '1', 'stok.stok_flag' => '1', 'stok_stok_kategori.stk_kat_flag' => '1','indirim_tip'=>'indirim'), 16);
		return $result;
	}

	function yeni_urunler()
	{
		$this->db->group_by('stok.stok_id');
		$this->db->select('stok.*, stok_kategori.*, stok_stok_kategori.*, stok_detay.*');
		$this->db->join('stok', 'stok.stok_id = stok_stok_kategori.stok_id');
		$this->db->join('stok_detay', 'stok_detay.stok_id = stok.stok_id');
		$this->db->join('stok_kategori', 'stok_kategori.stk_kategori_id = stok_stok_kategori.kategori_id');
		$result = $this->db->get_where('stok_stok_kategori', array('stok_detay.stk_detay_yeni_urun' => '1', 'stk_kat_flag' => '1', 'stok_kategori.stk_kategori_flag' => '1', 'stok.stok_flag' => '1'), 16);
		return $result;
	}

	function normal_urunler()
	{
		$this->db->group_by('stok.stok_id');
		$this->db->select('stok.*, stok_kategori.*, stok_stok_kategori.*, stok_detay.*');
		$this->db->join('stok', 'stok.stok_id = stok_stok_kategori.stok_id');
		$this->db->join('stok_detay', 'stok_detay.stok_id = stok.stok_id');
		$this->db->join('stok_kategori', 'stok_kategori.stk_kategori_id = stok_stok_kategori.kategori_id');
		$result = $this->db->get_where('stok_stok_kategori', array('stk_kat_flag' => '1', 'stok_kategori.stk_kategori_flag' => '1', 'stok.stok_flag' => '1'), 16);
		return $result;	
	}
}