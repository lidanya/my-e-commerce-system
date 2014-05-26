<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class cache
{
	var $CI;

	function __construct()
	{
		log_message('debug', 'Cache Model Initialized');
		
		$this->ci =& get_instance();
		$this->ci->load->helper('file');
		
		$path = $this->ci->config->item('cache_path');
		$cache_path = ($path == '') ? BASEPATH.'cache/' : $path;
		$files = glob($cache_path . 'daynex_cache.*');
		if ($files) {
			foreach ($files as $file) {
				$time = substr(strrchr($file, '.'), 1);
				if ($time < time()) {
					@unlink($file);
				}
			}
		}
	}

	function get($key, $group = null)
	{
		$path = $this->ci->config->item('cache_path');
		$cache_path = ($path == '') ? BASEPATH.'cache/' : $path;

		$group = (!is_null($group) AND $group !== '') ? '.' . $group : null;
		$files = glob($cache_path . 'daynex_cache' . $group . '.' . $key . '.*');

		if (!$files) {
			return FALSE;
		}

		foreach ($files as $file) {
			if ( ! @file_exists($file))
			{
				return FALSE;
			}

			$time = substr(strrchr($file, '.'), 1);
			if ($time < time()) {
				@unlink($file);
				return FALSE;
			}

			$cachedata = read_file($file);

			if ($cachedata === FALSE)
			{
				return FALSE;
			}

			return unserialize($cachedata);	
		}

		log_message('debug', "Cache file is current. Sending it to browser.");
	}

	function set($key, $value, $group = null, $exp)
	{
		$this->delete($key);
		
		$path = $this->ci->config->item('cache_path');
		$cache_path = ($path == '') ? BASEPATH.'cache/' : $path;

		$group = (!is_null($group) AND $group !== '') ? '.' . $group : null;
		$expire = time() + ($exp * 60);
		$cache_path .= 'daynex_cache'. $group . '.' . $key . '.' . $expire;

		if (write_file($cache_path, serialize($value)) === FALSE)
		{
			log_message('error', 'file not write ' . $cache_path);
			return FALSE;
		}

		@chmod($cache_path, DIR_WRITE_MODE);
		return TRUE;
	}

	function delete($key = null, $group = null)
	{
		$path = $this->ci->config->item('cache_path');
		$cache_path = ($path == '') ? BASEPATH.'cache/' : $path;

		$group = (!is_null($group) AND $group !== '') ? '.' . $group : null;
		$key = (!is_null($key) AND $key !== '') ? '.' . $key : null;
		$files = glob($cache_path . 'daynex_cache' . $group . $key . '.*');
		if ($files) {
			foreach ($files as $file) {
				@unlink($file);
			}
		}
	}
}