<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class sabittanimlar_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function listele($tanim_tip)
	{
		$this->db->where('tanimlar_tip', $tanim_tip);
		$this->db->from('tanimlar');
		$query = $this->db->get();
		return $query;		
	}

	function ekle($val)
	{
		$data = array(
			'tanimlar_adi'  	=> $val->tanimlar_adi,
			'tanimlar_tip' 		=> $val->tanim_tip
		);
		$kontrol = $this->db->insert('tanimlar', $data); 
		if ($kontrol){$kontrol_data = true;}else{$kontrol_data = false;}
		return $kontrol_data;
	}

	function duzenle($val)
	{
		$data = array(
			'tanimlar_adi'  	=> $val->tanimlar_adi
		);
		$this->db->where('tanimlar_id', $val->tanimlar_id);
		$kontrol = $this->db->update('tanimlar', $data); 
		if ($kontrol){$kontrol_data = true;}else{$kontrol_data = false;}
		return $kontrol_data;
	}

	function sil($val)
	{
		for ($i=0;$i<count($val->selected);$i++)
		{
			$this->db->where('tanimlar_id', $val->selected[$i]);
			$kontrol_sil=$this->db->delete('tanimlar'); 
			if ($kontrol_sil){$kontrol_data = true;}
		}
		return $kontrol_data;
	}

	function veri($tanimlar_id)
	{
		$this->db->where('tanimlar_id', $tanimlar_id);
		$this->db->from('tanimlar');
		$query = $this->db->get();
		$row = $query->row();
		return $row;		
	}
}