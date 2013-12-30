<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class kur_model extends CI_Model
{
	var $kur_adresi = array('xml' => 'http://www.tcmb.gov.tr/kurlar/today.xml', 'html' => 'http://www.tcmb.gov.tr/kurlar/today.html');

	protected $currencies = array();

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Kur Model Yüklendi');

		$this->kur_guncelle();
		$this->set_currencies();

		/* Kur Okuma */
		/*
			$this->kur_model->kur_oku('dolar', 'alis');
		*/
	}

	public function set_currencies()
	{
		$this->db->select(
			get_fields_from_table('kurlar', 'k.', array(), '')	
		);
		$this->db->from('kurlar k');
		$query = $this->db->get();
		foreach($query->result() as $result) {
			$send							= array();
			$send['kur_id']					= $result->kur_id;
			$send['kur_adi']				= $result->kur_adi;
			$send['kur_alis']				= $result->kur_alis;
			$send['kur_alis_eski']			= $result->kur_alis_eski;
			$send['kur_satis']				= $result->kur_satis;
			$send['kur_satis_eski']			= $result->kur_satis_eski;
			$send['kur_tipi']				= $result->kur_tipi;
			$send['kur_alis_manuel']		= $result->kur_alis_manuel;
			$send['kur_satis_manuel']		= $result->kur_satis_manuel;
			$send['kur_guncelleme_zamani']	= $result->kur_guncelleme_zamani;

			$key							= mb_strtolower($result->kur_adi);
			$this->currencies[$key]			= $send;
		}
	}

	public function get_curency($key, $item = NULL)
	{
		$currencies = $this->currencies;
		if(isset($currencies[$key])) {
			if($item != '') {
				if(isset($currencies[$key][$item])) {
					return $currencies[$key][$item];
				} else {
					return FALSE;
				}
			} else {
				return $currencies[$key];
			}
		} else {
			return FALSE;
		}
	}

	public function kur_oku($key, $type = 'satis')
	{
		$currency = $this->get_curency($key, NULL);
		if($currency) {
			$kur_id						= $currency['kur_id'];
			$kur_adi					= $currency['kur_adi'];
			$kur_alis					= $currency['kur_alis'];
			$kur_alis_eski				= $currency['kur_alis_eski'];
			$kur_satis					= $currency['kur_satis'];
			$kur_satis_eski				= $currency['kur_satis_eski'];
			$kur_tipi					= $currency['kur_tipi'];
			$kur_alis_manuel			= $currency['kur_alis_manuel'];
			$kur_satis_manuel			= $currency['kur_satis_manuel'];
			$kur_guncelleme_zamani		= $currency['kur_guncelleme_zamani'];

			if($type == 'satis') {
				if(config('site_ayar_kur') == '2' AND config('site_ayar_kur_yuzde')) {
					$yuzde_oran			= config('site_ayar_kur_yuzde');
					$oran				= (float)$yuzde_oran;
					$yuzde				= ($kur_satis * $oran)/100;
					$ret_price			= ($kur_satis + $yuzde);
				} elseif(config('site_ayar_kur') == '3') {
					$ret_price			= $kur_satis_manuel;
				} else {
					$ret_price			= $kur_satis;
				}
			} elseif($type == 'alis') {
				if(config('site_ayar_kur') == '2' AND config('site_ayar_kur_yuzde')) {
					$yuzde_oran			= config('site_ayar_kur_yuzde');
					$oran				= (float)$yuzde_oran;
					$yuzde				= ($kur_satis * $oran)/100;
					$ret_price			= ($kur_alis + $yuzde);
				} else if(config('site_ayar_kur') == '3') {
					$ret_price			= $kur_alis_manuel;
				} else {
					$ret_price			= $kur_alis;
				}
			} else {
				if(config('site_ayar_kur') == '2' AND config('site_ayar_kur_yuzde')) {
					$yuzde_oran			= config('site_ayar_kur_yuzde');
					$oran				= (float)$yuzde_oran;
					$yuzde				= ($kur_satis * $oran)/100;
					$ret_price			= ($kur_satis + $yuzde);
				} elseif(config('site_ayar_kur') == '3') {
					$ret_price			= $kur_satis_manuel;
				} else {
					$ret_price			= $kur_satis;
				}
			}
			return $ret_price;
		} else {
			return FALSE;
		}
	}

	/*function kur_oku($doviz_cinsi, $durum = 'satis')
	{
		$this->db->select('kur_id, kur_adi, kur_alis, kur_alis_eski, kur_satis, kur_satis_eski, kur_tipi, kur_alis_manuel, kur_satis_manuel, kur_guncelleme_zamani');
			$kur_sorgu = $this->db->get_where('kurlar', array('kur_adi' => $doviz_cinsi));

		if($kur_sorgu->num_rows() > 0)
		{
			$kur_bilgi = $kur_sorgu->row();
			if($durum == 'satis')
			{
				if(config('site_ayar_kur') == '2' && config('site_ayar_kur_yuzde'))
				{
					$yuzde_oran = config('site_ayar_kur_yuzde');
					$satis_fiyati = $kur_bilgi->kur_satis;
					$oran = floatval('0.' . $yuzde_oran);
					$yuzde = ($satis_fiyati * $oran);
					$satis_fiyati = ($satis_fiyati + $yuzde);
				} else if(config('site_ayar_kur') == '3') {
					$satis_fiyati = $kur_bilgi->kur_satis_manuel;
				} else {
					$satis_fiyati = $kur_bilgi->kur_satis;
				}

				return $satis_fiyati;
			} elseif($durum == 'alis') {
				if(config('site_ayar_kur') == '2' && config('site_ayar_kur_yuzde'))
				{
					$yuzde_oran = config('site_ayar_kur_yuzde');
					$alis_fiyati = $kur_bilgi->kur_alis;
					$oran = floatval('0.' . $yuzde_oran);
					$yuzde = ($alis_fiyati * $oran);
					$alis_fiyati = ($alis_fiyati + $yuzde);
				} else if(config('site_ayar_kur') == '3') {
					$alis_fiyati = $kur_bilgi->kur_alis_manuel;
				} else {
					$alis_fiyati = $kur_bilgi->kur_alis;
				}

				return $alis_fiyati;
			}
		}
	}
	*/

	function kur_guncelle()
	{
		if(config('site_ayar_kur') != '3')
		{
			//if(date('Hi', time()) == '1000' || date('Hi', time()) == '1600')
			//{
				$this->yeni_guncelleme($this->kur_adresi['xml']);
			//}
		}
	}

	function yeni_guncelleme($kur_adresi)
	{
		$this->db->where('(DATE(kur_guncelleme_zamani) = DATE(NOW()) AND HOUR(kur_guncelleme_zamani) < \''. (int) date('H', time()) .'\')');
		$gun_kontrol = $this->db->count_all_results('kurlar');

		if ($gun_kontrol == '0')
		{
			@ini_set('default_socket_timeout', 1);
			$dosya_kontrol = @simplexml_load_file($kur_adresi);
			if($dosya_kontrol)
			{
				$arrUsdSatis = $dosya_kontrol->xpath("Currency[@Kod='USD']/ForexSelling");
				$arrUsdAlis = $dosya_kontrol->xpath("Currency[@Kod='USD']/ForexBuying");
				
				$arrEurSatis = $dosya_kontrol->xpath("Currency[@Kod='EUR']/ForexSelling");
				$arrEurAlis = $dosya_kontrol->xpath("Currency[@Kod='EUR']/ForexBuying");
				
				$arrGbpSatis = $dosya_kontrol->xpath("Currency[@Kod='GBP']/ForexSelling");
				$arrGbpAlis = $dosya_kontrol->xpath("Currency[@Kod='GBP']/ForexBuying");
				
				if ($arrUsdSatis != '' and $arrUsdAlis != '' and $arrEurSatis != '' and $arrEurAlis != '' and $arrGbpSatis != '' and $arrGbpAlis != '')
				{
					$usd_kontrol = $this->db->get_where('kurlar', array('kur_adi' => 'USD'));
					$usd_alis = $arrUsdAlis[0];
					$usd_satis = $arrUsdSatis[0];
					
					if($usd_kontrol->num_rows() > 0)
					{
						$usd_bilgi = $usd_kontrol->row();

						$kur_data = array(
							'kur_alis' => $usd_alis,
							'kur_satis' => $usd_satis
						);

						if(!(date('Hi', time()) > '1600') && !(date('Hi', time()) < '1000'))
						{
							$kur_data['kur_alis_eski'] = $usd_bilgi->kur_alis;
							$kur_data['kur_satis_eski'] = $usd_bilgi->kur_satis;
						} else {
							$kur_data['kur_alis_eski'] = $usd_alis;
							$kur_data['kur_satis_eski'] = $usd_satis;
						}

						$this->db->where('kur_adi', 'USD');
						$this->db->update('kurlar', $kur_data);
					} else {
						$this->db->insert('kurlar', array('kur_alis' => $usd_alis, 'kur_alis_eski' => $usd_alis, 'kur_satis' => $usd_satis, 'kur_satis_eski' => $usd_satis, 'kur_adi' => 'USD', 'kur_tipi' => '2'));
					}

					$eur_kontrol = $this->db->get_where('kurlar', array('kur_adi' => 'EUR'));
					$eur_alis = $arrEurAlis[0];
					$eur_satis = $arrEurSatis[0];

					if($eur_kontrol->num_rows() > 0)
					{
						$eur_bilgi = $eur_kontrol->row();

						$kur_data = array(
							'kur_alis' => $eur_alis,
							'kur_satis' => $eur_satis
						);

						if(!(date('Hi', time()) > '1600') && !(date('Hi', time()) < '1000'))
						{
							$kur_data['kur_alis_eski'] = $eur_bilgi->kur_alis;
							$kur_data['kur_satis_eski'] = $eur_bilgi->kur_satis;
						} else {
							$kur_data['kur_alis_eski'] = $eur_alis;
							$kur_data['kur_satis_eski'] = $eur_satis;
						}

						$this->db->where('kur_adi', 'EUR');
						$this->db->update('kurlar', $kur_data);
					} else {
						$this->db->insert('kurlar', array('kur_alis' => $eur_alis, 'kur_alis_eski' => $eur_alis, 'kur_satis' => $eur_satis, 'kur_satis_eski' => $eur_satis, 'kur_adi' => 'EUR', 'kur_tipi' => '3'));
					}

					$gbp_kontrol = $this->db->get_where('kurlar', array('kur_adi' => 'GBP'));
					$gbp_alis = $arrGbpAlis[0];
					$gbp_satis = $arrGbpSatis[0];

					if($gbp_kontrol->num_rows() > 0)
					{
						$gbp_bilgi = $gbp_kontrol->row();

						$kur_data = array(
							'kur_alis' => $gbp_alis,
							'kur_satis' => $gbp_satis
						);

						if(!(date('Hi', time()) > '1600') && !(date('Hi', time()) < '1000'))
						{
							$kur_data['kur_alis_eski'] = $gbp_bilgi->kur_alis;
							$kur_data['kur_satis_eski'] = $gbp_bilgi->kur_satis;
						} else {
							$kur_data['kur_alis_eski'] = $gbp_alis;
							$kur_data['kur_satis_eski'] = $gbp_satis;
						}

						$this->db->where('kur_adi', 'GBP');
						$this->db->update('kurlar', $kur_data);
					} else {
						$this->db->insert('kurlar', array('kur_alis' => $gbp_alis, 'kur_alis_eski' => $gbp_alis, 'kur_satis' => $gbp_satis, 'kur_satis_eski' => $gbp_satis, 'kur_adi' => 'GBP', 'kur_tipi' => '4'));
					}

					$this->db->update('kurlar', array('kur_guncelleme_zamani' => standard_date('DATE_MYSQL', time(), 'tr')));
				}
			} else {
				log_message('error', 'Kur ' . standard_date('DATE_TR', time(), 'tr') . ' Tarihinde Html Dosyasına Ulaşamadı O Yüzden Güncellendi');
			}
		}
	}

	function tcmb($site)
	{
		/*$dosya = @curl_init();
		$adres = @curl_setopt($dosya, CURLOPT_URL, $site);
		$adres = @curl_setopt($dosya, CURLOPT_TIMEOUT, 1);
		ob_start();
		$adres = @curl_exec($dosya);
		@curl_close($dosya);
		$adres = ob_get_contents();
		ob_end_clean();*/
		@ini_set('default_socket_timeout', 1);
		$kontrol = @file_get_contents($site);

		if($kontrol !== false)
		{
			$currency = array(
				"USD"				=> "",
				"EUR"				=> "",
			);
			$convert = array( 
				"isim"				=> "isim",
				"forexbuying"		=> "alis",
				"forexselling"		=> "satis",
			);
			foreach($currency as $code => $arr)
			{
				preg_match("'<currency Kod=\"(".$code.")\".*>(.*)</currency>'Uis", $kontrol, $crst); 
				foreach($convert as $field => $value)
				{
					@preg_match("'<".$field.">(.*)</".$field.">'Uis", @$crst[2], $frst); 
					@$currency[$code][$value] = @$frst[1]; 
				}
			}
			
			foreach($currency as $kur => $degerleri)
			{
				$kur_kontrol = $this->db->get_where('kurlar', array('kur_adi' => $kur), 1);
				if($kur_kontrol->num_rows() > 0)
				{
					$kur_bilgi = $kur_kontrol->row();

					/*if(config('site_ayar_kur') == '2' && config('site_ayar_kur_yuzde'))
					{
						$yuzde_oran = config('site_ayar_kur_yuzde');
						$satis_fiyati = $degerleri['satis'];
						$oran = floatval('0.' . $yuzde_oran);
						$yuzde = ($satis_fiyati * $oran);
						$satis_fiyati = ($satis_fiyati + $yuzde);
					} else {
						$satis_fiyati = $degerleri['satis'];
					}*/

					$satis_fiyati = $degerleri['satis'];

					$kur_data = array(
						'kur_alis' => $degerleri['alis'],
						'kur_satis' => $satis_fiyati,
						'kur_guncelleme_zamani' => standard_date('DATE_TR', time(), 'tr')
					);

					if(!(date('Hi', time()) > '1600') && !(date('Hi', time()) < '1000'))
					{
						$kur_data['kur_alis_eski'] = $kur_bilgi->kur_alis;
						$kur_data['kur_satis_eski'] = $satis_fiyati;
					}

					$this->db->where('kur_id', $kur_bilgi->kur_id);
					$durum = $this->db->update('kurlar', $kur_data);
				} else {
					if($kur == 'USD')
					{
						$kur_tipi = '2';
					}
					elseif($kur == 'EUR')
					{
						$kur_tipi = '3';
					}
					
					$kur_data = array(
						'kur_alis' => $degerleri['alis'],
						'kur_alis_eski' => $degerleri['alis'],
						'kur_satis' => $degerleri['satis'],
						'kur_satis_eski' => $degerleri['satis'],
						'kur_guncelleme_zamani' => standard_date('DATE_TR', time(), 'tr'),
						'kur_tipi' => $kur_tipi,
						'kur_adi' => $kur
					);
					$durum = $this->db->insert('kurlar', $kur_data);
				}
				if($durum)
				{
					log_message('debug', 'Kur ' . standard_date('DATE_TR', time(), 'tr') . ' Tarihinde Güncellendi');
				} else {
					log_message('error', 'Kur ' . standard_date('DATE_TR', time(), 'tr') . ' Tarihinde Güncellenemedi');
				}
			}
		} else {
			log_message('debug', 'Kur ' . standard_date('DATE_TR', time(), 'tr') . ' Tarihinde Xml Dosyasına Ulaşamadı O Yüzden Güncellendi');
		}
	}
}