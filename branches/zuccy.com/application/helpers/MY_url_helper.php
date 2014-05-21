<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 **/

if ( ! function_exists('ssl_url'))
{
	function ssl_url($uri = '')
	{
		$ci =& get_instance();
		return $ci->fonksiyonlar->site_url($uri, config('site_ayar_ssl'));
	}
}

if ( ! function_exists('ssl_redirect'))
{
	function ssl_redirect($url = '')
	{
		redirect(ssl_url($url));
	}
}

if ( ! function_exists('ssl_status'))
{
	function ssl_status()
	{
		$ci =& get_instance();
		if (config('site_ayar_ssl') && $ci->input->server('HTTPS') && (($ci->input->server('HTTPS') == 'on') || ($ci->input->server('HTTPS') == '1'))) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

if ( ! function_exists('ssl_site_url'))
{
	function ssl_site_url($uri = '')
	{
		$ci =& get_instance();
		return $ci->fonksiyonlar->site_url($uri, ssl_status());
	}
}

if ( ! function_exists('base_url'))
{
	function base_url($ssl = FALSE)
	{
		$ci =& get_instance();
		if ($ssl && $ci->config->item('ssl_url'))
		{
			return $ci->config->slash_item('ssl_url');
		}
		return $ci->config->slash_item('base_url');
	}
}

if ( ! function_exists('current_url'))
{
	function current_url($ssl = FALSE)
	{
		$ci =& get_instance();
		$return = $ci->config->site_url($ci->uri->uri_string(), $ssl);
		if($ci->input->get() AND count($ci->input->get()) > 0)
		{
			$get =  array();
			foreach($ci->input->get() as $key => $val)
			{
				$get[] = $key.'='.$val;
			}
			$return .= '?' . implode('&', $get);
		}
		return $return;
	}
}

if ( ! function_exists('anchor'))
{
	function anchor($uri = '', $title = '', $attributes = '', $ssl = FALSE)
	{
		$title = (string) $title;

		if ( ! is_array($uri))
		{
			$site_url = ( ! preg_match('!^\w+://! i', $uri)) ? site_url($uri, $ssl) : $uri;
		}
		else
		{
			$site_url = site_url($uri);
		}

		if ($title == '')
		{
			$title = $site_url;
		}

		if ($attributes != '')
		{
			$attributes = _parse_attributes($attributes);
		}

		return '<a href="'.$site_url.'"'.$attributes.'>'.$title.'</a>';
	}
}

if ( ! function_exists('anchor_tag'))
{
	function anchor_tag($uri = '', $title = '', $attributes = '', $fist_tag = '', $last_tag = '', $ssl = FALSE)
	{
		$title = (string) $title;

		if ( ! is_array($uri))
		{
			$site_url = ( ! preg_match('!^\w+://! i', $uri)) ? site_url($uri, $ssl) : $uri;
		}
		else
		{
			$site_url = site_url($uri);
		}

		if ($title == '')
		{
			$title = $site_url;
		}

		if ($attributes != '')
		{
			$attributes = _parse_attributes($attributes);
		}

		return '<a href="'.$site_url.'"'.$attributes.'>'. $fist_tag . $title . $last_tag .'</a>';
	}
}

if ( ! function_exists('redirect'))
{
	function redirect($uri = '', $method = 'location', $http_response_code = 302, $ssl = FALSE)
	{
		if ( ! preg_match('#^https?://#i', $uri))
		{
			$uri = site_url($uri, $ssl);
		}
		
		switch($method)
		{
			case 'refresh'	: header("Refresh:0;url=".$uri);
				break;
			default			: header("Location: ".$uri, TRUE, $http_response_code);
				break;
		}
		exit;
	}
}

if ( ! function_exists('url_title'))
{
	function url_title($str, $separator = 'dash', $lowercase = FALSE)
	{
		if ($separator == 'dash')
		{
			$search		= '_';
			$replace	= '-';
		}
		else
		{
			$search		= '-';
			$replace	= '_';
		}

		$trans = array(
						'ç'						=> 'c',
						'Ç'						=> 'C',
						'ş'						=> 's',
						'Ş'						=> 'S',
						'ı'						=> 'i',
						'İ'						=> 'I',
						'ğ'						=> 'g',
						'Ğ'						=> 'G',
						'ü'						=> 'u',
						'Ü'						=> 'U',
						'ö'						=> 'o',
						'Ö'						=> 'O',
						'&\#\d+?;'				=> '',
						'&\S+?;'				=> '',
						'\\('					=> $replace,
						'\\)'					=> $replace,
						'\s+'					=> $replace,
						"'"						=> $replace,
						"’"						=> $replace,
						'[^a-üöçşığz A-ÜÖÇŞİĞZ 0-9\-\._]'		=> '',
						$replace.'+'			=> $replace,
						$replace.'$'			=> $replace,
						$replace."'"			=> $replace,
						$replace.'’'			=> $replace,
						'^'.$replace			=> $replace,
						'\.+$'					=> ''
					  );

		$str = strip_tags($str);

		foreach ($trans as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);
		}

		if ($lowercase === TRUE)
		{
			$str = mb_strtolower($str);
		}

		return trim(stripslashes($str));
	}
}

if ( ! function_exists('tr_en_temizle'))
{
	function tr_en_temizle($str, $lowercase = TRUE)
	{
		$trans = array(
			'ç'						=> 'c',
			'Ç'						=> 'C',
			'ş'						=> 's',
			'Ş'						=> 'S',
			'ı'						=> 'i',
			'İ'						=> 'I',
			'ğ'						=> 'g',
			'Ğ'						=> 'G',
			'ü'						=> 'u',
			'Ü'						=> 'U',
			'ö'						=> 'o',
			'Ö'						=> 'O',
			'&\#\d+?;'				=> '',
			'&\S+?;'				=> '',
			'\s\s+'					=> '',
			'\s+'					=> '',
			'[^a-z0-9\-\._]'		=> '',
			'\.+$'					=> ''
		);

		$str = strip_tags($str);

		foreach ($trans as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);
		}

		if ($lowercase === TRUE)
		{
			$str = mb_strtolower($str);
		}
		
		return trim(stripslashes($str));
	}
}

