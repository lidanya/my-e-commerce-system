<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class eklentiler_slider_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Slider Model Yüklendi');
	}

	function slider_listele()
	{
		if(eklenti_ayar('slider', 'siralama_sekli') != NULL)
		{
			$siralama = eklenti_ayar('slider', 'siralama_sekli');
		} else {
			$siralama = 'desc';
		}
		$this->db->order_by('slider_sira', $siralama);
		$this->db->select('slider_id, slider_link, slider_img, slider_flag, slider_sira');
		return $this->db->get_where('tool_slider', array('slider_flag' => '1'));
	}

	function kontrol()
	{
		$this->db->where('slider_flag', '1');
		$sorgu = $this->db->count_all_results('tool_slider');
		if($sorgu > 0)
		{
			return true;
		} else {
			return false;
		}
	}
}