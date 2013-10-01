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
	O:8:"stdClass":1:{s:5:"ccpay";O:8:"stdClass":4:{s:10:"merchantid";s:0:"";s:10:"terminalid";s:0:"";s:8:"username";s:0:"";s:8:"password";s:0:"";}}
	Php Kodu
	$olustur->{'ccpay'}->merchantid	= '';
	$olustur->{'ccpay'}->terminalid	= '';
	$olustur->{'ccpay'}->username	= '';
	$olustur->{'ccpay'}->password	= '';
	echo serialize($olustur);
 */

class garanti_ccpay
{
	public $ci;
	protected $banka = 'garanti';
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
		log_message('debug', 'garanti_ccpay Library Yüklendi');
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
			$fatura_bilgi	= $gelen_veriler->fatura_bilgi;
			$teslimat_bilgi = $gelen_veriler->teslimat_bilgi;
			$uye_bilgi		= $gelen_veriler->uye_bilgi;
			$banka_adi		= $this->banka;

			$banka_tip_		= $this->ci->encrypt->encode($this->ascii);
			$banka_tip		= base64_encode($banka_tip_);

			$security_data_terminal_hesapla = (strlen($pos_model_bilgi->terminalid) < 9) ? str_repeat('0', (9 - strlen($pos_model_bilgi->terminalid))) . $pos_model_bilgi->terminalid : $pos_model_bilgi->terminalid;

			$kart_numarasi	= strtr($gelen_veriler->kart_numarasi, array(' ' => ''));
			$kart_yil		= (strlen($gelen_veriler->kart_numarasi_yil > 2)) ? substr($gelen_veriler->kart_numarasi_yil, 2, 2) : $gelen_veriler->kart_numarasi_yil;
			$kart_ay		= $gelen_veriler->kart_numarasi_ay;
			$kart_cvv2		= $gelen_veriler->kart_numarasi_guvenlik_kodu;
			$fiyat			= str_replace(array(',', '.'), '', $gelen_veriler->fiyat);
			$taksit			= ($gelen_veriler->taksit != '' OR $gelen_veriler->taksit > 0) ? $gelen_veriler->taksit : '';

			$random_id		= md5($gelen_veriler->user_id . microtime());

			$strsuccessurl_ = strtr($basarili_url, array('{siparis_id}' => $siparis_id, '{fatura_id}' => $fatura_id, '{banka}' => $banka_adi, '{tip}' => $banka_tip));
			$strsuccessurl = ssl_url($strsuccessurl_);

			$post_url = $strsuccessurl;
			$form = '';
			$form .= $this->ci->config->item('banka_pos_pesin_mesaji');
			$form .= '<script type="text/javascript">' . "\n";
			$form .= '$(document).ready(function(){' . "\n";
			$form .= 'setTimeout("$(\'#' . $random_id . '\').submit()", 1500);';
			$form .= '});';
			$form .= '</script>' . "\n";
			$form .= '<form action="' . $post_url . '" id="' . $random_id . '" method="post">';
			$form .= form_hidden('kredi_kart_no', $kart_numarasi);
			$form .= form_hidden('kredi_kart_yil', $kart_yil);
			$form .= form_hidden('kredi_kart_ay', $kart_ay);
			$form .= form_hidden('kredi_kart_ccv', $kart_cvv2);
			$form .= form_hidden('kredi_kart_taksit', $taksit);
			$form .= form_hidden('toplam_ucret', $fiyat);
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

