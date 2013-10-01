<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class odeme_secenekleri_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Ödeme Seçenekleri Model Yüklendi');
	}

	function odeme_secenekleri_listele()
	{
		$this->db->order_by('odeme_sira', 'asc');
		return $this->db->get('odeme_secenekleri');
	}

	function odeme_secenek_kredi_karti_pos_taksit_listele($banka_id)
	{
		$this->db->order_by('kkts_taksit_sayisi', 'asc');
		return $this->db->get_where('odeme_secenek_kredi_karti_taksit_secenekleri', array('kk_id' => $banka_id));
	}

	function odeme_secenek_havale_banka_hesap_listele($banka_id)
	{
		$this->db->order_by('havale_detay_id', 'asc');
		return $this->db->get_where('odeme_secenek_havale_detay', array('banka_id' => $banka_id));
	}

	function odeme_varmi_kontrol($odeme_model)
	{
		$sorgu = $this->db->get_where('odeme_secenekleri', array('odeme_model' => $odeme_model, 'odeme_kurulum' => '1'), 1);
		if($sorgu->num_rows() > 0)
		{
			return true;
		} else {
			return false;
		}
	}

	function pos_duzenle($gelen_veriler)
	{
		$pos_sorgu = $this->db->get_where('odeme_secenek_kredi_karti', array('kk_banka_adi_ascii' => $gelen_veriler->pos_model), 1);
		if($pos_sorgu->num_rows() > 0)
		{
			$pos_bilgi = $pos_sorgu->row();

			$this->db->where('kk_banka_standart', '1');
			$standart_kontrol = $this->db->count_all_results('odeme_secenek_kredi_karti');

			if($standart_kontrol > 0)
			{
				if($gelen_veriler->pos_standart == '1')
				{
					$this->db->update('odeme_secenek_kredi_karti', array('kk_banka_standart' => '0'));
				}
				$standart = $gelen_veriler->pos_standart;
			} else {
				$standart = '1';
			}

			$pos_data['kk_banka_durum'] 			= $gelen_veriler->pos_durum;
			$pos_data['kk_banka_pos_tipi']			= $gelen_veriler->pos_tipi;
			$pos_data['kk_banka_standart']			= $standart;
			$pos_data['kk_pesin_komisyon']			= $gelen_veriler->pos_pesin_komisyon;
			$pos_data['kk_magaza_kodu']				= $gelen_veriler->pos_magaza_kodu;
			$pos_data['kk_kullanici_adi']			= $gelen_veriler->pos_kullanici_adi;
			$pos_data['kk_kullanici_sifresi']		= $gelen_veriler->pos_kullanici_sifresi;
			$pos_data['kk_storekey']				= $gelen_veriler->pos_storekey;
			$pos_data['kk_terminal_id']				= $gelen_veriler->pos_terminal_id;
			$pos_data['kk_posnet_id']				= $gelen_veriler->pos_posnet_id;
			$this->db->where('kk_banka_adi_ascii', $gelen_veriler->pos_model);
			if($this->db->update('odeme_secenek_kredi_karti', $pos_data))
			{
				$gonder->sonuc = true;
			} else {
				$gonder->sonuc = false;
			}

			$gonder->standart = $standart;
			$gonder->bilgi = $pos_bilgi;
			return $gonder;
		} else {
			$gonder->sonuc	= false;
			return $gonder;
		}

		return $gonder;
	}

	function hesap_duzenle($gelen_veriler)
	{
		$banka_sorgu = $this->db->get_where('odeme_secenek_havale', array('havale_banka_ascii' => $gelen_veriler->banka_model), 1);
		if($banka_sorgu->num_rows() > 0)
		{
			$banka_bilgi = $banka_sorgu->row();

			$banka_data['hesap_durum'] 					= $gelen_veriler->hesap_durum;
			$banka_data['tur']							= $gelen_veriler->hesap_tur;
			$banka_data['iban_no']						= $gelen_veriler->hesap_iban_no;
			$banka_data['hesap_sahip']					= $gelen_veriler->hesap_hesap_sahip;
			$banka_data['sube']							= $gelen_veriler->hesap_sube;
			$banka_data['hesap_no']						= $gelen_veriler->hesap_no;
			$this->db->where('havale_detay_id', $gelen_veriler->hesap_id);
			if($this->db->update('odeme_secenek_havale_detay', $banka_data))
			{
				$gonder->sonuc = true;
			} else {
				$gonder->sonuc = false;
			}

			$gonder->bilgi = $banka_bilgi;
			return $gonder;
		} else {
			$gonder->sonuc	= false;
			return $gonder;
		}

		return $gonder;
	}

	function hesap_sil($gelen_veriler)
	{
		$banka_sorgu = $this->db->get_where('odeme_secenek_havale', array('havale_banka_ascii' => $gelen_veriler->banka_model), 1);
		if($banka_sorgu->num_rows() > 0)
		{
			$banka_bilgi = $banka_sorgu->row();

			$banka_data['havale_detay_id']				= $gelen_veriler->hesap_detay_id;
			$banka_data['banka_id']						= $banka_bilgi->havale_id;
			if($this->db->delete('odeme_secenek_havale_detay', $banka_data))
			{
				$gonder->sonuc = true;
			} else {
				$gonder->sonuc = false;
			}

			$gonder->bilgi = $banka_bilgi;
			return $gonder;
		} else {
			$gonder->sonuc	= false;
			return $gonder;
		}

		return $gonder;
	}

	function hesap_ekle($gelen_veriler)
	{
		$banka_sorgu = $this->db->get_where('odeme_secenek_havale', array('havale_banka_ascii' => $gelen_veriler->banka_model), 1);
		if($banka_sorgu->num_rows() > 0)
		{
			$banka_bilgi = $banka_sorgu->row();

			$banka_data['hesap_durum'] 					= $gelen_veriler->hesap_durum;
			$banka_data['tur']							= $gelen_veriler->hesap_tur;
			$banka_data['iban_no']						= $gelen_veriler->hesap_iban_no;
			$banka_data['hesap_sahip']					= $gelen_veriler->hesap_hesap_sahip;
			$banka_data['sube']							= $gelen_veriler->hesap_sube;
			$banka_data['hesap_no']						= $gelen_veriler->hesap_no;
			$banka_data['banka_id']						= $banka_bilgi->havale_id;
			if($this->db->insert('odeme_secenek_havale_detay', $banka_data))
			{
				$gonder->sonuc = true;
			} else {
				$gonder->sonuc = false;
			}

			$gonder->bilgi = $banka_bilgi;
			return $gonder;
		} else {
			$gonder->sonuc	= false;
			return $gonder;
		}

		return $gonder;
	}

	function banka_duzenle($gelen_veriler)
	{
		$banka_sorgu = $this->db->get_where('odeme_secenek_havale', array('havale_banka_ascii' => $gelen_veriler->banka_model), 1);
		if($banka_sorgu->num_rows() > 0)
		{
			$banka_bilgi = $banka_sorgu->row();

			//var_dump($gelen_veriler);

			$banka_data['havale_durum'] 			= $gelen_veriler->banka_durum;
			$this->db->where('havale_banka_ascii', $gelen_veriler->banka_model);
			if($this->db->update('odeme_secenek_havale', $banka_data))
			{
				$gonder->sonuc = true;
			} else {
				$gonder->sonuc = false;
			}

			$gonder->bilgi = $banka_bilgi;
			return $gonder;
		} else {
			$gonder->sonuc	= false;
			return $gonder;
		}

		return $gonder;
	}

	function pos_varmi_kontrol($pos_model)
	{
		$sorgu = $this->db->get_where('odeme_secenek_kredi_karti', array('kk_banka_adi_ascii' => $pos_model), 1);
		if($sorgu->num_rows() > 0)
		{
			return true;
		} else {
			return false;
		}
	}

	function banka_varmi_kontrol($banka_model)
	{
		$sorgu = $this->db->get_where('odeme_secenek_havale', array('havale_banka_ascii' => $banka_model), 1);
		if($sorgu->num_rows() > 0)
		{
			return true;
		} else {
			return false;
		}
	}

	function odeme_secenek_duzenle($odeme_model)
	{
		$update_data = array(
			'odeme_durum' => $this->input->post('odeme_durum'),
			'odeme_sira' => $this->input->post('odeme_sira')
		);
		$this->db->where('odeme_model', $odeme_model);
		if($this->db->update('odeme_secenekleri', $update_data))
		{
			return true;
		} else {
			return false;
		}
	}

	function odeme_secenek_bilgi($odeme_model)
	{
		$sorgu = $this->db->get_where('odeme_secenekleri', array('odeme_model' => $odeme_model), 1);
		if($sorgu->num_rows() > 0)
		{
			return $sorgu->row();
		} else {
			return NULL;
		}
	}

	function odeme_secenek_kredi_karti_pos_listele()
	{
		return $this->db->get('odeme_secenek_kredi_karti');
	}

	function odeme_secenek_havale_hesap_listele()
	{
		return $this->db->get('odeme_secenek_havale');
	}

	function odeme_kur($odeme_model)
	{
		$sorgu = $this->db->get_where('odeme_secenekleri', array('odeme_model' => $odeme_model, 'odeme_kurulum' => '0'), 1);
		if($sorgu->num_rows() > 0)
		{
			if($odeme_model == 'havale')
			{
				// Tüm Bankaların hesap numaralarını sil.
				$this->db->truncate('odeme_secenek_havale_detay');
				// Tüm bankaların pasif et.
				$this->db->update('odeme_secenek_havale', array('havale_durum' => '0'));
			}

			if($odeme_model == 'kredi_karti')
			{
				// Tüm banka pos bilgilerinin taksit seçeneklerini sil.
				$this->db->truncate('odeme_secenek_kredi_karti_taksit_secenekleri');
				// Tüm banka pos bilgilerini boşalt.
				$kredi_karti_data = array(
					'kk_banka_durum'		=> '0',
					'kk_banka_pos_tipi'		=> 'normal',
					'kk_banka_standart'		=> '0',
					'kk_banka_taksit'		=> '0',
					'kk_pesin_komisyon'		=> '00',
					'kk_magaza_kodu'		=> '',
					'kk_kullanici_adi'		=> '',
					'kk_kullanici_sifresi'	=> '',
					'kk_storekey'			=> '',
					'kk_terminal_id'		=> '',
					'kk_posnet_id'			=> '',
				);
				$this->db->update('odeme_secenek_kredi_karti', $kredi_karti_data);
			}

			$this->db->select_max('odeme_sira');
			$son_sira_sorgu = $this->db->get('odeme_secenekleri');
			$son_sira = $son_sira_sorgu->row();
			$this->db->where('odeme_model', $odeme_model);
			$this->db->update('odeme_secenekleri', array('odeme_kurulum' => '1', 'odeme_durum' => '1', 'odeme_sira' => ($son_sira->odeme_sira + 1)));
		}
	}

	function odeme_kaldir($odeme_model)
	{
		$sorgu = $this->db->get_where('odeme_secenekleri', array('odeme_model' => $odeme_model, 'odeme_kurulum' => '1'), 1);
		if($sorgu->num_rows() > 0)
		{
			if($odeme_model == 'havale')
			{
				// Tüm Bankaların hesap numaralarını sil.
				$this->db->truncate('odeme_secenek_havale_detay');
				// Tüm bankaların pasif et.
				$this->db->update('odeme_secenek_havale', array('havale_durum' => '0'));
			}

			if($odeme_model == 'kredi_karti')
			{
				// Tüm banka pos bilgilerinin taksit seçeneklerini sil.
				$this->db->truncate('odeme_secenek_kredi_karti_taksit_secenekleri');
				// Tüm banka pos bilgilerini boşalt.
				$kredi_karti_data = array(
					'kk_banka_durum'		=> '0',
					'kk_banka_pos_tipi'		=> 'normal',
					'kk_banka_standart'		=> '0',
					'kk_banka_taksit'		=> '0',
					'kk_pesin_komisyon'		=> '00',
					'kk_magaza_kodu'		=> '',
					'kk_kullanici_adi'		=> '',
					'kk_kullanici_sifresi'	=> '',
					'kk_storekey'			=> '',
					'kk_terminal_id'		=> '',
					'kk_posnet_id'			=> '',
				);
				$this->db->update('odeme_secenek_kredi_karti', $kredi_karti_data);
			}
			
			$this->db->where('odeme_model', $odeme_model);
			$this->db->update('odeme_secenekleri', array('odeme_kurulum' => '0', 'odeme_durum' => '0', 'odeme_sira' => '0'));
		}
	}
}