<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class kargo_model extends CI_Model
{
    function __construct()
    {
		parent::__construct();
    }

	function kargo_listele($sort, $order)
	{
		$this->db->where('kargo_flag !=', '0');
		$this->db->from('kargo');
		$this->db->order_by($sort, $order);
		$query = $this->db->get();
		return $query;		
	}

	function kargo_ekle($val)
	{
		$this->db->select_max('kargo_sira');
		$this->db->from('kargo');
		$get = $this->db->get();
		$row =$get->row();
		$sira_bul = $row->kargo_sira+1;
		$data = array(
			'kargo_adi'      	=> $val->kargo_adi,
			'kargo_logo'   		=> $val->product_image,
			'kargo_flag'     	=> $val->kargo_durum,
			'kargo_parca'     	=> 2, //$val->kargo_parca,
			'kargo_ucret_tip' 	=> $val->kargo_ucret_tip,
			'kargo_sira' 		=> $sira_bul
		);
		$kontrol = $this->db->insert('kargo', $data); 
		$son_kargo_id = $this->db->insert_id();	
		for ($i=1;$i<9;$i++)
		{
			$kargo_ucret = 'ucret_tip'.$i;
			if ($val->$kargo_ucret>0){$ucret = $val->$kargo_ucret;} else {$ucret = NULL;}
			$data = array(
				'kargo_ucret_tip'   => $kargo_ucret,
				'kargo_ucret_ucret' => $ucret,
				'kargo_id'			=> $son_kargo_id,
				'kargo_ucret_flag'  => $val->kargo_durum
			);
			$kontrol1 = $this->db->insert('kargo_ucret', $data); 
		}
		if (($kontrol) and ($kontrol1)){$kontrol_data = true;}else{$kontrol_data = false;}
		return $kontrol_data;
	}

	function kargo_duzenle($val)
	{
		$data = array(
			'kargo_adi'      	=> $val->kargo_adi,
			'kargo_logo'   		=> $val->product_image,
			'kargo_flag'     	=> $val->kargo_durum,
			'kargo_parca'     	=> 2, //$val->kargo_parca,
			'kargo_ucret_tip' 	=> $val->kargo_ucret_tip,
		);
		$this->db->where('kargo_id',$val->kargo_id);
		$kontrol = $this->db->update('kargo', $data); 
		for ($i=1;$i<9;$i++)
		{
			$kargo_ucret = 'ucret_tip'.$i;
			if ($val->$kargo_ucret>0){$ucret = $val->$kargo_ucret;} else {$ucret = NULL;}
			$data = array(
				'kargo_ucret_ucret' => $ucret,
				'kargo_ucret_flag'  => $val->kargo_durum
			);
			$this->db->where('kargo_ucret_tip',$kargo_ucret);
			$this->db->where('kargo_id',$val->kargo_id);
			$kontrol1 = $this->db->update('kargo_ucret', $data); 
		}
		if (($kontrol) and ($kontrol1)){$kontrol_data = true;}else{$kontrol_data = false;}
		return $kontrol_data;
	}

	function kargo_sil($val)
	{
		for ($i=0;$i<count($val->selected);$i++)
		{
			$data = array(
				'kargo_flag'	=> '0'
			);
			$this->db->where('kargo_id', $val->selected[$i]);
			$kontrol_sil=$this->db->update('kargo', $data); 
			if ($kontrol_sil){$kontrol_data = true;}
		}
		return $kontrol_data;
	}

	function kargo_veri($kargo_id)
	{
		$this->db->where('kargo_id', $kargo_id);
		$this->db->where('kargo_flag !=', '0');
		$this->db->from('kargo');
		$query = $this->db->get();
		$row = $query->row();
		return $row;		
	}

	function kargo_ucret_veri($kargo_id)
	{
		$this->db->where('kargo_id', $kargo_id);
		$this->db->from('kargo_ucret');
		$query = $this->db->get();
		return $query;		
	}

	function kargo_durum($kargo_id, $tip)
	{
		if ($tip=='goster')
		{
			$data =array('kargo_flag'=>'1');
			$this->db->where('kargo_id', $kargo_id);
			$this->db->update('kargo', $data);
		} else if ($tip=='gizle'){
			$data =array('kargo_flag'=>'2');
			$this->db->where('kargo_id', $kargo_id);
			$this->db->update('kargo', $data);
		}
	}

	function kargo_enust($kargo_id)
	{
		$this->db->from('kargo');
		$this->db->where('kargo_flag !=','0');
		$this->db->order_by('kargo_sira','ASC');
		$query = $this->db->get();	
		$ii=1;
		foreach($query->result() as $row)
		{
			if ($row->kargo_id!=$kargo_id)
			{
				$ii=$ii+1;
				$yeni_sira = $ii;
				$data = array('kargo_sira'=>$yeni_sira);
				$this->db->where('kargo_id',$row->kargo_id);
				$this->db->update('kargo', $data);
			}
		}
		$data=array('kargo_sira'=>'1');
		$this->db->where('kargo_id',$kargo_id);
		$this->db->update('kargo', $data);
	}

	function kargo_enalt($kargo_id)
	{
		$this->db->from('kargo');
		$this->db->where('kargo_flag !=','0');
		$this->db->order_by('kargo_sira','ASC');
		$query = $this->db->get();	
		$ii=0;
		foreach($query->result() as $row)
		{
			if ($row->kargo_id!=$kargo_id)
			{
				$ii=$ii+1;
				$yeni_sira = $ii;
				$data=array('kargo_sira'=>$yeni_sira);
				$this->db->where('kargo_id',$row->kargo_id);
				$this->db->update('kargo', $data);
			}
		}
		$ii=$ii+1;
		$data=array('kargo_sira'=>$ii);
		$this->db->where('kargo_id',$kargo_id);
		$this->db->update('kargo', $data);
	}

	function kargo_ust($kargo_id)
	{
		$this->db->where('kargo_id', $kargo_id);
		$this->db->where('kargo_flag !=','0');
		$this->db->order_by('kargo_sira','ASC');
		$this->db->from('kargo');
		$query = $this->db->get();	
		$row = $query->row();
		
		$sira_ust = $row->kargo_sira-1;

		$ust_resim_id_q = $this->db->get_where('kargo',array('kargo_sira'=>$sira_ust,'kargo_flag !='=>'0'));
		$ust_resim_id = $ust_resim_id_q->row();

		if ($sira_ust<=0){$sira_ust=1;}
		$data = array('kargo_sira'=>$sira_ust);
		$this->db->where('kargo_flag !=','0');
		$this->db->where('kargo_id',$kargo_id);
		$this->db->update('kargo', $data);
		
		$data = array('kargo_sira'=>$row->kargo_sira);
		$this->db->where('kargo_flag !=','0');
		$this->db->where('kargo_id', $ust_resim_id->kargo_id);
		$this->db->update('kargo', $data);
	}

	function kargo_alt($kargo_id)
	{
		$this->db->where('kargo_id', $kargo_id);
		$this->db->where('kargo_flag !=','0');
		$this->db->order_by('kargo_sira','ASC');
		$this->db->from('kargo');
		$query = $this->db->get();	
		$row = $query->row();
		
		$sira_ust = $row->kargo_sira+1;
		$ust_resim_id_q = $this->db->get_where('kargo',array('kargo_sira'=>$sira_ust,'kargo_flag !='=>'0'));
		$ust_resim_id = $ust_resim_id_q->row();
		
		$data = array('kargo_sira'=>$row->kargo_sira);
		$this->db->where('kargo_flag !=','0');
		$this->db->where('kargo_id',$ust_resim_id->kargo_id);
		$this->db->update('kargo', $data);

		if ($ust_resim_id_q->num_rows()<=0){$sira_ust=$sira_ust-1;}
		$data=array('kargo_sira'=>$sira_ust);
		$this->db->where('kargo_flag !=','0');
		$this->db->where('kargo_id',$kargo_id);
		$this->db->update('kargo', $data);
	}
}