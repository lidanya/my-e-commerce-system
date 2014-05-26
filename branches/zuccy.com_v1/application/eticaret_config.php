<?php

/**
 * Ayar Dosyası
 *
 * @package Config
 * @author serkan koc
 **/

	require FCPATH . 'config' . EXT;
	require APPPATH . 'eticaret_version' . EXT;

/* Global Configs */
	if($base_default_url !== '')
	{
		$base_url = $base_default_url;
	} else {
		if(isset($_SERVER['HTTP_HOST']))
		{
			//$base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
			$base_url = 'http';
			$base_url .= '://'. $_SERVER['HTTP_HOST'];
			$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
		}
		else
		{
			$base_url = 'http://localhost/';
		}
	}

	define('base_url', $base_url);
	define('ssl_url', 'https://' . substr(base_url, 7));
	define('index_page', ''); // default index.php/welcome or welcome - index.php
	define('url_suffix', ''); // default page.html or page - .html
	define('language', 'turkish'); // default active language
	define('charset', 'UTF-8'); // default charset
	define('enable_hooks', FALSE); // default enable_hooks on off
	define('subclass_prefix', 'MY_'); // default subclass prefix
	define('allow_get_array', TRUE); // page?get=get on off
	define('enable_query_strings', FALSE); // query string on off

	define('log_path', 'application/logs/'); // default log path
	define('log_date_format', 'Y-m-d H:i:s'); // default log date format

	define('cache_path', 'application/cache/'); // default cache path
	define('encryption_key', 'eticaret_2011_' . md5($base_url)); // default encryption key

	define('sess_cookie_name', 'eticaret_sess_' . md5($base_url)); // default session cookie name
	define('sess_expiration', 86400); // default session expire time
	define('sess_expire_on_close', TRUE); // default session expire on close browser on off
	define('sess_encrypt_cookie', TRUE); // default session encrypt cookie on off
	define('sess_table_name', 'sessions'); // default session table name
	define('sess_match_ip', FALSE); // default session match ip on off
	define('sess_match_useragent', TRUE); // default session match useragent on off
	define('sess_time_to_update', 300); // default session update time

	define('cookie_prefix', ''); // default cookie prefix
	define('cookie_domain', ''); // default cookie domain
	define('cookie_path', '/'); // default cookie path

	define('global_xss_filtering', FALSE); // default global xss filtering on off

	define('csrf_protection', FALSE); // default csrf protection on off
	define('csrf_token_name', 'eticaret_token_' . md5($base_url)); // default csrf token name
	define('csrf_cookie_name', 'eticaret_cookie_' . md5($base_url)); // default csrf cookie name
	define('csrf_expire', 7200); // default csrf expire

	define('time_reference', 'local'); // default time reference - local gmt
	define('rewrite_short_tags', FALSE); // default short tags on off
	define('proxy_ips', ''); // default proxy ips - 10.0.1.200,10.0.1.201
/* Global Configs */
/* Dabatase Config */
	
	define('database_cache_on', FALSE);
	define('database_cachedir', 'application/cache/mysql/');
	define('database_char_set', 'utf8');
	define('database_dbcollat', 'utf8_general_ci');
	define('database_swap_pre', '');
	define('database_autoinit', TRUE);
	define('database_stricton', FALSE);
/* Dabatase Config */
	if(!defined('codeigniter_index'))
	{
		die();
		header('Location: '. base_url .'');
	}
?>