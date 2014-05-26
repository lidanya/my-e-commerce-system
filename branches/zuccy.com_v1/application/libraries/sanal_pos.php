<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class sanal_pos
{
	protected $ci;

	function __construct()
	{
		log_message('debug', 'Sanal Pos Kütüphanesi Yüklendi');
		$this->ci =& get_instance();
	}

	function banka_sec_baglan($banka_id, $extra_degerler = array())
	{
		$banka_sorgu = $this->ci->db->get_where('odeme_secenek_kredi_karti', array('kk_id' => $banka_id, 'kk_banka_durum' => '1'), 1);
		if($banka_sorgu->num_rows() > 0)
		{
			$banka_bilgi = $banka_sorgu->row();

			$pos_tipi = $banka_bilgi->kk_banka_pos_tipi;

			if(file_exists(APPPATH . 'libraries/sanal_pos/' . $banka_bilgi->kk_banka_adi_ascii . '/' . $banka_bilgi->kk_banka_adi_ascii . '_' . $pos_tipi . EXT))
			{
				$yeni_banka_ascii = $banka_bilgi->kk_banka_adi_ascii . '_' . $pos_tipi;
				$this->ci->load->library('sanal_pos/' . $banka_bilgi->kk_banka_adi_ascii . '/' . $yeni_banka_ascii);

				if($banka_bilgi->kk_banka_bilgi != '')
				{
					$banka_verileri = @unserialize($banka_bilgi->kk_banka_bilgi);
				} else {
					$banka_verileri = false;
				}

				//var_dump($banka_verileri);

				$banka_bilgi_gonder->kk_id = $banka_bilgi->kk_id;
				$banka_bilgi_gonder->kk_odeme_id = $banka_bilgi->kk_odeme_id;
				$banka_bilgi_gonder->kk_banka_adi = $banka_bilgi->kk_banka_adi;
				$banka_bilgi_gonder->kk_banka_adi_ascii = $banka_bilgi->kk_banka_adi_ascii;
				$banka_bilgi_gonder->kk_banka_durum = $banka_bilgi->kk_banka_durum;
				$banka_bilgi_gonder->kk_banka_pos_tipi = $banka_bilgi->kk_banka_pos_tipi;
				$banka_bilgi_gonder->kk_banka_test_tipi = $banka_bilgi->kk_banka_test_tipi;
				$banka_bilgi_gonder->kk_banka_standart = $banka_bilgi->kk_banka_standart;
				$banka_bilgi_gonder->kk_banka_taksit = $banka_bilgi->kk_banka_taksit;
				$banka_bilgi_gonder->kk_pesin_komisyon = $banka_bilgi->kk_pesin_komisyon;
				$banka_bilgi_gonder->banka_bilgi = $banka_verileri;

				$bilgi_tanimlama = $this->ci->$yeni_banka_ascii->banka_bilgi_tanimla($banka_bilgi_gonder);

				if($bilgi_tanimlama)
				{
					$gonder->durum		= true;
					$gonder->mesaj		= '';
					$gonder->kod		= '';
					$gonder->veri		= '';
					$gonder->class 		= $this->ci->$yeni_banka_ascii;
				} else {
					log_message('error', $banka_bilgi->kk_banka_adi . ' bankasının bilgileri' . $banka_bilgi->kk_banka_adi_ascii . ' modeline atanamadı');
					$gonder->durum		= false;
					$gonder->mesaj		= 'Banka bilgileri atanamadı';
					$gonder->kod		= '';
					$gonder->veri		= '';
				}
				return $gonder;
			} else {
				log_message('error', $banka_bilgi->kk_banka_adi . ' bankasının ' . $banka_bilgi->kk_banka_adi_ascii . ' modelinin dosyasına erişilemedi, dosya : ' . APPPATH . 'libraries/sanal_pos/' . $banka_bilgi->kk_banka_adi_ascii . '/' . $banka_bilgi->kk_banka_adi_ascii . '_' . $pos_tipi . EXT);
				$gonder->durum		= false;
				$gonder->mesaj		= $banka_bilgi->kk_banka_adi . ' bankasının ' . $banka_bilgi->kk_banka_adi_ascii . ' modelinin dosyasına erişilemedi';
				$gonder->kod		= '';
				$gonder->veri		= '';
				return $gonder;
			}
		} else {
			log_message('error', $banka_id . ' numaralı bankaya ulaşılamadı');
			$gonder->durum		= false;
			$gonder->mesaj		= '<div id="onaysiz"><b>Ödeme Sırasında Hata Oluştu</b><br/>Teknik bir nedenden dolayı şuanda kredi kartı ile ödeme yapılamıyor.</div>
<div id="islem_sonu"><a class="buton" href="javascript:;" onclick="location = \''. ssl_url('odeme/adim_4/kredi_karti/'. $extra_degerler['siparis_id'] .'/'. $extra_degerler['fatura_id']) .'\';"><span><b>Geri Dön</b></span></a></div>
<div class="clear"></div>';
			$gonder->kod		= '';
			$gonder->veri		= '';
			return $gonder;
		}
	}

	function form_gonder($gelen_bilgiler)
	{
		if(is_object($gelen_bilgiler))
		{
			// Örnek Gidecek Veriler
			//$siparis_bilgi_gonder->siparis_id = '1';
			//$siparis_bilgi_gonder->fatura_id = '2';
			//$siparis_bilgi_gonder->ip_adres = '127.0.0.1';
			//$siparis_bilgi_gonder->email_adres = 'etinali@gmail.com';
			//$siparis_bilgi_gonder->user_id = '31';
			//$siparis_bilgi_gonder->fiyat = '5.50';
			//$siparis_bilgi_gonder->taksit = 0;
			//$siparis_bilgi_gonder->kart_numarasi = '1111111111111111';
			//$siparis_bilgi_gonder->kart_numarasi_ay = '11';
			//$siparis_bilgi_gonder->kart_numarasi_yil = '11';
			//$siparis_bilgi_gonder->kart_numarasi_guvenlik_kodu = '111';
			$form_gonder = $this->ci->$yeni_banka_ascii->form_gonder($gelen_bilgiler);
			return $form_gonder;
		} else {
			$gonder->durum		= false;
			$gonder->mesaj		= '<div id="onaysiz"><b>Ödeme Sırasında Hata Oluştu</b><br/>Teknik bir nedenden dolayı şuanda kredi kartı ile ödeme yapılamıyor.</div>
<div id="islem_sonu"><a class="buton" href="javascript:;" onclick="location = \''. ssl_url('odeme/adim_4/kredi_karti/'. $extra_degerler['siparis_id'] .'/'. $extra_degerler['fatura_id']) .'\';"><span><b>Geri Dön</b></span></a></div>
<div class="clear"></div>';
			$gonder->kod		= '';
			$gonder->veri		= '';
			return $gonder;
		}
	}
}