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
	O:8:"stdClass":1:{s:6:"3dfull";O:8:"stdClass":5:{s:10:"merchantid";s:0:"";s:10:"terminalid";s:0:"";s:8:"storekey";s:0:"";s:8:"username";s:0:"";s:8:"password";s:0:"";}}
	Php Kodu
	$olustur->{'3dmodel'}->merchantid	= '';
	$olustur->{'3dmodel'}->terminalid	= '';
	$olustur->{'3dmodel'}->storekey		= '';
	$olustur->{'3dmodel'}->username		= '';
	$olustur->{'3dmodel'}->password		= '';
	echo serialize($olustur);
 */

class garanti_3dmodel
{
	public $ci;
	protected $banka = 'garanti';
	protected $ascii = '3dmodel';
	protected $banka_bilgi = false;

	/**
	 * 3dmodel construct
	 *
	 * @return void
	 * @author E-Ticaret Sistemim
	 **/

	function __construct()
	{
		log_message('debug', 'garanti_3dmodel Library Yüklendi');
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
			
			$strmode = "PROD";
			$strapiversion = "v0.01";
			$strterminalprovuserid = "PROVAUT";
			$strtype = "sales";

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

			$strerrorurl_ = strtr($hatali_url, array('{siparis_id}' => $siparis_id, '{fatura_id}' => $fatura_id, '{banka}' => $banka_adi, '{tip}' => $banka_tip));
			$strerrorurl = ssl_url($strerrorurl_);

			$strsuccessurl_ = strtr($basarili_url, array('{siparis_id}' => $siparis_id, '{fatura_id}' => $fatura_id, '{banka}' => $banka_adi, '{tip}' => $banka_tip));
			$strsuccessurl = ssl_url($strsuccessurl_);

			// Müşteri Ayarları
			$strcustomeripaddress = $gelen_veriler->ip_adres;
			$strcustomeremailaddress = $gelen_veriler->email_adres;
			$strterminaluserid = $gelen_veriler->user_id;
			$strorderid = md5($gelen_veriler->user_id . microtime());

			$strinstallmentcount = ($gelen_veriler->taksit != '' OR $gelen_veriler->taksit > 0) ? $gelen_veriler->taksit : ''; //Taksit Sayısı. Boş gönderilirse taksit yapılmaz
			$stramount = str_replace(array(',', '.'), '', $gelen_veriler->fiyat); //İşlem Tutarı
			$strcurrencycode = "949";	// 949 TRL // 840 USD // 978 EURO // 826 GBP // 392 JPY
			$security_data_terminal_hesapla = (strlen($pos_model_bilgi->terminalid) < 9) ? str_repeat('0', (9 - strlen($pos_model_bilgi->terminalid))) . $pos_model_bilgi->terminalid : $pos_model_bilgi->terminalid;

			$securitydata = strtoupper(sha1($pos_model_bilgi->password . $security_data_terminal_hesapla));
			$hashdata = strtoupper(sha1($pos_model_bilgi->terminalid . $strorderid . $stramount . $strsuccessurl . $strerrorurl . $strtype . $strinstallmentcount . $pos_model_bilgi->storekey . $securitydata));

			$post_url = 'https://'. $banka_host_bilgileri[$banka_bilgi->kk_banka_adi_ascii]['host'][$banka_bilgi->kk_banka_test_tipi] . $banka_host_bilgileri[$banka_bilgi->kk_banka_adi_ascii][$this->ascii];
			$form = '';
			$form .= $this->ci->config->item('banka_pos_3d_mesaji');
			$form .= '<script type="text/javascript">' . "\n";
			$form .= '$(document).ready(function(){' . "\n";
			$form .= 'setTimeout("$(\'#' . $strorderid . '\').submit()", 1500);';
			$form .= '});';
			$form .= '</script>' . "\n";
			$form .= '<form action="' . $post_url . '" id="' . $strorderid . '" method="post">';
			$form .= form_hidden('secure3dsecuritylevel', '3D');
			$form .= form_hidden('cardnumber', $gelen_veriler->kart_numarasi);
			$form .= form_hidden('cardexpiredatemonth', $gelen_veriler->kart_numarasi_ay);
			$form .= form_hidden('cardexpiredateyear', substr($gelen_veriler->kart_numarasi_yil, -2));
			$form .= form_hidden('cardcvv2', $gelen_veriler->kart_numarasi_guvenlik_kodu);
			$form .= form_hidden('mode', $strmode);
			$form .= form_hidden('apiversion', $strapiversion);
			$form .= form_hidden('terminalprovuserid', $strterminalprovuserid);
			$form .= form_hidden('terminaluserid', $strterminaluserid);
			$form .= form_hidden('terminalmerchantid', $pos_model_bilgi->merchantid);
			$form .= form_hidden('txntype', $strtype);
			$form .= form_hidden('txnamount', $stramount);
			$form .= form_hidden('txncurrencycode', $strcurrencycode);
			$form .= form_hidden('txninstallmentcount', $strinstallmentcount);
			$form .= form_hidden('orderid', $strorderid);
			$form .= form_hidden('terminalid', $pos_model_bilgi->terminalid);
			$form .= form_hidden('successurl', $strsuccessurl);
			$form .= form_hidden('errorurl', $strerrorurl);
			$form .= form_hidden('customeremailaddress', $strcustomeremailaddress);
			$form .= form_hidden('customeripaddress', $strcustomeripaddress);
			$form .= form_hidden('secure3dhash', $hashdata);
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

			$strmdstatus = isset($gelen_veriler->post_verileri['mdstatus']) ? $gelen_veriler->post_verileri['mdstatus'] : false;

			if($strmdstatus == "1")
			{
				$strmdstatus_mesaj = "Tam Doğrulama";
			}
			if($strmdstatus == "2")
			{
				$strmdstatus_mesaj = "Kart Sahibi veya bankası sisteme kayıtlı değil";
			}
			if($strmdstatus == "3")
			{
				$strmdstatus_mesaj = "Kartın bankası sisteme kayıtlı değil";
			}
			if($strmdstatus == "4")
			{
				$strmdstatus_mesaj = "Doğrulama denemesi, kart sahibi sisteme daha sonra kayıt olmayı seçmiş";
			}
			if($strmdstatus == "5")
			{
				$strmdstatus_mesaj = "Doğrulama yapılamıyor";
			}
			if($strmdstatus == "6")
			{
				$strmdstatus_mesaj = "3-D Secure Hatası";
			}
			if($strmdstatus == "7")
			{
				$strmdstatus_mesaj = "Sistem Hatası";
			}
			if($strmdstatus == "8")
			{
				$strmdstatus_mesaj = "Bilinmeyen Kart No";
			}
			if($strmdstatus == "0")
			{
				$strmdstatus_mesaj = "Doğrulama Başarısız, 3-D Secure imzası geçersiz.";
			}

			//Tam Doğrulama, Kart Sahibi veya bankası sisteme kayıtlı değil, Kartın bankası sisteme kayıtlı değil
			//Doğrulama denemesi, kart sahibi sisteme daha sonra kayıt olmayı seçmiş responselarını alan
			//işlemler için Provizyon almaya çalışıyoruz
			if ($strmdstatus == "1" || $strmdstatus == "2" || $strmdstatus == "3" || $strmdstatus == "4") 
			{
				$strmode = $gelen_veriler->post_verileri['mode'];
				$strversion = $gelen_veriler->post_verileri['apiversion'];
				$strterminalid = $pos_model_bilgi->terminalid;
				$strprovisionpassword = $pos_model_bilgi->password; //SanalPos Şifreniz -Provizyona giderken tekrar HASH edilecek-
				$strprovuserid = $pos_model_bilgi->username;
				$struserid = $gelen_veriler->user_id;
				$strmerchantid = $pos_model_bilgi->merchantid;
				$stripaddress = $gelen_veriler->ip_adres;
				$stremailaddress = $gelen_veriler->email_adres;
				$strorderid = $gelen_veriler->post_verileri['orderid'];
				$strnumber = ""; //Kart bilgilerinin boş gitmesi gerekiyor
				$strexpiredate = ""; //Kart bilgilerinin boş gitmesi gerekiyor
				$strcvv2 = ""; //Kart bilgilerinin boş gitmesi gerekiyor
				$stramount = $gelen_veriler->post_verileri['txnamount'];
				$strcurrencycode = $gelen_veriler->post_verileri['txncurrencycode'];
				$strcardholderpresentcode = "13"; //3D Model işlemde bu değer 13 olmalı
				$strtype = $gelen_veriler->post_verileri['txntype'];
				$strmotoind = "N";
				$strauthenticationcode = $gelen_veriler->post_verileri['cavv'];
				$strsecuritylevel = $gelen_veriler->post_verileri['eci'];
				$strtxnid = $gelen_veriler->post_verileri['xid'];
				$strmd = $gelen_veriler->post_verileri['md'];

				$security_data_terminal_hesapla = (strlen($strterminalid) < 9) ? str_repeat('0', (9 - strlen($strterminalid))) . $strterminalid : $strterminalid;

				$securitydata = strtoupper(sha1($strprovisionpassword . $security_data_terminal_hesapla));
				$hashdata = strtoupper(sha1($strorderid . $strterminalid . $stramount . $securitydata)); //Daha kısıtlı bilgileri HASH ediyoruz.
				$strhostaddress = 'https://'. $banka_host_bilgileri[$banka_bilgi->kk_banka_adi_ascii]['host'] . $banka_host_bilgileri[$banka_bilgi->kk_banka_adi_ascii]['provizyon']; //Provizyon için xml'in post edileceği adres

				//Provizyona Post edilecek XML Şablonu
				$strxml = '<?xml version="1.0" encoding="ISO-8859-1"?>
<GVPSRequest>
<Mode>'. $strmode .'</Mode>
<Version>'. $strversion .'</Version>
<ChannelCode></ChannelCode>
<Terminal>
<ProvUserID>'. $strprovuserid .'</ProvUserID>
<hashdata>'. $hashdata .'</hashdata>
<UserID>'. $struserid .'</UserID>
<ID>'. $strterminalid .'</ID>
<MerchantID>'. $strmerchantid .'</MerchantID>
</Terminal>
<Customer>
<IPAddress>'. $stripaddress .'</IPAddress>
<EmailAddress>'. $stremailaddress .'</EmailAddress>
</Customer>
<Card>
<Number></Number>
<ExpireDate></ExpireDate>
</Card>
<Order>
<OrderID>'. $strorderid .'</OrderID>
<GroupID></GroupID>
<Description></Description>
</Order>
<Transaction>
<Type>'. $strtype .'</Type>
<InstallmentCnt></InstallmentCnt>
<Amount>'. $stramount .'</Amount>
<CurrencyCode>'. $strcurrencycode .'</CurrencyCode>
<CardholderPresentCode>'. $strcardholderpresentcode .'</CardholderPresentCode>
<MotoInd>'. $strmotoind .'</MotoInd>
<Secure3D>
<AuthenticationCode>'. $strauthenticationcode .'</AuthenticationCode>
<SecurityLevel>'. $strsecuritylevel .'</SecurityLevel>
<TxnID>'. $strtxnid .'</TxnID>
<Md>'. $strmd .'</Md>
</Secure3D>
</Transaction>
</GVPSRequest>';

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $strhostaddress);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1) ;
				curl_setopt($ch, CURLOPT_POSTFIELDS, "data=" . $strxml);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				$results = curl_exec($ch);
				curl_close($ch);

