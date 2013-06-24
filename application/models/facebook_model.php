<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class facebook_model extends CI_Model {
	
	/**
	 * Get account facebook
	 *
	 * @access public
	 * @param string $account_id
	 * @return object account facebook
	 */
	function get_by_account_id($user_id)
	{
		$query = $this->db->get_where('users_facebook', array('user_id' => $user_id));
		if($query->num_rows()) {
			return $query->row();
		}
		return FALSE;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get account facebook
	 *
	 * @access public
	 * @param string $facebook_id
	 * @return object account facebook
	 */
	function get_by_facebook_id($facebook_id)
	{
		$query = $this->db->get_where('users_facebook', array('facebook_id' => $facebook_id));
		if($query->num_rows()) {
			return $query->row();
		}
		return FALSE;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Insert account facebook
	 *
	 * @access public
	 * @param int $account_id
	 * @param int $facebook_id
	 * @return void
	 */
	function insert($user_id, $facebook_id)
	{
		if ( ! $this->get_by_facebook_id($facebook_id))  // ignore insert
		{
			$this->db->insert('users_facebook', array(
				'user_id' => $user_id, 
				'facebook_id' => $facebook_id
			));
			return TRUE;
		}
		return FALSE;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Delete account facebook
	 *
	 * @access public
	 * @param int $facebook_id
	 * @return void
	 */
	function delete($facebook_id)
	{
		$this->db->delete('users_facebook', array('facebook_id' => $facebook_id)); 
	}
	
}


/* End of file account_facebook_model.php */
/* Location: ./application/modules/account/models/account_facebook_model.php */