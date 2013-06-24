<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class db_ayar
{
	var $ci;
	var $referanslar;
	var $adwords;

	/**
	 * isimsiz construct
	 *
	 * @return void
	 * @author Serkan Koch, Serkan Koch -> E-Ticaret Sistemim
	 **/

	function __construct()
	{
		log_message('debug', 'Db Ayar Library Yüklendi');
		
		$this->ci =& get_instance();
		$this->olustur();
	}
	
	function olustur()
	{
		$db_ayar = $this->ci->load->database('ayarlar', TRUE);
		$ayarlar_tablosu = $db_ayar->get_where('ayarlar', array('ayar_durum' => '1'));
		foreach($ayarlar_tablosu->result() as $ayarlar)
		{
			$kullanici_adi = $this->ci->encrypt->decode($ayarlar->ayar_k_adi);
			$kullanici_sifresi = $this->ci->encrypt->decode($ayarlar->ayar_k_sifresi);
			$db_adi = $this->ci->encrypt->decode($ayarlar->ayar_db_adi);
			$server = $this->ci->encrypt->decode($ayarlar->ayar_server);
			
			$pist = $ayarlar->ayar_baslik;
			
			//log_message('error', $kullanici_adi . $kullanici_sifresi . $db_adi . $server);
			
			$pist_1['hostname'] = $server;
			$pist_1['username'] = $kullanici_adi;
			$pist_1['password'] = $kullanici_sifresi;
			$pist_1['database'] = $db_adi;
			$pist_1['dbdriver'] = "mysql";
			$pist_1['dbprefix'] = "daynex_";
			$pist_1['pconnect'] = FALSE;
			$pist_1['db_debug'] = FALSE;
			$pist_1['cache_on'] = FALSE;
			$pist_1['cachedir'] = "";
			$pist_1['char_set'] = "utf8";
			$pist_1['dbcollat'] = "utf8_general_ci";

			$this->$pist = $this->ci->load->database($pist_1, TRUE);
			
			if($this->$pist->conn_id)
			{
				log_message('debug', $pist . ' database ' . standard_date('DATE_TR', time(), 'tr') . ' açıldı.');
			} else {
				log_message('debug', $pist . ' database ' . standard_date('DATE_TR', time(), 'tr') . ' açılamadı.');
			}
		}
	}
}