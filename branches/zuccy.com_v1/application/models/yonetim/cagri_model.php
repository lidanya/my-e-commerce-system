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
	}

	function cevaplanmis_cagri($sort_lnk = "ticket_desc", $filter = "kimden|]", $page=0)
	{
		$per_page = (config('site_ayar_urun_yonetim_sayfa')) ? config('site_ayar_urun_yonetim_sayfa') :20;
		$sort_lnk_e = explode('_',$sort_lnk);
		$sort  = 'ticket.ticket_id';
		$order = (!empty($sort_lnk_e[1])) ? $sort_lnk_e[1] : 'desc';	
		// Sorguulama
		$this->db->select('ticket.*,usr_ide_inf.*');
		$this->db->join('ticket as tc','tc.ticket_prm_id=ticket.ticket_id','left');
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
							$filter_field = 'ticket.ticket_konu';
							$this->db->like($filter_field, $filter_data);
						}
						
						if($filter_field == 'durum')
						{
							if($filter_data != 'T'){
								$filter_field = 'ticket.ticket_adm_durum';
								$this->db->like($filter_field, $filter_data);
							}
						}

						if($filter_field == 'tarih')
						{
							$filter_field = 'ticket.ticket_tarih';
							if($filter_data != '')
							{
								$tarih 		 = explode('-',$filter_data);
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2],$tarih[0]);
								$this->db->where($filter_field.' >=' ,$filter_data);
								
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2]+1,$tarih[0]);
								$this->db->where($filter_field.' <' ,$filter_data);
							}
						}

					}
				}
			}
		}

		$this->db->order_by('ticket.ticket_id', $order);
		$this->db->group_by('ticket.ticket_id');
		$this->db->where('ticket.ticket_tip','cevap');
		$this->db->where('ticket.ticket_prm_id',0);
		$this->db->where('ticket.ticket_flag',1);
		$this->db->join('usr_ide_inf','usr_ide_inf.user_id=ticket.user_id','left');
		$this->db->limit($per_page,$page);
		$result = $this->db->get('ticket');

		// Sorgulama
		$this->db->select('ticket.*,usr_ide_inf.*');
		$this->db->join('ticket as tc','tc.ticket_prm_id=ticket.ticket_id','left');
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
							$filter_field = 'ticket.ticket_konu';
							$this->db->like($filter_field, $filter_data);
						}
						
						if($filter_field == 'durum')
						{
							if($filter_data != 'T')
							{
								$filter_field = 'ticket.ticket_adm_durum';
								$this->db->like($filter_field, $filter_data);
							}
						}

						if($filter_field == 'tarih')
						{
							$filter_field = 'ticket.ticket_tarih';
							if($filter_data != '')
							{
								$tarih 		 = explode('-',$filter_data);
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2],$tarih[0]);
								$this->db->where($filter_field.' >=' ,$filter_data);
								
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2]+1,$tarih[0]);
								$this->db->where($filter_field.' <' ,$filter_data);
							}
						}

					}
				}
			}
		}
		
		$this->db->group_by('ticket.ticket_id');
		$this->db->where('ticket.ticket_tip','cevap');
		$this->db->where('ticket.ticket_prm_id',0);
		$this->db->where('ticket.ticket_flag',1);
		$this->db->join('usr_ide_inf','usr_ide_inf.user_id=ticket.user_id');
		$sorgu_say = $this->db->get('ticket');
		
		$config['per_page'] = $per_page;
		$config['total_rows'] 		= $sorgu_say->num_rows();
		$config['full_tag_open'] 	= 'Sayfa : ';
		$config['full_tag_close'] 	= '';
		$config['num_links'] = 6;
		$config['base_url'] = base_url() . 'yonetim/cagri/cevaplanmis/listele/'. $sort_lnk .'/'. $filter;
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

		return $result;
	}

	function cevapbekleyen_cagri($sort_lnk = "ticket_desc", $filter = "kimden|]", $page=0)
	{
		$per_page = (config('site_ayar_urun_yonetim_sayfa')) ? config('site_ayar_urun_yonetim_sayfa') :20;
		$sort_lnk_e = explode('_',$sort_lnk);
		$sort  = (!empty($sort_lnk_e[0])) ? $sort_lnk_e[0] : 'ticket';
		
		$sort  = 'ticket.tikcet_id';
		$order = (!empty($sort_lnk_e[1])) ? $sort_lnk_e[1] : 'desc';	
		// Sorguulama
		$this->db->select('ticket.*,usr_ide_inf.*');
		$this->db->join('ticket as tc','tc.ticket_prm_id=ticket.ticket_id','left');
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
							$filter_field = 'ticket.ticket_konu';
							$this->db->like($filter_field, $filter_data);
						}
						
						if($filter_field == 'durum')
						{
							if($filter_data != 'T'){
								$filter_field = 'ticket.ticket_adm_durum';
								$this->db->like($filter_field, $filter_data);
							}
						}

						if($filter_field == 'tarih')
						{
							$filter_field = 'ticket.ticket_tarih';
							if($filter_data != '')
							{
								$tarih 		 = explode('-',$filter_data);
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2],$tarih[0]);
								$this->db->where($filter_field.' >=' ,$filter_data);
								
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2]+1,$tarih[0]);
								$this->db->where($filter_field.' <' ,$filter_data);
							}
						}

					}
				}
			}
		}

		$this->db->order_by('ticket.ticket_id', $order);
		$this->db->group_by('ticket.ticket_id');
		$this->db->where('ticket.ticket_tip','soru');
		$this->db->where('ticket.ticket_prm_id',0);
		$this->db->where('ticket.ticket_flag',1);
		$this->db->join('usr_ide_inf','usr_ide_inf.user_id=ticket.user_id','left');
		$this->db->limit($per_page,$page);
		$result = $this->db->get('ticket');

		// Sorgulama
		$this->db->select('ticket.*,usr_ide_inf.*');
		$this->db->join('ticket as tc','tc.ticket_prm_id=ticket.ticket_id','left');
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
							$filter_field = 'ticket.ticket_konu';
							$this->db->like($filter_field, $filter_data);
						}
						
						if($filter_field == 'durum')
						{
							if($filter_data != 'T'){
								
								$filter_field = 'ticket.ticket_adm_durum';
								$this->db->like($filter_field, $filter_data);
							}
						}

						if($filter_field == 'tarih')
						{
							$filter_field = 'ticket.ticket_tarih';
							if($filter_data != '')
							{
								$tarih 		 = explode('-',$filter_data);
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2],$tarih[0]);
								$this->db->where($filter_field.' >=' ,$filter_data);
								
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2]+1,$tarih[0]);
								$this->db->where($filter_field.' <' ,$filter_data);
							}
						}

					}
				}
			}
		}
		
		$this->db->group_by('ticket.ticket_id');
		$this->db->where('ticket.ticket_tip','soru');
		$this->db->where('ticket.ticket_prm_id',0);
		$this->db->where('ticket.ticket_flag','1');
		$this->db->join('usr_ide_inf','usr_ide_inf.user_id=ticket.user_id');
		$sorgu_say = $this->db->get('ticket');
		
		$config['per_page'] = $per_page;
		$config['total_rows'] 		= $sorgu_say->num_rows();
		$config['full_tag_open'] 	= 'Sayfa : ';
		$config['full_tag_close'] 	= '';
		$config['num_links'] = 6;
		$config['base_url'] = base_url() . 'yonetim/cagri/cevapbekleyen/listele/'. $sort_lnk .'/'. $filter;
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

		return $result;
	}

	function arsivdeki_cagri($sort_lnk = "ticket_desc", $filter = "kimden|]", $page=0)
	{
		
		$per_page = (config('site_ayar_urun_yonetim_sayfa')) ? config('site_ayar_urun_yonetim_sayfa') :20;
		
		$sort_lnk_e = explode('_',$sort_lnk);
		$sort  = (!empty($sort_lnk_e[0])) ? $sort_lnk_e[0] : 'ticket';
		
		$sort  = 'ticket.tikcet_id';
		$order = (!empty($sort_lnk_e[1])) ? $sort_lnk_e[1] : 'desc';	
		// Sorguulama
		$this->db->select('ticket.*,usr_ide_inf.*');
		$this->db->join('ticket as tc','tc.ticket_prm_id=ticket.ticket_id','left');
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
							$filter_field = 'ticket.ticket_konu';
							$this->db->like($filter_field, $filter_data);
						}
						
						if($filter_field == 'durum')
						{
							if($filter_data != 'T'){
								$filter_field = 'ticket.ticket_adm_durum';
								$this->db->like($filter_field, $filter_data);
							}
						}

						if($filter_field == 'tarih')
						{
							$filter_field = 'ticket.ticket_tarih';
							if($filter_data != '')
							{
								$tarih 		 = explode('-',$filter_data);
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2],$tarih[0]);
								$this->db->where($filter_field.' >=' ,$filter_data);
								
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2]+1,$tarih[0]);
								$this->db->where($filter_field.' <' ,$filter_data);
							}
						}

					}
				}
			}
		}

		$this->db->order_by('ticket.ticket_id', $order);
		$this->db->group_by('ticket.ticket_id');
		$this->db->where('ticket.ticket_prm_id',0);
		$this->db->where('ticket.ticket_flag',2);
		$this->db->join('usr_ide_inf','usr_ide_inf.user_id=ticket.user_id','left');
		$this->db->limit($per_page,$page);
		$result = $this->db->get('ticket');

		// Sorgulama
		$this->db->select('ticket.*,usr_ide_inf.*');
		$this->db->join('ticket as tc','tc.ticket_prm_id=ticket.ticket_id','left');
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
							$filter_field = 'ticket.ticket_konu';
							$this->db->like($filter_field, $filter_data);
						}
						
						if($filter_field == 'durum')
						{
							if($filter_data != 'T'){
								$filter_field = 'ticket.ticket_adm_durum';
								$this->db->like($filter_field, $filter_data);
							}
						}

						if($filter_field == 'tarih')
						{
							$filter_field = 'ticket.ticket_tarih';
							if($filter_data != '')
							{
								$tarih 		 = explode('-',$filter_data);
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2],$tarih[0]);
								$this->db->where($filter_field.' >=' ,$filter_data);
								
								$filter_data = mktime(0,0,0,$tarih[1],$tarih[2]+1,$tarih[0]);
								$this->db->where($filter_field.' <' ,$filter_data);
							}
						}

					}
				}
			}
		}
		
		$this->db->group_by('ticket.ticket_id');
		$this->db->where('ticket.ticket_prm_id',0);
		$this->db->where('ticket.ticket_flag',2);
		$this->db->join('usr_ide_inf','usr_ide_inf.user_id=ticket.user_id');
		$sorgu_say = $this->db->get('ticket');
		
		$config['per_page'] = $per_page;
		$config['total_rows'] 		= $sorgu_say->num_rows();
		$config['full_tag_open'] 	= 'Sayfa : ';
		$config['full_tag_close'] 	= '';
		$config['num_links'] = 6;
		$config['base_url'] = base_url() . 'yonetim/cagri/arsivdeki/listele/'. $sort_lnk .'/'. $filter;
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

		return $result;
	}

	function cagri_getir( $where = 0 , $where_field = "",$where_not_in = 0)
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

	function cevap_yaz($val)
	{

		$this->db->update('ticket', array('ticket_tip'=>'cevap'), array('ticket_id'=>$val->id) );
		$insert = array(
						'ticket_icerik'		=>nl2br($val->txt_mesaj),
						'ticket_tip'		=>'cevap',
						'ticket_tarih'		=>time(),
						'ticket_uye_durum'	=>1,
						'ticket_adm_durum'	=>2,
						'ticket_prm_id'		=>$val->id,
						'ticket_flag'		=>1,
						'user_id'			=>$this->dx_auth->get_user_id(),
					   );
		$this->db->insert('ticket',$insert);
		$user_id = $this->db->get_where('ticket',array('ticket_id'=>$val->id))->row()->user_id;
		$son_ticket_id = $this->db->insert_id();
		
		//mail gönder - basla
		$from 							= config('site_ayar_email_cevapsiz');
		$ticket_q 						= $this->cagri_detay_id_listele($val->id);
		$subject 						= $ticket_q->row()->ticket_kodu.'\'lu Çağrınıza cevap geldi.';
		$user_inf 						= uye_bilgi($user_id);
		$mail_data['ticket_row']		= $ticket_q->row();
		$mail_data['ad'] 				= $user_inf->ide_adi;
		$mail_data['soyad'] 			= $user_inf->ide_soy;
		$mail_data['icerik'] 			= $val->txt_mesaj;
		$mail_data['ticket_kodu'] 		= $ticket_q->row()->ticket_kodu;
		$mail_data['siparis_tarih']		= standard_date('DATE_TR1', time(), 'tr');
		$message = $this->load->view(tema() . 'mail_sablon/ticket/ticket_gonder', $mail_data, true);
		
		$this->dx_auth->_email($user_inf->email, $from, $subject, $message);
		$admin_mail = config('site_ayar_email_admin');
		$subject 						= 'Çağrınıza başarıyla gönderildi.';
		$this->dx_auth->_email($admin_mail,$user_inf->email, $subject, $message);
		
		redirect('yonetim/cagri/cevap_yaz/index/'.$val->id.'?geri_don='.$this->input->get('geri_don'));
	}

	function yazismalar($id)
	{
		$this->db->order_by('ticket_tarih','asc');
		$this->db->order_by('ticket_id','asc');
		return $this->db->get_where('ticket',array('ticket_prm_id'=>$id));
	}

	function cagri_kapat($ticket_id = 0)
	{
		$result = $this->db->update('ticket', array('ticket_flag'=>2), array('ticket_id'=>$ticket_id));
		return $result;
	}

	function cagri_acik($ticket_id = 0)
	{
		$result = $this->db->update('ticket', array('ticket_flag'=>1), array('ticket_id'=>$ticket_id));
		return $result;
	}

	function arsive_ekle($val = array())
	{
		foreach($val as $r)
		{
			$this->db->update('ticket', array('ticket_flag'=>2), array('ticket_id'=>$r));
		}
	}

	function arsivden_cikart($val = array())
	{
		foreach($val as $r)
		{
			$this->db->update('ticket', array('ticket_flag'=>1), array('ticket_id'=>$r));
		}
	}

	function cagri_detay_id_listele($id = 0)
	{
		$result = $this->db->get_where('ticket',array('ticket_id'=>$id));
		return $result;
	}
}