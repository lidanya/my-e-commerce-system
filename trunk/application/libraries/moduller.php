<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class moduller
{
	var $ci;

	function __construct()
	{
		log_message('debug', 'Modüller Kütüphanesi Yüklendi');
		
		// Yapıcılar
		$this->ci =& get_instance();
	}

	function modul_cagir($yer = 'sol', $type = 'site')
	{
		$moduller = $this->ci->eklentiler_model->eklenti_cagir($yer);
		$i = 0;
		foreach($moduller->result() as $modul_detay)
		{
			$i++;
			if ($type == 'site') {
				if(file_exists(APPPATH . 'views/'. tema() .'eklentiler/' . $modul_detay->eklenti_ascii . '_view' . EXT))
				{
					$model = 'eklentiler_' . $modul_detay->eklenti_ascii . '_model';
					$this->ci->load->model('eklentiler/' . $model);
					if($this->ci->$model->kontrol())
					{
						$language_id						= $this->ci->fonksiyonlar->get_language('language_id');
						$baslik_unserialize					= @unserialize($modul_detay->eklenti_baslik);
						$gonder['eklenti_baslik']			= (isset($baslik_unserialize[$language_id])) ? $baslik_unserialize[$language_id] : NULL;
						$gonder['eklenti_baslik_goster']	= ($modul_detay->eklenti_baslik_goster == '1') ? true : false;
						$gonder['eklenti_detay']			= $modul_detay;
						$gonder['yer']						= $yer;
						$this->ci->load->view(tema() . 'eklentiler/' . $modul_detay->eklenti_ascii . '_view', $gonder) . "\n";
					}
				} else {
					log_message('error', $modul_detay->eklenti_baslik . ' eklentisi bulunamadı '. tema() . 'eklentiler/' . $modul_detay->eklenti_ascii . '_view' .' sayfa yüklenmedi.');
				}
			} elseif($type == 'face') {
				if(file_exists(APPPATH . 'views/'. face_tema() .'eklentiler/' . $modul_detay->eklenti_ascii . '_view' . EXT))
				{
					$model = 'eklentiler_' . $modul_detay->eklenti_ascii . '_model';
					$this->ci->load->model('eklentiler/' . $model);
					if($this->ci->$model->kontrol())
					{
						$language_id						= $this->ci->fonksiyonlar->get_language('language_id');
						$baslik_unserialize					= @unserialize($modul_detay->eklenti_baslik);
						$gonder['eklenti_baslik']			= (isset($baslik_unserialize[$language_id])) ? $baslik_unserialize[$language_id] : NULL;
						$gonder['eklenti_baslik_goster']	= ($modul_detay->eklenti_baslik_goster == '1') ? true : false;
						$gonder['eklenti_detay']			= $modul_detay;
						$gonder['yer']						= $yer;
						$this->ci->load->view(face_tema() . 'eklentiler/' . $modul_detay->eklenti_ascii . '_view', $gonder) . "\n";
					}
				} else {
					log_message('error', $modul_detay->eklenti_baslik . ' eklentisi bulunamadı '. face_tema() . 'eklentiler/' . $modul_detay->eklenti_ascii . '_view' .' sayfa yüklenmedi.');
				}
			}
		}
	}
}