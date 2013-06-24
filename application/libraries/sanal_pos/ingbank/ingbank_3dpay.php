<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

 /*
	SQL Kodu
	O:8:"stdClass":1:{s:5:"3dpay";O:8:"stdClass":2:{s:10:"merchantid";s:0:"";s:8:"storekey";s:0:"";}}
	Php Kodu
	$olustur->{'3dpay'}->merchantid	= '';
	$olustur->{'3dpay'}->storekey	= '';
	echo serialize($olustur);
 */

class ingbank_3dpay
{
	public $ci;
	protected $banka				= 'ingbank';
	protected $ascii				= '3dpay';
	protected $banka_bilgi			= FALSE;
	protected $banka_grubu			= 'EST';
	protected $banka_grubu_verisi	= FALSE;

	/**
	 * 3dpay construct
	 *
	 * @return void
	 * @author E-Ticaret Sistemim
	 **/

	function __construct()
	{
		log_message('debug', 'ingbank_3dpay Library Yüklendi');
		$this->ci =& get_instance();
		$this->ci->load->library('encrypt');

		// Autoloads
		$this->banka_grubu_verisi = $this->select_group();
	}

	public function select_group()
	{
		$group = $this->banka_grubu;
		$ascii = $this->ascii;
		if ($group) {
			$group = mb_strtolower($group);
			$ascii = mb_strtolower($ascii);
			if ( ! is_file(APPPATH . 'libraries/sanal_pos/group/' . $group . '/' . $group . '_' . $ascii . EXT)) {
				return FALSE;
			}
			$config = array('banka' => $this->banka);
			$this->ci->load->library('sanal_pos/group/' . $group . '/' . $group . '_' . $ascii, $config, $group . '_' . $ascii);
			return $this->ci->{$group . '_' . $ascii};
		}
		return FALSE;
	}

	public function banka_bilgi_tanimla($gelen_degerler = null)
	{
		if ($this->banka_grubu_verisi) {
			return $this->banka_grubu_verisi->banka_bilgi_tanimla($gelen_degerler);
		}
	}

	public function banka_bilgi()
	{
		if ($this->banka_grubu_verisi) {
			return $this->banka_grubu_verisi->banka_bilgi();
		}
	}

	public function form_gonder($gelen_veriler = null)
	{
		if ($this->banka_grubu_verisi) {
			return $this->banka_grubu_verisi->form_gonder($gelen_veriler);
		}
	}

	public function form_sonuc($gelen_veriler = null)
	{
		if ($this->banka_grubu_verisi) {
			return $this->banka_grubu_verisi->form_sonuc($gelen_veriler);
		}
	}

}

/* End of file isimsiz.php */
/* Location: ./dev10/application/libraries/isimsiz.php */

?>