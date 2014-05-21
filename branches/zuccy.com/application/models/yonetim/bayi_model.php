<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class bayi_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function listele()
	{
		$this->db->order_by('bayi_id','desc');
		$result = $this->db->get('bayiler');
		return $result;
	}

	function duzenle($bayi_id)
	{
		$this->db->where('bayi_id',$bayi_id);
		$result = $this->db->get('bayiler',1)->row();
		return $result;
	}

	function duzelt($val)
	{
			$update = array(
			'bayi_adi'			=>$val->bayi_adi,
			'bayi_eposta'		=>$val->bayi_eposta,
			'bayi_adres'		=>$val->bayi_adres,
			'bayi_maps_flag'	=>$val->google_durum,
			'bayi_maps_kodu'	=>$val->bayi_maps_kodu,
			'bayi_tel'			=>$val->bayi_tel,
			'bayi_tel2'			=>$val->bayi_tel2,
			'bayi_tel3'			=>$val->bayi_tel3,
			'bayi_tel4'			=>$val->bayi_tel4,
			'bayi_tel5'			=>$val->bayi_tel5,
			'bayi_tel_p'		=>$val->bayi_tel_p,
			'bayi_tel2_p'		=>$val->bayi_tel2_p,
			'bayi_tel3_p'		=>$val->bayi_tel3_p,
			'bayi_tel4_p'		=>$val->bayi_tel4_p,
			'bayi_tel5_p'		=>$val->bayi_tel5_p,
			'bayi_fax'			=>$val->bayi_fax,
			'bayi_fax2'			=>$val->bayi_fax2,
			'bayi_fax3'			=>$val->bayi_fax3,
			'bayi_fax_p'		=>$val->bayi_fax_p,
			'bayi_fax2_p'		=>$val->bayi_fax2_p,
			'bayi_fax3_p'		=>$val->bayi_fax3_p
			);

		$this->db->where('bayi_id',$val->bayi_id);
		$result = $this->db->update('bayiler',$update);
		return $result;
	}

	function ekle($val)
	{
		$insert = array(
			'bayi_adi'			=>$val->bayi_adi,
			'bayi_eposta'		=>$val->bayi_eposta,
			'bayi_adres'		=>$val->bayi_adres,
			'bayi_maps_flag'	=>$val->google_durum,
			'bayi_maps_kodu'	=>$val->bayi_maps_kodu,
			'bayi_tel'			=>$val->bayi_tel,
			'bayi_tel2'			=>$val->bayi_tel2,
			'bayi_tel3'			=>$val->bayi_tel3,
			'bayi_tel4'			=>$val->bayi_tel4,
			'bayi_tel5'			=>$val->bayi_tel5,
			'bayi_tel_p'		=>$val->bayi_tel_p,
			'bayi_tel2_p'		=>$val->bayi_tel2_p,
			'bayi_tel3_p'		=>$val->bayi_tel3_p,
			'bayi_tel4_p'		=>$val->bayi_tel4_p,
			'bayi_tel5_p'		=>$val->bayi_tel5_p,
			'bayi_fax'			=>$val->bayi_fax,
			'bayi_fax2'			=>$val->bayi_fax2,
			'bayi_fax3'			=>$val->bayi_fax3,
			'bayi_fax_p'		=>$val->bayi_fax_p,
			'bayi_fax2_p'		=>$val->bayi_fax2_p,
			'bayi_fax3_p'		=>$val->bayi_fax3_p,
			'bayi_ektar'		=>time()
			);
		$result = $this->db->insert('bayiler',$insert);
		return $result;
	}

	function sil($val)
	{
		$this->db->where_in('bayi_id',$val->selected);
		$result = $this->db->delete('bayiler');
		return $result;
	}
}