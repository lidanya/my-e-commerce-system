<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 **/

class coupon_model extends CI_Model 
{

	public function __construct() 
	{
		parent::__construct();
	}

	public function check_coupon($val) 
	{
		$data['aciklama'] = "";

		$this->db->where('code',$val->code);
		$this->db->where('status','1');
		$sorgu = $this->db->get('coupon');
		if($sorgu->num_rows() > 0) $data['aciklama'] = lang('messages_cart_coupon_used');

		if($data['aciklama'] == "")
		{
			$this->db->where('code',$val->code);
			$this->db->where('date_end <',date('Y-m-d'));
			$this->db->where('status','0');
			$sorgu = $this->db->get('coupon');
			if($sorgu->num_rows() > 0) $data['aciklama'] = lang('messages_cart_coupon_expires');
		}

		if($data['aciklama'] == "")
		{
			$this->db->where('code',$val->code);
			$this->db->where('date_start >',date('Y-m-d'));
			$this->db->where('status','0');
			$sorgu = $this->db->get('coupon');
			if($sorgu->num_rows() > 0) $data['aciklama'] = lang('messages_cart_coupon_no_start');
		}

		if($data['aciklama'] == "")
		{
			$this->db->where('code',$val->code);
			$this->db->where('date_start <=',date('Y-m-d'));
			$this->db->where('date_end >=',date('Y-m-d'));
			$this->db->where('status','0');
			$sorgu = $this->db->get('coupon');
			if($sorgu->num_rows() > 0)
			{
				if($sorgu->row()->type == '1')
				{
					$data['aciklama'] = strtr(lang('messages_cart_coupon_percent'), array('{_discount_}' => $sorgu->row()->value));
				}
				elseif($sorgu->row()->type == '2')
				{
					$data['aciklama'] = strtr(lang('messages_cart_coupon_tl'), array('{_discount_}' => $sorgu->row()->value));
				}
				$data['tip'] = $sorgu->row()->type;
				$data['sonuc'] = "ok";
				$data['deger'] = $sorgu->row()->value;
				$this->cart->kupon_tipi($data['tip']);
				$this->cart->kupon_kodu($val->code);
				$this->cart->kupon_degeri($data['deger']);
			}
		}
		if($data['aciklama'] == '')
		{
		    $data['aciklama'] = lang('messages_cart_invalid_coupon_code');
		}
		$this->cart->set_kupon_mesaj($data['aciklama']);
		exit(json::encode($data));
	}

}