			$gelen_veriler->fiyat = isset($gelen_veriler->post_verileri['toplam_ucret']) ? $gelen_veriler->post_verileri['toplam_ucret'] : 0;
			$gelen_veriler->taksit = isset($gelen_veriler->post_verileri['kredi_kart_taksit']) ? $gelen_veriler->post_verileri['kredi_kart_taksit'] : 0;
			$gelen_veriler->kart_numarasi = isset($gelen_veriler->post_verileri['kredi_kart_no']) ? $gelen_veriler->post_verileri['kredi_kart_no'] : 0;
			$gelen_veriler->kart_numarasi_ay = isset($gelen_veriler->post_verileri['kredi_kart_ay']) ? $gelen_veriler->post_verileri['kredi_kart_ay'] : 0;
			$gelen_veriler->kart_numarasi_yil = isset($gelen_veriler->post_verileri['kredi_kart_yil']) ? $gelen_veriler->post_verileri['kredi_kart_yil'] : 0;
			$gelen_veriler->kart_numarasi_guvenlik_kodu = isset($gelen_veriler->post_verileri['kredi_kart_ccv']) ? $gelen_veriler->post_verileri['kredi_kart_ccv'] : 0;

			$siparis_id = $gelen_veriler->siparis_id;
			$fatura_id  = $gelen_veriler->fatura_id;
			$fatura_bilgi = $gelen_veriler->fatura_bilgi;
			$teslimat_bilgi = $gelen_veriler->teslimat_bilgi;
			$uye_bilgi	= $gelen_veriler->uye_bilgi;
			$banka_adi  = $this->banka;

			$strmode = "PROD";
			$strversion = "v0.01";
			$strterminalid = $pos_model_bilgi->terminalid;
			$strprovuserid = $pos_model_bilgi->username;
			$strprovisionpassword = $pos_model_bilgi->password; //SanalPos şifreniz
			$struserid = $gelen_veriler->user_id;
			$strmerchantid = $pos_model_bilgi->merchantid; //MerchantID (Uye işyeri no)
			$stripaddress = $gelen_veriler->ip_adres;
			$stremailaddress = $gelen_veriler->email_adres;
			$strorderid = md5($gelen_veriler->user_id . microtime());
			$strinstallmentcnt = ($gelen_veriler->taksit != '' OR $gelen_veriler->taksit > 0) ? $gelen_veriler->taksit : ''; //Taksit Sayısı. Boş gönderilirse taksit yapılmaz

			$banka_host_bilgileri = $this->ci->config->item('banka_detaylari');
			$basarili_url = $this->ci->config->item('banka_pos_gonderim_adres_basarili');
			$hatali_url = $this->ci->config->item('banka_pos_gonderim_adres_hatali');

			$siparis_id  = $gelen_veriler->siparis_id;
			$fatura_id  = $gelen_veriler->fatura_id;
			$banka_adi  = $this->ascii;

			$banka_tip_ = $this->ci->encrypt->encode($this->ascii);
			$banka_tip  = base64_encode($banka_tip_);

			$security_data_terminal_hesapla = (strlen($pos_model_bilgi->terminalid) < 9) ? str_repeat('0', (9 - strlen($pos_model_bilgi->terminalid))) . $pos_model_bilgi->terminalid : $pos_model_bilgi->terminalid;

