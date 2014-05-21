<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class site_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Site Model Yüklendi');
	}

	function random_key($kac_karakter = '6')
	{
		$pool = '123456789abcdefghijklmnopqrstuvwxyz';

		$str = '';
		for ($i = 0; $i < $kac_karakter; $i++)
		{
			$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
		}

		return $str;
	}

	function ticket_ekle()
	{
		$kontrol_data['uye_kontrol']=false;
		$kontrol_data['kontrol']=false;
		
		$this->db->where('username',$this->input->post('eposta') );
		$this->db->or_where('email',$this->input->post('eposta') );
		$user_kontrol = $this->db->get('users');
		

		if($user_kontrol->num_rows() > 0)
		{

			$this->dx_auth->login($this->input->post('eposta'), $this->input->post('uye_password'));
			
			if ( ! $this->dx_auth->is_logged_in())
			{
				$kontrol_data['uye_kontrol']=false;
				$user_bilgi = $user_kontrol->row();
				$user_bilgi_r = $this->kullanici_adi_getir($user_id=$user_bilgi->id);
								
				$kontrol_data['uye_adi']=$user_bilgi_r->ide_adi.' '.$user_bilgi_r->ide_soy;
				
			} else {
				$kontrol_data['uye_kontrol'] = TRUE;
				
				$user_bilgi = $user_kontrol->row();
				
				if ($this->input->post('ticket_prm')){$prm_id = $this->input->post('ticket_prm');}else{$prm_id=0;}
				$data = array (
					'ticket_konu'	=> $this->input->post('ticket_konu'),
					'ticket_icerik'	=> nl2br($this->input->post('ticket_mesaj')),
					'ticket_tip'	=> $this->input->post('ticket_tip'),
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
				
				if ($this->input->post('ticket_tip')=="kapat"){
					$data = array ( 'ticket_flag'	=> '2' );
					$this->db->where('ticket_id', $prm_id);
					$this->db->update('ticket', $data); 
	
					$this->db->where('ticket_id', $prm_id);
					$this->db->from('ticket');
					$ticket_query1 = $this->db->get();
					$ticket_row1 = $ticket_query1->row();	
					
					$data = array ( 'ticket_flag'	=> '2' );
					$this->db->where('ticket_id', $ticket_row1->ticket_prm_id);
					$this->db->update('ticket', $data); 	
				}

				$kontrol_data['kontrol'] = true;

				// Create email
				$from = $this->config->item('site_ayar_email_cevapsiz');
				$subject = 'Ticket';
				$mail_data['ad'] 	= user_ide_inf($this->dx_auth->get_user_id())->row()->ide_adi;
				$mail_data['soyad'] 	= user_ide_inf($this->dx_auth->get_user_id())->row()->ide_soy;
				$mail_data['ticket_kodu'] 	= $ticket_no;
				$message = $this->load->view(tema() . 'mail_sablon/ticket/ticket_gonder', $mail_data, true);

				// Send email with account details
				$this->dx_auth->_email($this->dx_auth->get_username(), $from, $subject, $message);	

				$admin_mail = $this->config->item('site_ayar_email_admin');
				$this->dx_auth->_email($admin_mail, $this->config->item('site_ayar_email_admin'), $subject, $message);
			}
		}  else {
			mt_srand();
			$pass = md5(uniqid(mt_rand()));	
			$result = $this->dx_auth->register($this->input->post('eposta'), $pass, $this->input->post('eposta'), false, '1');
			
			$sonid = $result['sonid'];

			$data = array (
				'ticket_konu'		=> $this->input->post('ticket_konu'),
				'ticket_icerik'		=> nl2br($this->input->post('ticket_mesaj')),
				'ticket_tip'		=> 'soru',
				'ticket_prm_id'		=> 0,
				'ticket_uye_durum'	=> 2,
				'ticket_adm_durum'	=> 1,
				'ticket_tarih'		=> time(),
				'user_id'			=> $sonid,
				'ticket_flag'		=> 1
			);
			$this->db->insert('ticket', $data); 
			$son_ticket_id = $this->db->insert_id();
			
			if ($this->input->post('ticket_prm')){$prm_id = $this->input->post('ticket_prm');}else{$prm_id=0;}
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
				
			
$exp = explode(' ', $this->input->post('TxtAdSoyad'));
$exp_say = count($exp);
if ($exp_say>1)
{
	$soyisim_yer = $exp_say - 1;
	$soyisim = $exp[$soyisim_yer];
	for ($isi=0;$isi<$soyisim_yer-1;$isi++)
	{
		$isim = $isim . $exp[$isi];
	}
} else {
	$isim = $exp[0];
	$soyisim = "";
}
			
			$data = array(
				'ide_adi' 	=> $isim,
				'ide_soy'	=> $soyisim,
				'ide_flag'	=> '1',
				'ide_unv' 	=> 'Auto User'
			);
			$this->db->where('user_id', $sonid);
			$this->db->update('usr_ide_inf', $data);

			$kontrol_data['uye_kontrol'] = TRUE;
			$kontrol_data['kontrol'] = TRUE;
		}
		/*
		//mail gönder - basla		
		$from 							= config('site_ayar_email_cevapsiz');
		$subject 						= 'Ticketınız başarıyla alınmıştır.';
		$user_inf 						= $this->siteModel->kullanici_adi_getir();
		$ticket_q 						= $this->siteModel->ticket_detay_id_listele($son_ticket_id);
		$mail_data['ticket_row']		= $ticket_q->row();
		$mail_data['ad'] 				= $user_inf->ide_adi;
		$mail_data['soyad'] 			= $user_inf->ide_soy;
		$mail_data['basvuru_tip'] 		= 'ticket';
		$mail_data['siparis_tarih']		= standard_date('DATE_TR1', time(), 'tr');
		$message = $this->load->view(tema() . 'mail_sablon/ticket/ticket_gonder', $mail_data, true);
		$this->dx_auth->_email($user_inf->email, $from, $subject, $message);
		$admin_mail = config('site_ayar_email_admin');
		$this->dx_auth->_email($admin_mail,$user_inf->email, $subject, $message);
		//mail gönder - bitir
		*/
		return $kontrol_data;
	}

	function kampanya_hesapla($stok_id)
	{
		$this->db->distinct();
		$this->db->from('stok_indirim_kampanya sik');
		$this->db->where('sik.indirim_basla < UNIX_TIMESTAMP()');
		$this->db->where('sik.indirim_bitir > UNIX_TIMESTAMP()');
		$this->db->where('sik.stok_id', $stok_id);
		$this->db->where('sik.indirim_flag', '1');
		$this->db->where('sik.indirim_tip', 'kampanya');
		if(!$this->dx_auth->is_role('admin-gruplari')) {
			if($this->dx_auth->is_logged_in()) {
				$this->db->where('sik.indirim_musteri_grubu', $this->dx_auth->get_role_id());
			}
		}
		$this->db->order_by('sik.indirim_oncelik', 'asc');
		$this->db->limit(1);
		$sorgu = $this->db->get();
		return $sorgu;
	}

	function indirim_hesapla($stok_id)
	{
		$this->db->distinct();
		$this->db->from('stok_indirim_kampanya sik');
		$this->db->where('sik.indirim_basla < UNIX_TIMESTAMP()');
		$this->db->where('sik.indirim_bitir > UNIX_TIMESTAMP()');
		$this->db->where('sik.stok_id', $stok_id);
		$this->db->where('sik.indirim_flag', '1');
		$this->db->where('sik.indirim_tip', 'indirim');
		if(!$this->dx_auth->is_role('admin-gruplari')) {
			if($this->dx_auth->is_logged_in()) {
				$this->db->where('sik.indirim_musteri_grubu', $this->dx_auth->get_role_id());
			}
		}
		$this->db->order_by('sik.indirim_oncelik', 'asc');
		$this->db->limit(1);
		$sorgu = $this->db->get();
		return $sorgu;
	}
}