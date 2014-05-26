<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// CodeIgniter i18n library by Jérôme Jaglale
// http://maestric.com/en/doc/php/codeigniter_i18n
// version 6 - April 20, 2009

class MY_Config extends CI_Config {

	function __construct()
	{
		parent::__construct();
	}

	function site_url($uri = '')
	{
		if (is_array($uri))
		{
			$uri = implode('/', $uri);
		}

		// make it compatible with CI 2.0
		if (class_exists('CI_Controller') OR class_exists('Public_Controller') OR class_exists('Admin_Controller'))
		{
			$ci =& get_instance();
			$uri = $ci->lang->localized($uri);
		}

		return $this->_site_url($uri);
	}

	function _site_url($uri = '', $ssl = FALSE)
	{
		if($ssl AND config('site_ayar_ssl')) {
			$base_url = $this->slash_item('ssl_url');
		} else {
			$base_url = $this->slash_item('base_url');
		}

		if ($uri == '')
		{
			return $base_url.$this->item('index_page');
		}

		if ($this->item('enable_query_strings') == FALSE)
		{
			if (is_array($uri))
			{
				$uri = implode('/', $uri);
			}

			$index = $this->item('index_page') == '' ? '' : $this->slash_item('index_page');
			$suffix = ($this->item('url_suffix') == FALSE) ? '' : $this->item('url_suffix');
			return $base_url.$index.trim($uri, '/').$suffix;
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
					$str .= $prefix.$key.'='.$val;
					$i++;
				}

				$uri = $str;
			}

			return $base_url.$this->item('index_page').'?'.$uri;
		}
	}

	function ssl_url($uri = '')
	{
		return $this->site_url($uri, TRUE);
	}
}
// END MY_Config Class

/* End of file MY_Config.php */
/* Location: ./system/application/libraries/MY_Config.php */