if ( ! function_exists('tr_en_temizle_iletisim'))
{
	function tr_en_temizle_iletisim($str, $lowercase = FALSE)
	{
		$trans = array(
			'ç'						=> 'c',
			'Ç'						=> 'C',
			'ş'						=> 's',
			'Ş'						=> 'S',
			'ı'						=> 'i',
			'İ'						=> 'I',
			'ğ'						=> 'g',
			'Ğ'						=> 'G',
			'ü'						=> 'u',
			'Ü'						=> 'U',
			'ö'						=> 'o',
			'Ö'						=> 'O',
			'\&\?'					=> '',
			'[^a-z 0-9~%.:_\-]'		=> '',
		);

		$str = strip_tags($str);

		foreach ($trans as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);
		}

		if ($lowercase === TRUE)
		{
			$str = mb_strtolower($str);
		}
		
		return trim(stripslashes($str));
	}
}

if ( ! function_exists('tr_en_temizle_file'))
{
	function tr_en_temizle_file($str, $lowercase = FALSE)
	{
		$trans = array(
			'ç'						=> 'c',
			'Ç'						=> 'C',
			'ş'						=> 's',
			'Ş'						=> 'S',
			'ı'						=> 'i',
			'İ'						=> 'I',
			'ğ'						=> 'g',
			'Ğ'						=> 'G',
			'ü'						=> 'u',
			'Ü'						=> 'U',
			'ö'						=> 'o',
			'Ö'						=> 'O',
			' '						=> '-'
		);

		$str = strip_tags($str);

		foreach ($trans as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);
		}

		if ($lowercase === TRUE)
		{
			$str = mb_strtolower($str);
		}
		
		return trim(stripslashes($str));
	}
}

if ( ! function_exists('tr_en_temizle_arama'))
{
	function tr_en_temizle_arama($str, $lowercase = TRUE)
	{
		$trans = array(
			'ç'				=> 'c',
			'Ç'				=> 'C',
			'ş'				=> 's',
			'Ş'				=> 'S',
			'ı'				=> 'i',
			'İ'				=> 'I',
			'ğ'				=> 'g',
			'Ğ'				=> 'G',
			'ü'				=> 'u',
			'Ü'				=> 'U',
			'ö'				=> 'o',
			'Ö'				=> 'O',
			'*'				=> '',
			'!'				=> '',
			'^'				=> '',
			'+'				=> '',
			'%'				=> '',
			'&'				=> '',
			'/'				=> '',
			'\''			=> '',
			'('				=> '',
			')'				=> '',
			'='				=> '',
			'?'				=> '',
			'>'				=> '',
			'<'				=> '',
			'£'				=> '',
			'#'				=> '',
			'$'				=> '',
			'½'				=> '',
			'{'				=> '',
			'}'				=> '',
			'['				=> '',
			']'				=> '',
			'|'				=> ''
		);

		$str = strip_tags($str);

		$str = strtr($str, $trans);

		if ($lowercase === TRUE)
		{
			$str = mb_strtolower($str, 'UTF-8');
		}
		
		return trim(stripslashes($str));
	}
}

if ( ! function_exists('tr_en'))
{
	function tr_en($str, $lowercase = TRUE)
	{
		$trans = array(
			'ç'				=> 'c',
			'Ç'				=> 'C',
			'ş'				=> 's',
			'Ş'				=> 'S',
			'ı'				=> 'i',
			'İ'				=> 'I',
			'ğ'				=> 'g',
			'Ğ'				=> 'G',
			'ü'				=> 'u',
			'Ü'				=> 'U',
			'ö'				=> 'o',
			'Ö'				=> 'O',
			'*'				=> '',
			'!'				=> '',
			'^'				=> '',
			'+'				=> '',
			'%'				=> '',
			'&'				=> '',
			'/'				=> '',
			'\''			=> '',
			'('				=> '',
			')'				=> '',
			'='				=> '',
			'?'				=> '',
			'>'				=> '',
			'<'				=> '',
			'£'				=> '',
			'#'				=> '',
			'$'				=> '',
			'½'				=> '',
			'{'				=> '',
			'}'				=> '',
			'['				=> '',
			']'				=> '',
			'|'				=> ''
		);

		$str = strip_tags($str);

		$str = strtr($str, $trans);

		if ($lowercase === TRUE)
		{
		    $str = mb_strtolower($str, 'UTF-8');
		}

		return trim(stripslashes($str));
	}
}