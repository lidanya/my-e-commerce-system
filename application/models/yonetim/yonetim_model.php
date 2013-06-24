<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class yonetim_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function kullanici_bilgi_getir($user_id='')
	{
		if (!$user_id){ $user_id = $this->dx_auth->get_user_id(); }
		$this->db->join('users', 'users.id = usr_ide_inf.user_id', 'left');
		$this->db->join('usr_adr_inf', 'usr_adr_inf.user_id = usr_ide_inf.user_id', 'left');
		$this->db->where('usr_ide_inf.user_id', $user_id);
		$this->db->from('usr_ide_inf');
		$query = $this->db->get();
		$kullanici_kimlik = $query->row();
		return $kullanici_kimlik;
	}

	function kullanici_listele()
	{
		$this->db->join('users', 'users.id = usr_ide_inf.user_id', 'left');
		$this->db->where('usr_ide_inf.ide_adi !=', '');
		$this->db->from('usr_ide_inf');
		$query = $this->db->get();
		return $query;
	}

	function musgrup_listele($parent_id)
	{
		$this->db->where('parent_id', $parent_id);
		$this->db->from('roles');
		$query = $this->db->get();
		return $query;
	}

	function tanimlar_listele($tanim_tip, $siralama = array('tanimlar_id' => 'asc'))
	{
		foreach($siralama as $key => $value)
		{
			$this->db->order_by($key, $value);
		}
		$this->db->where('tanimlar_tip', $tanim_tip);
		$this->db->from('tanimlar');
		$query = $this->db->get();
		return $query;
	}

	function tanimlar_bilgi($tanim_tip, $tanim_id)
	{
		$query = $this->db->get_where('tanimlar', array('tanimlar_id' => $tanim_id, 'tanimlar_tip' => $tanim_tip), 1);
		return $query;
	}

	function kur_bilgi($kur_adi)
	{
		$this->db->where('kur_adi', $kur_adi);
		$this->db->from('kurlar');
		$query = $this->db->get();
		$row   = $query->row();
		return $row;
	}

	function uye_istatistikleri()
	{
		$bugun = date('d');
		$buhafta = date('W');
		$buay = date('m');		
		$this->db->where('ayar_adi','uye_bugun_'.$bugun);
		$query = $this->db->get('ayarlar');		
		return($query);
	}

	function kullanici_takip()
	{
		$this->db->limit(5);
		$this->db->join('e_user_istatistik d1','d1.session_id = e_sessions.session_id','left');
		$this->db->join('e_usr_ide_inf d2','d2.user_id = d1.session_uid','left outer');
		$this->db->join('e_users d3','d3.id = d2.user_id','left');
		$this->db->group_by('e_sessions.session_id');
		$this->db->where('d3.role_id <> 2');
		$query = $this->db->get('sessions');		
		return($query);
	}

	function son_uye()
	{
		$this->db->select('users.*, usr_ide_inf.*');
		$this->db->from('usr_ide_inf');
		$this->db->join('users','users.id=usr_ide_inf.user_id');
		$this->db->where('e_usr_ide_inf.user_id=(select max(e_users.id) from e_users)');
		$query = $this->db->get();
		$row   = $query->row();
		return $row;
	}

	function son_siparisler()
	{
		$this->db->select('siparis.*, siparis_detay.*, usr_ide_inf.*');
		$this->db->from('siparis');
		$this->db->join('siparis_detay', 'siparis_detay.siparis_id=siparis.siparis_id', 'left');
		$this->db->join('usr_ide_inf', 'usr_ide_inf.user_id=siparis.user_id', 'left');
		$this->db->order_by('siparis.kayit_tar','DESC');
		$this->db->limit(5);
		$query = $this->db->get();
		return $query;
	}

	function hizmet_takip($tip)
	{
		if ($tip=='adw'){
			$this->db->where('adw_flag', '1');
			$this->db->from('siparis_adw');
			$sorgu_say =  $this->db->get();
		} else if ($tip=='seo'){
			$this->db->where('seo_flag', '1');
			$this->db->from('siparis_seo');
			$sorgu_say =  $this->db->get();
		} else if ($tip=='website'){
			$this->db->where('web_flag', '1');
			$this->db->from('siparis_website');
			$sorgu_say =  $this->db->get();
		}
		return $sorgu_say->num_rows();
	}

	function ticket($tip)
	{
		if ($tip=='cevap_bekleyen'){
			$sorgu_say = $this->db->query('SELECT * FROM e_ticket WHERE ticket_id 
			NOT IN(SELECT ticket_prm_id FROM e_ticket WHERE ticket_prm_id <> 0) and ticket_tip <> "kapat" and ticket_prm_id = 0');
		} else if ($tip=='arsiv'){
			$this->db->where(array('ticket_tip'=>'kapat','ticket_flag'=>2));
			$sorgu_say = $this->db->get_where('ticket');
		} else if ($tip=='acik'){
			$this->db->where(array('ticket_prm_id'=>'0','ticket_flag'=>1));
			$sorgu_say = $this->db->get_where('ticket');
		} else if ($tip=='cevaplanmis'){
			$this->db->select('*');
			$this->db->from('ticket as tc');
			$this->db->join('ticket as tc1', 'tc.ticket_prm_id = tc1.ticket_id','inner');
			$this->db->join('usr_ide_inf', 'tc1.user_id= usr_ide_inf.user_id');
			$this->db->where('tc1.ticket_tip <>','kapat');
			$this->db->group_by('tc1.ticket_id');
			$sorgu_say =  $this->db->get();
		}
		return $sorgu_say->num_rows();
	}

	function uye_istatistik()
	{
		$zaman[1] = date('d');
		$zaman[2] = date('W');
		$zaman[3] = date('m');
		$zaman[4] = date('Y');
		
		$uye['uye_fields_1']	= 'uye_bugun_';
		$uye['uye_fields_2']	= 'uye_buhafta_';
		$uye['uye_fields_3']	= 'uye_buay_';
		$uye['uye_fields_4']	= 'uye_buyil_';		
		
		for($i=1; $i<=4; $i++)
		{
			$this->db->where('ayar_adi',$uye['uye_fields_'.$i].$zaman[$i]);
			$query = $this->db->get('ayarlar');
			
			$istatistik[$i] = $query->row($uye['uye_fields_'.$i]);
		}
		return($istatistik);
	}
}