<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class siparisler extends Admin_Controller {

	var $izin_linki;

	function __construct()
	{
		parent::__construct();	
		$this->load->model('yonetim/yonetim_model');
		$this->load->model('yonetim/siparis_model');

		$this->izin_linki = 'satis/siparisler';
	}

	function index()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/satis/siparisler');
		redirect('yonetim/satis/siparisler/listele');
	}

	function listele($sort_link = 's.siparis_id-desc', $filter = 's.siparis_flag|]', $page = 0)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/satis/siparisler/listele/' . $sort_link . '/' . $filter . '/' . $page);
		
		$sort_link_e = explode('-',$sort_link);
		$sort  = $sort_link_e[0];
		$order = $sort_link_e[1];

		/*if(preg_match('/siparis_flag\|0/i', $filter, $matches))
		{
			$filter = preg_replace('/siparis_flag\|0/', 'siparis_flag|00', $filter, -1);
		}*/

		$data = array();
		$data['siparisler'] = array();
		$orders = $this->siparis_model->get_orders_by_all($page, $sort, $order, $filter, $sort_link);
		foreach($orders->result() as $result)
		{
			$islemler = array();
			$islemler[] = array(
				'text' => 'Düzenle',
				'href' => 'yonetim/satis/siparisler/duzenle/' . $result->siparis_id
			);

			$data['siparisler'][] = array(
				'siparis_id'		=> $result->siparis_id,
				'siparis_zamani'	=> $result->kayit_tar,
				'musteri_adi'		=> $result->namesurname . ' (' . $result->username . ')',
				'musteri_id'		=> $result->user_id,
				'musteri_mail'		=> $result->username,
				'selected'			=> ($this->input->post('selected') && in_array($result->siparis_id, $this->input->post('selected'))),
				'islemler'			=> $islemler,
				'siparis_durum'		=> siparis_durum_goster($result->siparis_flag)
			);
		}

		$data['sort_link']	= $sort_link;
		$data['filt_lnk']	= $filter;
		$data['page_link']	= $page;

		$sort_lnk_e			= explode('-', $sort_link);
		$data['sort']		= $sort_lnk_e[0];
		$data['order']		= $sort_lnk_e[1];
		$data['page_link']	= $page;

		if ($order)
		{
			if ($order == 'asc')
			{
				$data['order_link'] = 'desc';
			} else if ($order == 'desc') {
				$data['order_link'] = 'asc';
			}
		} else{
			$data['order_link'] = 'asc';		
			$data['order'] = 'desc';		
		}
		
		$data['filt_link'] = $filter;

		$_c_array = explode(', ', get_fields_from_table('siparis', 's.', array('siparis_id', 'kayit_tar', 'siparis_flag', 'siparis_flag_data', 'user_id')));
		$_r_array = explode(', ', get_fields_from_table('users', 'u.'));
		$_cc_array = array('uii.namesurname');
		$_cc2_array = array('namesurname');
		$_filter_allowed = array_merge($_c_array, $_r_array, $_cc_array, $_cc2_array);

		if ($filter != 's.siparis_flag|]') {
			$filter_e = explode(']', $filter);
			foreach($filter_e as $yaz) {
				if($yaz != '') {
					if(preg_match('/|/i', $yaz)) {
						$explode = explode('|', $yaz);
						if((isset($explode[0]) AND $explode[0] != '') AND (isset($explode[1]) AND $explode[1] != '')) {
							if(in_array($explode[0], $_filter_allowed)) {
								$data['filter_' . str_replace('.', '_', $explode[0])] = $explode[1];
							}
						}
					}
				}
			}
		}

		$this->load->view('yonetim/satis/siparisler/siparisler_view', $data);
	}

	function duzenle($id)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/satis/siparisler/duzenle' . $id);

		$this->db->where_not_in('siparis_flag', '-1');
		$siparis_sorgu = $this->db->get_where('siparis', array('siparis_id' => $id), 1);
		if($siparis_sorgu->num_rows() > 0)
		{
			$val = $this->validation;
			$rules['siparis_aciklama'] = 'trim|xss_clean';
			$rules['siparis_durum'] = 'trim|required|xss_clean';

			$fields['siparis_aciklama'] = 'Sipariş Durum Açıklaması';
			$fields['siparis_durum'] = 'Sipariş Durumu';

			$val->set_rules($rules);
			$val->set_fields($fields);
			if($val->run() == FALSE)
			{
				$data['siparis'] = $siparis_sorgu;
				$this->load->view('yonetim/satis/siparisler/siparis_duzenle_view',$data);			
			} else {
				$kontrol = $this->siparis_model->siparis_duzenle($val, $id, $siparis_sorgu);

				if($kontrol)
				{
					$yonetim_mesaj 				= array();
					$yonetim_mesaj['durum'] 	= '1';// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('Başarılı: Sipariş Güncellendi.');	
				} else {
					$yonetim_mesaj 				= array();
					$yonetim_mesaj['durum'] 	= '2';// 1 başarılı - 2 başarısız
					$yonetim_mesaj['mesaj']		= array('Başarısız: Sipariş Güncellenemedi.');
				}
				$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
				redirect('yonetim/satis/siparisler');
			}
		} else {
			redirect('yonetim/satis/siparisler');
		}
	}

	function siparis_yazdir()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/satis/siparisler/siparis_yazdir');

		$val = $this->validation;
		$rules['selected'] = 'trim|required|xss_clean';
		$val->set_rules($rules);
		if($val->run() == TRUE)
		{
			$data['siparisler']	= $val->selected;
			$this->load->view('yonetim/satis/siparisler/siparis_yazdir_view',$data);
		} else {
			$yonetim_mesaj 				= array();
			$yonetim_mesaj['durum'] 	= '2';// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Herhangi bir sipariş seçilmediği için yazdırma sayfası açılamadı.');
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/satis/siparisler/listele');
		}
	}

	// Tek Siparisin Durumnu Değiştirme
	function durum()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/satis/siparisler/durum');

		$val = $this->validation;
		$rules['s_id'] 	  	= 'trim|integer|xss_clean';
		$rules['s_drm']		= 'trim|integer|xss_clean';
		$val->set_rules($rules);
		if($val->run() == TRUE)
		{
			$this->db->where('siparis_id',$val->s_id);
			$this->db->update('siparis',array('siparis_flag'=>$val->s_drm));
			$sonuc['ok'] = true;
			exit(json_encode($sonuc));
		} else {
			$sonuc['ok'] = false;
			exit(json_encode($sonuc));;
		}
	}

	// Çok Siparisin Durumnu Değiştirme
	function siparisler_durum()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/satis/siparisler/siparisler_durum');

		$val = $this->validation;
		$rules['select1'] = 'trim|xss_clean';
		$rules['eylem1']  = 'trim|xss_clean';
		$val->set_rules($rules);
		if($val->run() == TRUE)
		{
			foreach( $val->select1 as $i => $r )
			{
				$this->db->where('siparis_id',$r);
				$this->db->where('siparis_flag <>',2);
				$this->db->update('siparis',array('siparis_flag'=>$val->eylem1));
			}
			redirect('yonetim/satis/siparisler');
		} else {
			redirect('yonetim/satis/siparisler');
		}
	}

	function urun_siparis()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/satis/siparisler/urun_siparis');

		$sort_lnk = "siparis_desc";
		$filter = "durum|]";
		$page = "0";

		$data['siparisler'] = $this->siparisModel->siparis_listele($sort_lnk,$filter,$page);
		$this->load->view('yonetim/satis/siparisler/siparisler_view',$data);
	}
}
?>