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
	O:8:"stdClass":1:{s:3:"3ds";O:8:"stdClass":3:{s:10:"merchantid";s:0:"";s:10:"terminalid";s:0:"";s:8:"posnetid";s:0:"";}}
	Php Kodu
	$olustur->{'3ds'}->merchantid	= '';
	$olustur->{'3ds'}->terminalid	= '';
	$olustur->{'3ds'}->posnetid		= '';
	echo serialize($olustur);
 */

class yapikredi_3ds
{
	public $ci;
	protected $banka = 'yapikredi';
	protected $ascii = '3ds';
	protected $banka_bilgi = false;

	/**
	 * 3ds construct
	 *
	 * @return void
	 * @author E-Ticaret Sistemim
	 **/

	function __construct()
	{
		log_message('debug', 'yapikredi_3ds Library Yüklendi');
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

			// Müşteri Ayarları
			$strcustomeripaddress = $gelen_veriler->ip_adres;
			$strcustomeremailaddress = $gelen_veriler->email_adres;
			$strterminaluserid = $gelen_veriler->user_id;
			$strorderid = microtime() . $gelen_veriler->user_id;

			$strinstallmentcount1 = ($gelen_veriler->taksit == '') ? '00' : $gelen_veriler->taksit;
			$strinstallmentcount = (strlen($strinstallmentcount1) < 2) ? '0' . $strinstallmentcount1 : $strinstallmentcount1;
			
			
			$stramount = str_replace('.', ',', $gelen_veriler->fiyat); //İşlem Tutarı
//			$security_data_terminal_hesapla = (strlen($pos_model_bilgi->terminalid) < 9) ? str_repeat('0', (9 - strlen($pos_model_bilgi->terminalid))) . $pos_model_bilgi->terminalid : $pos_model_bilgi->terminalid;

			$post_url = 'https://'. $banka_host_bilgileri[$banka_bilgi->kk_banka_adi_ascii]['host'][$banka_bilgi->kk_banka_test_tipi] . $banka_host_bilgileri[$banka_bilgi->kk_banka_adi_ascii][$this->ascii];
			$form = '';
			$form .= $this->ci->config->item('banka_pos_3d_mesaji');
			$form .= '<script type="text/javascript">' . "\n";
			$form .= '$(document).ready(function(){' . "\n";
			$form .= 'setTimeout("$(\'#' . md5($strorderid) . '\').submit()", 1500);';
			$form .= '});';
			$form .= '</script>' . "\n";
			$form .= '<form action="' . $post_url . '" id="' . md5($strorderid) . '" method="post">';
			$form .= form_hidden('instalment', $strinstallmentcount);
			$form .= form_hidden('amount', $stramount);
			$form .= form_hidden('posnetID', $pos_model_bilgi->posnetid);
			$form .= form_hidden('mid', $pos_model_bilgi->merchantid);
			$form .= form_hidden('tid', $pos_model_bilgi->terminalid);
			$form .= form_hidden('xid', substr(md5($strorderid),0,20));
			$form .= form_hidden('tranType', 'Auth');
			$form .= form_hidden('lang', 'tr');
			$form .= form_hidden('currencyCode', 'TL');
			$form .= form_hidden('merchantReturnSuccessURL', $strsuccessurl);
			$form .= form_hidden('merchantReturnFailURL', $strerrorurl);
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

			$gelen_veriler->fiyat = isset($gelen_veriler->get_verileri['amount']) ? $gelen_veriler->get_verileri['amount'] : 0;
			$gelen_veriler->taksit = isset($gelen_veriler->get_verileri['kredi_kart_taksit']) ? $gelen_veriler->get_verileri['kredi_kart_taksit'] : 0;
			$gelen_veriler->kart_numarasi = isset($gelen_veriler->get_verileri['kredi_kart_no']) ? $gelen_veriler->get_verileri['kredi_kart_no'] : 0;
			$gelen_veriler->kart_numarasi_ay = isset($gelen_veriler->get_verileri['kredi_kart_ay']) ? $gelen_veriler->get_verileri['kredi_kart_ay'] : 0;
			$gelen_veriler->kart_numarasi_yil = isset($gelen_veriler->get_verileri['kredi_kart_yil']) ? $gelen_veriler->get_verileri['kredi_kart_yil'] : 0;
			$gelen_veriler->kart_numarasi_guvenlik_kodu = isset($gelen_veriler->get_verileri['kredi_kart_ccv']) ? $gelen_veriler->get_verileri['kredi_kart_ccv'] : 0;

			$siparis_id = $gelen_veriler->siparis_id;
			$fatura_id  = $gelen_veriler->fatura_id;
			$fatura_bilgi = $gelen_veriler->fatura_bilgi;
			$teslimat_bilgi = $gelen_veriler->teslimat_bilgi;
			$uye_bilgi	= $gelen_veriler->uye_bilgi;
			$banka_adi  = $this->banka;

			if($gelen_veriler->get_verileri['errcode'] != '/' AND $gelen_veriler->get_verileri['errmsg'] AND ($gelen_veriler->get_verileri['returncode'] == '0'))
			{
				$gonder->durum = false;
				$gonder->kod   = (isset($gelen_veriler->get_verileri['errcode'])) ? $gelen_veriler->get_verileri['errcode']:'0092';
				$gonder->mesaj = (isset($gelen_veriler->get_verileri['errmsg'])) ? $gelen_veriler->get_verileri['errmsg']:'Hata Oluştu İşlem Gerçekleşmedi';
			} elseif(($gelen_veriler->get_verileri['errcode'] == '/') AND (!$gelen_veriler->get_verileri['errmsg']) AND ($gelen_veriler->get_verileri['returncode'] == '1')) {
				$gonder->kod   = '00';
				$gonder->durum = true;
				$gonder->mesaj = 'İşleminiz Başarılı Bir Şekilde Gerçekleşti';
			} else {
				$gonder->kod   = '92';
				$gonder->durum = false;
				$gonder->mesaj = 'Hata Oluştu İşlem Gerçekleşmedi';
			}

			$gonder->ozel_veri->fiyat = isset($gelen_veriler->get_verileri['amount']) ? (strtr($gelen_veriler->get_verileri['amount'],array(',' => '', '.' => '')) / 100) : 0;
			$gonder->ozel_veri->kart_numarasi = isset($gelen_veriler->get_verileri['kredi_kart_no']) ? $gelen_veriler->get_verileri['kredi_kart_no'] : 0;
			$gonder->ozel_veri->taksit = isset($gelen_veriler->get_verileri['kredi_kart_taksit']) ? $gelen_veriler->get_verileri['kredi_kart_taksit'] : 0;
			$gonder->debug	= '';
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