<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 **/

class discount_model extends CI_Model 
{

	protected $discounts = array();

	public function __construct() 
	{
		parent::__construct();
		$this->set_discounts();
	}

	protected function set_discounts()
	{
		$this->db->select(
			get_fields_from_table('product_discount', 'pd.', array(), '')	
		);
		$this->db->from('product_discount pd');
		$this->db->where('pd.date_start <= UNIX_TIMESTAMP()');
		$this->db->where('pd.date_end >= UNIX_TIMESTAMP()');
		$query = $this->db->get();
		foreach($query->result() as $result) {
			$send							= array();
			$send['product_discount_id']	= $result->product_discount_id;
			$send['product_id']				= $result->product_id;
			$send['user_group_id']			= $result->user_group_id;
			$send['quantity']				= $result->quantity;
			$send['priority']				= $result->priority;
			$send['price']					= $result->price;
			$send['date_start']				= $result->date_start;
			$send['date_end']				= $result->date_end;

			$key							= $result->product_id;
			$this->discounts[$key]			= $send;
		}
	}

	public function get_discount($product_id, $key = NULL)
	{
		$discounts = $this->discounts;

		if(isset($discounts[$product_id])) {
		$timestam=strtotime('now');
		foreach($discounts as $keyi => $vali){
		//print '---'.$keyi.'__'.$vali['product_discount_id'].'__'.$vali['price'].date("Y-m-d H:i:s",$vali['date_start']).'__'.$vali['date_end'].'__'.$timestam.'---';
		  if($vali['date_start']>$timestam || $vali['date_end']<$timestam){
		   unset($discounts[$keyi]);
		}
		}
		
			if($key != NULL) {
				if($discounts[$product_id][$key]) {
					return $discounts[$product_id][$key];
				} else {
					return FALSE;
				}
			} else {
				if(!config('site_ayar_kdv_goster')){
					
				$this->db->select('tax', FALSE);
		        $this->db->from('e_product');
		        $this->db->where('product_id',$discounts[$product_id]['product_id']);
	            $query = $this->db->get();
					$discounts[$product_id]['price']=$discounts[$product_id]['price']*(($query->row()->tax/100)+1);
				} 
				
				return $discounts[$product_id];
			}
		} else {
			return FALSE;
		}
	}

	public function get_discounts()
	{
		$discounts = $this->discounts;
		return $discounts;
	}

}