<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class siparis_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function siparis_durum_liste()
	{
		return $this->db->get_where('siparis_durum');
	}

	public function get_orders_by_all($page, $sort = 's.siparis_id', $order = 'desc', $filter = 's.siparis_id|]', $sort_link)
	{
		$_c_array = explode(', ', get_fields_from_table('siparis', 's.', array('siparis_id', 'kayit_tar', 'siparis_flag', 'siparis_flag_data', 'user_id')));
		$_r_array = explode(', ', get_fields_from_table('users', 'u.'));
		$_cc_array = array('uii.namesurname');
		$_cc2_array = array('namesurname');
		$_filter_allowed = array_merge($_c_array, $_r_array, $_cc_array, $_cc2_array);
		$_sort_allowed = array_merge($_c_array, $_r_array, $_cc_array, $_cc2_array);

		$per_page = (config('site_ayar_urun_yonetim_sayfa')) ? config('site_ayar_urun_yonetim_sayfa') : 20;

		if( ! in_array($sort, $_sort_allowed)) {
			$sort = 's.siparis_id';
		}

		$_order_allowed = array('asc', 'desc', 'random');

		if( ! in_array($order, $_order_allowed)) {
			$order = 'desc';
		}

		$this->db->distinct();
		$this->db->select('SQL_CALC_FOUND_ROWS ' . 
			get_fields_from_table('siparis', 's.', array('siparis_id', 'kayit_tar', 'siparis_flag', 'siparis_flag_data', 'user_id'), ', ') . 
			'CONCAT(uii.ide_adi, \' \', uii.ide_soy) as namesurname, u.username as username'
		, FALSE);
		$this->db->from('siparis s');
		$this->db->join('users u', 's.user_id = u.id', 'left');
		$this->db->join('usr_ide_inf uii', 's.user_id = uii.user_id', 'left');
		$this->db->where_not_in('siparis_flag', '-1');

		if ($filter != 's.siparis_id|]') {
			$filter_e = explode(']', $filter);
			foreach($filter_e as $yaz) {
				if($yaz != '') {
					if(preg_match('/|/i', $yaz)) {
						$explode = explode('|', $yaz);
						if((isset($explode[0]) AND $explode[0] != '') AND (isset($explode[1]) AND $explode[1] != '')) {
							if(in_array($explode[0], $_filter_allowed)) {
								if($explode[0] == 'uii.namesurname') {
									$this->db->like('CONCAT(uii.ide_adi, \' \', uii.ide_soy)', $explode[1]);
								} elseif($explode[0] == 's.kayit_tar') {
									$this->db->where('(DATE_FORMAT(FROM_UNIXTIME(s.kayit_tar), \'%Y-%m-%d\'))', $filter_data, FALSE);
								} else {
									$this->db->like($explode[0], $explode[1]);
								}
							}
						}
					}
				}
			}
		}

		$this->db->order_by($sort, $order);
		$this->db->limit($per_page, $page);
		$query = $this->db->get();
		$query_count = $this->db->select('FOUND_ROWS() as count')->get()->row()->count;

		$config['base_url'] 		= base_url() . 'yonetim/satis/siparisler/listele/' . $sort_link . '/' . $filter;
		$config['uri_segment']		= 7;
		$config['per_page'] 	  	= $per_page;
		$config['total_rows'] 	  	= $query_count;
		$config['full_tag_open']  	= 'Sayfa : ';
		$config['full_tag_close'] 	= '';
		$config['num_links'] 	  	= 6;

		$mevcut_sayfa = floor(($page / $per_page) + 1);
		$toplam_sayisi = $query_count;
		$toplam_sayfa = ceil($toplam_sayisi / $per_page);

		$config['full_tag_open'] = '<div class="pagination"><div class="links">';
		$config['full_tag_close'] = '</div><div class="results">
Toplam '. $toplam_sayfa .' sayfa içinde '. $mevcut_sayfa .'. sayfadasın, toplam sipariş sayısı '. $query_count .'</div></div>';

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
		return $query;
	}

	function siparis_duzenle($gelen_degerler, $siparis_id, $siparis_bilgi)
	{
		$siparis_bilgi = $siparis_bilgi->row();
		if($siparis_bilgi->siparis_flag != $gelen_degerler->siparis_durum)
		{
			if ($gelen_degerler->siparis_durum == '2') {
				$siparis_detay_sorgu = $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_id));
				foreach($siparis_detay_sorgu->result() as $siparis_det) {
					if ($siparis_det->stokdan_dus_durum == '0') {
						$product_query = $this->db->get_where('product', array('model' => $siparis_det->stok_kodu, 'subtract' => '1'));
						if ($product_query->num_rows()) {
							$product_info = $product_query->row();
							$quantity = ($product_info->quantity - $siparis_det->stok_miktar);
							if ($quantity < 0) {
								$quantity = 0;
							}
							$this->db->update('product', array('quantity' => (int) $quantity), array('model' => $siparis_det->stok_kodu));
						}

						$this->db->update('siparis_detay', array('stokdan_dus_durum' => '1'), array('siparis_det_id' => $siparis_det->siparis_det_id));

						if ($siparis_det->siparis_det_data) {
							$unserialize_data = @unserialize($siparis_det->siparis_det_data);
							if ($unserialize_data) {
								if (isset($unserialize_data['secenek']) AND $unserialize_data['secenek']) {
									foreach ($unserialize_data['secenek'] as $secenek) {
										if (isset($secenek['subtract']) AND $secenek['subtract']) {
											$option_query = $this->db->get_where('product_option_value', array('product_option_value_id' => (int) $secenek['product_option_value_id'], 'subtract' => '1'));
											if ($option_query->num_rows()) {
												$option_info = $option_query->row();
												$quantity = ($option_info->quantity - $siparis_det->stok_miktar);
												if ($quantity < 0) {
													$quantity = 0;
												}
												$this->db->update('product_option_value', array('quantity' => (int) $quantity), array('product_option_value_id' => (int) $secenek['product_option_value_id']));
											}
										}
									}
								}
							}
						}
					}
				}
			}

			if ($gelen_degerler->siparis_durum == '3') {
				$siparis_detay_sorgu = $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_id));
				foreach($siparis_detay_sorgu->result() as $siparis_det) {
					if ($siparis_det->stokdan_dus_durum == '1') {
						$product_query = $this->db->get_where('product', array('model' => $siparis_det->stok_kodu, 'subtract' => '1'));
						if ($product_query->num_rows()) {
							$product_info = $product_query->row();
							$quantity = ($product_info->quantity + $siparis_det->stok_miktar);
							$this->db->update('product', array('quantity' => (int) $quantity), array('model' => $siparis_det->stok_kodu));
						}

						$this->db->update('siparis_detay', array('stokdan_dus_durum' => '0'), array('siparis_det_id' => $siparis_det->siparis_det_id));

						if ($siparis_det->siparis_det_data) {
							$unserialize_data = @unserialize($siparis_det->siparis_det_data);
							if ($unserialize_data) {
								if (isset($unserialize_data['secenek']) AND $unserialize_data['secenek']) {
									foreach ($unserialize_data['secenek'] as $secenek) {
										if (isset($secenek['subtract']) AND $secenek['subtract']) {
											$option_query = $this->db->get_where('product_option_value', array('product_option_value_id' => (int) $secenek['product_option_value_id'], 'subtract' => '1'));
											if ($option_query->num_rows()) {
												$option_info = $option_query->row();
												$quantity = ($option_info->quantity + $siparis_det->stok_miktar);
												$this->db->update('product_option_value', array('quantity' => (int) $quantity), array('product_option_value_id' => (int) $secenek['product_option_value_id']));
											}
										}
									}
								}
							}
						}
					}
				}
			}

			$uye_bilgi = uye_bilgi($siparis_bilgi->user_id);

			// Create email
			$from = config('site_ayar_email_cevapsiz');
			$subject = $siparis_id . ' numaralı siparişinizin durum değişikliliği';

			$mail_data['adsoyad'] 		= $uye_bilgi->ide_adi . ' ' . $uye_bilgi->ide_soy;
			$mail_data['siparis_no']	= $siparis_id;
			$mail_data['siparis_durum']	= siparis_durum_goster($gelen_degerler->siparis_durum);

			$message = $this->load->view(tema() . 'mail_sablon/siparis/siparis_durum_degisikliligi', $mail_data, true);

			// Send email with account details
			$this->dx_auth->_email($uye_bilgi->email, $from, $subject, $message);
		}

		$this->db->where('siparis_id', $siparis_id);
		$kontrol = $this->db->update('siparis', array('siparis_flag_data' => $gelen_degerler->siparis_aciklama, 'siparis_flag' => $gelen_degerler->siparis_durum));
		if($kontrol)
		{
			return true;
		} else {
			return false;
		}
	}
}