<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class cagri_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', '�a�r� Model Y�klendi');

		$this->load->library('pagination');
		$this->load->library('daynex_pagination');
	}

	function cagri_yaz()
	{
		if ($this->input->post('ticket_prm')){$prm_id = $this->input->post('ticket_prm');}else{$prm_id=0;}
		
		if($prm_id != 0)
		{
			$this->db->where('ticket_id',$prm_id);
			$ticket_tip =  $this->input->post('ticket_tip');
			if($ticket_tip == 'kapat')
			{
				$ticket_tip = 'soru';
				$this->db->update('ticket',array('ticket_tip'=>'soru','ticket_flag'=>'2','ticket_adm_durum'=>1));
			} else {
				$this->db->update('ticket',array('ticket_tip'=>'soru','ticket_adm_durum'=>1));
			}
			
		}	

		$data = array (
			'ticket_konu'	=> $this->input->post('ticket_konu'),
			'ticket_icerik'	=> nl2br($this->input->post('ticket_mesaj')),
			'ticket_tip'	=> $ticket_tip,
			'ticket_prm_id'	=> $prm_id,
			'ticket_uye_durum'	=> 2,
			'ticket_adm_durum'	=> 1,
			'ticket_tarih'	=> time(),
			'user_id'		=> $this->dx_auth->get_user_id(),
			'ticket_flag'	=> 1
		);
		$this->db->insert('ticket', $data); 
		$son_ticket_id = $this->db->insert_id();	
		
		$gelen_tarih = standard_date('DATE_TR5', time(), 'tr');
		$gelen_ay = substr($gelen_tarih, 0, 2);
		$gelen_gun = substr($gelen_tarih, 3, 2);
		$gelen_yil = substr($gelen_tarih, 8, 2);
		
		$ticket_kodu_basla = $gelen_gun.$gelen_ay.$gelen_yil.'-';
		
		$this->db->like('ticket_kodu', $ticket_kodu_basla);
		$this->db->where('ticket_prm_id', 0);
		$this->db->from('ticket');
		$ticket_query = $this->db->get();
		$ticket_row = $ticket_query->row();

		if (($prm_id==0)){
			if ($ticket_query->num_rows>0){
				$son_no_y  = $ticket_query->num_rows + 1;
				$kachane   = strlen($son_no_y);
				if ($kachane==1){$son_no_y='000'.$son_no_y;} else if ($kachane==2){$son_no_y='00'.$son_no_y;} else if ($kachane==3){$son_no_y='0'.$son_no_y;}
				$ticket_no = $ticket_kodu_basla.$son_no_y;
			} else {
				$son_no_y = '0001';
				$ticket_no = $ticket_kodu_basla.$son_no_y;
			}
		} else { $ticket_no="threat"; }
		
		$data = array ( 'ticket_kodu'	=> $ticket_no );
		$this->db->where('ticket_id', $son_ticket_id);
		$this->db->update('ticket', $data);
	}

	function cagri_durum($ticket)
	{
		$this->db->update('ticket',array('ticket_flag'=>2), array('ticket_id'=>$ticket, 'user_id'=>$this->dx_auth->get_user_id()));
	}

	function cagri_goster($ticket)
	{
		
		$this->db->where('user_id',$this->dx_auth->get_user_id());
		$this->db->where('ticket_id',$ticket);
		$sorgu = $this->db->get('ticket');
		
		// Update
		$update = array('ticket_uye_durum'=>2);
		$this->db->where('ticket_prm_id',$ticket);
		$this->db->update('ticket',$update);
		return $sorgu;
	}

	function cagri_liste($sayfa = 0)
	{
		$per_page = (config('site_ayar_urun_site_sayfa')) ? config('site_ayar_urun_site_sayfa') : 9;
		$this->db->order_by('ticket_tarih', 'desc');
		$this->db->where('user_id',$this->dx_auth->get_user_id());
		$this->db->where('ticket_prm_id',0);
		$this->db->limit($per_page, $sayfa);
		$sorgu = $this->db->get('ticket');

		$this->db->where('user_id',$this->dx_auth->get_user_id());
		$this->db->where('ticket_prm_id',0);
		$sorgu_say = $this->db->get('ticket');
		
		$config['per_page'] = $per_page;
		$config['total_rows'] = $sorgu_say->num_rows();
		$config['full_tag_open'] = 'Sayfa : ';
		$config['full_tag_close'] = '';
		$config['num_links'] = 6;
		$config['base_url'] = site_url('uye/cagri');
		$config['uri_segment'] = 4;

		$config['full_tag_open'] = '<div class="liste_sag saga"><ul>';
		$config['full_tag_close'] = '</ul></div>';

		$config['first_link'] = '<img src="'. site_resim() .'liste_bas.png" alt="" style="margin:3px 0;" />';
		$config['first_tag_open'] = '<li><span>';
		$config['first_tag_close'] = '</span></li>';
		$config['first_a_class'] = 'class="buton"';

		$config['last_link'] = '<img src="'. site_resim() .'liste_son.png" alt="" style="margin:3px 0;" />';
		$config['last_tag_open'] = '<li><span>';
		$config['last_tag_close'] = '</span></li>';
		$config['last_a_class'] = 'class="buton"';

		$config['next_link'] = '<img src="'. site_resim() .'liste_ileri.png" alt="" style="margin:3px 0;" />';
		$config['next_tag_open'] = '<li><span>';
		$config['next_tag_close'] = '</span></li>';
		$config['next_a_class'] = 'class="buton"';

		$config['prev_link'] = '<img src="'. site_resim() .'liste_geri.png" alt="" style="margin:3px 0;" />';
		$config['prev_tag_open'] = '<li><span>';
		$config['prev_tag_close'] = '</span></li>';
		$config['prev_a_class']	= 'class="buton"';

		$config['cur_tag_open'] = '<li class="l_aktif">';
		$config['cur_tag_close'] = '</li>';
		$config['cur_a_class'] = 'class="l_sayfa"';

		$config['num_tag_open'] = '<li><span>';
		$config['num_tag_close'] = '</span></li>';
		$config['num_a_class'] = 'class="l_sayfa"';

		$this->daynex_pagination->initialize($config);
		return $sorgu;
	}
}