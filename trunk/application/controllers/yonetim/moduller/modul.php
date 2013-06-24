<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class modul extends Admin_Controller {

	var $izin_linki;

	function __construct()
	{
		parent::__construct();

		$this->load->model('yonetim/yonetim_model');

		$this->izin_linki = 'moduller/modul';
	}

	function index()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/moduller/modul');
		redirect('yonetim/moduller/modul/listele');
	}

	function listele()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/moduller/modul/listele');

		$data['moduller'] = $this->db->get('eklentiler');
		$this->load->view('yonetim/moduller/moduller_listele_view', $data);
	}

	function duzenle($eklenti_id)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/moduller/modul/duzenle/' . $eklenti_id);

		$sorgu = $this->db->get_where('eklentiler', array('eklenti_id' => $eklenti_id), 1);
		if($sorgu->num_rows() > 0)
		{
			$data['languages'] = $this->fonksiyonlar->get_languages();
			$data['modul'] = $sorgu->row();
			$data['modul_ayarlar'] = $this->db->get_where('eklentiler_ayalar', array('eklenti_ascii' => $sorgu->row()->eklenti_ascii));
			$this->load->view('yonetim/moduller/duzenle/'. $sorgu->row()->eklenti_ascii .'_duzenle_view', $data);
		} else {
			$yonetim_mesaj 				= array();
			$yonetim_mesaj['durum'] 	= '2';// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Girmiş olduğunuz modül bulunamadı.');
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/moduller/modul/listele');
		}
	}
}
?>