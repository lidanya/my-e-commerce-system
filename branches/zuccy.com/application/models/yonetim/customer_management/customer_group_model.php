<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class customer_group_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Customer Group Model Yüklendi');
	}

	public function get_groups_by_parent_id($parent_id)
	{
		$this->db->select(get_fields_from_table('roles', 'r.', array(), ''));
		$this->db->from('roles r');
		$this->db->where('r.parent_id', $parent_id);
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	public function create_tree_group_by_parent_id($parent_id, $flush = FALSE)
	{
		static $_roles = array();
		if($_roles) {
			$_roles = array();
		}

		$this->db->select(get_fields_from_table('roles', 'r.', array('name', 'id'), ''));
		$this->db->from('roles r');
		$this->db->where('r.parent_id', (int) $parent_id);
		$this->db->order_by('r.name','asc');
		$query = $this->db->get();
		foreach($query->result() as $roles_result) {
			$this->db->select(get_fields_from_table('roles', 'r.', array('name', 'id'), ''));
			$this->db->from('roles r');
			$this->db->where('r.parent_id', (int) $roles_result->id);
			$this->db->order_by('r.name','asc');
			$sub_query = $this->db->get();
			if($sub_query->num_rows()) {
				foreach($sub_query->result() as $result) {
					$_roles[$roles_result->name][$result->id] = $result->name;
				}
			} else {
				$_roles[$roles_result->id] = $roles_result->name;
			}
		}

		return $_roles;
	}

}