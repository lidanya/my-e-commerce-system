<?php

/**
 * Ayar Dosyası
 *
 * @package Config
 **/

	/*
		empty is this base folders
	*/
	$base_default_url = '';
	/*
		default AUTO,
		PATH_INFO,
		QUERY_STRING,
		REQUEST_URI,
		ORIG_PATH_INFO
	*/
	define('uri_protocol', 'PATH_INFO');
	/*
		Facebook uygulaması izni 0 kapalı 1 açık
	*/
	define('facebook_app_status', '0');
	/*
		permitted_uri_chars = izin verilen karakterler
	*/
	define('permitted_uri_chars', 'a-üöçşığz A-ÜÖÇŞİĞZ! 0-9~%.:_=+-\@\|\>\?\&\=\]\+\'\’\(\)'); // permitted uri chars
	/* 
		0 = Disables logging, Error logging TURNED OFF,
		1 = Error Messages (including PHP errors),
		2 = Debug Messages,
		3 = Informational Messages,
		4 = All Messages
	*/
	define('log_threshold', 0);
	/*
		default compress output on off
	*/
	define('compress_output', FALSE);
	/*
		database_hostname = veritabanı host adresi
		database_username = veritabanı kullanıcı adı
		database_password = veritabanı kullanıcı şifresi
		database_database = veritabanı adı
		database_dbdriver = veritabanı tipi
	*/
	
	 define('database_hostname', 'localhost');
	 define('database_username', 'demouser');
	 define('database_password', '323232');
	 define('database_database', 'webs_demo');
	 define('database_dbdriver', 'mysql');
	 define('database_dbprefix', 'e_'); 
	 define('database_pconnect', FALSE);
	 define('database_db_debug', TRUE);
?>
