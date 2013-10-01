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
	O:8:"stdClass":1:{s:8:"3doospay";O:8:"stdClass":5:{s:10:"merchantid";s:0:"";s:10:"terminalid";s:0:"";s:8:"storekey";s:0:"";s:8:"username";s:0:"";s:8:"password";s:0:"";}}
	Php Kodu
	$olustur->{'3doospay'}->merchantid	= '';
	$olustur->{'3doospay'}->terminalid	= '';
	$olustur->{'3doospay'}->storekey	= '';
	$olustur->{'3doospay'}->username	= '';
	$olustur->{'3doospay'}->password	= '';
	echo serialize($olustur);
 */

class garanti_3doospay
{
	public $ci;
	protected $banka = 'garanti';
	protected $ascii = '3doospay';
	protected $banka_bilgi = false;

	/**
	 * garanti_3doospay construct
	 *
	 * @return void
	 * @author E-Ticaret Sistemim
	 **/

	function __construct()
	{
		log_message('debug', 'garanti_oospay Library Yüklendi');
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

			$security_data_terminal_hesapla = (strlen($pos_model_bilgi->terminalid) < 9) ? str_repeat('0', (9 - strlen($pos_model_bilgi->terminalid))) . $pos_model_bilgi->terminalid : $pos_model_bilgi->terminalid; //TerminalID başına 000 ile 9 digit yapılmalı

			$strmode = "PROD";
			$strapiversion = "v0.01";
			$strterminalprovuserid = $pos_model_bilgi->username;
			$strtype = "sales";
			$stramount = str_replace(array(',', '.'), '', $gelen_veriler->fiyat); //İşlem Tutarı
			$strcurrencycode = "949";	// 949 TRL // 840 USD // 978 EURO // 826 GBP // 392 JPY
			$strinstallmentcount = ($gelen_veriler->taksit != '' OR $gelen_veriler->taksit > 0) ? $gelen_veriler->taksit : ''; //Taksit Sayısı. Boş gönderilirse taksit yapılmaz
			$strterminaluserid = $gelen_veriler->user_id;
			$stremailaddress = $gelen_veriler->email_adres;
			$strorderid = md5($gelen_veriler->user_id . microtime());
			$strcustomeripaddress = $gelen_veriler->ip_adres;
			$strterminalid = $pos_model_bilgi->terminalid;
			$strterminalmerchantid = $pos_model_bilgi->merchantid; //MerchantID
			$strstorekey = $pos_model_bilgi->storekey; //3D Secure şifreniz
			$strprovisionpassword = $pos_model_bilgi->password; //SanalPos şifreniz
			mt_srand();
			$strtimestamp_uniq = md5(uniqid(mt_rand()));
			$strtimestamp = $strtimestamp_uniq; //Random ve Unique bir değer olmalı
			$strlang = "tr";
			$securitydata = strtoupper(sha1($strprovisionpassword . $security_data_terminal_hesapla));
			$hashdata = strtoupper(sha1($strterminalid . $strorderid . $stramount . $strsuccessurl . $strerrorurl . $strtype . $strinstallmentcount . $strstorekey . $securitydata));

			$post_url = 'https://'. $banka_host_bilgileri[$banka_bilgi->kk_banka_adi_ascii]['host'][$banka_bilgi->kk_banka_test_tipi] . $banka_host_bilgileri[$banka_bilgi->kk_banka_adi_ascii][$this->ascii];
			$form = '';
			$form .= $this->ci->config->item('banka_pos_3d_mesaji');
			$form .= '<script type="text/javascript">' . "\n";
			$form .= '$(document).ready(function(){' . "\n";
			$form .= 'setTimeout("$(\'#' . $strorderid . '\').submit()", 1500);';
			$form .= '});';
			$form .= '</script>' . "\n";
			$form .= '<form action="' . $post_url . '" id="' . $strorderid . '" method="post">';
			$form .= form_hidden('secure3dsecuritylevel', '3D_OOS_PAY');
			$form .= form_hidden('refreshtime', '5');
			$form .= form_hidden('customeremailaddress', $stremailaddress);
			$form .= form_hidden('mode', $strmode);
			$form .= form_hidden('companyname', 'Firma İsmi');
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
			$form .= form_hidden('customeripaddress', $strcustomeripaddress);
			$form .= form_hidden('secure3dhash', $hashdata);
			$form .= form_hidden('lang', $strlang);
			$form .= form_hidden('txntimestamp', $strtimestamp);

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
		}

		return $gonder;
	}

	function form_sonuc($gelen_veriler = null)
	{
		if(!is_null($gelen_veriler) AND $banka_bilgi = $this->banka_bilgi())
		{
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
			if (($strmdstatus == "1" || $strmdstatus == "2" || $strmdstatus == "3" || $strmdstatus == "4") OR $gelen_veriler->post_verileri['response']) 
			{
				if($gelen_veriler->post_verileri['response'] == 'Approved')
				{
					$gonder->durum = true;
					$gonder->kod   = '00';
					$gonder->veri  = '';
					$gonder->mesaj = 'İşleminiz Başarılı Bir Şekilde Gerçekleşti';
				} else {
					$gonder->durum = false;
					$gonder->kod   = '';
					$gonder->veri  = '';
					$gonder->mesaj = isset($gelen_veriler->post_verileri['errmsg']) ? $gelen_veriler->post_verileri['errmsg'] : $strmdstatus_mesaj;
				}
			} else {
				$gonder->durum	= false;
				$gonder->mesaj	= isset($gelen_veriler->post_verileri['errmsg']) ? $gelen_veriler->post_verileri['errmsg'] : $strmdstatus_mesaj;
				$gonder->veri	= '';
				$gonder->kod	= isset($gelen_veriler->post_verileri['procreturncode']) ? $gelen_veriler->post_verileri['procreturncode'] : '';
			}

			$gonder->ozel_veri->fiyat = isset($gelen_veriler->post_verileri['txnamount']) ? ($gelen_veriler->post_verileri['txnamount'] / 100) : 0;
			$gonder->ozel_veri->kart_numarasi = isset($gelen_veriler->post_verileri['kredi_kart_no']) ? $gelen_veriler->post_verileri['kredi_kart_no'] : 0;
			$gonder->ozel_veri->taksit = isset($gelen_veriler->post_verileri['kredi_kart_taksit']) ? $gelen_veriler->post_verileri['kredi_kart_taksit'] : 0;
			$gonder->debug	= $gelen_veriler;
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