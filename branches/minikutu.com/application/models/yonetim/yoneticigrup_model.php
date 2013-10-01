<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class yoneticigrup_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Müşteri Grubu Model Yüklendi');
	}

	function yonetici_grup_listele($page = 0)
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
		$config['base_url'] = base_url() . 'yonetim/satis/yonetici_grup/listele';
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

	function yonetici_grup_ekle($val)
	{
		$data = array(
			'name'      	=> url_title($val->name),
			'fiyat_orani'	=> $val->price,
			'fiyat_tip'		=> $val->price_tip,
			'yetki'			=> serialize($val->yetki),
			'parent_id'		=> '2'
		);
		$kontrol = $this->db->insert('roles', $data); 
		return $kontrol;
	}

	function yonetici_grup_duzenle($val)
	{
		$data = array(
			'name'      	=> url_title($val->name),
			'fiyat_orani'	=> $val->price,
			'fiyat_tip'		=> $val->price_tip,
			'yetki'			=> serialize($val->yetki),
		);
		$this->db->where('parent_id','2');
		$this->db->where('id',$val->id);
		$kontrol = $this->db->update('roles', $data); 
		return $kontrol;
	}

	function yonetici_grup_sil($val)
	{
		foreach($val as $r)
		{
			$this->db->where('role_id', $r);
			$this->db->update('users', array('role_id' => config('site_ayar_varsayilan_mus_grub')));

			$this->db->where('id', $r);
			$this->db->delete('roles');
		}

		$kontrol = true;

		return $kontrol;
	}

	function yonetici_grup_veri($group_id)
	{
		$this->db->where('parent_id','2');	
		$this->db->where('id', $group_id);
		$this->db->from('roles');
		$query = $this->db->get();
		$row = $query->row();
		return $row;		
	}
}