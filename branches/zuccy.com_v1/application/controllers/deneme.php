<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class deneme extends Public_Controller {

	protected $_table_1;
	protected $_table_2;

	/**
	 * isimsiz construct
	 *
	 * @return void
	 **/

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'isimsiz Controller Yüklendi');
		$this->load->library('encrypt');
	}

	/**
	 * index function
	 *
	 * @return void
	 * @author Serkan Koch,  -> 
	 **/

	function index()
	{
		$this->load->helper('string');
		exit(mb_strtolower(random_string('alnum', 4)));
		$this->load->model('yonetim/urunler/product_product_model');
		$seo = $this->product_product_model->check_seo('dsa', 1);
		exit(var_dump($seo));
	}

	function aa()
	{
		/*$_sql_fields = config('sql_table_fields');
		exit(debug($_sql_fields));*/

		$db_array = array();
		$yaz = '';
		foreach($this->db->list_tables() as $tables) {
			$_table = strtr($tables, array('daynex_' => ''));
			$fields = $this->db->list_fields($tables);
			$_fields = array();
			foreach($fields as $field) {
				$_fields[] = strtr($field, array('daynex_' => ''));
			}
			$yaz .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/* '. ucwords(strtr($_table, array('_' => ' '))) .' */' . "<br />";
			$yaz .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\''. $_table .'\' => \''. implode(', ', $_fields) .'\',' . "<br />";
		}

		exit($yaz);
	}

	function _table_1_variables()
	{
		$db_array = array();
		$db_1 = $this->load->database('eticaretsistemim', TRUE);
		$tables = $db_1->list_tables();
		foreach($tables as $table)
		{
			//$table = 'daynex_ayarlar';
			$fields = $db_1->list_fields($table);

			//$lf_i = 0;
			foreach($fields as $field)
			{
				$fd_1 = new stdClass();
				//$fd_1_i = 0;
				$fields_1 = $db_1->field_data($table);
				foreach ($fields_1 as $field_1)
				{
					$fd_1->{$field_1->name}->primary_key			= $field_1->primary_key;
					$fd_1->{$field_1->name}->max_length				= $field_1->max_length;
					$fd_1->{$field_1->name}->default				= $field_1->default;
					$fd_1->{$field_1->name}->type					= $field_1->type;
					$fd_1->{$field_1->name}->name					= $field_1->name;

					/*$fd_1->{$field_1->name}->blob					= $field_1->blob;
					$fd_1->{$field_1->name}->multiple_key			= $field_1->multiple_key;
					$fd_1->{$field_1->name}->not_null				= $field_1->not_null;
					$fd_1->{$field_1->name}->numeric				= $field_1->numeric;
					$fd_1->{$field_1->name}->table					= $field_1->table;
					$fd_1->{$field_1->name}->unique_key				= $field_1->unique_key;
					$fd_1->{$field_1->name}->blob					= $field_1->blob;
					$fd_1->{$field_1->name}->unsigned				= $field_1->unsigned;
					$fd_1->{$field_1->name}->zerofill				= $field_1->zerofill;*/
				}

				//$tablo_varmi = ($db_2->table_exists($table) === TRUE) ? true : false;
				$db->{$table}->{'table'} = $table;
				$db->{$table}->{'field'} = $fd_1;
			}
		}
		$aa->db = $db_1;
		$aa->veri = $db;
		return $aa;
	}

	function _table_2_variables()
	{
		$db_array = array();
		$db_2 = $this->load->database('group_two', TRUE);
		$tables = $db_2->list_tables();
		foreach($tables as $table)
		{
			//$table = 'daynex_ayarlar';
			$fields = $db_2->list_fields($table);

			//$lf_i = 0;
			foreach($fields as $field)
			{
				$fd_1 = new stdClass();
				//$fd_1_i = 0;
				$fields_1 = $db_2->field_data($table);
				foreach ($fields_1 as $field_1)
				{
					$fd_1->{$field_1->name}->primary_key			= $field_1->primary_key;
					$fd_1->{$field_1->name}->max_length				= $field_1->max_length;
					$fd_1->{$field_1->name}->default				= $field_1->default;
					$fd_1->{$field_1->name}->type					= $field_1->type;
					$fd_1->{$field_1->name}->name					= $field_1->name;

					$fd_1->{$field_1->name}->blob					= $field_1->blob;
					$fd_1->{$field_1->name}->multiple_key			= $field_1->multiple_key;
					$fd_1->{$field_1->name}->not_null				= $field_1->not_null;
					$fd_1->{$field_1->name}->numeric				= $field_1->numeric;
					$fd_1->{$field_1->name}->table					= $field_1->table;
					$fd_1->{$field_1->name}->unique_key				= $field_1->unique_key;
					$fd_1->{$field_1->name}->blob					= $field_1->blob;
					$fd_1->{$field_1->name}->unsigned				= $field_1->unsigned;
					$fd_1->{$field_1->name}->zerofill				= $field_1->zerofill;
				}

				//$tablo_varmi = ($db_2->table_exists($table) === TRUE) ? true : false;
				$db->{$table}->{'table'} = $table;
				$db->{$table}->{'field'} = $fd_1;
			}
		}

		$aa->db = $db_2;
		$aa->veri = $db;
		return $aa;
	}

	function tablolar()
	{
		foreach($this->_table_1->veri as $veriler)
		{
			// tablo varmı yokmu kontrolü
			if(self::_table_exists($veriler->table, $this->_table_2->veri) === TRUE)
			{
				//log_message('error', $veriler->table . ' tablosu 2. tabloda bulundu');

				// tablo field varmı yokmu konrolü
				foreach($veriler->field as $_field_key => $_field_value)
				{
					if(self::_table_field_exists($_field_key, $this->_table_2->veri->{$veriler->table}->field) === TRUE)
					{
						
					} else {
						/*if(self::_table_field_create($_field_key, $_field_value, $this->_table_2->db))
						{
							
						}*/
						log_message('error', $veriler->table . ' tablosunda '. $_field_key .' field bulunamadı.');
					}
				}
				// tablo field varmı yokmu konrolü

			} else {
				log_message('error', $veriler->table . ' tablosu bulunamadı');
			}
			// tablo varmı yokmu kontrolü
		}

		exit(debug(array($this->_table_1->veri, $this->_table_2->veri)));
	}

	function _table_exists($_key, $_table_2)
	{
		if(isset($_table_2->$_key))
		{
			return true;
		} else {
			return false;
		}
	}

	function _table_field_exists($_key, $_table_2)
	{
		if(isset($_table_2->$_key))
		{
			return true;
		} else {
			return false;
		}
	}

	function _table_field_create($_key, $_table_1, $db)
	{
		$this->load->dbforge();

		//exit(debug($db));

		$sorgu = $db->query('SHOW COLUMNS FROM `daynex_users`');
		exit(var_dump($_table_1));

		$fields = array(
                        'blog_id' => array(
                                                 'type' => 'INT',
                                                 'constraint' => 5, 
                                                 'unsigned' => TRUE,
                                                 'auto_increment' => TRUE
                                          ),
                        'blog_title' => array(
                                                 'type' => 'VARCHAR',
                                                 'constraint' => '100',
                                          ),
                        'blog_author' => array(
                                                 'type' =>'VARCHAR',
                                                 'constraint' => '100',
                                                 'default' => 'King of Town',
                                          ),
                        'blog_description' => array(
                                                 'type' => 'TEXT',
                                                 'null' => TRUE,
                                          ),
                );

		if($_table_1->blob === '1')
		{
			
		}

		$_insert = array();
		$_insert[$_key] = array();
		$_insert[$_key] = array();

		$this->dbforge->add_column($_table_1->table, $_insert);

        /*    [blob] => 0
            [max_length] => 14
            [multiple_key] => 1
            [name] => kk_banka_resim
            [not_null] => 1
            [numeric] => 0
            [primary_key] => 0
            [table] => daynex_odeme_secenek_kredi_karti
            [type] => string
            [unique_key] => 0
            [unsigned] => 0
	            [zerofill] => 0*/

		exit(debug(array($_key, $_table_1, $db)));
	}
}

/* End of file isimsiz.php */
/* Location: ./dev10/application/controllers/isimsiz.php */

?>