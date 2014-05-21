<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class haber_model extends CI_Model
{
    function __construct()
    {
		parent::__construct();
    }

	function haber_listele($sort, $order, $filter, $page, $sort_lnk)
	{
		$per_page = 20;
		
		if ($filter!='solmod_flag|]')
		{
			$filter_e = explode(']',$filter);
			for($i=0;$i<count($filter_e);$i++)
			{
				if ($filter_e[$i]){
					$filter_d = explode('|',$filter_e[$i]);
					
					$filter_field  = $filter_d[0];
					$filter_data   = $filter_d[1];
					if (($filter_field) and ($filter_data)){ $this->db->like($filter_field, $filter_data); }
				}
			}
		}
		$this->db->where('solmod_tip','haberler');
		$this->db->where_not_in('solmod_flag', '0');
		$this->db->order_by($sort, $order);
		$sorgu = $this->db->get('solmod', $per_page, $page);


		if ($filter!='solmod_flag|]'){
			$filter_e = explode(']',$filter);
			for($i=0;$i<count($filter_e);$i++)
			{
				if ($filter_e[$i]){
				$filter_d = explode('|',$filter_e[$i]);
				
				$filter_field  = $filter_d[0];
				$filter_data   = $filter_d[1];
				if (($filter_field) and ($filter_data)){ $this->db->like($filter_field, $filter_data); }
				}
			}
		}
		$this->db->where('solmod_tip','haberler');
		$this->db->where_not_in('solmod_flag', '0');
		$sorgu_say = $this->db->get('solmod');

		if($this->uri->segment(6))
		{
			$config['base_url'] = base_url() . 'yonetim/icerik_yonetimi/haberler/listele/' . $sort_lnk . '/' . $this->uri->segment(6);
			$config['uri_segment'] = 7;
		} else {
			$config['base_url'] = base_url() . 'yonetim/icerik_yonetimi/haberler/listele/' . $sort_lnk.'/solmod_flag|]';
			$config['uri_segment'] = 7;
		}
		$config['per_page'] 	  = $per_page;
		$config['total_rows'] 	  = $sorgu_say->num_rows();
		$config['full_tag_open']  = 'Sayfa : ';
		$config['full_tag_close'] = '';
		$config['num_links'] 	  = 6;

		$mevcut_sayfa = floor(($page / $per_page) + 1);
		if ($filter!='solmod_flag|]'){
			$filter_e = explode(']',$filter);
			for($i=0;$i<count($filter_e);$i++)
			{
				if ($filter_e[$i]){
				$filter_d = explode('|',$filter_e[$i]);
				$filter_field  = $filter_d[0];
				$filter_data   = $filter_d[1];
				if (($filter_field) and ($filter_data)){ $this->db->like($filter_field, $filter_data); }
				}
			}
		}
		$this->db->where_not_in('solmod_flag', '0');
		$this->db->where('solmod_tip','haberler');
		$toplam_stok_sayisi = $this->db->count_all_results('solmod');
		$toplam_sayfa = ceil($toplam_stok_sayisi / $per_page);

		$config['full_tag_open'] = '<div class="pagination"><div class="links">';
		$config['full_tag_close'] = '</div><div class="results">Toplam Sayfa '. $toplam_sayfa .' Mevcut Sayfa '. $mevcut_sayfa .'</div></div>';

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

	function haber_ekle($val)
	{
		$kontrol_data=FALSE;
		
		$degis   =  array( 
 			'{Daynex_Resim_Url}',
 			'{Daynex_Js_Url}',
 			'{Daynex_Site_Url}',
 			'{Daynex_Flash_Url}',
 			'<',
 			'>'
		);
		$bul  =  array(
			site_url().APPPATH.'views/'.tema().'images/',
 			site_url().APPPATH.'views/'.tema().'js/',
 			site_url(),
 			site_url().APPPATH.'views/'.tema().'flash/',
 			'&lt;',
 			'&gt;'
		);
		$icerik = str_replace($bul, $degis, $val->solmod_icerik); 
		$link = $val->solmod_lnk;
		$data = array(
			'solmod_baslik' 			=> $val->solmod_baslik,
			'solmod_tip' 				=> 'haberler',
			'solmod_ektar'				=> time(),
			'solmod_guntar'				=> time(),
			'solmod_icerik' 			=> $icerik,
			'solmod_ack'	 			=> $val->solmod_ack,
			'solmod_seoname'			=> $link,
			'solmod_sayfa_baslik' 		=> $val->solmod_sayfa_baslik,
			'solmod_sayfa_keywords' 	=> $val->solmod_sayfa_keywords,
			'solmod_sayfa_description' 	=> $val->solmod_sayfa_description,
			'solmod_flag'				=> '1'
		);
		$kontrol_data = $this->db->insert('solmod', $data);
		return $kontrol_data;	
	}

	function haber_duzenle($val, $haber_id)
	{
		$kontrol_data=FALSE;
		$degis   =  array( 
 			'{Daynex_Resim_Url}',
 			'{Daynex_Js_Url}',
 			'{Daynex_Site_Url}',
 			'{Daynex_Flash_Url}',
 			'<',
 			'>'
		);
		$bul  =  array(
			site_url().APPPATH.'views/'.tema().'images/',
 			site_url().APPPATH.'views/'.tema().'js/',
 			site_url(),
 			site_url().APPPATH.'views/'.tema().'flash/',
 			'&lt;',
 			'&gt;'
		);
		$icerik = str_replace($bul, $degis, $val->solmod_icerik); 
		$link = $val->solmod_lnk;
		$data = array(
			'solmod_baslik' 			=> $val->solmod_baslik,
			'solmod_guntar'				=> time(),
			'solmod_icerik' 			=> $icerik,
			'solmod_ack'	 			=> $val->solmod_ack,
			'solmod_seoname'			=> $link,
			'solmod_sayfa_baslik' 		=> $val->solmod_sayfa_baslik,
			'solmod_sayfa_keywords' 	=> $val->solmod_sayfa_keywords,
			'solmod_sayfa_description' 	=> $val->solmod_sayfa_description
		);
		$this->db->where('solmod_id',$haber_id);
		$kontrol_data = $this->db->update('solmod', $data);
		return $kontrol_data;	
	}

	function haber_sil($val)
	{
		for ($i=0;$i<count($val->selected);$i++)
		{
			$data = array(
				'solmod_flag'	=> '0'
			);
			$this->db->where('solmod_id', $val->selected[$i]);
			$kontrol_sil=$this->db->update('solmod', $data); 
			if ($kontrol_sil){$kontrol_data = true;}
		}
		return $kontrol_data;
	}

	function haber_durum($haber_id, $tip)
	{
		if ($tip=='goster')
		{
			$data =array('solmod_flag'=>'1');
			$this->db->where('solmod_id', $haber_id);
			$this->db->update('solmod', $data);
		} else if ($tip=='gizle'){
			$data =array('solmod_flag'=>'2');
			$this->db->where('solmod_id', $haber_id);
			$this->db->update('solmod', $data);
		}	
	}

	function haber_veri($haber_id)
	{
		$this->db->where('solmod.solmod_flag !=','0');
		$this->db->where('solmod.solmod_tip', 'haberler');
		$this->db->where('solmod.solmod_id', $haber_id);
		$this->db->from('solmod');
		$query = $this->db->get();
		$row = $query->row();
		return $row;
	}
}