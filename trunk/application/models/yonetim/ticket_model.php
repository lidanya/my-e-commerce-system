<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class ticket_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function ticket_getir( $where = 0 , $where_field = "",$where_not_in = 0)
	{
		if($where != 0){
			$this->db->where($where);
		}
		if($where_not_in != 0)
		{
			$this->db->where_not_in($where_field,$where_not_in);
		}
		return $this->db->get('ticket');
	}

	function cevaplanmis_ticket($sort_lnk, $filter, $page)
	{
		$per_page = (config('site_ayar_urun_yonetim_sayfa')) ? config('site_ayar_urun_yonetim_sayfa') :20;

		$sort_lnk_e = explode('_',$sort_lnk);
		$sort  = (!empty($sort_lnk_e[0])) ? $sort_lnk_e[0] : 'ticket';
		$order = (!empty($sort_lnk_e[1])) ? $sort_lnk_e[1] : 'desc';
		
		$this->db->select('*');
		$this->db->from('ticket as tc');
		$this->db->join('ticket as tc1', 'tc.ticket_prm_id = tc1.ticket_id','inner');
		$this->db->join('usr_ide_inf', 'tc1.user_id= usr_ide_inf.user_id');
		$this->db->where('tc1.ticket_tip <>','kapat');
		$this->db->limit($per_page,$page);

		// Sayfadaki KAyıt
		if ($filter != 'kimden|]')
		{
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

						if($filter_field == 'kimden')
						{
							$this->db->like('usr_ide_inf.ide_adi', $filter_data);
							$this->db->or_like('usr_ide_inf.ide_soy', $filter_data);
						}

						if($filter_field == 'konu')
						{
							$filter_field = 'tc.ticket_konu';
							$this->db->like($filter_field, $filter_data);
						}
						
						if($filter_field == 'bolum')
						{
							$filter_field = 'tc.ticket_ilgili_bolum';
							$this->db->like($filter_field, $filter_data);
						}

						if($filter_field == 'tarih')
						{
							$filter_field = 'tc.ticket_tarih';
							if($filter_data != '')
							{
								$tarih 		 = explode('-',$filter_data);
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2],$tarih[0]);
								$this->db->where($filter_field.' >=' ,$filter_data);
								
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2]+1,$tarih[0]);
								$this->db->where($filter_field.' <' ,$filter_data);
							}
						}

						if($filter_field == 'durum')
						{
							if($filter_data != 'T'){
								$filter_field = 'tc1.ticket_adm_durum';
								$this->db->like($filter_field, $filter_data);
							}
						}
						
					}
				}
			}
		}
		
		if($filter == ''){
			$filter='ticket_asc/durum|T]';
		}

		$this->db->group_by('tc1.ticket_id');
		$this->db->order_by('tc1.ticket_id',$order);
		$sorgu = $this->db->get();
		
		
		$this->db->select('*');
		$this->db->from('ticket as tc');
		$this->db->join('ticket as tc1', 'tc.ticket_prm_id = tc1.ticket_id','inner');
		$this->db->join('usr_ide_inf', 'tc1.user_id= usr_ide_inf.user_id');
		$this->db->where('tc1.ticket_tip <>','kapat');
		// Toplam  Kayıt Sayısı
		if ($filter != 'kimden|]')
		{
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

						if($filter_field == 'kimden')
						{
							$this->db->like('usr_ide_inf.ide_adi', $filter_data);
							$this->db->or_like('usr_ide_inf.ide_soy', $filter_data);
						}

						if($filter_field == 'konu')
						{
							$filter_field = 'tc.ticket_konu';
							$this->db->like($filter_field, $filter_data);
						}
						
						if($filter_field == 'bolum')
						{
							$filter_field = 'tc.ticket_ilgili_bolum';
							$this->db->like($filter_field, $filter_data);
						}

						if($filter_field == 'tarih')
						{
							$filter_field = 'tc.ticket_tarih';
							if($filter_data != '')
							{
								$tarih 		 = explode('-',$filter_data);
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2],$tarih[0]);
								$this->db->where($filter_field.' >=' ,$filter_data);
								
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2]+1,$tarih[0]);
								$this->db->where($filter_field.' <' ,$filter_data);
							}
						}

						if($filter_field == 'durum')
						{
							if($filter_data != 'T'){
								$filter_field = 'tc1.ticket_adm_durum';
								$this->db->like($filter_field, $filter_data);
							}
						}
						
					}
				}
			}
		}
		
		if($filter == ''){
			$filter='ticket_asc/durum|T]';
		}
		
		$this->db->group_by('tc1.ticket_id');
		$sorgu_say =  $this->db->get();
		$config['per_page'] = $per_page;
		$config['total_rows'] 		= $sorgu_say->num_rows();
		$config['full_tag_open'] 	= 'Sayfa : ';
		$config['full_tag_close'] 	= '';
		$config['num_links'] = 6;
		$config['base_url'] = base_url() . 'yonetim/ticket/cevaplanmis/listele/'. $sort_lnk .'/'. $filter;
		$config['uri_segment'] = 7;
		
		$mevcut_sayfa = floor(($page / $per_page) + 1);
		
		$toplam_stok_sayisi = $sorgu_say->num_rows();
		$toplam_sayfa = ceil($toplam_stok_sayisi / $per_page);

		$config['full_tag_open'] = '<div class="pagination"><div class="links">';
		$config['full_tag_close'] = '</div><div class="results">Toplam Sayfa '. $toplam_sayfa .' Mevcut Sayfa '. $mevcut_sayfa .' </div></div>';

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

	function cevap_bekleyen_ticket($sort_lnk, $filter, $page)
	{
		$per_page = (config('site_ayar_urun_yonetim_sayfa')) ? config('site_ayar_urun_yonetim_sayfa') :20;
		
		$sort_lnk_e = explode('_',$sort_lnk);
		$sort  = (!empty($sort_lnk_e[0])) ? $sort_lnk_e[0] : 'ticket';
		$order = (!empty($sort_lnk_e[1])) ? $sort_lnk_e[1] : 'desc';
		$where = '';
		// Sayfadaki KAyıt
		if ($filter != 'kimden|]')
		{
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

						if($filter_field == 'kimden')
						{
							$where .= " and e_usr_ide_inf.ide_adi like '%$filter_data%' or e_usr_ide_inf.ide_soy like '%$filter_data%'";						
						}

						if($filter_field == 'konu')
						{
							$where .= " and e_ticket.ticket_konu like '%$filter_data%'";
						}
						

						if($filter_field == 'tarih')
						{
							$filter_field = 'ticket_tarih';
							if($filter_data != '')
							{
								$tarih 		 = explode('-',$filter_data);
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2],$tarih[0]);
								$where .= " and e_ticket.ticket_tarih >= $filter_data";
								
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2]+1,$tarih[0]);
								$where .= " and e_ticket.ticket_tarih < $filter_data";
							}
						}

						if($filter_field == 'durum')
						{
							if($filter_data != 'T'){
								$where .= " and e_ticket.ticket_adm_durum = $filter_data";
							}
						}
						
					}
				}
			}
		}
		
		if($filter == ''){
			$filter='ticket_asc/durum|T]';
		}

		$page = ($page == '') ? 0 : $page;
		$sorgu = $this->db->query('SELECT * FROM e_ticket 
		JOIN e_usr_ide_inf ON e_usr_ide_inf.user_id = e_ticket.user_id 
		WHERE ticket_id 
		NOT IN(SELECT ticket_prm_id FROM e_ticket WHERE ticket_prm_id <> 0) and ticket_tip <> "kapat" and ticket_prm_id = 0  '. $where .'  Order By e_ticket.ticket_id '.$order.', e_ticket.ticket_tarih desc, e_ticket.ticket_flag asc Limit '.$page.' ,'.$per_page);

		// Sayfadaki KAyıt
		if ($filter != 'kimden|]')
		{
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

						if($filter_field == 'kimden')
						{
							$where .= " and e_usr_ide_inf.ide_adi like %$filter_data% or e_usr_ide_inf.ide_soy like %$filter_data%";						
						}

						if($filter_field == 'konu')
						{
							$where .= " and e_ticket.ticket_konu like %$filter_data%";
						}
						

						if($filter_field == 'tarih')
						{
							$filter_field = 'ticket_tarih';
							if($filter_data != '')
							{
								$tarih 		 = explode('-',$filter_data);
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2],$tarih[0]);
								$where .= " and e_ticket.ticket_tarih >= $filter_data";
								
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2]+1,$tarih[0]);
								$where .= " and e_ticket.ticket_tarih < $filter_data";
							}
						}

						if($filter_field == 'durum')
						{
							if($filter_data != 'T'){
								$where .= " and e_ticket.ticket_adm_durum = $filter_data";
							}
						}
						
					}
				}
			}
		}

		if($filter == ''){
			$filter='ticket_asc/durum|T]';
		}

		$sorgu_say = $this->db->query('SELECT * FROM e_ticket WHERE ticket_id 
		NOT IN(SELECT ticket_prm_id FROM e_ticket WHERE ticket_prm_id <> 0) and ticket_tip <> "kapat" and ticket_prm_id = 0');
	
		$config['per_page'] = $per_page;
		$config['total_rows'] 		= $sorgu_say->num_rows();
		$config['full_tag_open'] 	= 'Sayfa : ';
		$config['full_tag_close'] 	= '';
		$config['num_links'] = 6;
		$config['base_url'] = base_url() . 'yonetim/ticket/cevapbekleyen/listele/'. $sort_lnk .'/'. $filter;
		$config['uri_segment'] = 7;
		
		$mevcut_sayfa = floor(($page / $per_page) + 1);
		
		$toplam_stok_sayisi = $sorgu_say->num_rows();
		$toplam_sayfa = ceil($toplam_stok_sayisi / $per_page);

		$config['full_tag_open'] = '<div class="pagination"><div class="links">';
		$config['full_tag_close'] = '</div><div class="results">Toplam Sayfa '. $toplam_sayfa .' Mevcut Sayfa '. $mevcut_sayfa .' </div></div>';

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

	function arsivdeki_ticketlar($sort_lnk, $filter, $page)
	{
		$this->output->enable_profiler(FALSE);
		
		$per_page = (config('site_ayar_urun_yonetim_sayfa')) ? config('site_ayar_urun_yonetim_sayfa') :20;
		
		$sort_lnk_e = explode('_',$sort_lnk);
		$sort  = (!empty($sort_lnk_e[0])) ? $sort_lnk_e[0] : 'ticket';
		$order = (!empty($sort_lnk_e[1])) ? $sort_lnk_e[1] : 'desc';
		
		// Sayfadaki KAyıt
		if ($filter != 'kimden|]')
		{
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

						if($filter_field == 'kimden')
						{
							$this->db->like('ide_adi', $filter_data);
							$this->db->or_like('ide_soy', $filter_data);
						}

						if($filter_field == 'konu')
						{
							$filter_field = 'ticket_konu';
							$this->db->like($filter_field, $filter_data);
						}
						

						if($filter_field == 'tarih')
						{
							$filter_field = 'ticket_tarih';
							if($filter_data != '')
							{
								$tarih 		 = explode('-',$filter_data);
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2],$tarih[0]);
								$this->db->where($filter_field.' >=' ,$filter_data);
								
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2]+1,$tarih[0]);
								$this->db->where($filter_field.' <' ,$filter_data);
							}
						}

						if($filter_field == 'durum')
						{
							if($filter_data != 'T'){
								$filter_field = 'ticket_adm_durum';
								$this->db->where($filter_field, $filter_data);
							}
						}
						
					}
				}
			}
		}
		
		if($filter == ''){
			$filter='ticket_asc/durum|T]';
		}
		
		$this->db->select('ticket.*');
		$this->db->order_by('ticket_id',$order);
		$this->db->order_by('ticket_tarih','desc');
		$this->db->limit($per_page,$page);
		$this->db->join('usr_ide_inf','usr_ide_inf.user_id = ticket_id','left');
		$this->db->where(array('ticket_tip'=>'kapat','ticket_flag'=>2));
		$sorgu = $this->db->get_where('ticket');

		if ($filter != 'kimden|]')
		{
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

						if($filter_field == 'kimden')
						{
							$this->db->like('ide_adi', $filter_data);
							$this->db->or_like('ide_soy', $filter_data);
						}

						if($filter_field == 'konu')
						{
							$filter_field = 'ticket_konu';
							$this->db->like($filter_field, $filter_data);
						}
						

						if($filter_field == 'tarih')
						{
							$filter_field = 'ticket_tarih';
							if($filter_data != '')
							{
								$tarih 		 = explode('-',$filter_data);
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2],$tarih[0]);
								$this->db->where($filter_field.' >=' ,$filter_data);
								
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2]+1,$tarih[0]);
								$this->db->where($filter_field.' <' ,$filter_data);
							}
						}

						if($filter_field == 'durum')
						{
							if($filter_data != 'T'){
								$filter_field = 'ticket_adm_durum';
								$this->db->where($filter_field, $filter_data);
							}
						}
						
					}
				}
			}
		}
		
		if($filter == ''){
			$filter='ticket_asc/durum|T]';
		}

		
		$this->db->join('usr_ide_inf','usr_ide_inf.user_id = ticket_id','left');
		$this->db->where(array('ticket_tip'=>'kapat','ticket_flag'=>2));
		$sorgu_say = $this->db->get_where('ticket');
		
		$config['per_page'] = $per_page;
		$config['total_rows'] 		= $sorgu_say->num_rows();
		$config['full_tag_open'] 	= 'Sayfa : ';
		$config['full_tag_close'] 	= '';
		$config['num_links'] = 6;
		$config['base_url'] = base_url() . 'yonetim/ticket/arsivdeki/listele/'. $sort_lnk .'/'. $filter;
		$config['uri_segment'] = 7;
		
		$mevcut_sayfa = floor(($page / $per_page) + 1);
		
		$toplam_stok_sayisi = $sorgu_say->num_rows();
		$toplam_sayfa = ceil($toplam_stok_sayisi / $per_page);

		$config['full_tag_open'] = '<div class="pagination"><div class="links">';
		$config['full_tag_close'] = '</div><div class="results">Toplam Sayfa '. $toplam_sayfa .' Mevcut Sayfa '. $mevcut_sayfa .' </div></div>';

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

	function ticket_cevap_yaz($val)
	{
		$insert = array(			
						'ticket_icerik'		=>$val->txt_mesaj,
						'ticket_tip'		=>'cevap',
						'ticket_tarih'		=>time(),
						'ticket_uye_durum'	=>1,
						'ticket_adm_durum'	=>2,
						'ticket_prm_id'		=>$val->id,
						'ticket_flag'		=>1,
						'user_id'			=>$this->dx_auth->get_user_id(),
					   );
		$this->db->insert('ticket',$insert);
		$user_id = $this->ticket_getir(array('ticket_id'=>$val->id))->row()->user_id;
		$son_ticket_id = $this->db->insert_id();
		
		//mail gönder - basla
		$from 							= config('site_ayar_email_cevapsiz');
		$subject 						= 'Ticketınız başarıyla alınmıştır.';
		$user_inf 						= $this->siteModel->kullanici_adi_getir($user_id);
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

		redirect('yonetim/ticket_cevap_yaz/'.$val->id);
	}

	function ticket_yazismalar($id)
	{
		$this->db->order_by('ticket_tarih','asc');
		$this->db->order_by('ticket_id','asc');
		return $this->db->get_where('ticket',array('ticket_prm_id'=>$id));
	}
}