<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 **/

class campaign_model extends CI_Model 
{

	protected $campaigns = array();

	public function __construct() 
	{
		parent::__construct();
		$this->set_campaigns();
	}

	protected function set_campaigns()
	{
		$this->db->select(
			get_fields_from_table('product_special', 'ps.', array(), '')	
		);
		$this->db->from('product_special ps');
		$this->db->where('ps.date_start <= UNIX_TIMESTAMP()');
		$this->db->where('ps.date_end >= UNIX_TIMESTAMP()');
		$query = $this->db->get();
		foreach($query->result() as $result) {
			$send							= array();
			$send['product_special_id']		= $result->product_special_id;
			$send['product_id']				= $result->product_id;
			$send['user_group_id']			= $result->user_group_id;
			$send['quantity']				= $result->quantity;
			$send['priority']				= $result->priority;
			$send['price']					= $result->price;
			$send['date_start']				= $result->date_start;
			$send['date_end']				= $result->date_end;

			$key							= $result->product_id;
			$this->campaigns[$key]			= $send;
		}
	}

	public function get_campaign($product_id, $key = NULL)
	{
		$campaigns = $this->campaigns;

		if(isset($campaigns[$product_id])) {
			if($key != NULL) {
				if(isset($campaigns[$product_id][$key])) {
					return $campaigns[$product_id][$key];
				} else {
					return FALSE;
				}
			} else {
				if(!config('site_ayar_kdv_goster')){
					
				$this->db->select('tax', FALSE);
		        $this->db->from('e_product');
		        $this->db->where('product_id',$campaigns[$product_id]['product_id']);
	            $query = $this->db->get();
					
					$campaigns[$product_id]['price']=$campaigns[$product_id]['price']*(($query->row()->tax/100)+1);
				}  
				return $campaigns[$product_id];
			}
		} else {
			return FALSE;
		}
	}

	public function get_campaigns()
	{
		$campaigns = $this->campaigns;
		return $campaigns;
	}

}