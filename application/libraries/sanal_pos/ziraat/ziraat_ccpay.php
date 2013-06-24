<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

 /*
	SQL Kodu
	O:8:"stdClass":1:{s:5:"ccpay";O:8:"stdClass":2:{s:10:"merchantid";s:0:"";s:8:"password";s:0:"";}}
	Php Kodu
	$olustur->{'ccpay'}->merchantid	= '';
	$olustur->{'ccpay'}->password	= '';
	echo serialize($olustur);
 */

class ziraat_ccpay
{
	public $ci;
	protected $banka = 'ziraat';
	protected $ascii = 'ccpay';
	protected $banka_bilgi = false;

	/**
	 * ccpay construct
	 *
	 * @return void
	 * @author E-Ticaret Sistemim
	 **/

	function __construct()
	{
		log_message('debug', 'ziraat_ccpay Library Yüklendi');
		$this->ci =& get_instance();
		$this->ci->load->library('encrypt');
	}

	function banka_bilgi_tanimla($gelen_degerler = null)
	{
		$durum = false;
		if(!is_null($gelen_degerler))
		{
			if(is_object($gelen_degerler))
			{
				$this->banka_bilgi = $gelen_degerler;
				$durum = true;
			}
		}
		return $durum;
	}

	function banka_bilgi()
	{
		if(is_object($this->banka_bilgi))
		{
			return $this->banka_bilgi;
		} else {
			return false;
		}
	}

	function form_gonder($gelen_veriler = null)
	{
		if(!is_null($gelen_veriler) AND $banka_bilgi = $this->banka_bilgi())
		{
			$pos_model_bilgi = $banka_bilgi->banka_bilgi->{$this->ascii};

			$banka_host_bilgileri = $this->ci->config->item('banka_detaylari');
			$basarili_url	= $this->ci->config->item('banka_pos_gonderim_adres_basarili');
			$hatali_url		= $this->ci->config->item('banka_pos_gonderim_adres_hatali');

			$siparis_id		= $gelen_veriler->siparis_id;
			$fatura_id		= $gelen_veriler->fatura_id;
			$banka_adi		= $this->banka;

			$banka_tip_		= $this->ci->encrypt->encode($this->ascii);
			$banka_tip		= base64_encode($banka_tip_);

			$kart_numarasi	= strtr($gelen_veriler->kart_numarasi, array(' ' => ''));
			$kart_yil		= $gelen_veriler->kart_numarasi_yil;
			$kart_ay		= $gelen_veriler->kart_numarasi_ay;
			$kart_cvv2		= $gelen_veriler->kart_numarasi_guvenlik_kodu;
			$fiyat			= $gelen_veriler->fiyat;
			$kart_tipi		= $gelen_veriler->kart_tipi;
			$taksit			= ($gelen_veriler->taksit != '' OR $gelen_veriler->taksit > 0) ? $gelen_veriler->taksit : '';

			$random_id		= md5($gelen_veriler->user_id . microtime());

			$strsuccessurl_ = strtr($basarili_url, array('{siparis_id}' => $siparis_id, '{fatura_id}' => $fatura_id, '{banka}' => $banka_adi, '{tip}' => $banka_tip));
			$strsuccessurl  = ssl_url($strsuccessurl_);

			$post_url = $strsuccessurl;
			$form = '';
			$form .= $this->ci->config->item('banka_pos_pesin_mesaji');
			$form .= '<script type="text/javascript">' . "\n";
			$form .= '$(document).ready(function(){' . "\n";
			$form .= 'setTimeout("$(\'#' . md5($random_id) . '\').submit()", 1500);';
			$form .= '});';
			$form .= '</script>' . "\n";
			$form .= '<form action="' . $post_url . '" id="' . md5($random_id) . '" method="post">';
			$form .= form_hidden('kredi_kart_no', $kart_numarasi);
			$form .= form_hidden('kredi_kart_yil', $kart_yil);
			$form .= form_hidden('kredi_kart_ay', $kart_ay);
			$form .= form_hidden('kredi_kart_ccv', $kart_cvv2);
			$form .= form_hidden('kredi_kart_taksit', $taksit);
			$form .= form_hidden('toplam_ucret', $fiyat);
			$form .= form_hidden('kredi_kart_tipi', $kart_tipi);
			$form .= '</form>';

			$gonder->veri	= $form;
			$gonder->kod	= '';
			$gonder->durum	= true;
			$gonder->mesaj	= '';
			$gonder->debug	= '';
		} else {
			$gonder->veri	= '';
			$gonder->kod	= '';
			$gonder->durum	= false;
			$gonder->mesaj	= 'Banka Bilgilerine Ulaşılamadı';
			$gonder->debug	= '';
		}

		return $gonder;
	}

