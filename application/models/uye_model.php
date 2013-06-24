<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class uye_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Üye Model Yüklendi');

		$this->load->library('validation');
	}

	function ajax_uye_kayit($val)
	{
		$bilgi = $this->dx_auth->register($val->email, $val->password, $val->email);

		$update_data = array(
			'ide_adi' => $val->adiniz,
			'ide_soy' => $val->soyadiniz
		);
		$this->db->where('user_id', $bilgi['sonid']);
		if($bilgi AND $this->db->update('usr_ide_inf', $update_data))
		{
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function ajax_uye_giris($val)
	{
		if($this->dx_auth->login($val->email, $val->password, $val->remember))
		{
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function captcha_check($code, $neresi = 'uye_kayit')
	{
		$result = strtolower($code) == strtolower($this->session->userdata($neresi));
		return $result;
	}

	function usr_ide_inf_update($bilgiler, $user_id)
	{
		if(is_array($bilgiler))
		{
			$this->db->where('user_id', $user_id);
			if($this->db->update('usr_ide_inf', $bilgiler))
			{
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	function usr_adr_inf_update($bilgiler, $user_id)
	{
		if(is_array($bilgiler))
		{
			$this->db->where('user_id', $user_id);
			if($this->db->update('usr_adr_inf', $bilgiler))
			{
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	function get_usr_ide_inf($user_id)
	{
		$this->db->select('ide_id, ide_adi, ide_soy, ide_dogtar, ide_dogyer, ide_cins, ide_cep, ide_flag, ide_unv, ide_tckimlik, ide_alternatif_mail, ide_web_site, user_id');
		$sorgu = $this->db->get_where('usr_ide_inf', array('user_id' => $user_id), 1);
		if($sorgu->num_rows() > 0)
		{
			return $sorgu->row();
		} else {
			return FALSE;
		}
	}
}