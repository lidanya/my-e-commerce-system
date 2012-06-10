<?php

	if ( ! function_exists('show_face_page') )
	{
		function show_face_page($information_id, $anchor_tag = '', $fist_tag = '', $last_tag = '', $ssl = FALSE)
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
				$url = face_site_url(strtr($information_type[$info->type]['url'], array('{url}' => $info->seo)));
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

	if ( ! function_exists('face_ssl_url'))
	{
		function face_ssl_url($uri = '')
		{
			$ci =& get_instance();
			return $ci->fonksiyonlar->face_site_url($uri, config('site_ayar_ssl'));
		}
	}

	if ( ! function_exists('ssl_face_url'))
	{
		function ssl_face_url($uri = NULL, $ssl = FALSE)
		{
			$ci		=& get_instance();
			$url	= ''; //'face_app';
			return $ci->fonksiyonlar->face_site_url($url . '/' . $uri, ssl_status());
		}
	}

	if ( ! function_exists('face_site_url'))
	{
		function face_site_url($uri = NULL, $ssl = FALSE)
		{
			$ci		=& get_instance();

			$url		= ''; //'face_app';
			$base_url	= $ci->fonksiyonlar->face_site_url($url . '/' . $uri, $ssl);
			return $base_url;
		}
	}

	if ( ! function_exists('face_base_url'))
	{
		function face_base_url($ssl = FALSE)
		{
			$url		= 'face_app';
			$base_url	= base_url($ssl) . $url . '/';
			return $base_url;
		}
	}

	/* Start Yollar Start */
	
	if ( ! function_exists('face_tema'))
	{
		function face_tema()
		{
			$secili_tema = (config('site_ayar_facebook_tema')) ? config('site_ayar_facebook_tema') : 'daynex_standart';
			return 'face_temalar/' . $secili_tema . '/';
		}
	}

	if ( ! function_exists('face_tema_asset'))
	{
		function face_tema_asset($base = FALSE)
		{
			$secili_tema = (config('site_ayar_facebook_tema')) ? config('site_ayar_facebook_tema') : 'daynex_standart';
			$secili_tema_asset = (config('site_ayar_facebook_tema_asset')) ? config('site_ayar_facebook_tema_asset') : 'daynex_standart';

			if($base) {
				$tema_asset = '/';
			} else {
				$tema_asset = '/tema_asset/' . $secili_tema_asset . '/';
			}

			return 'face_temalar/' . $secili_tema . $tema_asset;
		}
	}
	
	if ( ! function_exists('face_resim'))
	{
		function face_resim($base = FALSE)
		{
			$_ci =& get_instance();

			if (config('site_ayar_ssl') == '1' && isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
				$base_url = $_ci->config->item('ssl_url');
			} else {
				$base_url = $_ci->config->item('base_url');
			}

			$tema_asset = face_tema_asset($base);

			//return $base_url . APPPATH . 'views/' . tema_asset() .  'images/';
			return APPPATH . 'views/' . $tema_asset .  'images/';
		}
	}
	
	if ( ! function_exists('face_css'))
	{
		function face_css($base = FALSE)
		{
			$_ci =& get_instance();

			if (config('site_ayar_ssl') == '1' && isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
				$base_url = $_ci->config->item('ssl_url');
			} else {
				$base_url = $_ci->config->item('base_url');
			}

			$tema_asset = face_tema_asset($base);

			//return $base_url . APPPATH . 'views/' . tema_asset() . 'css/';
			return APPPATH . 'views/' . $tema_asset . 'css/';
		}
	}
	
	if ( ! function_exists('face_js'))
	{
		function face_js()
		{
			$_ci =& get_instance();

			if (config('site_ayar_ssl') == '1' && isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
				$base_url = $_ci->config->item('ssl_url');
			} else {
				$base_url = $_ci->config->item('base_url');
			}

			//return $base_url . APPPATH . 'views/' . tema() . 'js/';
			return APPPATH . 'views/' . face_tema() . 'js/';
		}
	}
	
	if ( ! function_exists('face_flash'))
	{
		function face_flash()
		{
			$_ci =& get_instance();

			if (config('site_ayar_ssl') == '1' && isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
				$base_url = $_ci->config->item('ssl_url');
			} else {
				$base_url = $_ci->config->item('base_url');
			}

			//return $base_url . APPPATH . 'views/' . tema() . 'flash/';
			return APPPATH . 'views/' . face_tema() . 'flash/';
		}
	}

?>