				$xml_obje_sonucu = new SimpleXMLElement($results);

				if(isset($xml_obje_sonucu->Transaction->Response->Message) AND $xml_obje_sonucu->Transaction->Response->Message == 'Declined')
				{
					$gonder->durum = false;
					$gonder->kod   = (isset($xml_obje_sonucu->Transaction->Response->Code)) ? $xml_obje_sonucu->Transaction->Response->Code:'92';
					$gonder->mesaj = (isset($xml_obje_sonucu->Transaction->Response->SysErrMsg)) ? $xml_obje_sonucu->Transaction->Response->SysErrMsg:'Hata Oluştu İşlem Gerçekleşmedi';
				} elseif (isset($xml_obje_sonucu->Transaction->Response->Message) AND $xml_obje_sonucu->Transaction->Response->Message == 'Approved') {
					$gonder->kod   = '00';
					$gonder->durum = true;
					$gonder->mesaj = 'İşleminiz Başarılı Bir Şekilde Gerçekleşti';
				} else {
					$gonder->kod   = isset($gelen_veriler->post_verileri['procreturncode']) ? $gelen_veriler->post_verileri['procreturncode'] : '';
					$gonder->durum = false;
					$gonder->mesaj = isset($gelen_veriler->post_verileri['errmsg']) ? $gelen_veriler->post_verileri['errmsg'] : $strmdstatus_mesaj;
				}
				$gonder->debug	= $xml_obje_sonucu;
			} else {
				$gonder->durum	= false;
				$gonder->mesaj	= isset($gelen_veriler->post_verileri['errmsg']) ? $gelen_veriler->post_verileri['errmsg'] : $strmdstatus_mesaj;
				$gonder->veri	= '';
				$gonder->kod	= isset($gelen_veriler->post_verileri['procreturncode']) ? $gelen_veriler->post_verileri['procreturncode'] : '';
				$gonder->debug	= $gelen_veriler;
			}

			$gonder->ozel_veri->fiyat = isset($gelen_veriler->post_verileri['toplam_ucret']) ? ($gelen_veriler->post_verileri['toplam_ucret'] / 100) : 0;
			$gonder->ozel_veri->kart_numarasi = isset($gelen_veriler->post_verileri['kredi_kart_no']) ? $gelen_veriler->post_verileri['kredi_kart_no'] : 0;
			$gonder->ozel_veri->taksit = isset($gelen_veriler->post_verileri['kredi_kart_taksit']) ? $gelen_veriler->post_verileri['kredi_kart_taksit'] : 0;
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