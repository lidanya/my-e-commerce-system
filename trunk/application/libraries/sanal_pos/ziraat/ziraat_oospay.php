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
	O:8:"stdClass":1:{s:6:"oospay";O:8:"stdClass":3:{s:10:"merchantid";s:0:"";s:8:"username";s:0:"";s:8:"password";s:0:"";}}
	Php Kodu
	$olustur->{'oospay'}->merchantid	= '';
	$olustur->{'oospay'}->username		= '';
	$olustur->{'oospay'}->password		= '';
	echo serialize($olustur);
 */

class ziraat_oospay
{
	public $ci;
	protected $banka = 'ziraat';
	protected $ascii = 'oospay';
	protected $banka_bilgi = false;

	/**
	 * ccpay construct
	 *
	 * @return void
	 * @author E-Ticaret Sistemim
	 **/

	function __construct()
	{
		log_message('debug', 'ziraat_oospay Library Yüklendi');
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
			$fiyat			= strtr($gelen_veriler->fiyat, array('.' => ''));
			$kart_tipi		= $gelen_veriler->kart_tipi;
			$taksit			= ($gelen_veriler->taksit != '' OR $gelen_veriler->taksit > 0) ? $gelen_veriler->taksit : '';

			$currencycode = "949"; // 949 TRL
			$username = $pos_model_bilgi->username; // Sanal pos api kullanicisi adı
			$password = $pos_model_bilgi->password; // Sanal pos api kullanicisi sifresi
			$merchantid = $pos_model_bilgi->merchantid; // Sanal pos magaza numarasi
			$ipaddress = $gelen_veriler->ip_adres;
			$emailaddress = $gelen_veriler->email_adres;
			$orderid = md5($gelen_veriler->user_id . microtime());

			$random_id		= md5($gelen_veriler->user_id . microtime());

			$strsuccessurl_ = strtr($basarili_url, array('{siparis_id}' => $siparis_id, '{fatura_id}' => $fatura_id, '{banka}' => $banka_adi, '{tip}' => $banka_tip));
			$strsuccessurl  = ssl_url($strsuccessurl_);

			$this->ci->session->set_userdata('sanal_pos_yonlendirme', $strsuccessurl);
			$this->ci->session->set_userdata('sanal_pos_fiyat', $fiyat);

			if($banka_bilgi->kk_banka_test_tipi == 'test') {
				$save_transaction = 'https://yonetim-test.ziraatbank.com.tr/IPOSMerchant_UserInterface/save_transaction.aspx';
				$send_transaction = 'https://yonetim-test.ziraatbank.com.tr/IposMerchant_UserInterface/SendTransaction.aspx';
			} else {
				$save_transaction = 'https://yonetim.ziraatbank.com.tr/IPOSMerchant_UserInterface/save_transaction.aspx';
				$send_transaction = 'https://yonetim.ziraatbank.com.tr/IposMerchant_UserInterface/SendTransaction.aspx';
			}

			$registerUrl = $save_transaction;
			$MerchantGUID = $random_id;
			$AmountMerchant = $fiyat;
			$AmountCode = $currencycode;
			$MerchantID = $merchantid;
			$UserName = $username;
			$Password = $password;
			$InstalmentCount = $taksit;

			$post_fields = 	'MerchantGUID=' .		$MerchantGUID .
							'&AmountMerchant=' .	$AmountMerchant .
							'&AmountBank=' .		$AmountMerchant .
							'&AmountCode=' .		$AmountCode .
							'&MerchantID=' .		$MerchantID .
							'&UserName=' .			$UserName .
							'&Password=' .			$Password .
							'&InstalmentCount=' .	$InstalmentCount;

			$ch = curl_init(); // initialize curl handle
			curl_setopt($ch, CURLOPT_URL, $registerUrl); // set url to post to
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
			curl_setopt($ch, CURLOPT_TIMEOUT, 90); // times out after 4s
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); // add POST fields
			$results = curl_exec($ch); // run the whole process

			//exit($results);

