<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class fonksiyonlar
{
	var $ci;
	var $linkler;
	var $tema;
	protected $languages;
	protected $languages_v2;
	protected $routes;

	/**
	 * Fonksiyonlar construct
	 *
	 * @return void
	 **/

	function __construct()
	{
		log_message('debug', 'Fonksiyonlar Kütüphanesi Yüklendi');
		
		// Yapıcılar
		$this->ci =& get_instance();
		
		// Fonksiyon Çağır
		//$this->_ayarlari_olustur();

		// Set Languages
		$this->_set_languages();
		$this->_set_languages_v2();

		/*// Set Routes
		$this->_set_routes();*/

		$this->tema = (config('ayar_secili_tema')) ? 'tema/' . config('ayar_secili_tema'):'tema/standart' . '/';

		$this->bakim_modu_kontrol();
	}
	
	

	/* Rewrite Functions */
	protected function _set_routes()
	{
		$this->ci->db->select('r.routes_id, r.routes_seo, r.routes_language_id, r.routes_type, r.routes_type_data, r.routes_status, l.code, l.language_id, l.name, l.code');
		$this->ci->db->order_by('routes_id', 'asc');
		$this->ci->db->from('routes r');
		$this->ci->db->join('language l', 'r.routes_language_id = l.language_id', 'left');
		$this->ci->db->where('routes_status', '1');
		$query = $this->ci->db->get();
		foreach ($query->result() as $result)
		{
			if($result->routes_status == '1')
			{
				$this->routes[$result->code . '_' . $result->routes_type . '_' . $result->routes_type_data] = $result->routes_seo;
			}
		}
	}

	function get_route($item)
	{
		if(isset($this->routes[$item]))
		{
			return $this->routes[$item];
		} else {
			return FALSE;
		}
	}

	function get_routes()
	{
		return $this->routes;
	}

	function rewrite_url($_id, $_type)
	{
		$language_code = $this->get_language('code');
		$routes_types = $this->ci->config->item('routes_type_key_to_value');
		if($this->ci->config->item('site_ayar_seo') == '1')
		{
			$route = $this->get_route($language_code . '_' . $_type . '_' . $_id);
			if($_type == 'static_page')
			{
				if($route)
				{
					$_return = $route;
				} else { 
					$_return = $_id;
				}
			} else {
				if($route)
				{
					$_return = $route;
				} else {
					$_return = strtr($routes_types[$_type], array('{_id_}' => $_id));
				}
			}
		} else {
			if($_type == 'static_page')
			{
				$_return = $_id;
			} else {
				$_return = strtr($routes_types[$_type], array('{_id_}' => $_id));
			}
		}

		return $_return;
	}
	/* Rewrite Functions */

	function bakim_modu_kontrol()
	{
		$uri_1 = $this->ci->uri->segment(1);
		$uri_2 = $this->ci->uri->segment(3);
		if($uri_2 != 'bakim_modu')
		{
			if($uri_1 != 'yonetim' && config('site_ayar_bakim') == '1')
			{
				redirect('site/bakim_modu');
			}
		}
	}

	/* Ayarlar Fonksiyonları */
	/*function _ayarlari_olustur()
	{
		$this->ci->db->select('ayar_adi, ayar_deger');
		$sogru = $this->ci->db->get('ayarlar');
		foreach ($sogru->result() as $ayarlar) {
			if($ayarlar->ayar_adi == 'site_ayar_dil') {
				$language = $this->ci->input->cookie('language');
				if($language != FALSE)
				{
					$this->ci->config->set_item('language', $language);
				} else {
					$this->ci->config->set_item('language', $ayarlar->ayar_deger);
				}
			} else {
				$this->ci->config->set_item($ayarlar->ayar_adi, $ayarlar->ayar_deger);
			}
		}
	}*/
	/* Ayarlar Fonksiyonları */

	/* Language Functions */
	protected function _set_languages()
	{
		$this->ci->db->select('language_id, name, code, locale, image, directory, filename, sort_order, status');
		$query = $this->ci->db->get('language');

		foreach($query->result() as $languages)
		{
			if($languages->status) {
				$language_code				= $languages->code;
				$language['language_id']	= $languages->language_id;
				$language['name']			= $languages->name;
				$language['code']			= $languages->code;
				$language['locale']			= $languages->locale;
				$language['image']			= $languages->image;
				$language['directory']		= $languages->directory;
				$language['sort_order']		= $languages->sort_order;
				$language['status']			= $languages->status;

				$this->languages[$language_code] = $language;
			}
		}
	}

	function get_languages()
	{
		return $this->languages;
	}

	function get_language($item = null, $language = null)
	{
		if(is_null($language))
		{
			$code = $this->ci->lang->lang();
		} else {
			$code = $language;
		}

		if(isset($this->languages[$code]))
		{
			if(is_null($item))
			{
				return $this->languages[$code];
			} else {
				return $this->languages[$code][$item];
			}
		} else {
			return FALSE;
		}
	}
	/* Language Functions */

	/* Language V2 Functions */
	protected function _set_languages_v2()
	{
		$this->ci->db->select('language_id, name, code, locale, image, directory, filename, sort_order, status');
		$query = $this->ci->db->get('language');

		foreach($query->result() as $languages)
		{
			if($languages->status) {
				$language_id				= $languages->language_id;
				$language['language_id']	= $languages->language_id;
				$language['name']			= $languages->name;
				$language['code']			= $languages->code;
				$language['locale']			= $languages->locale;
				$language['image']			= $languages->image;
				$language['directory']		= $languages->directory;
				$language['sort_order']		= $languages->sort_order;
				$language['status']			= $languages->status;

				$this->languages_v2[$language_id] = $language;
			}
		}
	}

	function get_languages_v2()
	{
		return $this->languages_v2;
	}

	function get_language_v2($item = null, $language_id = null)
	{
		if(is_null($language_id))
		{
			$id = $this->get_language('language_id');
		} else {
			$id = $language_id;
		}

		if(isset($this->languages_v2[$id]))
		{
			if(is_null($item))
			{
				return $this->languages_v2[$id];
			} else {
				return $this->languages_v2[$id][$item];
			}
		} else {
			return FALSE;
		}
	}
	/* Language V2 Functions */

	/* Url Functions */
	function face_site_url($uri = '', $ssl = FALSE)
	{
		if (is_array($uri))
		{
			$uri = implode('/', $uri);
		}

		//$uri = $this->ci->lang->localized($uri);
		return $this->_face_site_url($uri, $ssl);
	}

	function _face_site_url($uri = '', $ssl = FALSE)
	{
		$face_url		= config('site_ayar_facebook_url');
		$face_ssl_url	= 'https://' . substr(config('site_ayar_facebook_url'), 7);
		if($ssl AND config('site_ayar_ssl')) {
			$base_url = $face_ssl_url;
		} else {
			$base_url = $face_url;
		}

		if ($uri == '')
		{
			return $base_url . $this->ci->config->item('index_page');
		}

		if ($this->ci->config->item('enable_query_strings') == FALSE)
		{
			if (is_array($uri))
			{
				$uri = implode('/', $uri);
			}

			$index = $this->ci->config->item('index_page') == '' ? '' : $this->ci->config->slash_item('index_page');
			$suffix = ($this->ci->config->item('url_suffix') == FALSE) ? '' : $this->ci->config->item('url_suffix');
			return $base_url . $index.trim($uri, '/') . $suffix;
		}
		else
		{
			if (is_array($uri))
			{
				$i = 0;
				$str = '';
				foreach ($uri as $key => $val)
				{
					$prefix = ($i == 0) ? '' : '&';
					$str .= $prefix . $key.'='.$val;
					$i++;
				}

				$uri = $str;
			}

			return $base_url . $this->ci->config->item('index_page') . '?' . $uri;
		}
	}

	function site_url($uri = '', $ssl = FALSE)
	{
		if (is_array($uri))
		{
			$uri = implode('/', $uri);
		}

		$uri = $this->ci->lang->localized($uri);
		return $this->_site_url($uri, $ssl);
	}

	function _site_url($uri = '', $ssl = FALSE)
	{
		if($ssl AND config('site_ayar_ssl')) {
			$base_url = $this->ci->config->slash_item('ssl_url');
		} else {
			$base_url = $this->ci->config->slash_item('base_url');
		}

		if ($uri == '')
		{
			return $base_url . $this->ci->config->item('index_page');
		}

		if ($this->ci->config->item('enable_query_strings') == FALSE)
		{
			if (is_array($uri))
			{
				$uri = implode('/', $uri);
			}

			$index = $this->ci->config->item('index_page') == '' ? '' : $this->ci->config->slash_item('index_page');
			$suffix = ($this->ci->config->item('url_suffix') == FALSE) ? '' : $this->ci->config->item('url_suffix');
			return $base_url . $index.trim($uri, '/') . $suffix;
		}
		else
		{
			if (is_array($uri))
			{
				$i = 0;
				$str = '';
				foreach ($uri as $key => $val)
				{
					$prefix = ($i == 0) ? '' : '&';
					$str .= $prefix . $key.'='.$val;
					$i++;
				}

				$uri = $str;
			}

			return $base_url . $this->ci->config->item('index_page') . '?' . $uri;
		}
	}

	function ssl_url($uri = '')
	{
		return $this->ci->config->site_url($uri, TRUE);
	}
	/* Url Functions */

	function sayi2yazi($sayi) {
		$birler = array("","Bir&nbsp;","İki&nbsp;","Üç&nbsp;","Dört&nbsp;","Beş&nbsp;","Altı&nbsp;","Yedi&nbsp;","Sekiz&nbsp;","Dokuz&nbsp;");
		$onlar = array("","On&nbsp;","Yirmi&nbsp;","Otuz&nbsp;","Kırk&nbsp;","Elli&nbsp;","Altmış&nbsp;","Yetmiş&nbsp;","Seksen&nbsp;","Doksan&nbsp;");
		$ustler= array("","Bin&nbsp;","Milyon&nbsp;","Milyar&nbsp;","Trilyon&nbsp;");
		$sayi=number_format($sayi, 2, '.', '');
		$sayi1=explode('.',$sayi);
		for ($id = 0; $id < 2; $id++) {
	  		$sayi=$sayi1[$id];
	  		$kalan = strlen($sayi) % 3; 
	  		if ($kalan != 0) $sayi = str_repeat("0", 3-$kalan) . $sayi;
	  		if ($id==0)	{ $parcalar = str_split($sayi, 3); } 
	   		else if ($id==1)	{ $parcalar = str_split($sayi, 3); }
	   		$parca_adedi = sizeof($parcalar);
	  		$sonuc= "";
	  		for ($i = $parca_adedi; $i > 0; $i--) {
		  		$p_yazi = "";
	      		$parca = $parcalar[$i-1];
	      		for ($j = 0; $j < strlen($parca); $j++) {
	      			$bit = $parca[$j];
	       			if ($bit != 0) {
	        			switch ($j)  {
	            			case 0: {
	            				if ($bit != 1) $p_yazi .= $birler[$bit];
	            				$p_yazi .= "Yüz&nbsp;";
	            				break;
	            			}
	            			case 1: {
	            				$p_yazi .= $onlar[$bit];
	            				break;
	            			}
	            			case 2: {
	            				$p_yazi .= $birler[$bit];
	            				break;
	           				}
	        			}
	       			}
	      		}
	
	      		if ($p_yazi=="Bir&nbsp;" && $ustler[$parca_adedi-$i]=="Bin&nbsp;") 
	        		$sonuc = $ustler[$parca_adedi - $i] .$sonuc; 
	      		else 
	        		$sonuc = $p_yazi . $ustler[$parca_adedi - $i] . $sonuc;
	        		
	   		}
	   		if ($id=='0')	{ $sonuc1 = $sonuc . ' Lira&nbsp;&nbsp;';} 
	   		if ($id=='1')	{ if ($sayi[1]){$sonuc2 = $sonuc1 . $sonuc . ' Kuruş';} else {$sonuc2 = $sonuc1 . $sonuc;}}
	  	}
	 	return $sonuc2 . "\n";
	}
}