	function form_sonuc($gelen_veriler = null)
	{
		if(!is_null($gelen_veriler) AND $banka_bilgi = $this->banka_bilgi())
		{
			$pos_model_bilgi = $banka_bilgi->banka_bilgi->{$this->ascii};

			$gelen_veriler->fiyat = isset($gelen_veriler->post_verileri['toplam_ucret']) ? strtr($gelen_veriler->post_verileri['toplam_ucret'], array('.' => '')) : 0;
			$gelen_veriler->taksit = isset($gelen_veriler->post_verileri['kredi_kart_taksit']) ? $gelen_veriler->post_verileri['kredi_kart_taksit'] : 0;
			$gelen_veriler->kart_numarasi = isset($gelen_veriler->post_verileri['kredi_kart_no']) ? $gelen_veriler->post_verileri['kredi_kart_no'] : 0;
			$gelen_veriler->kart_numarasi_ay = isset($gelen_veriler->post_verileri['kredi_kart_ay']) ? $gelen_veriler->post_verileri['kredi_kart_ay'] : 0;
			$gelen_veriler->kart_numarasi_yil = isset($gelen_veriler->post_verileri['kredi_kart_yil']) ? $gelen_veriler->post_verileri['kredi_kart_yil'] : 0;
			$gelen_veriler->kart_numarasi_guvenlik_kodu = isset($gelen_veriler->post_verileri['kredi_kart_ccv']) ? $gelen_veriler->post_verileri['kredi_kart_ccv'] : 0;

			$type = "Sale"; // Sale: Satış PreAuth Ön Otorizasyon
			$currencycode = "949"; // 949 TRL
			$password = $pos_model_bilgi->password; // Sanal pos api kullanicisi sifresi
			$merchantid = $pos_model_bilgi->merchantid; // Sanal pos magaza numarasi
			$ipaddress = $gelen_veriler->ip_adres;
			$emailaddress = $gelen_veriler->email_adres;
			$orderid = md5($gelen_veriler->user_id . microtime());
			$taksit = ($gelen_veriler->taksit != '' OR $gelen_veriler->taksit > 0) ? $gelen_veriler->taksit : ''; //Taksit Sayısı. Boş gönderilirse taksit yapılmaz
			$kart_tipi = ucfirst($gelen_veriler->post_verileri['kredi_kart_tipi']);

			$banka_host_bilgileri = $this->ci->config->item('banka_detaylari');
			$basarili_url = $this->ci->config->item('banka_pos_gonderim_adres_basarili');
			$hatali_url = $this->ci->config->item('banka_pos_gonderim_adres_hatali');

			$siparis_id = $gelen_veriler->siparis_id;
			$fatura_id  = $gelen_veriler->fatura_id;
			$fatura_bilgi = $gelen_veriler->fatura_bilgi;
			$teslimat_bilgi = $gelen_veriler->teslimat_bilgi;
			$uye_bilgi	= $gelen_veriler->uye_bilgi;
			$banka_adi  = $this->banka;

			$banka_tip_ = $this->ci->encrypt->encode($this->ascii);
			$banka_tip  = base64_encode($banka_tip_);

			$number = $gelen_veriler->kart_numarasi;
			$date = $gelen_veriler->kart_numarasi_yil . $gelen_veriler->kart_numarasi_ay; // 201003 gibi
			$cvv2 = $gelen_veriler->kart_numarasi_guvenlik_kodu;
			$amount = $gelen_veriler->fiyat;

			$request = '<?xml version="1.0" encoding="UTF-8"?>
<PosRequest>
<Originator>
<Acquirer ID="1"/>
<Merchant HostMerchantId="'. $merchantid .'" Password="'. $password .'"/>
</Originator>
<Trnx Type="'. $type .'"/>
<Payment>
<PAN PAN="'. $number .'" Expiry="'. $date .'" CVV2="'. $cvv2 .'" Brand="'. $kart_tipi .'"/>
<Amount Amount="'. $amount .'" Type="1" Code="'. $currencycode .'"/>
<Options>
<Item Name="Instalment" Value="'. $taksit .'" />
</Options>
</Payment>
</PosRequest>';

			$hata_mesajlari = array(
				'00'	=> 'Onay - otorizasyon verildi',
				'01'	=> 'Kartı veren bankayı arayınız',
				'05'	=> 'Red - işlem onaylanmadı',
				'12'	=> 'Geçersiz işlem',
				'13'	=> 'Geçersiz İşlem Tutarı',
				'14'	=> 'Geçersiz kart numarası',
				'41'	=> 'Kayıp kart',
				'43'	=> 'Çalıntı kart',
				'51'	=> 'Kart limiti yetersiz',
				'54'	=> 'Vade sonu geçmiş kart',
				'55'	=> 'Hatalı kart şifresi',
				'56'	=> 'Tanımsız kart',
				'57'	=> 'İşlem tipine izin yok',
				'58'	=> 'İşlem tipi terminale kapalı',
				'91'	=> 'Kartı veren banka hizmetdışı',
				'96'	=> 'Sistem arızası',
				'312'	=> 'Güvenlik kodu hatalı'
			);

			$post_url = 'https://'. $banka_host_bilgileri[$banka_bilgi->kk_banka_adi_ascii]['host'][$banka_bilgi->kk_banka_test_tipi] . $banka_host_bilgileri[$banka_bilgi->kk_banka_adi_ascii][$this->ascii];

			$client = new SoapClient($post_url);
			$param = array('parameters' => array('xmlRequest' => $request));
			$result = $client->__call('Process', $param);

			$xml = $result->ProcessResult;
			$result->ProcessResult = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $xml);

			$results = $result->ProcessResult;
			$xml_obje_sonucu = new SimpleXMLElement($results);

			if(isset($xml_obje_sonucu->Result->Code) AND ($xml_obje_sonucu->Result->Code != '00'))
			{
				$gonder->durum = false;
				$gonder->kod   = (isset($xml_obje_sonucu->Result->Code)) ? $xml_obje_sonucu->Result->Code:'92';
				$code = (int) $xml_obje_sonucu->Result->Code;
				if(isset($hata_mesajlari[$code]))
				{
					$olumsuz_hata_mesaji = $hata_mesajlari[$code];
				} else {
					$olumsuz_hata_mesaji = 'Hata Oluştu İşlem Gerçekleşmedi';
				}
				$gonder->mesaj = $olumsuz_hata_mesaji;
			} elseif (isset($xml_obje_sonucu->Result->Code) AND $xml_obje_sonucu->Result->Code == '00') {
				$gonder->kod   = '00';
				$gonder->durum = true;
				$gonder->mesaj = 'İşleminiz Başarılı Bir Şekilde Gerçekleşti';
			} else {
				$gonder->kod   = '92';
				$gonder->durum = false;
				$gonder->mesaj = 'Hata Oluştu İşlem Gerçekleşmedi';
			}

			$gonder->ozel_veri->fiyat = isset($gelen_veriler->post_verileri['toplam_ucret']) ? $gelen_veriler->post_verileri['toplam_ucret'] : 0;
			$gonder->ozel_veri->kart_numarasi = isset($gelen_veriler->post_verileri['kredi_kart_no']) ? $gelen_veriler->post_verileri['kredi_kart_no'] : 0;
			$gonder->ozel_veri->taksit = isset($gelen_veriler->post_verileri['kredi_kart_taksit']) ? $gelen_veriler->post_verileri['kredi_kart_taksit'] : 0;
			$gonder->veri	= '';
			$gonder->debug	= $xml_obje_sonucu;
		} else {
			$gonder->durum	= false;
			$gonder->mesaj	= 'Banka Bilgilerine Ulaşılamadı';
			$gonder->veri	= '';
			$gonder->kod	= '';
			$gonder->debug	= '';
		}
		return $gonder;
	}

}

/* End of file isimsiz.php */
/* Location: ./dev10/application/libraries/isimsiz.php */

?>