			$result_error = FALSE;
			$result_error_message = null;
			if(!$results) {
				$result_error = TRUE;
				$result_error_message = 'Bağlantı Sağlanamadı!';
			} elseif($results == 'Merchant not Found!') {
				$result_error = TRUE;
				$result_error_message = 'Merchant not Found!';
			}

			if($result_error) {
				$post_url = $strsuccessurl . '?RC=96&errormsg=' . $result_error_message;
				$form = '';
				$form .= $this->ci->config->item('banka_pos_3d_mesaji');
				$form .= '<script type="text/javascript">' . "\n";
				$form .= '$(document).ready(function(){' . "\n";
				$form .= 'document.location.href = \''. $post_url .'\';';
				$form .= '});';
				$form .= '</script>' . "\n";

				$gonder->veri	= $form;
				$gonder->kod	= '';
				$gonder->durum	= false;
				$gonder->mesaj	= '';
				$gonder->debug	= '';

				return $gonder;
			}

			$sendTrnxUrl = $send_transaction. '?TransactionID=';
			$sendTrnxUrl = $sendTrnxUrl . $results;

			$post_url = $strsuccessurl;
			$form = '';
			$form .= $this->ci->config->item('banka_pos_3d_mesaji');
			$form .= '<script type="text/javascript">' . "\n";
			$form .= '$(document).ready(function(){' . "\n";
			$form .= 'setTimeout("document.location.href = \''. $sendTrnxUrl .'\'", 1500);';
			$form .= '});';
			$form .= '</script>' . "\n";

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
			$this->ci->session->unset_userdata('sanal_pos_yonlendirme');

			$pos_model_bilgi = $banka_bilgi->banka_bilgi->{$this->ascii};

			$gelen_veriler->fiyat = isset($gelen_veriler->post_verileri['toplam_ucret']) ? strtr($gelen_veriler->post_verileri['toplam_ucret'], array('.' => '')) : ($this->ci->session->userdata('sanal_pos_fiyat')) ? strtr($this->ci->session->userdata('sanal_pos_fiyat'), array('.' => '')) : 0;
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

			if(isset($gelen_veriler->get_verileri['RC']) AND ($gelen_veriler->get_verileri['RC'] != '00'))
			{
				$gonder->durum = false;
				$gonder->kod   = (isset($gelen_veriler->get_verileri['RC'])) ? $gelen_veriler->get_verileri['RC'] : '92';
				$code = (int) $gelen_veriler->get_verileri['RC'];

				if(isset($hata_mesajlari[$code]))
				{
					if(isset($gelen_veriler->get_verileri['errormsg'])) {
						$olumsuz_hata_mesaji = $gelen_veriler->get_verileri['errormsg'];
					} else {
						$olumsuz_hata_mesaji = $hata_mesajlari[$code];
					}
				} else {
					if(isset($gelen_veriler->get_verileri['errormsg'])) {
						$olumsuz_hata_mesaji = $gelen_veriler->get_verileri['errormsg'];
					} else {
						$olumsuz_hata_mesaji = 'Hata Oluştu İşlem Gerçekleşmedi';
					}
				}
				$gonder->mesaj = $olumsuz_hata_mesaji;
			} elseif (isset($gelen_veriler->get_verileri['RC']) AND $gelen_veriler->get_verileri['RC'] == '00') {
				$gonder->kod   = '00';
				$gonder->durum = true;
				$gonder->mesaj = 'İşleminiz Başarılı Bir Şekilde Gerçekleşti';
			} else {
				$gonder->kod   = '92';
				$gonder->durum = false;
				$gonder->mesaj = 'Hata Oluştu İşlem Gerçekleşmedi';
			}

			$gonder->ozel_veri->fiyat = ($gelen_veriler->fiyat / 100);
			$gonder->ozel_veri->kart_numarasi = isset($gelen_veriler->post_verileri['kredi_kart_no']) ? $gelen_veriler->post_verileri['kredi_kart_no'] : 0;
			$gonder->ozel_veri->taksit = isset($gelen_veriler->post_verileri['kredi_kart_taksit']) ? $gelen_veriler->post_verileri['kredi_kart_taksit'] : 0;
			$gonder->veri	= '';
			$gonder->debug	= $gelen_veriler->get_verileri;
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