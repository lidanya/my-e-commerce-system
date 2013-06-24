<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class arsivdeki extends Admin_Controller {

	var $izin_linki;

	function __construct()
	{
		parent::__construct();
		$this->load->model('yonetim/cagri_model');

		$this->izin_linki = 'cagri/arsivdeki';
	}

	function index()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/cagri/arsivdeki');
		redirect('yonetim/cagri/arsivdeki/listele');
	}

	function listele()
	{
		$sort_lnk	= $this->uri->segment(5);
		$filter 	= $this->uri->segment(6);
		$page 		= $this->uri->segment(7);
		
		$sort_lnk 	= (!empty($sort_lnk)) ? $sort_lnk  : 'ticket_desc';
		$filter 	= (!empty($filter)) ? $filter  : 'konu|]';
		$page 		= (!empty($page)) ? $page  : 0;

		sayfa_kontrol($this->izin_linki, 'yonetim/cagri/arsivdeki/listele');

		$data['cevaplanmis'] = $this->cagri_model->arsivdeki_cagri($sort_lnk, $filter, $page);
		$this->session->set_flashdata('sayfa', $this->uri->segment(3));
		$this->load->view('yonetim/cagri/arsivdeki_view',$data);
	}

	function arsivden_cikart($ticket_id = 0)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/cagri/arsivdeki/arsivden_cikart/' . $ticket_id);

		if($ticket_id == 0){
			if($this->input->post('select2'))
			{
				$this->cagri_model->arsivden_cikart($this->input->post('select2'));
			}			
		} else
		{
			if(is_numeric($ticket_id))
			{
				$this->cagri_model->cagri_acik($ticket_id);
			}
		}
		redirect('yonetim/cagri/arsivdeki/listele');
	}
}?>