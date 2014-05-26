<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 **/

	if ( ! function_exists('giris_kontrol'))
	{
		function giris_kontrol($sayfa = '')
		{
			$ci =& get_instance();
			if (!$ci->dx_auth->is_logged_in())
			{
				if($sayfa != '')
				{
					$ci->session->set_flashdata('login_redirect', $sayfa);
				}
				redirect('yonetim/giris');
			} else {
				if (!$ci->dx_auth->is_role('admin-gruplari'))
				{  
					redirect('');
				}
			}
		}
	}

	if ( ! function_exists('sayfa_kontrol') )
	{
		function sayfa_kontrol($sayfa_url, $giris_url = null, $return = false)
		{
			$CI =& get_instance();

			if(!is_null($giris_url))
			{
				giris_kontrol($giris_url);
			}

			// Grup Yetki Başlangıcı
				// Yetki Kontrol Başlangıç
				$CI->db->like('yetki', $sayfa_url);
				$sayfa_yetki_kontrol = $CI->db->get_where('roles', array('id' => $CI->dx_auth->get_role_id()), 1);
				if($sayfa_yetki_kontrol->num_rows() == '1')
				{
					$sayfa_yetki_bilgi = $sayfa_yetki_kontrol->row();
					$yetkiler = @unserialize($sayfa_yetki_bilgi->yetki);
					if(is_array($yetkiler))
					{
						if(array_key_exists($sayfa_url, $yetkiler))
						{
							if(array_key_exists('izin', $yetkiler[$sayfa_url]))
							{
								if($yetkiler[$sayfa_url]['izin'] == '1')
								{
									$durum = true;
								} else {
									$durum = false;
								}
							} else {
								$durum = false;
							}
						} else {
							$durum = false;
						}
					} else {
						$durum = false;
					}
				} else {
					$durum = false;
				}
				// Yetki Kontrol Bitiş
			// Grup Yetki Bitişi

			if(!$return)
			{
				if(!$durum)
				{
					redirect('yonetim/giris/yetki_yok');
				}
			} else {
				return $durum;
			}
		}
	}

	if ( ! function_exists('uye_bilgi'))
	{
		function uye_bilgi($uye_id = '')
		{
			$ci =& get_instance();
			$ci->db->join('usr_ide_inf', 'users.id = usr_ide_inf.user_id');
			$sorgu = $ci->db->get_where('users', array('id' => $uye_id));
			if($sorgu->num_rows() > 0)
			{
				$uye_bilgi = $sorgu->row();
				return $uye_bilgi;
			} else {
				return false;
			}
		}
	}

	if ( ! function_exists('siparis_durum_goster'))
	{
		function siparis_durum_goster($siparis_durum_tanim_id)
		{
			$ci =& get_instance();
			$sorgu = $ci->db->get_where('siparis_durum', array('siparis_durum_tanim_id' => $siparis_durum_tanim_id), 1);
			if($sorgu->num_rows() > 0)
			{
				$siparis_durum_bilgi = $sorgu->row();
				return $siparis_durum_bilgi->siparis_durum_baslik;
			} else {
				return 'Sipariş Durumu Tanımsız';
			}
		}
	}

	if ( ! function_exists('musteri_yorumlari'))
	{
		function musteri_yorumlari($tip = 'urunler', $limit = NULL)
		{
			$ci =& get_instance();
			if(!is_null($limit))
			{
				$ci->db->limit($limit);
			}
			$sorgu = $ci->db->get_where('yorum', array('yorum_tip' => $tip));
			return $sorgu;
		}
	}

	if ( ! function_exists('kargo_adi_yazdir'))
	{
		function kargo_adi_yazdir($kargo_id)
		{
			$ci =& get_instance();
			$sorgu = $ci->db->get_where('kargo', array('kargo_id' => $kargo_id), 1);
			if($sorgu->num_rows() > 0)
			{
				$kargo_bilgi = $sorgu->row();
				return $kargo_bilgi->kargo_adi;
			} else {
				return 'Kargo Bulunamadı yada Kargo Silinmiş';
			}
		}
	}

	if ( ! function_exists('bilgi_sayfasi_kategori_adi_yazdir'))
	{
		function bilgi_sayfasi_kategori_adi_yazdir($kat_id)
		{
			$ci =& get_instance();
			$sorgu = $ci->db->get_where('bilgi_sayfalari_kategori', array('kat_id' => $kat_id), 1);
			if($sorgu->num_rows() > 0)
			{
				$kategori_bilgi = $sorgu->row();
				return $kategori_bilgi->kat_kat_adi;
			} else {
				return 'Kategori Adı Bulunamadı yada Silinmiş';
			}
		}
	}

	if ( ! function_exists('yonetim_url'))
	{
		function yonetim_url($gelen_url = '', $base_goster = true)
		{
			$yonetim_url = 'yonetim';
			$_url = ($gelen_url == '') ? $yonetim_url . '/' : $yonetim_url . '/' . $gelen_url;
			return ($base_goster) ? site_url($_url) : $_url;
		}
	}

	/**
	 * Tests if an input is valid PHP serialized string.
	 *
	 * Checks if a string is serialized using quick string manipulation
	 * to throw out obviously incorrect strings. Unserialize is then run
	 * on the string to perform the final verification.
	 *
	 * Valid serialized forms are the following:
	 * <ul>
	 * <li>boolean: <code>b:1;</code></li>
	 * <li>integer: <code>i:1;</code></li>
	 * <li>double: <code>d:0.2;</code></li>
	 * <li>string: <code>s:4:"test";</code></li>
	 * <li>array: <code>a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}</code></li>
	 * <li>object: <code>O:8:"stdClass":0:{}</code></li>
	 * <li>null: <code>N;</code></li>
	 * </ul>
	 *
	 * @author		Chris Smith <code+php@chris.cs278.org>
	 * @copyright	Copyright (c) 2009 Chris Smith (http://www.cs278.org/)
	 * @license		http://sam.zoy.org/wtfpl/ WTFPL
	 * @param		string	$value	Value to test for serialized form
	 * @param		mixed	$result	Result of unserialize() of the $value
	 * @return		boolean			True if $value is serialized data, otherwise false
	 */

	if ( ! function_exists('is_serialized'))
	{
		function is_serialized($value, &$result = null)
		{
			// Bit of a give away this one
			if (!is_string($value))
			{
				return false;
			}

			// Serialized false, return true. unserialize() returns false on an
			// invalid string or it could return false if the string is serialized
			// false, eliminate that possibility.
			if ($value === 'b:0;')
			{
				$result = false;
				return true;
			}

			$length	= strlen($value);
			$end	= '';

			switch ($value[0])
			{
				case 's':
					if ($value[$length - 2] !== '"')
					{
						return false;
					}
				case 'b':
				case 'i':
				case 'd':
					// This looks odd but it is quicker than isset()ing
					$end .= ';';
				case 'a':
				case 'O':
					$end .= '}';

					if ($value[1] !== ':')
					{
						return false;
					}

					switch ($value[2])
					{
						case 0:
						case 1:
						case 2:
						case 3:
						case 4:
						case 5:
						case 6:
						case 7:
						case 8:
						case 9:
						break;

						default:
							return false;
					}
				case 'N':
					$end .= ';';

					if ($value[$length - 1] !== $end[0])
					{
						return false;
					}
				break;

				default:
					return false;
			}

			if (($result = @unserialize($value)) === false)
			{
				$result = null;
				return false;
			}
			return true;
		}
	}

/* End of file isimsiz_helper.php */
/* */

?>