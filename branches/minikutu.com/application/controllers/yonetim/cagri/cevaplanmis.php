<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class cevaplanmis extends Admin_Controller {

	var $izin_linki;

	function __construct()
	{
		parent::__construct();
		$this->load->model('yonetim/cagri_model');

		$this->izin_linki = 'cagri/cevaplanmis';
	}

	function index()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/cagri/cevaplanmis');
		redirect('yonetim/cagri/cevaplanmis/listele');
	}

	function listele()
	{
		$sort_lnk	= $this->uri->segment(5);
		$filter 	= $this->uri->segment(6);
		$page 		= $this->uri->segment(7);
		
		$sort_lnk 	= (!empty($sort_lnk)) ? $sort_lnk  : 'ticket_desc';
		$filter 	= (!empty($filter)) ? $filter  : 'konu|]';
		$page 		= (!empty($page)) ? $page  : 0;

		sayfa_kontrol($this->izin_linki, 'yonetim/cagri/cevaplanmis/listele');

		$data['cevaplanmis'] = $this->cagri_model->cevaplanmis_cagri($sort_lnk, $filter, $page);
		$this->session->set_flashdata('sayfa', $this->uri->segment(3));
		$this->load->view('yonetim/cagri/cevaplanmis_view',$data);
	}

	function kapat($id = 0)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/cagri/cevaplanmis/kapat/' . $id);

		$this->cagri_model->cagri_kapat($id);
		redirect('yonetim/cagri/cevaplanmis/listele');
	}

	function arsive_ekle()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/cagri/cevaplanmis/arsive_ekle');

		if($this->input->post('select2'))
		{
			$this->cagri_model->arsive_ekle($this->input->post('select2'));
		}
		redirect('yonetim/cagri/cevaplanmis/listele');
	}
}
?>