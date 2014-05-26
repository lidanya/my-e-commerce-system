<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class yorum_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
	function yorum_listele($sort, $order)
	{
		$this->db->from('yorum');
		$this->db->order_by($sort, $order);
		$query = $this->db->get();
		return $query;		
	}
	
	function yorum_ekle($val)
	{
		$data = array(
			'yorum_icerik'  	=> $val->yorum_icerik,
			'yorum_user_id' 	=> $val->yorum_user_id,
			'yorum_oy'     		=> $val->yorum_oy,
			'yorum_ektar'     	=> time(),
			'yorum_tip'     	=> 'stok',
			'yorum_tip_id'     	=> $val->yorum_tip_id,
			'yorum_ust_id'     	=> '0',
			'yorum_category'	=> $val->yorum_category,
			'yorum_flag'     	=> $val->yorum_flag,
		);
		$kontrol = $this->db->insert('yorum', $data); 
		if ($kontrol){$kontrol_data = true;}else{$kontrol_data = false;}
		return $kontrol_data;
	}

	function yorum_duzenle($val)
	{
		$data = array(
			'yorum_icerik'  	=> $val->yorum_icerik,
			'yorum_yazar' 		=> $val->yorum_yazar,
			'yorum_oy'     		=> $val->yorum_oy,
			'yorum_flag'     	=> $val->yorum_flag
		);
		
		$this->db->where('yorum_id',$val->yorum_id);
		$kontrol = $this->db->update('yorum', $data); 
		return $kontrol;
	}
	function yorum_sil($val)
	{
		$this->db->where_in('yorum_id', $val->selected);
		$kontrol = $this->db->delete('yorum'); 
		return $kontrol;
	}
	function yorum_veri($yorum_id)
	{
		$this->db->where('yorum_id', $yorum_id);
		$this->db->from('yorum');
		$query = $this->db->get();
		$row = $query->row();
		return $row;		
	}
	function yorum_durum($yorum_id, $tip)
	{
		if ($tip=='goster')
		{
			$data =array('yorum_flag'=>'1');
			$this->db->where('yorum_id', $yorum_id);
			$this->db->update('yorum', $data);
		} else if ($tip=='gizle'){
			$data =array('yorum_flag'=>'2');
			$this->db->where('yorum_id', $yorum_id);
			$this->db->update('yorum', $data);
		}
	}
}