			$strnumber = $gelen_veriler->kart_numarasi;
			$strexpiredate = $gelen_veriler->kart_numarasi_ay . $gelen_veriler->kart_numarasi_yil;
			$strcvv2 = $gelen_veriler->kart_numarasi_guvenlik_kodu;
			$stramount = str_replace(array(',', '.'), '', $gelen_veriler->fiyat); //İşlem Tutarı
			$strtype = "sales";
			$strcurrencycode = "949"; // 949 TRL // 840 USD // 978 EURO // 826 GBP // 392 JPY
			$strcardholderpresentcode = "0";
			$strmotoind = "N";
			$securitydata = strtoupper(sha1($strprovisionpassword . $security_data_terminal_hesapla));
			$HashData = strtoupper(sha1($strorderid . $strterminalid . $strnumber . $stramount . $securitydata));
			$xml= '<?xml version="1.0" encoding="ISO-8859-1"?>
<GVPSRequest>
<Mode>'. $strmode .'</Mode>
<Version>'. $strversion .'</Version>
<Terminal>
<ProvUserID>'. $strprovuserid .'</ProvUserID>
<HashData>'. $HashData .'</HashData>
<UserID>'. $struserid .'</UserID>
<ID>'. $strterminalid .'</ID>
<MerchantID>'. $strmerchantid .'</MerchantID>
</Terminal>
<Customer>
<IPAddress>'. $stripaddress .'</IPAddress>
<EmailAddress>'. $stremailaddress .'</EmailAddress>
</Customer>
<Card>
<Number>'. $strnumber .'</Number>
<ExpireDate>'. $strexpiredate .'</ExpireDate>
<CVV2>'. $strcvv2 .'</CVV2>
</Card>
<Order>
<OrderID>'. $strorderid .'</OrderID>
<GroupID></GroupID>
<Description></Description>
</Order>
<Transaction>
<Type>'. $strtype .'</Type>
<InstallmentCnt>'. $strinstallmentcnt .'</InstallmentCnt>
<Amount>'. $stramount .'</Amount>
<CurrencyCode>'. $strcurrencycode .'</CurrencyCode>
<CardholderPresentCode>'. $strcardholderpresentcode .'</CardholderPresentCode>
<MotoInd>'. $strmotoind .'</MotoInd>
<Description></Description>
<OriginalRetrefNum></OriginalRetrefNum>
</Transaction>
</GVPSRequest>';

			$post_url = 'https://'. $banka_host_bilgileri[$banka_bilgi->kk_banka_adi_ascii]['host'][$banka_bilgi->kk_banka_test_tipi] . $banka_host_bilgileri[$banka_bilgi->kk_banka_adi_ascii][$this->ascii];
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $post_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1) ;
			curl_setopt($ch, CURLOPT_POSTFIELDS, "data=" . $xml);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			$results = curl_exec($ch);
			curl_close($ch);

			$xml_obje_sonucu = new SimpleXMLElement($results);

			if(isset($xml_obje_sonucu->Transaction->Response->Message) AND $xml_obje_sonucu->Transaction->Response->Message == 'Declined')
			{
				$gonder->durum = false;
				$gonder->kod   = (isset($xml_obje_sonucu->Transaction->Response->Code)) ? $xml_obje_sonucu->Transaction->Response->Code:'92';
				$gonder->mesaj = (isset($xml_obje_sonucu->Transaction->Response->ErrorMsg)) ? $xml_obje_sonucu->Transaction->Response->ErrorMsg:'Hata Oluştu İşlem Gerçekleşmedi';
			} elseif (isset($xml_obje_sonucu->Transaction->Response->Message) AND $xml_obje_sonucu->Transaction->Response->Message == 'Approved') {
				$gonder->kod   = '00';
				$gonder->durum = true;
				$gonder->mesaj = 'İşleminiz Başarılı Bir Şekilde Gerçekleşti';
			} else {
				$gonder->kod   = '92';
				$gonder->durum = false;
				$gonder->mesaj = 'Hata Oluştu İşlem Gerçekleşmedi';
			}

			$gonder->ozel_veri->fiyat = isset($gelen_veriler->post_verileri['toplam_ucret']) ? ($gelen_veriler->post_verileri['toplam_ucret'] / 100) : 0;
			$gonder->ozel_veri->kart_numarasi = isset($gelen_veriler->post_verileri['kredi_kart_no']) ? $gelen_veriler->post_verileri['kredi_kart_no'] : 0;
			$gonder->ozel_veri->taksit = isset($gelen_veriler->post_verileri['kredi_kart_taksit']) ? $gelen_veriler->post_verileri['kredi_kart_taksit'] : 0;
			$gonder->veri	= '';
			$gonder->debug	= $xml_obje_sonucu;
		} else {
			$gonder->durum	= false;
			$gonder->mesaj	= 'Banka Bilgilerine Ulaşılamadı';
			$gonder->veri	= '';
			$gonder->kod	= '';
			$gonder->debug	= $gelen_veriler;
		}
		return $gonder;
	}

}

/* End of file isimsiz.php */
/* Location: ./dev10/application/libraries/isimsiz.php */

?>