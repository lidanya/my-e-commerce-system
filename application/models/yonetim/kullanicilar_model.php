<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author Daynex.com.tr
 **/

class kullanicilar_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Kullanıcılar Model Yüklendi');
	}

	function musteri_grup_listele()
	{
		return $this->db->get_where('roles', array('parent_id' => '2'));
	}

	function musteri_kontrol($user_id)
	{
		$this->db->join('roles','roles.id=users.role_id','left');
		$sorgu = $this->db->get_where('users', array('users.id' => $user_id, 'roles.parent_id' => '2'), 1);
		if($sorgu->num_rows() > 0)
		{
			return true;
		} else {
			return false;
		}
	}

	function musteri_duzenle($gelen_degerler)
	{
		$user_id = $gelen_degerler->musteri_id;
		$role_id = $gelen_degerler->role_id;
		$musteri_bilgi = $this->musteri_bilgi($user_id);
		$dogum_tarih = 				$gelen_degerler->ide_dogtar. ' 00:00:00';
		// usr_ide_inf
			//tab_kimlik
			$usr_ide_inf_data = array(
				'ide_adi' 				=> $gelen_degerler->ide_adi,
				'ide_soy' 				=> $gelen_degerler->ide_soy,
				'ide_cins' 				=> $gelen_degerler->ide_cins,
				'ide_alternatif_mail' 	=> $gelen_degerler->ide_alternatif_mail,
				'ide_tckimlik' 			=> $gelen_degerler->ide_tckimlik,
				'ide_unv' 				=> $gelen_degerler->ide_unv,
				'ide_dogtar' 			=> mysql_to_unix($dogum_tarih),
				'ide_web_site' 			=> $gelen_degerler->ide_web_site,
				'ide_haber' 			=> $gelen_degerler->ide_haber,
				'ide_cep' 				=> $gelen_degerler->ide_cep
			);

			$this->db->where('user_id', $user_id);
			if($this->db->update('usr_ide_inf', $usr_ide_inf_data))
			{
				$usr_ide_inf_durum = true;
			} else {
				$usr_ide_inf_durum = false;
			}

		//usr_adr_inf
			//tab_iletisim
			$usr_adr_inf_data = array(
				'adr_is_tel1' 			=> $gelen_degerler->adr_is_tel1,
				'adr_is_fax' 			=> $gelen_degerler->adr_is_fax,
				'adr_is_tel2' 			=> $gelen_degerler->adr_is_tel2,
				'adr_is_ack' 			=> $gelen_degerler->adr_is_ack
			);

			$this->db->where('user_id', $user_id);
			if($this->db->update('usr_adr_inf', $usr_adr_inf_data))
			{
				$usr_adr_inf_durum = true;
			} else {
				$usr_adr_inf_durum = false;
			}

		//users
			//tab_guvenlik && tab_kimlik
			$users_data = array(
				'role_id' 				=> $gelen_degerler->role_id
			);

			if($musteri_bilgi->username != $gelen_degerler->email && $musteri_bilgi->email != $gelen_degerler->email)
			{
				$users_data['email'] 	= $gelen_degerler->email;
				$users_data['username'] = $gelen_degerler->email;
			}

			if($gelen_degerler->password && $gelen_degerler->confirm)
			{
				if(!empty($gelen_degerler->password) && !empty($gelen_degerler->confirm))
				{
					if($gelen_degerler->password == $gelen_degerler->confirm)
					{
						// Crypt and encode new password
						$yeni_sifre = crypt($this->dx_auth->_encode($gelen_degerler->password));
						$users_data['password'] = $yeni_sifre;
					}
				}
			}

			$this->db->where('id', $user_id);
			if($this->db->update('users', $users_data))
			{
				$users_data_durum = true;
			} else {
				$users_data_durum = false;
			}

		if($usr_ide_inf_durum == TRUE && $usr_adr_inf_durum == TRUE && $users_data_durum == TRUE)
		{
			return true;
		} else {
			return false;
		}
	}

	function musteri_inv_inf($user_id)
	{
		$sorgu = $this->db->get_where('usr_inv_inf', array('user_id' => $user_id));
		return $sorgu->result();
	}

	function musteri_inf($user_id)
	{
		$sorgu = $this->db->get_where('usr_ide_inf', array('user_id' => $user_id), 1);
		return $sorgu->row();
	}

	function musteri_sec_inf($user_id)
	{
		$sorgu = $this->db->get_where('usr_sec_inf', array('user_id' => $user_id), 1);
		return $sorgu->row();
	}

	function musteri_ilt_inf($user_id)
	{
		$sorgu = $this->db->get_where('usr_adr_inf', array('user_id' => $user_id), 1);
		return $sorgu->row();
	}

	function musteri_bilgi($user_id)
	{
		$sorgu = $this->db->get_where('users', array('id' => $user_id, 'role_id !=' => '2'), 1);
		return $sorgu->row();
	}

	function musteri_listesi($sort, $order, $filter = 'username|]', $page = 0, $sort_lnk)
	{
		$per_page = (config('site_ayar_urun_yonetim_sayfa')) ? config('site_ayar_urun_yonetim_sayfa'):20;
		
		if ($filter != 'username|]'){
			$filter_e = explode(']',$filter);
			for($i=0;$i<count($filter_e);$i++)
			{
				if ($filter_e[$i])
				{
					$filter_d = explode('|',$filter_e[$i]);
					$filter_field  = $filter_d[0];
					$filter_data   = $filter_d[1];
					if (($filter_field) and ($filter_data))
					{
						$this->db->like($filter_field, $filter_data);
					}
				}
			}
		}
		
		$this->db->select('users.*, usr_ide_inf.*, roles.*, users.id as user_id, roles.name as role_name');
		$this->db->join('usr_ide_inf', 'users.id = usr_ide_inf.user_id','left');
		$this->db->join('roles', 'users.role_id = roles.id','left');
		$this->db->where('roles.parent_id', '2');
		$this->db->order_by($sort, $order);
		$sorgu = $this->db->get('users', $per_page, $page);
		
		if ($filter != 'username|]'){
			$filter_e = explode(']',$filter);
			for($i=0;$i<count($filter_e);$i++)
			{
				if ($filter_e[$i])
				{
					$filter_d = explode('|',$filter_e[$i]);
					$filter_field  = $filter_d[0];
					$filter_data   = $filter_d[1];
					if (($filter_field) and ($filter_data))
					{
						$this->db->like($filter_field, $filter_data);
					}
				}
			}
		}
		
		$this->db->join('roles', 'users.role_id = roles.id','left');
		$this->db->where('roles.parent_id', '2');
		$sorgu_say = $this->db->get('users');
		$config['per_page'] = $per_page;
		$config['total_rows'] = $sorgu_say->num_rows();
		$config['full_tag_open'] = 'Sayfa : ';
		$config['full_tag_close'] = '';
		$config['num_links'] = 6;
		$config['base_url'] = base_url() . 'yonetim/sistem/kullanicilar/listele/' . $sort_lnk . '/' . $filter;
		$config['uri_segment'] = 7;
		
		$mevcut_sayfa = floor(($page / $per_page) + 1);
		
		if ($filter != 'username|]'){
			$filter_e = explode(']',$filter);
			for($i=0;$i<count($filter_e);$i++)
			{
				if ($filter_e[$i])
				{
					$filter_d = explode('|',$filter_e[$i]);
					$filter_field  = $filter_d[0];
					$filter_data   = $filter_d[1];
					if (($filter_field) and ($filter_data))
					{
						$this->db->like($filter_field, $filter_data);
					}
				}
			}
		}
		
		$this->db->join('roles', 'users.role_id = roles.id','left');
		$this->db->where('roles.parent_id', '2');
		$toplam_stok_sayisi = $this->db->count_all_results('users');
		$toplam_sayfa = ceil($toplam_stok_sayisi / $per_page);
		
		$config['full_tag_open'] = '<div class="pagination"><div class="links">';
		$config['full_tag_close'] = '</div><div class="results">
Toplam Sayfa '. $toplam_sayfa .' Mevcut Sayfa '. $mevcut_sayfa .' </div></div>';

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
}