<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class toplu_mail_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'site model Initialized');
		$this->load->library('email');
		$this->load->library('Admin_Pagination');
	}

	function musterileri_getir($ara)
	{
		$bol = explode(' ',$ara);
		
		$bol_1 = $bol[0];
		
		$this->db->join('e_usr_ide_inf d1','d1.user_id = e_users.id','left');
		$this->db->like('d1.ide_adi', $ara);
		
		$this->db->or_like('d1.ide_soy', $ara);
		$this->db->or_like('e_users.email', $ara);
		
		$this->db->or_like('d1.ide_adi', $bol[0]);
		
		if(count($bol)>1){ $this->db->or_like('d1.ide_soy', $bol[1]); }
		
		$this->db->group_by('e_users.id');

		$query = $this->db->get('users');
		
		return $query->result();
	}

	function bulten_isteyen_istemeyen($id)
	{
		$this->db->join('e_usr_ide_inf d1','d1.user_id = e_users.id','left');
		$this->db->group_by('e_users.id');
		
		if($id == '1')
		{
			$this->db->where('d1.ide_haber',$id);	
		}else{
			$this->db->where('d1.ide_haber','1');
			$this->db->or_where('d1.ide_haber = ', '0'); 
		}
		
		$query = $this->db->get('users');
		
		return $query->result();
	}

	function gonderilen_mail_dus($giden_adet,$Kalan)
	{
		$son_kalan = $Kalan - $giden_adet;
		$Kalan = ($son_kalan < 0) ? 0 : $son_kalan;
		
		$data = array('ayar_deger' => $Kalan);
		
		$this->db->where('ayar_adi','bayi_mail_credit');
		$this->db->update('ayarlar',$data);
		
		return($Kalan);
	}
}