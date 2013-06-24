<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class ajax extends Public_Controller {

	/**
	 * Sepet Ajax construct
	 *
	 * @return void
	 **/

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Sepet Ajax Controller Yüklendi');
	}

	public function urun_pasif()
	{
		$sonuc = NULL;
		$hash_id = $this->input->post('hash_id');
		
		$data['rowid'] = $hash_id;
		$data['durum'] = '0'; // 1 Aktif 0 Pasif
		
		if($this->cart->update($data))
		{
			$sonuc['basarili'] = 'Sepet Ürün Silme Başarılı.';
		} else {
			$sonuc['basarisiz'] = 'Sepet Ürün Silme Başarısız.';
		}
		
		exit(json::encode($sonuc));
	}

	public function urun_aktif()
	{
		$sonuc = NULL;
		$hash_id = $this->input->post('hash_id');
		
		$data['rowid'] = $hash_id;
		$data['durum'] = '1'; // 1 Aktif 0 Pasif
		
		if($this->cart->update($data))
		{
			$sonuc['basarili'] = 'Sepet Ürün Aktif Etme Başarılı.';
		} else {
			$sonuc['basarisiz'] = 'Sepet Ürün Aktif Etme Başarısız.';
		}
		
		exit(json::encode($sonuc));
	}

	public function urun_deger_gir()
	{
		$sonuc 		= NULL;
		$hash_id 	= $this->input->post('hash_id');
		$qty		= $this->input->post('qty');
		$tip		= $this->input->post('tip');

		$data['rowid'] = $hash_id;
		$data['qty'] = $qty; // 1 Aktif 0 Pasif
		$data['tip'] = $tip;

		if($this->cart->update($data)) {
			$sonuc['basarili'] = 'Sepet Ürün Değer Girme Başarılı.';
		} else {
			$sonuc['basarisiz'] = 'Sepet Ürün Değer Girme Başarısız.';
		}
		
		exit(json::encode($sonuc));
	}

	public function urun_deger_kontrol()
	{
		$sonuc 		= NULL;
		$hash_id	= $this->input->post('hash_id');
		$qty		= $this->input->post('qty');
		$cart		= $this->cart->contents();

		if($cart[$hash_id]['qty'] AND $cart[$hash_id]['allowed_qty']) {

			$message = '';
			if ($cart[$hash_id]['allowed_qty'] < $qty) 
			{
				$message = $cart[$hash_id]['allowed_qty'];
			}

			$sonuc['basarili']['message']	= $message;
			$sonuc['basarili']['qty']		= $cart[$hash_id]['qty'];
		} else {
			$sonuc['basarisiz'] = 'Başarısız';
		}

		exit(json::encode($sonuc));
	}

	public function urun_ilerleme_kontrol()
	{
		$sonuc = NULL;
		if($this->cart->total() > 0)
		{
			$sonuc['basarili'] = 'Başarılı';
		} else {
			$sonuc['basarisiz'] = 'Başarısız';
		}
		exit(json::encode($sonuc));
	}

	public function urun_toplam_fiyat()
	{
		$hash_id = $this->input->post('hash_id');
		$cart = $this->cart->contents();
		
		if($cart[$hash_id]['subtotal'])
		{
			$sonuc['basarili'] = $this->cart->format_number($cart[$hash_id]['subtotal']);
		} else {
			$sonuc['basarisiz'] = 'Başarısız';
		}
		exit(json::encode($sonuc));
	}

	public function toplam_fiyat()
	{
		$sonuc['basarili'] = $this->cart->format_number($this->cart->total());
		exit(json::encode($sonuc));
	}

	public function kdv_fiyat()
	{
		if($this->cart->toplam_kdv())
		{
			$toplam = $this->cart->format_number($this->cart->toplam_kdv());
		} else {
			$toplam = '0,00';
		}
		$sonuc['basarili'] = $toplam;
		exit(json::encode($sonuc));
	}

	public function kdv_toplam_fiyat()
	{
		if($this->cart->toplam_kdv())
		{
			$genel_toplam = $this->cart->toplam_kdv() + $this->cart->total() - $this->cart->toplam_indirim();
			$toplam = ($genel_toplam > 0) ? $genel_toplam : 0.01;
			$toplam = $this->cart->format_number($toplam);
		} else {
			$toplam = '0,00';
		}
		$sonuc['basarili'] = $toplam;
		exit(json::encode($sonuc));
	}

	public function indirim_toplam_fiyat()
	{
		if($this->cart->toplam_indirim())
		{
			$toplam = $this->cart->format_number($this->cart->toplam_indirim());
		} else {
			$toplam = '0,00';
		}
		$sonuc['basarili'] = $toplam;
		exit(json::encode($sonuc));
	}

	/**
	 * Siparişte kullanılacak kupon kodunu uygular
	 * 
	 * @return string
	 * @author E Ticaret Sistemim (Serkan Koch)
	 **/
	public function apply_coupon_code()
	{
		$this->load->library('form_validation', null, 'fv');
		$this->fv->set_rules('coupon_code', 'lang:messages_cart_coupon_code', 'trim|required|callback__check_code');
		if($this->fv->run() === false) {
			$json = array(
				'error' => true,
				'msg' => validation_errors()
			);
			exit(json::encode($json));
		}
		$coupon = $this->db
			->select('code, type, value')
			->get_where('coupon', array('code' => $_POST['coupon_code']))
			->row();
		$json = array(
			'error' => false,
			'msg' => 'kod başarılı',
			'coupon' => array(
				'code' => $coupon->code,
				'type' => $coupon->type,
				'value' => $coupon->value
			)
		);
		exit(json::encode($json));
	}

	/**
	 * Girilen kupon kodunun uygunluğunu denetler
	 * 
	 * @author (Serkan Koch)
	 * @return bool
	 **/
	public function _check_code($code)
	{
		$count = $this->db
			->where(array('code' => $code, 'status' => '0'))
			->where("date_end > CURDATE()", null, false)
			->count_all_results('coupon');
		if(!$count) {
			$this->fv->set_message('_check_code', lang('messages_cart_invalid_coupon_code'));
			return false;
		}
		return true;
	}

	public function kupon_mesaj()
	{
		$sonuc['basarili'] = NULL;
		$sonuc['basarili'] = $this->cart->kupon_mesaj();
		exit(json::encode($sonuc));
	}

	public function kupon_kontrol()
	{
		$data['durum'] = 'Bilgi Yok!';

		$this->load->model('site/coupon_model');
		$val = $this->validation;

		$rules['code']		= "trim|xss_clean|required";
		$fields['code']		= "Kupon Kodu";

		$val->set_fields($fields);
		$val->set_rules($rules);

		if($val->run() == TRUE) {
			if($this->coupon_model->check_coupon($val)) {
				$data['durum'] = "ok";
			}
		}
		exit(json::encode($data));
	}

	function kupon_iptal()
	{
		$this->cart->kuponu_iptal_et();
		$json['durum'] = 'ok';
		exit(json::encode($json));
	}
}

/* End of file isimsiz.php */
/*  */

?>