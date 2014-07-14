<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 **/
    
    /*if ( ! function_exists('get_child')) // 
	{
		function get_child($katid)
		{
			$_ci =& get_instance();
			return $_ci->fonksiyonlar->get_child($katid);
		}
	}*/

	if ( ! function_exists('rewrite_url'))
	{
		function rewrite_url($_id, $_type)
		{
			$_ci =& get_instance();
			return $_ci->fonksiyonlar->rewrite_url($_id, $_type);
		}
	}

	if ( ! function_exists('get_fields_from_table') )
	{
		function get_fields_from_table($table, $prefix = NULL, $get_field = array(), $last = NULL)
		{
			$ci =& get_instance();
			$sql_fields = $ci->config->item('sql_table_fields');

			$return = '';
			$_explode = isset($sql_fields[$table]) ? explode(',', $sql_fields[$table]) : array();
			$_get_fields = (count($get_field) > 0) ? TRUE : FALSE;

			$_first = ', ';
			foreach($_explode as $explode) {
				$explode = trim($explode);

				if($_get_fields) {
					if(in_array($explode, $get_field)) {
						$return .= $_first . $prefix . $explode;
					}
				} else {
					$return .= $_first . $prefix . $explode;
				}
			}

			$return = ltrim($return, $_first);
			return $return . $last;
		}
	}

	if ( ! function_exists('get_language'))
	{
		function get_language($item = null, $language = null)
		{
			$_ci =& get_instance();
			return $_ci->fonksiyonlar->get_language($item, $language);
		}
	}

	if ( ! function_exists('get_languages'))
	{
		function get_languages()
		{
			$_ci =& get_instance();
			return $_ci->fonksiyonlar->get_languages();
		}
	}

if ( ! function_exists('indirim_orani'))
{
    function indirim_orani($i_fiyat, $normal_fiyat)
    {
        $fark = $normal_fiyat-$i_fiyat;
        if($fark > 0)

        return  ceil(100 * $fark / $normal_fiyat);

        else return false;
    }
}

