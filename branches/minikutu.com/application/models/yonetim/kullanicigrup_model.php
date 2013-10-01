<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class kullanicigrup_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Müşteri Grubu Model Yüklendi');
	}

	function musteri_grup_listele($page = 0)
	{
		$per_page = 20;
		
		$this->db->where('parent_id','2');
		$sorgu = $this->db->get('roles', $per_page, $page);
		
		$sorgu_say = $this->db->get_where('roles', array('parent_id' => '2'));
		$config['per_page'] = $per_page;
		$config['total_rows'] = $sorgu_say->num_rows();
		$config['full_tag_open'] = 'Sayfa : ';
		$config['full_tag_close'] = '';
		$config['num_links'] = 4;
		$config['base_url'] = base_url() . 'yonetim/sistem/kullanici_grup/listele';
		$config['uri_segment'] = 5;
		
		$mevcut_sayfa = floor(($page / $per_page) + 1);
		
		$this->db->where('parent_id', '2');
		$toplam_stok_sayisi = $this->db->count_all_results('roles');
		$toplam_sayfa = ceil($toplam_stok_sayisi / $per_page);
		
		$config['full_tag_open'] = '<div class="pagination"><div class="links">';
		$config['full_tag_close'] = '</div><div class="results"> Toplam Sayfa '. $toplam_sayfa .' Mevcut Sayfa '. $mevcut_sayfa .' </div></div>';
		
		$config['first_link'] = '|&lt;';
		$config['first_tag_open'] = '';
		$config['first_tag_close'] = '';
		
		$config['last_link'] = '&gt;|';
		$config['last_tag_open'] = '';
		$config['last_tag_close'] = '';
		
		$config['next_link'] = '&gt;';
		$config['next_tag_open'] = '';
		$config['next_tag_close'] = '';
		
		$config['prev_link'] = '&lt;';
		$config['prev_tag_open'] = '';
		$config['prev_tag_close'] = '';
		
		$config['cur_tag_open'] = '<b>';
		$config['cur_tag_close'] = '</b>';
		
		$config['num_tag_open'] = '';
		$config['num_tag_close'] = '';
		
		$this->pagination->initialize($config);
		return $sorgu;	
	}

	function musteri_grup_ekle($val)
	{
		$data = array(
			'name'      	=> $val->name,
			'fiyat_orani'	=> $val->price,
			'parent_id'		=> '2'
		);
		$kontrol = $this->db->insert('roles', $data); 
		$son_yetki_id = $this->db->insert_id();
		
		if (($val->permission_add)){
			for ($i=0;$i<count($val->permission_add);$i++)
			{
				$data = array(
					'yetki_menu_menu_id' => $val->permission_add[$i],
					'yetki_menu_user_id' => $son_yetki_id,
					'yetki_menu_yetki'	 => '1'
				);
				$kontrol_per_add = $this->db->insert('yetki_user',$data); 
			}
		}
		if ($val->permission_list){
			for ($i=0;$i<count($val->permission_list);$i++)
			{
				$data = array(
					'yetki_menu_menu_id' => $val->permission_list[$i],
					'yetki_menu_user_id' => $son_yetki_id,
					'yetki_menu_yetki'	 => '1'
				);
				$kontrol_per_add = $this->db->insert('yetki_user',$data); 
			}
		}
		if ($val->permission_edit){
			for ($i=0;$i<count($val->permission_edit);$i++)
			{
				$data = array(
					'yetki_menu_menu_id' => $val->permission_edit[$i],
					'yetki_menu_user_id' => $son_yetki_id,
					'yetki_menu_yetki'	 => '1'
				);
				$kontrol_per_add = $this->db->insert('yetki_user',$data); 
			}
		}
		if ($val->permission_delete){
			for ($i=0;$i<count($val->permission_delete);$i++)
			{
				$data = array(
					'yetki_menu_menu_id' => $val->permission_delete[$i],
					'yetki_menu_user_id' => $son_yetki_id,
					'yetki_menu_yetki'	 => '1'
				);
				$kontrol_per_add = $this->db->insert('yetki_user',$data); 
			}
		}
		if ($val->permission_run){
			for ($i=0;$i<count($val->permission_run);$i++)
			{
				$data = array(
					'yetki_menu_menu_id' => $val->permission_run[$i],
					'yetki_menu_user_id' => $son_yetki_id,
					'yetki_menu_yetki'	 => '1'
				);
				$kontrol_per_add = $this->db->insert('yetki_user',$data); 
			}
		}
		if ($kontrol){$kontrol_data = true;}else{$kontrol_data = false;}
		return $kontrol_data;
	}

	function musteri_grup_duzenle($val)
	{
		$data = array(
			'name'      	=> $val->name,
			'fiyat_orani'	=> $val->price,
			'parent_id'		=> '2'
		);
		$this->db->where('id', $val->grup_id);
		$kontrol = $this->db->update('roles', $data); 
		
		
		$this->db->where('yetki_menu_user_id', $val->grup_id);
		$this->db->delete('yetki_user');
		
		if (($val->permission_add)){
			for ($i=0;$i<count($val->permission_add);$i++)
			{
				$data = array(
					'yetki_menu_menu_id' => $val->permission_add[$i],
					'yetki_menu_user_id' => $val->grup_id,
					'yetki_menu_yetki'	 => '1'
				);
				$kontrol_per_add = $this->db->insert('yetki_user',$data); 
			}
		}
		if ($val->permission_list){
			for ($i=0;$i<count($val->permission_list);$i++)
			{
				$data = array(
					'yetki_menu_menu_id' => $val->permission_list[$i],
					'yetki_menu_user_id' => $val->grup_id,
					'yetki_menu_yetki'	 => '1'
				);
				$kontrol_per_add = $this->db->insert('yetki_user',$data); 
			}
		}
		if ($val->permission_edit){
			for ($i=0;$i<count($val->permission_edit);$i++)
			{
				$data = array(
					'yetki_menu_menu_id' => $val->permission_edit[$i],
					'yetki_menu_user_id' => $val->grup_id,
					'yetki_menu_yetki'	 => '1'
				);
				$kontrol_per_add = $this->db->insert('yetki_user',$data); 
			}
		}
		if ($val->permission_delete){
			for ($i=0;$i<count($val->permission_delete);$i++)
			{
				$data = array(
					'yetki_menu_menu_id' => $val->permission_delete[$i],
					'yetki_menu_user_id' => $val->grup_id,
					'yetki_menu_yetki'	 => '1'
				);
				$kontrol_per_add = $this->db->insert('yetki_user',$data); 
			}
		}
		if ($val->permission_run){
			for ($i=0;$i<count($val->permission_run);$i++)
			{
				$data = array(
					'yetki_menu_menu_id' => $val->permission_run[$i],
					'yetki_menu_user_id' => $val->grup_id,
					'yetki_menu_yetki'	 => '1'
				);
				$kontrol_per_add = $this->db->insert('yetki_user',$data); 
			}
		}
		if ($kontrol){$kontrol_data = true;}else{$kontrol_data = false;}
		return $kontrol_data;
	}

	function musteri_grup_sil($val)
	{
		for ($i=0;$i<count($val->selected);$i++)
		{
			$varyok = $this->db->get_where('users', array('role_id' => $val->selected[$i]));			
			if($varyok->num_rows() <= 0){	
				$this->db->where('id', $val->selected[$i]);
				$kontrol_sil=$this->db->delete('roles'); 
				if ($kontrol_sil){$kontrol_data = true;}
			}
			
		}
		return $kontrol_data;
	}

	function musteri_grup_veri($group_id)
	{
		$this->db->where('roles.parent_id','2');	
		$this->db->where('roles.id', $group_id);
		$this->db->from('roles');
		$query = $this->db->get();
		$row = $query->row();
		return $row;		
	}

	function yetki_listele($tip)
	{
		$this->db->from('yetki');
		$this->db->where('yetki_tip', $tip);
		$this->db->order_by('yetki_adi','DESC');
		$query = $this->db->get();
		return $query;		
	}

	function grup_yetki_listele($group_id, $tip)
	{
		$this->db->from('yetki_user');
		$this->db->join('yetki','yetki.yetki_id = yetki_user.yetki_menu_menu_id','left');
		$this->db->where('yetki.yetki_tip', $tip);
		$this->db->where('yetki_user.yetki_menu_user_id', $group_id);
		$query = $this->db->get();
		return $query;		
	}
}