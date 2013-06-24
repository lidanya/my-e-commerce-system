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
	O:8:"stdClass":1:{s:5:"ccpay";O:8:"stdClass":3:{s:10:"merchantid";s:0:"";s:8:"username";s:0:"";s:8:"password";s:0:"";}}
	Php Kodu
	$olustur->{'ccpay'}->merchantid	= '';
	$olustur->{'ccpay'}->username	= '';
	$olustur->{'ccpay'}->password	= '';
	echo serialize($olustur);
 */

class est_ccpay
{
	public $ci;
	protected $banka		= '';
	protected $ascii		= 'ccpay';
	protected $banka_bilgi	= FALSE;

	/**
	 * ccpay construct
	 *
	 * @return void
	 * @author E-Ticaret Sistemim
	 **/

	function __construct($param = array())
	{
		log_message('debug', 'EST_ccpay Library Yüklendi');
		$this->ci =& get_instance();
		$this->ci->load->library('encrypt');

		foreach ($param as $key => $value) {
			$this->$key = $value;
		}
	}

	function banka_bilgi_tanimla($gelen_degerler = null)
	{
		$durum = FALSE;
		if(!is_null($gelen_degerler))
		{
			if(is_object($gelen_degerler))
			{
				$this->banka_bilgi = $gelen_degerler;
				$durum = TRUE;
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
			return FALSE;
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
			$kart_yil		= (strlen($gelen_veriler->kart_numarasi_yil > 2)) ? substr($gelen_veriler->kart_numarasi_yil, 2, 2) : $gelen_veriler->kart_numarasi_yil;
			$kart_ay		= $gelen_veriler->kart_numarasi_ay;
			$kart_cvv2		= $gelen_veriler->kart_numarasi_guvenlik_kodu;
			$fiyat			= $gelen_veriler->fiyat;
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
			$form .= '</form>';

			$gonder->veri	= $form;
			$gonder->kod	= '';
			$gonder->durum	= TRUE;
			$gonder->mesaj	= '';
			$gonder->debug	= '';
		} else {
			$gonder->veri	= '';
			$gonder->kod	= '';
			$gonder->durum	= FALSE;
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

			$type = "Auth"; // Auth: Satış PreAuth Ön Otorizasyon
			$username = $pos_model_bilgi->username; // Sanal pos api kullanic adi
			$password = $pos_model_bilgi->password; // Sanal pos api kullanicisi sifresi
			$merchantid = $pos_model_bilgi->merchantid; // Sanal pos magaza numarasi
			$ipaddress = $gelen_veriler->ip_adres;
			$emailaddress = $gelen_veriler->email_adres;
			$orderid = md5($gelen_veriler->user_id . microtime());
			$taksit = ($gelen_veriler->taksit != '' OR $gelen_veriler->taksit > 0) ? $gelen_veriler->taksit : ''; //Taksit Sayısı. Boş gönderilirse taksit yapılmaz

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
			$date = $gelen_veriler->kart_numarasi_ay . '/' . $gelen_veriler->kart_numarasi_yil;
			$cvv2 = $gelen_veriler->kart_numarasi_guvenlik_kodu;
			$amount = $gelen_veriler->fiyat;

			$session_siparis_detay = $gelen_veriler->session_siparis_detay;
			$kredi_kart_no = $session_siparis_detay['kredi_kart']['kart_numarasi'];

			// Fatura Bilgileri
			$fatura_bilgi_isim_soyisim = ($fatura_bilgi AND (is_object($fatura_bilgi))) ? $fatura_bilgi->inv_username . ' ' . $fatura_bilgi->inv_usersurname : '';
			$fatura_bilgi_adres = ($fatura_bilgi AND (is_object($fatura_bilgi))) ? $fatura_bilgi->inv_adr_id : '';
			$fatura_bilgi_sehir = ($fatura_bilgi AND (is_object($fatura_bilgi))) ? sehir_adi2($fatura_bilgi->inv_sehir) : '';
			$fatura_bilgi_ilce = ($fatura_bilgi AND (is_object($fatura_bilgi))) ? $fatura_bilgi->inv_ilce : '';
			$fatura_bilgi_posta_kodu = ($fatura_bilgi AND (is_object($fatura_bilgi))) ? $fatura_bilgi->inv_pkodu : '';
			$fatura_bilgi_ulke = ($fatura_bilgi AND (is_object($fatura_bilgi))) ? ulke_kodu3($fatura_bilgi->inv_ulke) : '';
			$fatura_bilgi_firma_adi = ($fatura_bilgi AND (is_object($fatura_bilgi))) ? $fatura_bilgi->inv_firma : '';
			$fatura_bilgi_tel = ($fatura_bilgi AND (is_object($fatura_bilgi))) ? $fatura_bilgi->inv_tel : '';

			// Teslimat Bilgileri
			$teslimat_bilgi_isim_soyisim = ($teslimat_bilgi AND (is_object($teslimat_bilgi))) ? $teslimat_bilgi->ad_soyad : '';
			$teslimat_bilgi_adres = ($teslimat_bilgi AND (is_object($teslimat_bilgi))) ? $teslimat_bilgi->adres : '';
			$teslimat_bilgi_sehir = ($teslimat_bilgi AND (is_object($teslimat_bilgi))) ? sehir_adi2($teslimat_bilgi->sehir) : '';
			$teslimat_bilgi_ilce = ($teslimat_bilgi AND (is_object($teslimat_bilgi))) ? $teslimat_bilgi->ilce : '';
			$teslimat_bilgi_posta_kodu = ($teslimat_bilgi AND (is_object($teslimat_bilgi))) ? $teslimat_bilgi->posta_kodu : '';
			$teslimat_bilgi_ulke = ($teslimat_bilgi AND (is_object($teslimat_bilgi))) ? ulke_kodu3($teslimat_bilgi->ulke) : '';

			$xml = '<?xml version="1.0" encoding="UTF-8"?>
<CC5Request>
<Name>'. $username .'</Name>
<Password>'. $password .'</Password>
<ClientId>'. $merchantid .'</ClientId>
<IPAddress>'. $ipaddress .'</IPAddress>
<Email>'. $emailaddress .'</Email>
<Mode>P</Mode>
<OrderId>'. $orderid .'</OrderId>
<GroupId></GroupId>
<TransId></TransId>
<UserId></UserId>
<Type>'. $type .'</Type>
<Number>'. $kredi_kart_no .'</Number>
<Expires>'. $date .'</Expires>
<Cvv2Val>'. $cvv2 .'</Cvv2Val>
<Total>'. $amount .'</Total>
<Currency>949</Currency>
<Taksit>'. $taksit .'</Taksit>
<BillTo>
<Name>'. $fatura_bilgi_isim_soyisim .'</Name>
<Street1>'. $fatura_bilgi_adres .'</Street1>
<Street2></Street2>
<Street3></Street3>
<City>'. $fatura_bilgi_sehir .'</City>
<StateProv>'. $fatura_bilgi_ilce .'</StateProv>
<PostalCode>'. $fatura_bilgi_posta_kodu .'</PostalCode>
<Country>'. $fatura_bilgi_ulke .'</Country>
<Company>'. $fatura_bilgi_firma_adi .'</Company>
<TelVoice>'. $fatura_bilgi_tel .'</TelVoice>
</BillTo>
<ShipTo>
<Name>'. $teslimat_bilgi_isim_soyisim .'</Name>
<Street1>'. $teslimat_bilgi_adres .'</Street1>
<Street2></Street2>
<Street3></Street3>
<City>'. $teslimat_bilgi_sehir .'</City>
<StateProv>'. $teslimat_bilgi_ilce .'</StateProv>
<PostalCode>'. $teslimat_bilgi_posta_kodu .'</PostalCode>
<Country>'. $teslimat_bilgi_ulke .'</Country>
</ShipTo>
<Extra></Extra>
</CC5Request>';

			//exit($xml);

			$post_url = 'https://'. $banka_host_bilgileri[$banka_bilgi->kk_banka_adi_ascii]['host'][$banka_bilgi->kk_banka_test_tipi] . $banka_host_bilgileri[$banka_bilgi->kk_banka_adi_ascii][$this->ascii];

			$ch = curl_init(); // initialize curl handle
			curl_setopt($ch, CURLOPT_URL, $post_url); // set url to post to
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
			curl_setopt($ch, CURLOPT_TIMEOUT, 90); // times out after 4s
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "data=" . $xml); // add POST fields
			$results = curl_exec($ch); // run the whole process

			$xml_obje_sonucu = new SimpleXMLElement($results);

			if(isset($xml_obje_sonucu->Response) AND ($xml_obje_sonucu->Response == 'Declined' OR $xml_obje_sonucu->Response == 'Error'))
			{
				$gonder->durum = FALSE;
				$gonder->kod   = (isset($xml_obje_sonucu->ProcReturnCode)) ? $xml_obje_sonucu->ProcReturnCode:'92';
				if(isset($xml_obje_sonucu->Extra->HOSTMSG))
				{
					$olumsuz_hata_mesaji = $xml_obje_sonucu->Extra->HOSTMSG;
				} elseif($xml_obje_sonucu->ErrMsg) {
					$olumsuz_hata_mesaji = $xml_obje_sonucu->ErrMsg;
				} else {
					$olumsuz_hata_mesaji = 'Hata Oluştu İşlem Gerçekleşmedi';
				}
				$gonder->mesaj = $olumsuz_hata_mesaji;
			} elseif (isset($xml_obje_sonucu->Response) AND $xml_obje_sonucu->Response == 'Approved') {
				if (isset($xml_obje_sonucu->OrderId) AND $xml_obje_sonucu->OrderId != '') {
					$extra_security_check = $this->extra_security_approve_check($xml_obje_sonucu->OrderId, $banka_bilgi, $gelen_veriler);
					if ($extra_security_check AND $extra_security_check->Response == 'Approved') {
						$gonder->kod   = '00';
						$gonder->durum = TRUE;
						$gonder->mesaj = 'İşleminiz Başarılı Bir Şekilde Gerçekleşti';
					} else {
						$gonder->gelen_veriler			= $gelen_veriler;
						$gonder->uye_bilgi				= $uye_bilgi;
						$gonder->post_verileri			= $post_verileri;
						$gonder->banka_bilgi			= $banka_bilgi;
						$gonder->extra_security_check	= $extra_security_check;
						$this->extra_security_approve_mail($gonder);

						$gonder->kod   = '92';
						$gonder->durum = FALSE;
						$gonder->mesaj = 'Hata Oluştu İşlem Gerçekleşmedi';
					}
				} else {
					$gonder->kod   = '92';
					$gonder->durum = FALSE;
					$gonder->mesaj = 'Hata Oluştu İşlem Gerçekleşmedi';
				}
			} else {
				$gonder->kod   = '92';
				$gonder->durum = FALSE;
				$gonder->mesaj = 'Hata Oluştu İşlem Gerçekleşmedi';
			}

			$gonder->ozel_veri->fiyat = isset($gelen_veriler->post_verileri['toplam_ucret']) ? ($gelen_veriler->post_verileri['toplam_ucret']) : 0;
			$gonder->ozel_veri->kart_numarasi = $kredi_kart_no;
			$gonder->ozel_veri->taksit = isset($gelen_veriler->post_verileri['kredi_kart_taksit']) ? $gelen_veriler->post_verileri['kredi_kart_taksit'] : 0;
			$gonder->veri	= '';
			$gonder->debug	= $xml_obje_sonucu;
		} else {
			$gonder->durum	= FALSE;
			$gonder->mesaj	= 'Banka Bilgilerine Ulaşılamadı';
			$gonder->veri	= '';
			$gonder->kod	= '';
			$gonder->debug	= '';
		}
		return $gonder;
	}