if ( ! function_exists('get_count_category_product'))
{
    function get_count_category_product($category_id)
    {
        $ci =& get_instance();

        $ci->load->model('category_model');
        return  $ci->category_model->get_product_count($category_id);
    }
}


	if ( ! function_exists('get_language_v2'))
	{
		function get_language_v2($item = null, $language = null)
		{
			$_ci =& get_instance();
			return $_ci->fonksiyonlar->get_language_v2($item, $language);
		}
	}

	if ( ! function_exists('get_languages_v2'))
	{
		function get_languages_v2()
		{
			$_ci =& get_instance();
			return $_ci->fonksiyonlar->get_languages_v2();
		}
	}

	if ( ! function_exists('set_language'))
	{
		function set_language($value)
		{
			$_ci =& get_instance();
			return $_ci->fonksiyonlar->set_language($value);
		}
	}

	if ( ! function_exists('user_id') )
	{
		function user_id()
		{
			$CI =& get_instance();
			if($CI->dx_auth->is_logged_in())
			{
				return $CI->dx_auth->get_user_id();
			} else {
				return false;
			}
		}
	}

	if ( ! function_exists('show_page') )
	{
		function show_page($information_id, $anchor_tag = '', $fist_tag = '', $last_tag = '', $ssl = FALSE)
		{
			$ci =& get_instance();
			$language_id = $ci->fonksiyonlar->get_language('language_id');

			$information_type = $ci->config->item('information_types');

			$ci->db->select(
				get_fields_from_table('information', 'i.', array(), ', ') .
				get_fields_from_table('information_description', 'id.', array(), ', ')
			);
			$ci->db->from('information i');
			$ci->db->join('information_description id', 'i.information_id = id.information_id', 'left');
			$ci->db->where('id.language_id', $language_id);
			$ci->db->where('i.information_id', $information_id);
			$ci->db->where('i.status', '1');
			$ci->db->limit('1');
			$query = $ci->db->get();
			if($query->num_rows()) {
				$info = $query->row();
				$url = site_url(strtr($information_type[$info->type]['url'], array('{url}' => $info->seo)));
				$_anchor_tag = '';
				$_anchor_tag .= 'title="'. $info->title .'"';
				$_anchor_tag .= $anchor_tag;
				$return = anchor_tag($url, $info->title, $_anchor_tag, $fist_tag, $last_tag, $ssl);
				return $return;
			} else {
				return NULL;
			}
		}
	}

	if ( ! function_exists('show_image') )
	{
		function show_image($image_url, $w = 210, $h = 160)
		{
			$ci =& get_instance();
			if($image_url) {
				if(file_exists(DIR_IMAGE . $image_url)) {
					$image = $ci->image_model->resize($image_url, $w, $h);
				} else {
					$image = $ci->image_model->resize('no-image.jpg', $w, $h);
				}
			} else {
				$image = $ci->image_model->resize('no-image.jpg', $w, $h);
			}
			return $image;
		}
	}

 	if ( ! function_exists('urun_ana_kategori'))
	{
		function urun_ana_kategori()
		{
			$ci =& get_instance();

			$ci->load->model('category_model');
			$categories = $ci->category_model->get_categories_by_parent_id(0, $limit = '-1');

			$_categories = array();
			if($categories) {
				foreach($categories as $category) {
					$_categories[] = array(
										'urun_kat_id'	=> $category->category_id,
										'urun_kat_adi'	=> $category->name,
										'urun_kat_seo'	=> $category->seo
									);
				}
			}
			
			return $_categories;
		}
	}
	
 	if ( ! function_exists('eklenti_ayar'))
	{
		function eklenti_ayar($key, $config, $item = 'ayar_deger')
		{
			$ci =& get_instance();
			$eklenti = $ci->eklentiler_model->get_extension_option($key, $config, $item);
			if($eklenti) {
				return $eklenti;
			} else {
				return FALSE;
			}
		}
	}

	if ( ! function_exists('format_number') )
	{
		function format_number($n = '')
		{
			$CI =& get_instance();
			//$CI->load->library('cart');
			return $CI->cart->format_number($n);
		}
	}

 	if ( ! function_exists('ticket_mesaj_say'))
	{
		function ticket_mesaj_say($id)
		{
			$CI =& get_instance();
			return $CI->db->get_where('ticket',array('ticket_prm_id'=>$id));
		}
	}

 	if ( ! function_exists('user_info'))
	{
		function user_info($user_id = FALSE)
		{
			$ci =& get_instance();
			if ( ! $user_id) {
				$user_id = $ci->dx_auth->get_user_id();
			}
			$query = $ci->db
							->from('users u')
							->join('usr_ide_inf uii', 'u.id = uii.user_id', 'left')
							->join('usr_adr_inf uai', 'u.id = uai.user_id', 'left')
							->where('u.id', (int) $user_id)
							->get();
			if ($query->num_rows()) {
				return $query->row();
			}
			return FALSE;
		}
	}

 	if ( ! function_exists('user_ide_inf'))
	{
		function user_ide_inf($id)
		{
			$CI =& get_instance();
			return $CI->db->get_where('usr_ide_inf',array('user_id'=>$id));
		}
	}

	if ( ! function_exists('user_adr_inf'))
	{
		function user_adr_inf($id)
		{
			$CI =& get_instance();
			return $CI->db->get_where('usr_adr_inf',array('user_id'=>$id));
		}
	}

	if ( ! function_exists('get_user_adr_inf'))
	{
		function get_user_adr_inf($id)
		{
			$CI =& get_instance();
			$CI->db->order_by('adr_id', 'desc');
			$sorgu = $CI->db->get_where('usr_adr_inf', array('user_id' => $id), 1);
			if($sorgu->num_rows()) {
				return $sorgu->row();
			} else {
				return FALSE;
			}
		}
	}

	if ( ! function_exists('get_user_ide_inf'))
	{
		function get_user_ide_inf($id)
		{
			$CI =& get_instance();
			$CI->db->order_by('ide_id', 'desc');
			$sorgu = $CI->db->get_where('usr_ide_inf', array('user_id' => $id), 1);
			if($sorgu->num_rows()) {
				return $sorgu->row();
			} else {
				return FALSE;
			}
		}
	}

	if ( ! function_exists('get_user_inv_inf'))
	{
		function get_user_inv_inf($id)
		{
			$CI =& get_instance();
			$CI->db->order_by('inv_id', 'desc');
			$sorgu = $CI->db->get_where('usr_inv_inf', array('user_id' => $id), 1);
			if($sorgu->num_rows()) {
				return $sorgu->row();
			} else {
				return FALSE;
			}
		}
	}

	/* Start Cache Start */

	if ( ! function_exists('get_cache'))
	{
		function get_cache($oku)
		{
			$CI =& get_instance();
			return $CI->cache->get($oku);
		}
	}
	
 	if ( ! function_exists('resim_cek') )
	{
		function resim_cek($url, $ad, $yol)
		{
//			$link_info = pathinfo($url);
//			$uzanti = strtolower($link_info['extension']);
			$uzanti = end(explode('.',$url));
			$file 	= $ad . '.' . $uzanti;
			$yolcuk = $yol . $file;
			$curl = curl_init($url);
			$fopen = fopen($yolcuk,'w');
			curl_setopt($curl, CURLOPT_HEADER,0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($curl, CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_0);
			curl_setopt($curl, CURLOPT_FILE, $fopen);
			curl_exec($curl);
			curl_close($curl);
			fclose($fopen);
			return $file;
		}
	}
	
	if ( ! function_exists('set_cache'))
	{
		function set_cache($ad, $deger)
		{
			$CI =& get_instance();
			$CI->cache->set($ad, $deger);
		}
	}
	
	if ( ! function_exists('del_cache'))
	{
		function del_cache($ad)
		{
			$CI =& get_instance();
			$CI->cache->delete($ad);
		}
	}
	
	/* End Cache End */
	
	/* Start Menu Start */
	
	if ( ! function_exists('menu'))
	{
		function menu($uri,$uri2 = false,$name,$name2 = false,$class,$select)
		{
			$CI =& get_instance();
			
			//$deneme = array('admin2','admin3');
			//menu('2','3','admin',$deneme,'class="selected_lk"',2); // Örnek
			return $CI->menu->menu_class($uri,$uri2,$name,$name2,$class,$select);
		}
	}
	
	/* End Menu End */
	
	/* Start Yollar Start */
	
	if ( ! function_exists('tema'))
	{
		function tema()
		{
			$secili_tema = (config('site_ayar_tema')) ? config('site_ayar_tema'):'daynex_standart';
			return 'temalar/' . $secili_tema . '/';
		}
	}

	if ( ! function_exists('tema_asset'))
	{
		function tema_asset($base = FALSE)
		{
			$secili_tema = (config('site_ayar_tema')) ? config('site_ayar_tema'):'daynex_standart';
			$secili_tema_asset = (config('site_ayar_tema_asset')) ? config('site_ayar_tema_asset'):'daynex_standart';

			if($base) {
				$tema_asset = '/';
			} else {
				$tema_asset = '/tema_asset/' . $secili_tema_asset . '/';
			}

			return 'temalar/' . $secili_tema . $tema_asset;
		}
	}
	
	if ( ! function_exists('site_resim'))
	{
		function site_resim($base = FALSE)
		{
			$_ci =& get_instance();

			if (config('site_ayar_ssl') == '1' && isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
				$base_url = $_ci->config->item('ssl_url');
			} else {
				$base_url = $_ci->config->item('base_url');
			}

			$tema_asset = tema_asset($base);

			//return $base_url . APPPATH . 'views/' . tema_asset() .  'images/';
			return APPPATH . 'views/' . $tema_asset .  'images/';
		}
	}
	
	if ( ! function_exists('site_css'))
	{
		function site_css($base = FALSE)
		{
			$_ci =& get_instance();

			if (config('site_ayar_ssl') == '1' && isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
				$base_url = $_ci->config->item('ssl_url');
			} else {
				$base_url = $_ci->config->item('base_url');
			}

			$tema_asset = tema_asset($base);

			//return $base_url . APPPATH . 'views/' . tema_asset() . 'css/';
			return APPPATH . 'views/' . $tema_asset . 'css/';
		}
	}
	
	if ( ! function_exists('site_js'))
	{
		function site_js()
		{
			$_ci =& get_instance();

			if (config('site_ayar_ssl') == '1' && isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
				$base_url = $_ci->config->item('ssl_url');
			} else {
				$base_url = $_ci->config->item('base_url');
			}

			//return $base_url . APPPATH . 'views/' . tema() . 'js/';
			return APPPATH . 'views/' . tema() . 'js/';
		}
	}
	
	if ( ! function_exists('site_flash'))
	{
		function site_flash()
		{
			$_ci =& get_instance();

			if (config('site_ayar_ssl') == '1' && isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
				$base_url = $_ci->config->item('ssl_url');
			} else {
				$base_url = $_ci->config->item('base_url');
			}

			//return $base_url . APPPATH . 'views/' . tema() . 'flash/';
			return APPPATH . 'views/' . tema() . 'flash/';
		}
	}
	
	if ( ! function_exists('admin_resim'))
	{
		function admin_resim()
		{
			return base_url() . APPPATH . 'views/admin/images/';
		}
	}
	
	if ( ! function_exists('admin_css'))
	{
		function admin_css()
		{
			return base_url() . APPPATH . 'views/admin/css/';
		}
	}
	
	if ( ! function_exists('admin_js'))
	{
		function admin_js()
		{
			return base_url() . APPPATH . 'views/admin/js/';
		}
	}
	
	if ( ! function_exists('admin_flash'))
	{
		function admin_flash()
		{
			return base_url() . APPPATH . 'views/admin/flash/';
		}
	}
	
	
	
	if ( ! function_exists('yonetim_resim'))
	{
		function yonetim_resim()
		{
			return base_url() . APPPATH . 'views/yonetim/image/';
		}
	}
	
	if ( ! function_exists('yonetim_css'))
	{
		function yonetim_css()
		{
			return base_url() . APPPATH . 'views/yonetim/css/';
		}
	}
	
	if ( ! function_exists('yonetim_js'))
	{
		function yonetim_js()
		{
			return base_url() . APPPATH . 'views/yonetim/js/';
		}
	}
	
	if ( ! function_exists('yonetim_flash'))
	{
		function yonetim_flash()
		{
			return base_url() . APPPATH . 'views/yonetim/flash/';
		}
	}

	if ( ! function_exists('kur_oku') )
	{
		function kur_oku($kur, $durum = 'satis')
		{
			$CI =& get_instance();
			return $CI->kur_model->kur_oku($kur, $durum);
		}
	}

	if ( ! function_exists('sayi2yazi') )
	{
		function sayi2yazi($sayi)
		{
			$CI =& get_instance();
			return $CI->fonksiyonlar->sayi2yazi($sayi);
		}
	}
	/* End Yollar End */
	
	/* Ülke ve Şehir Fonksiyonları */
	if ( ! function_exists('get_ulkeler') )
	{
		function get_ulkeler()
		{
			$CI =& get_instance();
			$ulke_sorgu = $CI->db->get('ulkeler');
			$ulkeler = array();
			foreach($ulke_sorgu->result() as $ulke) {
				$ulkeler[$ulke->ulke_id] = $ulke->ulke_adi;
			}
			return $ulkeler;
		}
	}

	if ( ! function_exists('ulke_kodu2') )
	{
		function ulke_kodu2($ulke_id)
		{
			$CI =& get_instance();
			$ulke_sorgu = $CI->db->get_where('ulkeler', array('ulke_id' => $ulke_id), 1);
			if($ulke_sorgu->num_rows() > 0)
			{
				$ulke_bilgi = $ulke_sorgu->row();
				return tr_en_temizle($ulke_bilgi->iso_code_2, false);
			} else {
				return 'TR';
			}
		}
	}

	if ( ! function_exists('ulke_kodu3') )
	{
		function ulke_kodu3($ulke_id)
		{
			$CI =& get_instance();
			$ulke_sorgu = $CI->db->get_where('ulkeler', array('ulke_id' => $ulke_id), 1);
			if($ulke_sorgu->num_rows() > 0)
			{
				$ulke_bilgi = $ulke_sorgu->row();
				return $ulke_bilgi->iso_num_3;
			} else {
				return NULL;
			}
		}
	}

	if ( ! function_exists('ulke_adi') )
	{
		function ulke_adi($ulke_id)
		{
			$CI =& get_instance();
			$ulke_sorgu = $CI->db->get_where('ulkeler', array('ulke_id' => $ulke_id), 1);
			if($ulke_sorgu->num_rows() > 0)
			{
				$ulke_bilgi = $ulke_sorgu->row();
				return $ulke_bilgi->ulke_adi;
			} else {
				return 'Turkey';
			}
		}
	}

	if ( ! function_exists('get_sehirler') )
	{
		function get_sehirler($ulke_id)
		{
			$CI =& get_instance();
			$sehir_sorgu = $CI->db->get_where('ulke_bolgeleri', array('ulke_id' => $ulke_id));
			$sehirler = array();
			foreach($sehir_sorgu->result() as $sehir) {
				$sehirler[$sehir->bolge_id] = $sehir->bolge_adi;
			}
			return $sehirler;
		}
	}

	if ( ! function_exists('sehir_adi') )
	{
		function sehir_adi($sehir_id)
		{
			$CI =& get_instance();
			$sehir_sorgu = $CI->db->get_where('ulke_bolgeleri', array('bolge_id' => $sehir_id), 1);
			if($sehir_sorgu->num_rows() > 0)
			{
				$sehir_bilgi = $sehir_sorgu->row();
				return tr_en_temizle($sehir_bilgi->bolge_adi, false);
			} else {
				return 'Istanbul';
			}
		}
	}

	if ( ! function_exists('sehir_adi2') )
	{
		function sehir_adi2($sehir_id)
		{
			$CI =& get_instance();
			$sehir_sorgu = $CI->db->get_where('ulke_bolgeleri', array('bolge_id' => $sehir_id), 1);
			if($sehir_sorgu->num_rows() > 0)
			{
				$sehir_bilgi = $sehir_sorgu->row();
				return $sehir_bilgi->bolge_adi;
			} else {
				return '';
			}
		}
	}
	
	if ( ! function_exists('domain_sehirismi_ulkeid') )
	{
		function domain_sehirismi_ulkeid($sehir_adi)
		{
			$CI =& get_instance();
			$sorgu = $CI->db->get_where('ulke_bolgeleri', array('bolge_adi' => $sehir_adi), 1);
			if($sorgu->num_rows() > 0)
			{
				$bilgi = $sorgu->row();
				return $bilgi->ulke_id;
			} else {
				return '215';
			}
		}
	}
	/* Ülke ve Şehir Fonksiyonları */

	/* Ödeme Seçenekleri FOnksiyonları */
	if ( ! function_exists('odeme_secenek_bilgi_model') )
	{
		function odeme_secenek_bilgi_model($odeme_model)
		{
			$CI =& get_instance();
			$sorgu = $CI->db->get_where('odeme_secenekleri', array('odeme_model' => $odeme_model), 1);
			if($sorgu->num_rows() > 0)
			{
				return $bilgi = $sorgu->row();
			} else {
				return NULL;
			}
		}
	}
	
	if ( ! function_exists('odeme_secenek_bilgi_id') )
	{
		function odeme_secenek_bilgi_id($odeme_id)
		{
			$CI =& get_instance();
			$sorgu = $CI->db->get_where('odeme_secenekleri', array('odeme_id' => $odeme_id), 1);
			if($sorgu->num_rows() > 0)
			{
				return $bilgi = $sorgu->row();
			} else {
				return NULL;
			}
		}
	}
	
	
	//günlük mail kontrolü (en fazla günlük 50 mail)
	if ( ! function_exists('gunluk_mail') )
	{
		function gunluk_mail()
		{
			$CI =& get_instance();
			$query = $CI->db->get_where('ayarlar',array('ayar_adi'=>'bayi_mail_credit'));
			
			return($query->row('ayar_deger'));
		}
	}
	
	//yönetici mail  adresini getiriyor
	if ( ! function_exists('email_admin') )
	{
		function email_admin()
		{
			$CI =& get_instance();
			$query = $CI->db->get_where('ayarlar',array('ayar_adi'=>'site_ayar_email_admin'));
			
			return($query->row('ayar_deger'));
		}
	}
	
	// marka bilgilerini oku
	if ( ! function_exists('marka_oku') )
	{
		function marka_oku($marka_id)
		{
			$CI =& get_instance();
			$sorgu = $CI->db->get_where('stok_marka', array('marka_id' => $marka_id), 1);
			
			if($sorgu->num_rows() > 0)
			{
				$marka_bilgi = $sorgu->row();
				return $marka_bilgi->marka_adi;
			} else {
				return 'Belirsiz';
			}
		}
	}
	
	/* Ödeme Seçenekleri FOnksiyonları */
	
	if(function_exists("strptime") == false)
	{
		function strptime($sDate, $sFormat)
		{
			$aResult = array(
				'tm_sec'   => 0,
				'tm_min'   => 0,
				'tm_hour'  => 0,
				'tm_mday'  => 1,
				'tm_mon'   => 0,
				'tm_year'  => 0,
				'tm_wday'  => 0,
				'tm_yday'  => 0,
				'unparsed' => $sDate,
			);
		    
		    while($sFormat != "")
		    {
		        // ===== Search a %x element, Check the static string before the %x =====
		        $nIdxFound = strpos($sFormat, '%');
		        if($nIdxFound === false)
		        {
		            
		            // There is no more format. Check the last static string.
		            $aResult['unparsed'] = ($sFormat == $sDate) ? "" : $sDate;
		            break;
		        }
		    
				// ===== Create the other value of the result array =====
				$nParsedDateTimestamp = mktime($aResult['tm_hour'], $aResult['tm_min'], $aResult['tm_sec'],
				    $aResult['tm_mon'] + 1, $aResult['tm_mday'], $aResult['tm_year'] + 1900);
				
				// Before PHP 5.1 return -1 when error
				if(($nParsedDateTimestamp === false)
				||($nParsedDateTimestamp === -1)) return false;
				
				$aResult['tm_wday'] = (int) strftime("%w", $nParsedDateTimestamp); // Days since Sunday (0-6)
				$aResult['tm_yday'] = (strftime("%j", $nParsedDateTimestamp) - 1); // Days since January 1 (0-365)
				
				return $aResult;
			} // END of function
		}
	} // END if(function_exists("strptime") == false)


	if ( ! function_exists('yetki_kontrol') )
	{
		function yetki_kontrol($fonksiyon, $class, $klasor)
		{
			
			$CI =& get_instance();
			$CI->load->library('DX_Auth');
			$link 		= 'yonetim/'.$klasor.'/'.$class.'/'.$fonksiyon;
			$CI->db->join('yetki_user','yetki_user.yetki_menu_menu_id=yetki.yetki_id','left');
			$CI->db->where('yetki_user.yetki_menu_user_id', $CI->dx_auth->get_role_id());
			$CI->db->where('yetki.yetki_controller',$link);
			$CI->db->from('yetki');
			$query  = $CI->db->get();
			$row	= $query->row(); 
			
			if($query->num_rows() > 0)
			{
				return $row->yetki_menu_yetki;
			} else {
				return 1;
			}
		}
	}
	
	// Users İşlemleri
	if( ! function_exists('get_username_parse') )
	{
		function get_username_parse($str, $explode = ' ', $return_array = FALSE, $implode = ' ')
		{
			$explode = explode($explode, $str);
			if(count($explode) > 1) {
				$last = end($explode);
				$last_key = array_search($last, $explode);
				unset($explode[$last_key]);
				if($return_array) {
					$return = array(
						'name'			=> implode($implode, $explode),
						'surname'		=> $last
					);
				} else {
					$return = implode($implode, $explode) . $last;
				}
			} else {
				if($return_array) {
					$return = array(
						'name'			=> implode($implode, $explode),
						'surname'		=> NULL
					);
				} else {
					$return = implode($implode, $explode);
				}
			}

			return $return;
		}
	}

	if( ! function_exists('get_usr_ide_inf') )
	{
		function get_usr_ide_inf($user_id)
		{
			$CI =& get_instance();
			$CI->load->model('uye_model');
			return $CI->uye_model->get_usr_ide_inf($user_id);
		}
	}

	if ( ! function_exists('debug') )
	{
		function debug($gelen_deger, $duzgun_goster = true)
		{
			return '<pre>' . htmlspecialchars(stripslashes(print_r($gelen_deger, $duzgun_goster))) . '</pre>';
		}
	}

	if ( ! function_exists('kdv_hesapla') )
	{
		function kdv_hesapla($fiyat, $kdv_yuzdesi = '18', $direk_noktali = false, $toplam_ver = false)
		{
			if($direk_noktali)
			{
				$yuzde_birlestir = $kdv_yuzdesi;
			} else {
				if($toplam_ver)
				{
					$yuzde_birlestir = floatval('1.' . $kdv_yuzdesi);
				} else {
					$yuzde_birlestir = floatval('0.' . $kdv_yuzdesi);
				}
			}

			return ($fiyat * $yuzde_birlestir);
		}
	}

	if ( ! function_exists('fiyat_hesapla') )
	{
		function fiyat_hesapla($stok_kodu, $adet, $siparis_kurd, $siparis_kure, $normal_fiyat_goster = false)
		{
			$ci =& get_instance();
			$ci->load->model('site_model');

			$data = array();

			$data['stok_kod']  = $stok_kodu;
			$data['stok_adet'] = $adet;
			$data['stok_indirim'] = false;
			$data['stok_kampanya'] = false;

			$language_id = get_language('language_id');

			$ci->db->distinct();
			$ci->db->select(
				get_fields_from_table('product', 'p.', array(), ', ') .
				get_fields_from_table('product_description', 'pd.', array(), '')
			);
			$ci->db->from('product p');
			$ci->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
			$ci->db->where('pd.language_id', $language_id);
			$ci->db->where('p.model', $data['stok_kod']);
			$ci->db->where('p.date_available >= UNIX_TIMESTAMP()');
			$ci->db->where('p.status', '1');
			$ci->db->limit(1);
			$sorgu = $ci->db->get();
			if($sorgu->num_rows()) {

				$stok_bilgi				= $sorgu->row();
                                if(!config('site_ayar_kdv_goster')){ $fiyat=$stok_bilgi->price*(($stok_bilgi->tax/100)+1); } else { $fiyat=$stok_bilgi->price; }
				$data['kdv_orani']		= ($stok_bilgi->tax/100);
				$data['fiyat']			= $fiyat;
				$data['stok_adi']		= $stok_bilgi->name;
				$data['stok_id']		= $stok_bilgi->product_id;

				if($stok_bilgi->price_type == '1') {
					$data['fiyat_tur'] = 'TL';
				} elseif($stok_bilgi->price_type == '2') {
					$data['fiyat_tur'] = '$';
				} elseif($stok_bilgi->price_type == '3') {
					$data['fiyat_tur'] = '€';
				} else {
					$data['fiyat_tur'] = '';
				}

				$data['stok_tur'] = $stok_bilgi->stock_type;

				$indirim_orani = false;
				$indirim_kontrol = $ci->discount_model->get_discount($stok_bilgi->product_id);
				if($indirim_kontrol) {
					if ($ci->dx_auth->is_logged_in()) {
						if ($ci->dx_auth->is_role('admin-gruplari')) {
							$data['fiyat'] = (float) $indirim_kontrol['price'];
							$indirim_orani = ($indirim_kontrol['price'] / $data['fiyat']);
							$data['stok_indirim'] = true;
						} else {
							if($indirim_kontrol['user_group_id'] == $ci->dx_auth->get_role_id()) {
								$data['fiyat'] = (float) $indirim_kontrol['price'];
								$indirim_orani = ($indirim_kontrol['price'] / $data['fiyat']);
								$data['stok_indirim'] = true;
							}
						}
					} else {
						if ($indirim_kontrol['user_group_id'] == config('site_ayar_varsayilan_mus_grub')) {
							$data['fiyat'] = (float) $indirim_kontrol['price'];
							$indirim_orani = ($indirim_kontrol['price'] / $data['fiyat']);
							$data['stok_indirim'] = true;
						}
					}
				}

				$data['indirim_orani'] = $indirim_orani;

				$kampanya_kontrol = $ci->campaign_model->get_campaign($stok_bilgi->product_id);
				if($kampanya_kontrol) {
					if ($ci->dx_auth->is_logged_in()) {
						if ($ci->dx_auth->is_role('admin-gruplari')) {
							if($indirim_orani) {
								if($adet >= $kampanya_kontrol['quantity'])
								{
									$data['fiyat'] = (float) ($indirim_orani * $kampanya_kontrol['price']);
									$data['stok_kampanya'] = true;
								}
							} else {
								if($adet >= $kampanya_kontrol['quantity'])
								{
									$data['fiyat'] = (float) $kampanya_kontrol['price'];
								}
							}
						} else {
							if ($kampanya_kontrol['user_group_id'] == $ci->dx_auth->get_role_id()) {
								if($indirim_orani) {
									if($adet >= $kampanya_kontrol['quantity'])
									{
										$data['fiyat'] = (float) ($indirim_orani * $kampanya_kontrol['price']);
										$data['stok_kampanya'] = true;
									}
								} else {
									if($adet >= $kampanya_kontrol['quantity'])
									{
										$data['fiyat'] = (float) $kampanya_kontrol['price'];
									}
								}
							}
						}
					} else {
						if ($kampanya_kontrol['user_group_id'] == config('site_ayar_varsayilan_mus_grub')) {
							if($indirim_orani) {
								if($adet >= $kampanya_kontrol['quantity'])
								{
									$data['fiyat'] = (float) ($indirim_orani * $kampanya_kontrol['price']);
									$data['stok_kampanya'] = true;
								}
							} else {
								if($adet >= $kampanya_kontrol['quantity'])
								{
									$data['fiyat'] = (float) $kampanya_kontrol['price'];
								}
							}
						}
					}
				}

				if(!$normal_fiyat_goster) {
					if ($stok_bilgi->price_type == '1') {
						$data['fiyat'] = (float) $data['fiyat'];
					} else if ($stok_bilgi->price_type == '2') {
						$data['fiyat'] = (float) ($data['fiyat'] * $siparis_kurd);
					} else if ($stok_bilgi->price_type == '3') {
						$data['fiyat'] = (float) ($data['fiyat'] * $siparis_kure);
					}
				} else {
					$data['fiyat'] = (float) $data['fiyat'];
				}

				if($ci->dx_auth->is_logged_in()) {
					$role_id = $ci->dx_auth->get_role_id();
					$grup_indirim = $ci->db->get_where('roles', array('id' => $role_id), 1);
					if($grup_indirim->num_rows() > 0) {
						$indirim_bilgi = $grup_indirim->row();
						if($indirim_bilgi->fiyat_tip == '1') {
							$data['fiyat'] = (float) ($data['fiyat'] * ((100+$indirim_bilgi->fiyat_orani)/100));
						} else {
							$data['fiyat'] = (float) ($data['fiyat'] * ((100-$indirim_bilgi->fiyat_orani)/100));
						}
					}
				}

				$data['stok_gercek_fiyat'] = (float) $stok_bilgi->price;
				$data['fiyat_t'] = (float) ($data['fiyat'] * $data['stok_adet']);
				$data['stok_gercek_fiyat_t'] = (float) ($data['stok_gercek_fiyat'] * $data['stok_adet']);
				$data['indirim'] = (float) ($data['stok_gercek_fiyat_t'] - $data['fiyat_t']);
				$data['kdv_fiyat'] = (float) (($data['fiyat_t'] * (($stok_bilgi->tax/100)+1)) - $data['fiyat_t']);
				$data['kdv_fiyat_b'] = (float) (($data['fiyat'] * (($stok_bilgi->tax/100)+1)) - $data['fiyat']);
			} else {
				$data = FALSE;
			}

			return $data;
		}
	}
	
	if ( ! function_exists('money_format') )
	{
		function money_format($format, $number) 
		{ 
		    $regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'. 
		              '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/'; 
		    if (setlocale(LC_MONETARY, 0) == 'C') { 
		        setlocale(LC_MONETARY, '');
		    }
		    $locale = localeconv(); 
		    preg_match_all($regex, $format, $matches, PREG_SET_ORDER); 
		    foreach ($matches as $fmatch) { 
		        $value = floatval($number); 
		        $flags = array( 
		            'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ? 
		                           $match[1] : ' ', 
		            'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0, 
		            'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ? 
		                           $match[0] : '+', 
		            'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0, 
		            'isleft'    => preg_match('/\-/', $fmatch[1]) > 0 
		        ); 
		        $width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0; 
		        $left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0; 
		        $right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits']; 
		        $conversion = $fmatch[5]; 
		
		        $positive = true; 
		        if ($value < 0) { 
		            $positive = false; 
		            $value  *= -1; 
		        } 
		        $letter = $positive ? 'p' : 'n'; 
		
		        $prefix = $suffix = $cprefix = $csuffix = $signal = ''; 
		
		        $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign']; 
		        switch (true) { 
		            case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+': 
		                $prefix = $signal; 
		                break; 
		            case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+': 
		                $suffix = $signal; 
		                break; 
		            case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+': 
		                $cprefix = $signal; 
		                break; 
		            case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+': 
		                $csuffix = $signal; 
		                break; 
		            case $flags['usesignal'] == '(': 
		            case $locale["{$letter}_sign_posn"] == 0: 
		                $prefix = '('; 
		                $suffix = ')'; 
		                break; 
		        } 
		        if (!$flags['nosimbol']) { 
		            $currency = $cprefix . 
		                        ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) . 
		                        $csuffix; 
		        } else { 
		            $currency = ''; 
		        } 
		        $space  = $locale["{$letter}_sep_by_space"] ? ' ' : ''; 
		
		        $value = number_format($value, $right, $locale['mon_decimal_point'], 
		                 $flags['nogroup'] ? '' : $locale['mon_thousands_sep']); 
		        $value = @explode($locale['mon_decimal_point'], $value); 
		
		        $n = strlen($prefix) + strlen($currency) + strlen($value[0]); 
		        if ($left > 0 && $left > $n) { 
		            $value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0]; 
		        } 
		        $value = implode($locale['mon_decimal_point'], $value); 
		        if ($locale["{$letter}_cs_precedes"]) { 
		            $value = $prefix . $currency . $space . $value . $suffix; 
		        } else { 
		            $value = $prefix . $value . $space . $currency . $suffix; 
		        } 
		        if ($width > 0) { 
		            $value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ? 
		                     STR_PAD_RIGHT : STR_PAD_LEFT); 
		        } 
		
		        $format = str_replace($fmatch[0], $value, $format); 
		    } 
		    return $format; 
		}
	}

/* End of file isimsiz_helper.php */
/* */

?>