<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class slider_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'site model Initialized');
		$this->load->library('email');
		$this->load->library('Admin_Pagination');
	}

	function listele($sayfa)
	{
		$per_page = 20;
		$sorgu = $this->db->get('tool_slider', $per_page, $sayfa);
		$sorgu_say = $this->db->get('tool_slider');
		$config['per_page'] = $per_page;
		$config['total_rows'] = $sorgu_say->num_rows();
		$config['full_tag_open'] = 'Sayfa : ';
		$config['full_tag_close'] = '';
		$config['num_links'] = 6;
		$config['base_url'] = 'yonetim/moduller/slider/listele';
		$config['uri_segment'] = 5;

		$mevcut_sayfa = floor(($sayfa / $per_page) + 1);
		$toplam_slider_sayisi = $this->db->count_all_results('tool_slider');
		$toplam_sayfa = ceil($toplam_slider_sayisi / $per_page);

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

	function sil($val)
	{
		if(is_array($val))
		{
			foreach($val as $secilenler)
			{
				$this->db->delete('tool_slider', array('slider_id' => $secilenler));
			}
		} else {
			$this->db->delete('tool_slider', array('slider_id' => $val));
		}
		$sayi = count($val);
		if($sayi > 0)
		{
			return true;
		} else {
			return false;
		}
	}

	function slider_kontrol($slider_id)
	{
		$sorgu = $this->db->get_where('tool_slider', array('slider_id' => $slider_id), 1);
		if($sorgu->num_rows() > 0)
		{
			return $sorgu->row();
		} else {
			return false;
		}
	}

	function ekle($val)
	{
		$data = array(
			'slider_link'			=> $val->slider_link,
			'slider_img'			=> $val->image,
			'slider_sira'			=> $val->slider_sira,
			'slider_flag'			=> 1,
		);

		$sorgu = $this->db->insert('tool_slider', $data);

		if($sorgu)
		{
			return true;
		} else {
			return false;
		}
	}

	function duzenle($val)
	{
		$data = array(
			'slider_link'			=> $val->slider_link,
			'slider_img'			=> $val->image,
			'slider_sira'			=> $val->slider_sira,
			'slider_flag'			=> $val->slider_flag,
		);

		$this->db->where('slider_id', $val->slider_id);
		$sorgu = $this->db->update('tool_slider', $data);

		if($sorgu)
		{
			return true;
		} else {
			return false;
		}
	}
}