	protected function extra_security_approve_mail($get_data)
	{
		$gelen_veriler						= $get_data->gelen_veriler;
		$uye_bilgi							= $get_data->uye_bilgi;
		$post_verileri						= $gelen_veriler->post_verileri;
		$siparis_bilgi						= $gelen_veriler->siparis_verileri;
		$siparis_id							= $gelen_veriler->siparis_id;
		$banka_bilgi						= $get_data->banka_bilgi;
		$extra_security_check				= $get_data->extra_security_check;
		$session_siparis_detay				= $gelen_veriler->session_siparis_detay;

		$subject 							= 'Kredi Kartı Sahtekarlığı Saptandı';
		$to									= config('site_ayar_email_admin');
		$from								= config('site_ayar_email_cevapsiz');
		$sahtekarlik_data['site_adresi']	= base_url();
		$sahtekarlik_data['ad_soyad']		= ($uye_bilgi AND (is_object($uye_bilgi))) ? $uye_bilgi->ide_adi . ' ' . $uye_bilgi->ide_soy : '';
		$sahtekarlik_data['eposta_adresi']	= $gelen_veriler->email_adres;
		$sahtekarlik_data['siparis_tarih']	= date('d-m-Y H:i:s', $siparis_bilgi->kayit_tar);
		$sahtekarlik_data['islem_tarih']	= date('d-m-Y H:i:s', time());
		$sahtekarlik_data['siparis_no']		= $siparis_id;
		$kredi_karti 						= $session_siparis_detay['kredi_kart']['kart_numarasi'];
		$sahtekarlik_data['kart_no']		= substr($kredi_karti, 0, 4) . ' ' . substr($kredi_karti, 4, 4) . ' ' . substr($kredi_karti, 8, 4) . ' ' . substr($kredi_karti, 12, 4);
		$sahtekarlik_data['ip_adresi']		= $gelen_veriler->ip_adres;
		$sahtekarlik_data['tarayici']		= $gelen_veriler->agent;
		$sahtekarlik_data['isistemi']		= $gelen_veriler->platform;
		$sahtekarlik_data['banka_adi']		= $banka_bilgi->kk_banka_adi;
		$sahtekarlik_data['bsiparis_no']	= $extra_security_check->OrderId;
		$sahtekarlik_data['btarih']			= $extra_security_check->Extra->AUTH_DTTM;
		$sahtekarlik_data['bt_no']			= $extra_security_check->TransId;
		$message							= $this->ci->load->view(tema() . 'mail_sablon/siparis/kredi_sahtekarlik_bildirimi', $sahtekarlik_data, TRUE);
		$this->ci->dx_auth->_email($to, $from, $subject, $message);
		$this->ci->dx_auth->_email('info@eticaretsistemim.com', $from, $subject, $message);
	}

