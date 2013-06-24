<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class marka_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function seo_kontrol($seo_url, $marka_id = false)
	{
		$gelen_seo_url = $seo_url;

		if($marka_id)
		{
			$this->db->where_not_in('marka_id', $marka_id);
		}
		$this->db->where('marka_seo', $gelen_seo_url);
		$marka_adi_kontrol = $this->db->count_all_results('stok_marka');
		if($marka_adi_kontrol > 0)
		{
			$seo_url = $gelen_seo_url . '-' . rand(1, 1000);
			return $this->seo_kontrol($seo_url);
		} else {
			return $gelen_seo_url;
		}
	}

	function marka_kontrol($marka_id)
	{
		$sorgu = $this->db->get_where('stok_marka', array('marka_id' => $marka_id), 1);
		if($sorgu->num_rows() > 0)
		{
			return $sorgu->row();
		} else {
			return false;
		}
	}

	function marka_listele($isim, $tip)
	{
		$this->db->order_by($isim, $tip);	
		$sorgu = $this->db->get('stok_marka');
		return $sorgu;
	}

	function listele($sayfa)
	{
		$per_page = 20;
		$sorgu = $this->db->get('stok_marka', $per_page, $sayfa);
		$sorgu_say = $this->db->get('stok_marka');
		$config['per_page'] = $per_page;
		$config['total_rows'] = $sorgu_say->num_rows();
		$config['full_tag_open'] = 'Sayfa : ';
		$config['full_tag_close'] = '';
		$config['num_links'] = 6;
		$config['base_url'] = 'yonetim/urunler/marka/listele';
		$config['uri_segment'] = 5;

		$mevcut_sayfa = floor(($sayfa / $per_page) + 1);
		$toplam_marka_sayisi = $this->db->count_all_results('stok_marka');
		$toplam_sayfa = ceil($toplam_marka_sayisi / $per_page);

		$config['full_tag_open'] = '<div class="pagination"><div class="links">';
		$config['full_tag_close'] = '</div><div class="results">
Toplam '. $toplam_sayfa .' sayfa içinde '. $mevcut_sayfa .'. sayfadasın.</div></div>';

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

	function ekle($val)
	{
		$seo_url = ($val->seo_link) ? url_title($val->seo_link):url_title($val->name);
		$seo_url = $this->seo_kontrol($seo_url);

		$data = array(
			'marka_adi'				=> $val->name,
			'marka_seo'				=> $seo_url,
			'marka_sira'			=> $val->sort_order,
			'marka_logo'			=> $val->image,
			'marka_flag'			=> '1',
			'marka_description'		=> $val->description,
			'marka_keywords'		=> $val->keywords,
		);

		$sorgu = $this->db->insert('stok_marka', $data);

		if($sorgu)
		{
			return true;
		} else {
			return false;
		}
	}

	function duzenle($val)
	{
		$seo_url = ($val->seo_link) ? url_title($val->seo_link):url_title($val->name);
		$seo_url = $this->seo_kontrol($seo_url, $val->marka_id);

		$data = array(
			'marka_adi'				=> $val->name,
			'marka_seo'				=> $seo_url,
			'marka_sira'			=> $val->sort_order,
			'marka_logo'			=> $val->image,
			'marka_flag'			=> '1',
			'marka_description'		=> $val->description,
			'marka_keywords'		=> $val->keywords,
		);

		$this->db->where('marka_id', $val->marka_id);
		$kontrol = $this->db->update('stok_marka', $data); 

		if ($kontrol)
		{
			$kontrol_data = true;
		} else {
			$kontrol_data = false;
		}
		return $kontrol_data;
	}

	function sil($val)
	{
		foreach($val as $secilenler)
		{
			$this->db->delete('stok_marka', array('marka_id' => $secilenler));
		}
		$sayi = count($val);
		if($sayi > 0)
		{
			return true;
		} else {
			return false;
		}
	}
}