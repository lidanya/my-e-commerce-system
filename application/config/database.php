<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

	$active_group = 'e';
	$active_record = TRUE;

	$db['e']['hostname'] = database_hostname;
	$db['e']['username'] = database_username;
	$db['e']['password'] = database_password;
	$db['e']['database'] = database_database;
	$db['e']['dbdriver'] = database_dbdriver;
	$db['e']['dbprefix'] = database_dbprefix;
	$db['e']['pconnect'] = database_pconnect;
	$db['e']['db_debug'] = database_db_debug;
	$db['e']['cache_on'] = database_cache_on;
	$db['e']['cachedir'] = database_cachedir;
	$db['e']['char_set'] = database_char_set;
	$db['e']['dbcollat'] = database_dbcollat;
	$db['e']['swap_pre'] = database_swap_pre;
	$db['e']['autoinit'] = database_autoinit;
	$db['e']['stricton'] = database_stricton;

	$db['live']['hostname'] = database_hostname;
	$db['live']['username'] = "miniuser";
	$db['live']['password'] = "323232";
	$db['live']['database'] = 'webs_minikutudb';
	$db['live']['dbdriver'] = "mysql";
	$db['live']['dbprefix'] = database_dbprefix;
	$db['live']['pconnect'] = FALSE;
	$db['live']['db_debug'] = TRUE;
	$db['live']['cache_on'] = FALSE;
	$db['live']['cachedir'] = "";
	$db['live']['char_set'] = "utf8";
	$db['live']['dbcollat'] = "utf8_general_ci";
	$db['live']['swap_pre'] = "";
	$db['live']['autoinit'] = FALSE;
	$db['live']['stricton'] = FALSE;

/* End of file database.php */
/* Location: ./application/config/database.php */