	protected function extra_security_approve_check($order_id, $banka_bilgi, $gelen_veriler)
	{
		if ($order_id) {
			$banka_host_bilgileri = $this->ci->config->item('banka_detaylari');
			$pos_model_bilgi = $banka_bilgi->banka_bilgi->{'ccpay'};
			$xml = '<?xml version="1.0" encoding="UTF-8"?>
			<CC5Request>
			<Name>'. $pos_model_bilgi->username .'</Name>
			<Password>'. $pos_model_bilgi->password .'</Password>
			<ClientId>'. $pos_model_bilgi->merchantid .'</ClientId>
			<OrderId>'. $order_id .'</OrderId>
			<Mode>P</Mode>
			<Extra><ORDERSTATUS>SOR</ORDERSTATUS></Extra>
			</CC5Request>';

			$post_url = 'https://'. $banka_host_bilgileri[$banka_bilgi->kk_banka_adi_ascii]['host'][$banka_bilgi->kk_banka_test_tipi] . $banka_host_bilgileri[$banka_bilgi->kk_banka_adi_ascii]['ccpay'];

			$ch = curl_init(); // initialize curl handle
			curl_setopt($ch, CURLOPT_URL, $post_url); // set url to post to
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
			curl_setopt($ch, CURLOPT_TIMEOUT, 90); // times out after 4s
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "data=" . $xml); // add POST fields
			$results = curl_exec($ch); // run the whole process
			$result = new SimpleXMLElement($results);
			return $result;
		} else {
			return FALSE;
		}
	}

}

/* End of file isimsiz.php */
/* Location: ./dev10/application/libraries/isimsiz.php */

?>