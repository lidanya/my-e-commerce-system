<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

if (!defined('BASEPATH')) exit('Doğrudan link ya da adres girişi yasaklanmıştır. !');

/**
 * Ödeme Seçenekleri class
 *
 * @package Contorller
 **/

class odeme_secenekleri extends Admin_Controller {

	var $izin_linki;

	/**
	 * Ödeme Seçenekleri construct
	 *
	 * @return void
	 **/

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Ödeme Seçenekleri Controller Yüklendi');

		$this->load->model('yonetim/yonetim_model');
		$this->load->model('yonetim/odeme_secenekleri_model');

		$this->izin_linki = 'sistem/odeme_secenekleri';
	}

	/**
	 * index function
	 *
	 * @return void
	 **/

	function index()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/odeme_secenekleri');
		redirect('yonetim/sistem/odeme_secenekleri/listele');
	}
	
	function listele()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/odeme_secenekleri/listele');

		$data['odeme_secenekleri'] = $this->odeme_secenekleri_model->odeme_secenekleri_listele();
		$this->load->view('yonetim/sistem/odeme_secenekleri', $data);
	}
	
	
	function duzenle($odeme_model)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/odeme_secenekleri/duzenle/' . $odeme_model);

		if(!$this->odeme_secenekleri_model->odeme_varmi_kontrol($odeme_model))
		{
			redirect('yonetim/sistem/odeme_secenekleri');
		}
		$secenek_bilgi = $this->odeme_secenekleri_model->odeme_secenek_bilgi($odeme_model);

		/*if($_POST)
		{
			if($this->odeme_secenekleri_model->odeme_secenek_duzenle($odeme_model))
			{
				$data['duzenleme_durumu'] = true;
			} else {
				$data['duzenleme_durumu'] = false;
			}
		}*/

		$unserialize_baslik = @unserialize( $secenek_bilgi->odeme_baslik);

		$data['heading_title'] = $unserialize_baslik[get_language('language_id', config('site_ayar_yonetim_dil'))];
		$data['modul'] = $secenek_bilgi;
		$data['action'] = 'yonetim/sistem/odeme_secenekleri/duzenle/' . $odeme_model;
		$data['bilgi'] = $secenek_bilgi;
		$this->load->view('yonetim/sistem/odeme_secenekleri/duzenle/' . $odeme_model . '_view', $data);
	}

	function ajax_pos_bilgi_duzenle()
	{
		$val = $this->validation;

		$sonuc = NULL;

		$rules['pos_bilgi_id']						= 'trim|required|xss_clean';
		$rules['pos_bilgi_tipi']					= 'trim|required|xss_clean';
		$rules['pos_bilgi_test_tipi']				= 'trim|required|xss_clean';
		$rules['pos_bilgi_standart']				= 'trim|required|xss_clean';
		$rules['pos_bilgi_taksit']					= 'trim|required|xss_clean';
		$rules['pos_bilgi_pesin_komisyon']			= 'trim|required|xss_clean';
		$rules['pos_bilgi_durum']					= 'trim|required|xss_clean';
		$rules['pos_bilgi_modelleri']				= 'trim|xss_clean';

		$fields['pos_bilgi_id']						= 'Pos Numarası';
		$fields['pos_bilgi_tipi']					= 'Pos Tipi';
		$fields['pos_bilgi_test_tipi']				= 'Gönderim Tipi';
		$fields['pos_bilgi_standart']				= 'Standart';
		$fields['pos_bilgi_taksit']					= 'Taksit';
		$fields['pos_bilgi_pesin_komisyon']			= 'Peşin Komisyonu';
		$fields['pos_bilgi_durum']					= 'Pos Durumu';
		$fields['pos_bilgi_modelleri']				= 'Pos Modelleri';

		$sonuc['pos_bilgi_id_error']				= '';
		$sonuc['pos_bilgi_tipi_error']				= '';
		$sonuc['pos_bilgi_test_tipi_error']			= '';
		$sonuc['pos_bilgi_standart_error']			= '';
		$sonuc['pos_bilgi_taksit_error']			= '';
		$sonuc['pos_bilgi_pesin_komisyon_error']	= '';
		$sonuc['pos_bilgi_durum_error']				= '';
		$sonuc['pos_bilgi_standart']				= '';

		$sonuc['success']							= '';
		$sonuc['error']								= '';

		$val->set_rules($rules);
		$val->set_fields($fields);
		$val->set_error_delimiters('', '');
		if($val->run() === FALSE)
		{
			if($val->pos_bilgi_id_error)
			{
				$sonuc['pos_bilgi_id_error']				= $val->pos_bilgi_id_error;
			}

			if($val->pos_bilgi_tipi_error)
			{
				$sonuc['pos_bilgi_tipi_error']				= $val->pos_bilgi_tipi_error;
			}

			if($val->pos_bilgi_standart_error)
			{
				$sonuc['pos_bilgi_standart_error']			= $val->pos_bilgi_standart_error;
			}

			if($val->pos_bilgi_taksit_error)
			{
				$sonuc['pos_bilgi_taksit_error']			= $val->pos_bilgi_taksit_error;
			}

			if($val->pos_bilgi_pesin_komisyon_error)
			{
				$sonuc['pos_bilgi_pesin_komisyon_error']	= $val->pos_bilgi_pesin_komisyon_error;
			}

			if($val->pos_bilgi_durum_error)
			{
				$sonuc['pos_bilgi_durum_error']				= $val->pos_bilgi_durum_error;
			}

			if($val->pos_bilgi_test_tipi_error)
			{
				$sonuc['pos_bilgi_test_tipi_error']			= $val->pos_bilgi_test_tipi_error;
			}

			if($val->pos_bilgi_id_error OR $val->pos_bilgi_tipi_error OR $val->pos_bilgi_standart_error OR $val->pos_bilgi_taksit_error OR $val->pos_bilgi_pesin_komisyon_error OR $val->pos_bilgi_durum_error OR $val->pos_bilgi_test_tipi_error)
			{
				$sonuc['error']								= 'İşleminiz gerçekleşemedi, gerekli alanları doldurun! ';
			}
		} else {
			$kontrol = self::pos_bilgi_guncelleme_islemi($val);
			if($kontrol->durum === TRUE)
			{
				if($val->pos_bilgi_standart === '1')
				{
					$sonuc['pos_bilgi_standart']			= '1';
				}
				$sonuc['success']							= $kontrol->mesaj;
			} else {
				$sonuc['error']								= $kontrol->mesaj;
			}
		}

		exit(json_encode($sonuc));
	}

	function pos_bilgi_guncelleme_islemi($gelen_degerler)
	{
		$banka_bilgi_sorgu = $this->db->get_where('odeme_secenek_kredi_karti', array('kk_id' => $gelen_degerler->pos_bilgi_id), 1);
		if($banka_bilgi_sorgu->num_rows() > 0)
		{
			$banka_bilgi = $banka_bilgi_sorgu->row();
			$secilebilir_banka_pos_tipleri = @unserialize($banka_bilgi->kk_banka_secilebilir_pos_tipleri);

			if(isset($secilebilir_banka_pos_tipleri->{$gelen_degerler->pos_bilgi_tipi}) AND ($secilebilir_banka_pos_tipleri->{$gelen_degerler->pos_bilgi_tipi}->taksit !== '1' AND $gelen_degerler->pos_bilgi_taksit === '1'))
			{
				$gonder_sonuc->durum = false;
				$gonder_sonuc->mesaj = 'İşleminiz gerçekleşemedi, seçtiğiniz pos tipi taksit desteklemiyor!';
			} else {
				$update_data = array(
					'kk_banka_durum'						=> $gelen_degerler->pos_bilgi_durum,
					'kk_banka_pos_tipi'						=> $gelen_degerler->pos_bilgi_tipi,
					'kk_banka_test_tipi'					=> $gelen_degerler->pos_bilgi_test_tipi,
					'kk_banka_standart'						=> $gelen_degerler->pos_bilgi_standart,
					'kk_banka_taksit'						=> $gelen_degerler->pos_bilgi_taksit,
					'kk_pesin_komisyon'						=> $gelen_degerler->pos_bilgi_pesin_komisyon
				);

				$ser = null;
				foreach($gelen_degerler->pos_bilgi_modelleri as $pos_modelleri_key => $pos_modelleri_value)
				{
					$ser = (object) $pos_modelleri_value;
					foreach($pos_modelleri_value as $pos_modulleri_value_key => $pos_modulleri_value_value)
					{
						$ser->{$pos_modulleri_value_key} = (object) $pos_modulleri_value_value;
					}
				}

				if(!is_null($ser))
				{
					$pos_bilgileri_serialize = serialize($ser);
					$update_data['kk_banka_bilgi'] = $pos_bilgileri_serialize;
				}

				$this->db->where('kk_id', $gelen_degerler->pos_bilgi_id);
				$this->db->update('odeme_secenek_kredi_karti', $update_data);

				if($this->db->affected_rows() > 0)
				{
					$gonder_sonuc->durum = true;
					$gonder_sonuc->mesaj = 'İşleminiz başarılı bir şekilde gerçekleştir.';
				} else {
					$gonder_sonuc->durum = false;
					$gonder_sonuc->mesaj = 'İşleminiz gerçekleşemedi, herhangi bir değişiklik yapmadınız!';
				}

				if($gelen_degerler->pos_bilgi_standart === '1')
				{
					$this->db->update('odeme_secenek_kredi_karti', array('kk_banka_standart' => '0'));

					$this->db->where('kk_id' , $gelen_degerler->pos_bilgi_id);
					$this->db->update('odeme_secenek_kredi_karti', array('kk_banka_standart' => '1'));
				} else {
					$this->db->where('kk_banka_standart', '1');
					$standart_sayisi = $this->db->count_all_results('odeme_secenek_kredi_karti');
					if(!($standart_sayisi > 0))
					{
						$this->db->where('kk_id' , $gelen_degerler->pos_bilgi_id);
						$this->db->update('odeme_secenek_kredi_karti', array('kk_banka_standart' => '1'));
					}
				}

			}
		} else {
			$gonder_sonuc->durum = false;
			$gonder_sonuc->mesaj = 'İşleminiz gerçekleşemedi, banka bilgilerine ulaşılamadı!';
		}

		return $gonder_sonuc;
	}

	function ajax_pos_taksit_sil()
	{
		$sonuc = null;
		$kkts_id = $this->input->post('kkts_id');

		$sonuc['success']			= '';
		$sonuc['error']				= '';

		if(!$kkts_id)
		{
			$sonuc['error']			= 'İşleminiz başarısız, taksit seçeneği numarasına ulaşamadık!';
		} else {
			$this->db->delete('odeme_secenek_kredi_karti_taksit_secenekleri', array('kkts_id' => $kkts_id));
			if($this->db->affected_rows() > 0)
			{
				$sonuc['success']	= 'Taksit seçeneği silme işleminiz başarılı bir şekilde gerçekleştirilmiştir.';
			} else {
				$sonuc['error']		= 'İşleminiz başarısız, taksit seneçeği bulunamadı!';
			}
		}

		exit(json_encode($sonuc));
	}

	function ajax_pos_taksit_listele($pos_id)
	{
		$poslar = '';

		$yeni_ekle_komisyon['00'] = '0';
		$yeni_ekle_komisyon['01'] = '1';
		$yeni_ekle_komisyon['02'] = '2';
		$yeni_ekle_komisyon['03'] = '3';
		$yeni_ekle_komisyon['04'] = '4';
		$yeni_ekle_komisyon['05'] = '5';
		$yeni_ekle_komisyon['06'] = '6';
		$yeni_ekle_komisyon['07'] = '7';
		$yeni_ekle_komisyon['08'] = '8';
		$yeni_ekle_komisyon['09'] = '9';
		for($i=10;$i<=99;$i++)
		{
			$yeni_ekle_komisyon[$i] = $i;
		}

		$poslar .= '<tr>
			<form method="post" enctype="multipart/form-data" id="taksit_ekle_'. $pos_id .'">
			<td class="left">
				'. form_hidden('taksit_ekle_pos_id', $pos_id) .'
				<input type="text" name="taksit_ekle_taksit_sayisi" id="taksit_ekle_taksit_sayisi_'. $pos_id .'" /> <span id="taksit_ekle_taksit_sayisi_error_'. $pos_id .'"></span>
			</td>
			<td class="left">';

		$poslar .= '% ' . form_dropdown('taksit_ekle_komisyon', $yeni_ekle_komisyon, '00', 'id="'. 'taksit_ekle_komisyon_' . $pos_id .'"');

		$durum_array = array('0' => 'Pasif', '1' => 'Aktif');
		$poslar .= '</td>
			<td class="left">';
		
		$poslar .= form_dropdown('taksit_ekle_durum', $durum_array, 1, 'id="'. 'taksit_ekle_durum_' . $pos_id .'"');
		$poslar .= '</td>
			<td class="right">[ <span id="taksit_ekle_span_id_'. $pos_id .'"><a href="javascript:;" onclick="pos_taksit_ekle(\''. $pos_id .'\');">Ekle</a></span> ]</td>
			</form>
		</tr>';

		$this->db->order_by('kkts_taksit_sayisi', 'asc');
		$taksit_secenekleri = $this->db->get_where('odeme_secenek_kredi_karti_taksit_secenekleri', array('kk_id' => $pos_id));
		foreach($taksit_secenekleri->result() as $taksit_secenegi)
		{
			$poslar .= '<tr id="taksit_duzenle_tr_'. $taksit_secenegi->kkts_id .'">
				<form method="post" enctype="multipart/form-data" id="taksit_duzenle_'. $taksit_secenegi->kkts_id .'">
					<td class="left">
						'. $taksit_secenegi->kkts_taksit_sayisi .'
					</td>';
			$poslar .= '<td class="left">
				'. form_hidden('taksit_duzenle_id', $taksit_secenegi->kkts_id);

			$duzenle_komisyon['00'] = '0';
			$duzenle_komisyon['01'] = '1';
			$duzenle_komisyon['02'] = '2';
			$duzenle_komisyon['03'] = '3';
			$duzenle_komisyon['04'] = '4';
			$duzenle_komisyon['05'] = '5';
			$duzenle_komisyon['06'] = '6';
			$duzenle_komisyon['07'] = '7';
			$duzenle_komisyon['08'] = '8';
			$duzenle_komisyon['09'] = '9';
			for($i=10;$i<=99;$i++)
			{
				$duzenle_komisyon[$i] = $i;
			}

			$poslar .= '% ' . form_dropdown('taksit_duzenle_komisyon', $duzenle_komisyon, $taksit_secenegi->kkts_komisyon, 'id="'. 'taksit_duzenle_komisyon_' . $taksit_secenegi->kkts_id .'"');

			$poslar .= '</td>
				<td class="left">';

			$durum_array = array('0' => 'Pasif', '1' => 'Aktif');
			$poslar .= form_dropdown('taksit_duzenle_durum', $durum_array, $taksit_secenegi->kkts_durum, 'id="'. 'taksit_duzenle_durum_' . $taksit_secenegi->kkts_id .'"');

			$poslar .= '</td>
				<td class="right">[ <span id="taksit_sil_span_id_'. $taksit_secenegi->kkts_id .'"><a href="javascript:;" onclick="pos_taksit_sil(\''. $taksit_secenegi->kkts_id .'\', \''. $pos_id .'\');">Sil</a></span> ] [ <span id="taksit_duzenle_span_id_'. $taksit_secenegi->kkts_id .'"><a href="javascript:;" onclick="pos_taksit_guncelle(\''. $taksit_secenegi->kkts_id .'\', \''. $pos_id .'\');">Kaydet</a></span> ]</td>
				</form>
			</tr>';
		}

		exit($poslar);
	}

	function ajax_pos_taksit_duzenle()
	{
		$val = $this->validation;

		$sonuc = NULL;

		$rules['taksit_duzenle_id']					= 'trim|required|xss_clean';
		$rules['taksit_duzenle_komisyon']			= 'trim|required|xss_clean';
		$rules['taksit_duzenle_durum']				= 'trim|required|xss_clean';

		$fields['taksit_duzenle_id']				= 'Taksit Numarası';
		$fields['taksit_duzenle_komisyon']			= 'Taksit Komisyonu';
		$fields['taksit_duzenle_durum']				= 'Taksit Durumu';

		$sonuc['success']							= '';
		$sonuc['error']								= '';

		$val->set_rules($rules);
		$val->set_fields($fields);
		$val->set_error_delimiters('', '');
		if($val->run() === FALSE)
		{
			$sonuc['error']							= 'İşleminiz gerçekleşemedi, gerekli alanları doldurun! ';
		} else {
			$kontrol = self::pos_taksit_guncelleme_islemi($val);
			if($kontrol->durum === TRUE)
			{
				$sonuc['success']					= $kontrol->mesaj;
			} else {
				$sonuc['error']						= $kontrol->mesaj;
			}
		}

		exit(json_encode($sonuc));
	}

	function ajax_pos_taksit_ekle()
	{
		$val = $this->validation;

		$sonuc = NULL;

		$rules['taksit_ekle_taksit_sayisi']			= 'trim|required|xss_clean';
		$rules['taksit_ekle_komisyon']				= 'trim|required|xss_clean';
		$rules['taksit_ekle_durum']					= 'trim|required|xss_clean';
		$rules['taksit_ekle_pos_id']				= 'trim|required|xss_clean';

		$fields['taksit_ekle_taksit_sayisi']		= 'Taksit Sayısı';
		$fields['taksit_ekle_komisyon']				= 'Taksit Komisyonu';
		$fields['taksit_ekle_durum']				= 'Taksit Durumu';
		$fields['taksit_ekle_pos_id']				= 'Taksit Banka Numarası';

		$sonuc['success']							= '';
		$sonuc['error']								= '';
		$sonuc['taksit_ekle_taksit_sayisi_error']	= '';

		$val->set_rules($rules);
		$val->set_fields($fields);
		$val->set_error_delimiters('', '');
		if($val->run() === FALSE)
		{
			$sonuc['error']							= 'İşleminiz gerçekleşemedi, gerekli alanları doldurun!';
			if($val->taksit_ekle_taksit_sayisi_error)
			{
				$sonuc['taksit_ekle_taksit_sayisi_error'] = $val->taksit_ekle_taksit_sayisi_error;
			}
		} else {
			$kontrol = self::pos_taksit_ekleme_islemi($val);
			if($kontrol->durum === TRUE)
			{
				$sonuc['success']					= $kontrol->mesaj;
			} else {
				$sonuc['error']						= $kontrol->mesaj;
			}
		}

		exit(json_encode($sonuc));
	}

	function pos_taksit_ekleme_islemi($gelen_degerler)
	{

		if($gelen_degerler->taksit_ekle_taksit_sayisi === '1')
		{
			$gonder_sonuc->durum = false;
			$gonder_sonuc->mesaj = 'İşleminiz gerçekleşemedi, en az 2 taksit ekleyebilirsiniz!';
		} else {
			$this->db->where('kk_id', $gelen_degerler->taksit_ekle_pos_id);
			$this->db->where('kkts_taksit_sayisi', $gelen_degerler->taksit_ekle_taksit_sayisi);
			$taksit_sayisi_kontrol = $this->db->count_all_results('odeme_secenek_kredi_karti_taksit_secenekleri');
			if($taksit_sayisi_kontrol > 0)
			{
				$gonder_sonuc->durum = false;
				$gonder_sonuc->mesaj = 'İşleminiz gerçekleşemedi, girmiş olduğunuz taksit sayısı mevcut farklı giriniz!';
			} else {
				$insert_data = array(
					'kkts_komisyon'							=> $gelen_degerler->taksit_ekle_komisyon,
					'kkts_taksit_sayisi'					=> $gelen_degerler->taksit_ekle_taksit_sayisi,
					'kk_id'									=> $gelen_degerler->taksit_ekle_pos_id,
					'kkts_durum'							=> $gelen_degerler->taksit_ekle_durum
				);

				$this->db->insert('odeme_secenek_kredi_karti_taksit_secenekleri', $insert_data);

				if($this->db->affected_rows() > 0)
				{
					$gonder_sonuc->durum = true;
					$gonder_sonuc->mesaj = 'İşleminiz başarılı bir şekilde gerçekleştirilmiştir.';
				} else {
					$gonder_sonuc->durum = false;
					$gonder_sonuc->mesaj = 'İşleminiz gerçekleşemedi, herhangi bir ekleme yapmadınız!';
				}
			}
		}

		return $gonder_sonuc;
	}

	function pos_taksit_guncelleme_islemi($gelen_degerler)
	{
		$update_data = array(
			'kkts_komisyon'							=> $gelen_degerler->taksit_duzenle_komisyon,
			'kkts_durum'							=> $gelen_degerler->taksit_duzenle_durum
		);

		$this->db->where('kkts_id', $gelen_degerler->taksit_duzenle_id);
		$this->db->update('odeme_secenek_kredi_karti_taksit_secenekleri', $update_data);

		if($this->db->affected_rows() > 0)
		{
			$gonder_sonuc->durum = true;
			$gonder_sonuc->mesaj = 'İşleminiz başarılı bir şekilde gerçekleştirilmiştir.';
		} else {
			$gonder_sonuc->durum = false;
			$gonder_sonuc->mesaj = 'İşleminiz gerçekleşemedi, herhangi bir değişiklik yapmadınız!';
		}

		return $gonder_sonuc;
	}
	
	function ajax_banka_hesap_duzenle()
	{
		$sonuc = NULL;

		$banka_model 							= $this->input->post('banka_model');
		$hesap_id								= $this->input->post('hesap_id');
		$hesap_durum							= $this->input->post('durum');
		$hesap_tur								= $this->input->post('tur');
		$hesap_iban_no							= $this->input->post('iban_no');
		$hesap_hesap_sahip						= $this->input->post('hesap_sahip');
		$hesap_sube								= $this->input->post('sube');
		$hesap_no								= $this->input->post('hesap_no');
		
		$degerler->banka_model					= $banka_model;
		$degerler->hesap_id						= $hesap_id;
		$degerler->hesap_durum					= $hesap_durum;
		$degerler->hesap_tur					= $hesap_tur;
		$degerler->hesap_iban_no				= $hesap_iban_no;
		$degerler->hesap_hesap_sahip			= $hesap_hesap_sahip;
		$degerler->hesap_sube					= $hesap_sube;
		$degerler->hesap_no						= $hesap_no;
		
		if(!$this->odeme_secenekleri_model->banka_varmi_kontrol($banka_model))
		{
			$sonuc['basarisiz'] = 'Hata banka bulunamadı.';
		}

		$durum = $this->odeme_secenekleri_model->hesap_duzenle($degerler);
		if($durum->sonuc)
		{
			$sonuc['basarili']	= $durum->bilgi->havale_banka_baslik . ' hesap bilgisi başarılı bir şekilde düzenlenmiştir.';
		} else {
			$sonuc['basarisiz']	= 'Hata banka hesap bilgisi düzenlenemedi.';
		}

		exit(json_encode($sonuc));
	}

	function ajax_banka_duzenle()
	{
		$sonuc = NULL;

		$banka_model 							= $this->input->post('banka_model');
		$banka_durum							= $this->input->post('banka_durum');
		
		$degerler->banka_model					= $banka_model;
		$degerler->banka_durum					= $banka_durum;
		
		if(!$this->odeme_secenekleri_model->banka_varmi_kontrol($banka_model))
		{
			$sonuc['basarisiz'] = 'Hata banka bulunamadı.';
		}

		$durum = $this->odeme_secenekleri_model->banka_duzenle($degerler);
		if($durum->sonuc)
		{
			$sonuc['basarili']	= $durum->bilgi->havale_banka_baslik . ' banka bilgileri başarılı bir şekilde düzenlenmiştir.';
		} else {
			$sonuc['basarisiz']	= 'Hata banka düzenlenemedi.';
		}

		exit(json_encode($sonuc));
	}
	
	function ajax_banka_hesap_listele($banka_model)
	{
		$banka_bilgileri = $this->db->get_where('odeme_secenek_havale', array('havale_banka_ascii' => $banka_model), 1);

		$yaz = '';

		if($banka_bilgileri->num_rows() > 0)
		{
			$hesaplar = $banka_bilgileri->row();

			$yaz .= '<tr style="background-color: #e6fbd3;" id="hesap_tablo_'. $hesaplar->havale_banka_ascii .'">
				<td class="left" style="text-align:center;width:103px;"><b>'. $hesaplar->havale_banka_baslik .' - Hesap Numaraları</b></td>
				<td class="left" style="text-align:center;width:110px;"><b>Hesap No</b></td>
				<td class="left" style="text-align:center;width:110px;"><b>Şube</b></td>
				<td class="left" style="text-align:center;width:110px;"><b>Hesap Sahibi</b></td>
				<td class="left" style="text-align:center;width:225px;"><b>IBAN No</b></td>
				<td class="left" style="text-align:center;width:120px;"><b>Tip</b></td>
				<td class="left" style="text-align:center;width:120px;"><b>Durum</b></td>
				<td colspan="6"></td>
				<td class="right" style="width:200px;"><b>Aksiyon</b></td>
			</tr>';

			$turler = array(
				'1' => 'TL',
				'2' => '$',
				'3' => '€'
			);

			$yaz .= '<tr style="background-color: #fbf4cc;" class="hesap_tablo_2_'. $hesaplar->havale_banka_ascii .'">
				<td class="left" style="text-align:center; width:89px;">Yeni Ekle</td>
				<td class="left" style="text-align:center; width:89px;">
					<input type="text" value="" name="hesap_no" id="hesap_no_yeni_'. $hesaplar->havale_banka_ascii . '">
				</td>
				<td class="left" style="text-align:center; width:89px;">
					<input type="text" value="" name="sube" id="sube_yeni_'. $hesaplar->havale_banka_ascii . '">
				</td>
				<td class="left" style="text-align:center; width:89px;">
					<input type="text" value="" name="hesap_sahip" id="hesap_sahip_yeni_'. $hesaplar->havale_banka_ascii . '">
				</td>
				<td class="left" style="text-align:center; width:225px;">
					<input type="text" value="" name="iban_no" id="iban_no_yeni_'. $hesaplar->havale_banka_ascii . '" style="width: 210px;">
				</td>
				<td class="left" style="text-align:center; width:89px;">
					'. form_dropdown('tur', $turler, 'TL', 'id="tur_yeni_'. $hesaplar->havale_banka_ascii .'"') .'
				</td>
				<td class="left" style="text-align:center; width:89px;">
					<select name="hesap_durum" id="hesap_durum_yeni_'. $hesaplar->havale_banka_ascii .'">
						<option value="1" selected="selected">Açık</option>
						<option value="0">Kapalı</option>
					</select>
				</td>
				<td colspan="6"></td>
				<td class="right" style="width:95px;">
					[ <span id="banka_hesap_ekle_buton_'. $hesaplar->havale_banka_ascii . '">
						<a style="cursor: pointer;" id="banka_hesap_ekle_buton_'. $hesaplar->havale_banka_ascii .'" onclick="banka_hesap_ekle(\''. $hesaplar->havale_banka_ascii .'\');">Ekle</a>
					</span> ]
				</td>
			</tr>';
			
			$this->db->order_by('havale_detay_id', 'asc');
			$banka_hesap_sorgu = $this->db->get_where('odeme_secenek_havale_detay', array('banka_id' => $hesaplar->havale_id));
			foreach($banka_hesap_sorgu->result() as $banka_hesaplar)
			{
				$turler = array(
					'1' => 'TL',
					'2' => '$',
					'3' => '€'
				);

				if ($banka_hesaplar->hesap_durum == '1') {
					$hesap_durum = '<option value="1" selected="selected">Açık</option>
					<option value="0">Kapalı</option>';
				} else {
					$hesap_durum = '<option value="1">Açık</option>
					<option value="0" selected="selected">Kapalı</option>';
				}

				$yaz .= '<tr style="background-color: #fffdf2;" class="hesap_tablo_2_'. $hesaplar->havale_banka_ascii .'">
					<td class="left" style="text-align:center; width:89px;"></td>
					<td class="left" style="text-align:center; width:89px;">
						<input type="text" value="'. $banka_hesaplar->hesap_no .'" name="hesap_no" id="hesap_no_'. $hesaplar->havale_banka_ascii . '_' . $banka_hesaplar->havale_detay_id .'">
					</td>
					<td class="left" style="text-align:center; width:89px;">
						<input type="text" value="'. $banka_hesaplar->sube .'" name="sube" id="sube_'. $hesaplar->havale_banka_ascii . '_' . $banka_hesaplar->havale_detay_id .'">
					</td>
					<td class="left" style="text-align:center; width:89px;">
						<input type="text" value="'. $banka_hesaplar->hesap_sahip .'" name="hesap_sahip" id="hesap_sahip_'. $hesaplar->havale_banka_ascii . '_' . $banka_hesaplar->havale_detay_id .'">
					</td>
					<td class="left" style="text-align:center; width:225px;">
						<input type="text" value="'. $banka_hesaplar->iban_no .'" name="iban_no" id="iban_no_'. $hesaplar->havale_banka_ascii . '_' . $banka_hesaplar->havale_detay_id .'" style="width: 210px;">
					</td>
					<td class="left" style="text-align:center; width:89px;">
						'. form_dropdown('tur', $turler, $banka_hesaplar->tur, 'id="tur_'. $hesaplar->havale_banka_ascii . '_' . $banka_hesaplar->havale_detay_id .'"') .'
					</td>
					<td class="left" style="text-align:center; width:89px;">
						<select name="hesap_durum" id="hesap_durum_'. $hesaplar->havale_banka_ascii . '_'. $banka_hesaplar->havale_detay_id .'">
							'. $hesap_durum .'
						</select>
					</td>
					<td colspan="6"></td>
					<td class="right" style="width:95px;">
						[ <span id="banka_duzenle_buton_'. $hesaplar->havale_banka_ascii . '_' . $banka_hesaplar->havale_detay_id .'"><a onclick="banka_hesap_duzenle(\''. $hesaplar->havale_banka_ascii .'\', \''. $banka_hesaplar->havale_detay_id .'\');" style="cursor: pointer;">Kaydet</a></span> ]
						[ <a style="cursor: pointer;" id="taksit_sil_buton_'. $hesaplar->havale_banka_ascii .'" onclick="banka_hesap_sil(\''. $hesaplar->havale_banka_ascii .'\', \''. $banka_hesaplar->havale_detay_id .'\');">Sil</a> ]
					</td>
				</tr>';
			}
		}
		exit($yaz);
	}

	function ajax_banka_hesap_sil()
	{
		$sonuc = NULL;
		$banka_model 							= $this->input->post('banka_model');
		$hesap_detay_id							= $this->input->post('hesap_d_id');

		$degerler->banka_model					= $banka_model;
		$degerler->hesap_detay_id				= $hesap_detay_id;

		if(!$this->odeme_secenekleri_model->banka_varmi_kontrol($banka_model))
		{
			$sonuc['basarisiz'] = 'Hata banka bulunamadı.';
		}

		$durum = $this->odeme_secenekleri_model->hesap_sil($degerler);
		if($durum->sonuc)
		{
			$sonuc['basarili']	= $durum->bilgi->havale_banka_baslik . ' hesap bilgisi başarılı bir şekilde silinmiştir.';
		} else {
			$sonuc['basarisiz']	= 'Hata banka hesap bilgisi silinemedi.';
		}

		exit(json_encode($sonuc));
	}

	function ajax_banka_hesap_ekle()
	{
		$sonuc = NULL;

		$banka_model 							= $this->input->post('banka_model');
		$hesap_durum							= $this->input->post('durum');
		$hesap_tur								= $this->input->post('tur');
		$hesap_iban_no							= $this->input->post('iban_no');
		$hesap_hesap_sahip						= $this->input->post('hesap_sahip');
		$hesap_sube								= $this->input->post('sube');
		$hesap_no								= $this->input->post('hesap_no');
		
		$degerler->banka_model					= $banka_model;
		$degerler->hesap_durum					= $hesap_durum;
		$degerler->hesap_tur					= $hesap_tur;
		$degerler->hesap_iban_no				= $hesap_iban_no;
		$degerler->hesap_hesap_sahip			= $hesap_hesap_sahip;
		$degerler->hesap_sube					= $hesap_sube;
		$degerler->hesap_no						= $hesap_no;
		
		if(!$this->odeme_secenekleri_model->banka_varmi_kontrol($banka_model))
		{
			$sonuc['basarisiz'] = 'Hata banka bulunamadı.';
		}

		$durum = $this->odeme_secenekleri_model->hesap_ekle($degerler);
		if($durum->sonuc)
		{
			$sonuc['basarili']	= $durum->bilgi->havale_banka_baslik . ' hesap bilgisi başarılı bir şekilde eklenmiştir.';
		} else {
			$sonuc['basarisiz']	= 'Hata banka hesap bilgisi eklenemedi.';
		}

		exit(json_encode($sonuc));
	}
	
	function kur($odeme_model)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/odeme_secenekleri/kur/' . $odeme_model);

		if(!$this->odeme_secenekleri_model->odeme_varmi_kontrol($odeme_model))
		{
			redirect('yonetim/sistem/odeme_secenekleri');
		}

		$this->odeme_secenekleri_model->odeme_kur($odeme_model);
		redirect('yonetim/sistem/odeme_secenekleri');
	}
	
	function kaldir($odeme_model)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/sistem/odeme_secenekleri/kaldir/' . $odeme_model);

		if(!$this->odeme_secenekleri_model->odeme_varmi_kontrol($odeme_model))
		{
			redirect('yonetim/sistem/odeme_secenekleri');
		}

		$this->odeme_secenekleri_model->odeme_kaldir($odeme_model);
		redirect('yonetim/sistem/odeme_secenekleri');
	}

}

/* End of file isimsiz.php */
/*  */

?>