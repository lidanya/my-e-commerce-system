<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class yonetici_grup extends Admin_Controller {

	var $izin_linki;

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Müşteri Grubu Controller Yüklendi');

		$this->load->model('yonetim/yoneticigrup_model');
		$this->izin_linki = 'uye_yonetimi/yonetici_grup';
	}

	function index()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/uye_yonetimi/yonetici_grup');
		redirect('yonetim/uye_yonetimi/yonetici_grup/listele');
	}

	function listele($page = 0)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/uye_yonetimi/yonetici_grup/listele/' . $page);

		$this->output->enable_profiler(false);
		$data = array();
		$data['customers'] = array();

    	foreach ($this->yoneticigrup_model->yonetici_grup_listele()->result() as $result)
    	{
			$action = array();
			
			$action[] = array(
				'text' => 'Düzenle',
				'href' => 'yonetim/uye_yonetimi/yonetici_grup/duzenle/'.$result->id
			);

			$data['customer_groups'][] = array(
				'parent_id'    	=> $result->parent_id,
				'customer_group_id'    => $result->id,
				'name'          => $result->name,
				'fiyat_orani'   => $result->fiyat_orani,
				'fiyat_tip'  	=> $result->fiyat_tip,
				'durum'   		=> $result->flag,
				'toplam_musteri'=> $this->db->get_where('users',array('role_id'=>$result->id))->num_rows(),
				'selected'      => ($this->input->post('selected') && in_array($result->id, $this->input->post('selected'))),
				'action'        => $action
			);
		}

		$this->load->view('yonetim/uyeyonetimi/yoneticiler/yoneticigrup_listele_view', $data);
	}

	function ekle()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/uye_yonetimi/yonetici_grup/ekle');

		$val = $this->validation;
		
		$rules['name']    	= "trim|xss_clean|required";
		$rules['price']    	= "trim|xss_clean|required";
		$rules['price_tip']	= "trim|xss_clean";
		$rules['yetki']		= "trim|xss_clean";

		$fields['name'] 	= "Müşteri Grup Adı";
		$fields['price'] 	= "Müşteri Grup Fiyat Oranı";
		$fields['price_tip'] = "Müşteri Grup Fiyat Tipi";
		$fields['yetki']	= "Erişebileceği Sayfalar";

		$val->set_fields($fields);
		$val->set_rules($rules);

		if ($val->run() == FALSE)
		{
			$data['kontrol_data'] = false;
			$this->load->view('yonetim/uyeyonetimi/yoneticiler/yoneticigrup_ekle_view', $data);
		} else {
			$kontrol_data = $this->yoneticigrup_model->yonetici_grup_ekle($val);
			if($kontrol_data)
			{
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '1';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarılı: Yönetici Grubu Eklendi.');	
			} else{
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '2';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarısız: Yönetici Grubu Eklenemedi.');
			}
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/uye_yonetimi/yonetici_grup/listele');
		}
	}

	function duzenle($grup_id)
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/uye_yonetimi/yonetici_grup/duzenle/' . $grup_id);

		$val = $this->validation;
		
		$data['grup_veri']  = $this->yoneticigrup_model->yonetici_grup_veri($grup_id);
		$rules['name']    	= "trim|xss_clean|required";
		$rules['price']    	= "trim|xss_clean|required";
		$rules['price_tip']	= "trim|xss_clean";
		$rules['id']    	= "trim|xss_clean|required";
		$rules['yetki']		= "trim|xss_clean";

		$fields['name'] 	= "Müşteri Grup Adı";
		$fields['price'] 	= "Müşteri Grup Fiyat Oranı";
		$fields['price_tip']= "Müşteri Grup Fiyat Tip";
		$fields['id'] 		= "Müşteri Grup ID";
		$fields['yetki']	= "Erişebileceği Sayfalar";
		
		$val->set_fields($fields);
		$val->set_rules($rules);
		
		if ($val->run() == FALSE)
		{
			$data['kontrol_data'] = false;
			$this->load->view('yonetim/uyeyonetimi/yoneticiler/yoneticigrup_duzenle_view', $data);
		} else {
			$kontrol_data = $this->yoneticigrup_model->yonetici_grup_duzenle($val);
			if($kontrol_data)
			{
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '1';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarılı: Yönetici Grubu Güncellendi.');	
			} else {
				$yonetim_mesaj 				= array();
				$yonetim_mesaj['durum'] 	= '2';// 1 başarılı - 2 başarısız
				$yonetim_mesaj['mesaj']		= array('Başarısız: Yönetici Grubu Güncellenemedi.');
			}
			$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
			redirect('yonetim/uye_yonetimi/yonetici_grup/listele');
		}
	}

	function sil()
	{
		sayfa_kontrol($this->izin_linki, 'yonetim/uye_yonetimi/yonetici_grup/sil');

		$_selected = $this->input->post('selected');

		$kontrol_data = $this->yoneticigrup_model->yonetici_grup_sil($_selected);
		if($kontrol_data)
		{
			$yonetim_mesaj 				= array();
			$yonetim_mesaj['durum'] 	= '1';// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Başarılı: Silme İşlemi Tamamlandı.');	
		} else {
			$yonetim_mesaj 				= array();
			$yonetim_mesaj['durum'] 	= '2';// 1 başarılı - 2 başarısız
			$yonetim_mesaj['mesaj']		= array('Başarısız: Silme İşlemi Tamamlanamadı.');
		}

		$this->session->set_flashdata('yonetim_mesaj', $yonetim_mesaj);
		redirect('yonetim/uye_yonetimi/yonetici_grup/listele');
	}
}

/* End of file isimsiz.php */
/*  */

?>