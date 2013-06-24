<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class parser_csv {

	protected $_settings;
	protected $_filename;

	/**
	 * Parser construct
	 *
	 * @return void
	 **/

	function __construct()
	{
		log_message('debug', 'isimsiz Library Yüklendi');

		$default_settings = array(
			'eol' => ';',
			'newline' => "\r\n",
			'limit' => null
		);

		$this->_settings = $default_settings;
	}

	/**
	 * isimsiz function
	 *
	 * @return void
	 **/
	 
	public function load_file($file)
	{
		$this->_filename = $file;
	}

	public function set_setting($get_settings)
	{
		$this->_settings = $get_settings;
	}

	public function parse($limit = null)
	{
		// file readability
		if (!is_readable($this->_filename))
		{
			log_message('error', $this->file . ' dosyası okunabilir değil.');
		    return array();
		}

		// file exists
		if(!file_exists($this->_filename))
		{
			log_message('error', $this->file . ' dosyası bulunamadı.');
			return array();
		}

		$file = @file_get_contents($this->_filename);
		$file = mb_convert_encoding($file, 'UTF-8', 'ISO-8859-9, ISO-8859-1, WINDOWS-1254');
		$content = explode($this->_settings['newline'], $file);
		$contents = array();
		foreach($content as $key => $value)
		{
			$content_values = explode($this->_settings['eol'], $value);
			foreach($content_values as $content_key => $content_value)
			{
				$contents[$key][$content_key] = $content_value;
			}
		}

		if(!is_null($limit))
		{
			if(array_key_exists($limit, $contents))
			{
				$contents = $contents[$limit];
			} else {
				log_message('error', $limit . ' anahtarı bulunamadı.');
			}
		} elseif (!is_null($this->_settings['limit'])) {
			if(array_key_exists($this->_settings['limit'], $contents))
			{
				$contents = $contents[$this->_settings['limit']];
			} else {
				log_message('error', $this->_settings['limit'] . ' anahtarı bulunamadı.');
			}
		}

		return $contents;
	}

}

/* End of file isimsiz.php */
/* Location: ./dev10/application/libraries/isimsiz.php */

?>