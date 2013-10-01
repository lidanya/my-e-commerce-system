<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class fatura_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function fatura_getir($user_id = 0)
	{
		$this->db->where('user_id', $user_id);
		$this->db->limit(1);
		$this->db->order_by('inv_id', 'desc');
		$result = $this->db->get('usr_inv_inf');
		return $result;
	}

	function fatura_duzenle($inv_id = 0)
	{
		$this->db->where('inv_id', $inv_id);
		$result = $this->db->get('usr_inv_inf');
		return $result;
	}

	function fatura_duzelt($val,$inv_id)
	{
		$insert = array(
			'inv_name'			=>$val->fatura_adi,
			'inv_username'		=>$val->adi,
			'inv_usersurname'	=>$val->soyad,
			'inv_tckimlik'		=>$val->tckimlik,
			'inv_firma'			=>$val->firmaadi,
			'inv_adr_id'		=>$val->adres,
			'inv_ulke'			=>$val->ulke,
			'inv_sehir'			=>$val->sehir,
			'inv_ilce'			=>$val->ilce,
			'inv_pkodu'			=>$val->postak,
			'inv_vno'			=>$val->vergin,
			'inv_vda'			=>$val->vergid,
			'inv_tel'			=>$val->tel,
			'inv_fax'			=>$val->fax,
			'inv_flag'			=>1,
			'user_id'			=>$this->dx_auth->get_user_id()
		);
		$this->db->where('inv_id',$inv_id);
		$result = $this->db->update('usr_inv_inf',$insert);
		return $result;
	}

	function fatura_ekle($val)
	{
		$insert = array(
			'inv_name'			=> $val->fatura_adi,
			'inv_username'		=> $val->adi,
			'inv_usersurname'	=> $val->soyad,
			'inv_tckimlik'		=> $val->tckimlik,
			'inv_firma'			=> $val->firmaadi,
			'inv_adr_id'		=> $val->adres,
			'inv_ulke'			=> $val->ulke,
			'inv_sehir'			=> $val->sehir,
			'inv_ilce'			=> $val->ilce,
			'inv_pkodu'			=> $val->postak,
			'inv_vno'			=> $val->vergin,
			'inv_vda'			=> $val->vergid,
			'inv_tel'			=> $val->tel,
			'inv_fax'			=> $val->fax,
			'inv_flag'			=> 1,
			'user_id'			=> $this->dx_auth->get_user_id()
		);
		$result = $this->db->insert('usr_inv_inf',$insert);
		return $result;
	}

	function bolge_getir($val)
	{
		$bolgeler	= '';
		$bolgeid	= '';
		$bolge_ad	= '';
		
		$this->db->where('ulke_id',$val->ulke_id);
		$query = $this->db->get('ulke_bolgeleri');
		
		if($query->num_rows() > 0)
		{
			$bolgeler .= '<select name="sehir" id="sehir" style="background-color:#fefefe; border:1px solid #cccccc;  height:25px;">'."\n";
			foreach($query->result() as $bolge)
			{
				$bolgeid = (isset($bolge->bolge_id)) ? $bolge->bolge_id : null;
				$bolge_ad = (isset($bolge->bolge_adi)) ? $bolge->bolge_adi : null;
				
				$bolgeler .= '<option value="'.$bolgeid.'">'.$bolge_ad.'</option>'."\n";
			}
			
			$bolgeler .= '</select>'."\n";
		}else{
			$bolgeler .= '<select name="sehir" id="sehir" style="background-color:#fefefe; border:1px solid #cccccc;  height:25px;">'."\n";
			$bolgeler .= '<option value="">- - -</option>'."\n";
			
			$bolgeler .= '</select>'."\n";
		}
		return $bolgeler;
	}
}