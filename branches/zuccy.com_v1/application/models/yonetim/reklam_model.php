<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class reklam_model extends CI_Model
{
	function __cunstruct()
	{
		parent::__construct();
	}

	function ekle($val)
	{
		$insert = array(
			'reklam_adi'	=>$val->reklam_adi,
			'reklam_link'	=>$val->reklam_link,
			'reklam_icerik'	=>$val->reklam_metni,
			'reklam_flag'	=>$val->reklam_durum
		);
		$result = $this->db->insert('mail_reklamlar',$insert);
		return $result;
	}

	function duzelt($val)
	{
		$update = array(
			'reklam_adi'	=>$val->reklam_adi,
			'reklam_link'	=>$val->reklam_link,
			'reklam_icerik'	=>$val->reklam_metni,
			'reklam_flag'	=>$val->reklam_durum
		);
		$this->db->where('reklam_id',$val->reklam_id);
		$result = $this->db->update('mail_reklamlar',$update);
		return $result;
	}

	function listesi()
	{
		$result = $this->db->get('mail_reklamlar');
		return $result;
	}

	function durum($id)
	{
		if(is_numeric($id))
		{
			$this->db->where('reklam_id',$id);
			$reklam = $this->db->get('mail_reklamlar')->row();
			if($reklam){
				$flag = 0;
				if($reklam->reklam_flag == 0)
				{
					$flag = 1;
				}
				$this->db->where('reklam_id',$id);
				$result = $this->db->update('mail_reklamlar',array('reklam_flag'=>$flag));
			}else{
				return false;
			}
		}else{
			return false;
		}
		return $result;
	}

	function sil($val)
	{
		if($val->selected)
		{
			$this->db->where_in('reklam_id',$val->selected);
			$result = $this->db->delete('mail_reklamlar');
		}
		return $result;
	}
}