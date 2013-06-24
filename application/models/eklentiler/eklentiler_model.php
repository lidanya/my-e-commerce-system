<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class eklentiler_model extends CI_Model
{

	protected $extension_options = array();

	public function __construct() 
	{
		parent::__construct();
		log_message('debug', 'Eklentiler Model Yüklendi');
		$this->set_extension_options();
	}

	public function set_extension_options()
	{
		$this->db->select(
			get_fields_from_table('eklentiler_ayalar', 'ea.', array(), '')	
		);
		$this->db->from('eklentiler_ayalar ea');
		$query = $this->db->get();
		foreach($query->result() as $result) {
			$send							= array();
			$send['eklenti_ascii']			= $result->eklenti_ascii;
			$send['ayar_adi']				= $result->ayar_adi;
			$send['ayar_deger']				= $result->ayar_deger;

			$key							= md5($result->eklenti_ascii . '-' . $result->ayar_adi);
			$this->extension_options[$key]	= $send;
		}
	}

	public function get_extension_option($key, $config, $item = 'ayar_deger')
	{
		$extension_options					= $this->extension_options;
		$key								= md5($key . '-' . $config);
		if(isset($extension_options[$key])) {
			if($item != '') {
				if(isset($extension_options[$key][$item])) {
					return $extension_options[$key][$item];
				} else {
					return FALSE;
				}
			} else {
				return $extension_options[$key];
			}
		} else {
			return FALSE;
		}
	}

	public function get_extension_options()
	{
		$extension_options = $this->extension_options;
		return $extension_options;
	}

	function eklenti_cagir($yer)
	{
		$this->db->order_by('eklenti_sira', 'asc');
		$this->db->like('eklenti_yer', $yer);
		$this->db->select('eklenti_id, eklenti_yer, eklenti_baslik, eklenti_baslik_goster, eklenti_ascii, eklenti_durum, eklenti_sira');
		$sorgu = $this->db->get_where('eklentiler', array('eklenti_durum' => '1'));
		return $sorgu;
	}

	function eklenti_ayar($ascii, $okunacak)
	{
		$this->db->select('eklenti_ascii, ayar_adi, ayar_deger');
		$sorgu = $this->db->get_where('eklentiler_ayalar', array('eklenti_ascii' => $ascii, 'ayar_adi' => $okunacak), 1);
		if($sorgu->num_rows() > 0)
		{
			$bilgi = $sorgu->row();
			return $bilgi->ayar_deger;
		} else {
			return NULL;
		}
	}
}