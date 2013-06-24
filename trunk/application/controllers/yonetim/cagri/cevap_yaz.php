<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class cevap_yaz extends Admin_Controller {

	var $izin_linki;

	function __construct()
	{
		parent::__construct();
		$this->load->model('yonetim/cagri_model');

		$this->izin_linki = 'cagri/cevap_yaz';
	}

	function index($ticket_id = 0)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/cagri/cevap_yaz');

		$geri_don = $this->input->get("geri_don");
		if(empty($geri_don)){redirect('yonetim/cagri/cevaplanmis');}
		$val = $this->validation;
		$rules['txt_mesaj']			= 'trim|required|xss_clean';
		$rules['id']				= 'trim|required|integer|xss_clean';

		$fields['txt_mesaj']		= 'Mesaj';
		$fields['txt_mesaj']		= 'ID';
		$val->set_rules($rules);
		$val->set_fields($fields);

		if($val->run() == TRUE)
		{
			$this->cagri_model->cevap_yaz($val);
		}  else {
			if($ticket_id > 0)
			{
				$this->db->update('ticket', array('ticket_adm_durum'=>'2'), array('ticket_id'=>$ticket_id, 'user_id'=>$this->dx_auth->get_user_id()) );
			}

			$data['soru'] = $this->db->get_where('ticket',array('ticket_id'=>$ticket_id))->row();
			$data['yazismalar'] = $this->cagri_model->yazismalar($ticket_id);
			$this->load->view('yonetim/cagri/cevap_yaz_view',$data);
		}
	}
	
	function arsive_ekle($id = 0)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/cagri/arsive_ekle/' . $id);

		$this->cagri_model->cagri_kapat($id);
		$url = $this->input->get("geri_don");
		if(!empty($url))
		{
			redirect($url);
		} else {
			redirect('yonetim');
		}
	}
	
	function arsivden_cikart($id = 0)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/cagri/arsivden_cikart/' . $id);

		$this->cagri_model->cagri_acik($id);
		$url = $this->input->get("geri_don");
		if(!empty($url))
		{
			redirect($url);
		} else {
			redirect('yonetim');
		}
	}
}	
?>