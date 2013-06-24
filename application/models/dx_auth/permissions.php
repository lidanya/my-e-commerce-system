<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class permissions Extends CI_Model 
{
	function __construct()
	{
		parent::__construct();

		// Other stuff
		$this->_prefix = $this->config->item('DX_table_prefix');
		$this->_table = $this->_prefix.$this->config->item('DX_permissions_table');
		$this->_roles_table = $this->_prefix.$this->config->item('DX_roles_table');
	}

	function _serialize($data)
	{
		if (is_array($data))
		{
			foreach ($data as $key => $val)
			{
				$data[$key] = str_replace('\\', '{{slash}}', $val);
			}
		}
		else
		{
			$data = str_replace('\\', '{{slash}}', $data);
		}

		return serialize($data);
	}

	function _unserialize($data)
	{
		$data = unserialize(stripslashes($data));
		
		if (is_array($data))
		{
			foreach ($data as $key => $val)
			{
				$data[$key] = str_replace('{{slash}}', '\\', $val);
			}
			
			return $data;
		}

		return str_replace('{{slash}}', '\\', $data);
	}

	function get_permissions($roles_id)
	{
		$this->db->where_in('role_id', $roles_id);
		return $this->db->get($this->_table);
	}

	function get_permissions_data($roles_id, $array_key = 'role_id', $unserialize = TRUE)
	{
		$result = array();
			
		$query = $this->get_permissions($roles_id);
		
		foreach ($query->result() as $row)
		{
			$result[$row->id] = $row->data;
		
			if ($unserialize)
			{
				$result[$row->id] = $this->_unserialize($result[$row->id]);
			}
		}
		
		return $result;
	}

	function get_permission($role_id)
	{
		$this->db->where('role_id', $role_id);
		return $this->db->get($this->_table);
	}

	function get_permission_data($role_id, $unserialize = TRUE)
	{
		$result = array();
	
		$query = $this->get_permission($role_id);
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			
			$result = $row->data;
			
			if ($unserialize)
			{				
				$result = $this->_unserialize($row->data);
			}
		}
		
		return $result;
	}

	function get_permission_value($role_id, $key)
	{
		$result = NULL;
	
		$data = $this->get_permission_data($role_id);
		
		if ( ! empty($data))
		{	
			if (array_key_exists($key, $data))
			{
				$result = $data[$key];
			}
		}
		
		return $result;
	}

	function create_permission($role_id, $data)
	{
		$data['role_id'] = $role_id;
		return $this->db->insert($this->_table, $data);
	}

	function set_permission($role_id, $data, $auto_create = TRUE)
	{
		if ($auto_create)
		{			
			$this->db->select('1', FALSE);
			$this->db->where('role_id', $role_id);
			$query = $this->db->get($this->_table);
			
			// Check if role_id exist
			if ($query->num_rows() == 0)
			{
				// Create permission
				$query = $this->create_permission($role_id, $data);
			}
			else
			{
				// Update permission
				$this->db->where('role_id', $role_id);
				$query = $this->db->update($this->_table, $data);
			}
		}
		else
		{
			// Update permission
			$this->db->where('role_id', $role_id);
			$query = $this->db->update($this->_table, $data);
		}
		
		return $query;
	}

	function set_permission_data($role_id, $permission_data, $serialize = TRUE)
	{		
		if ($serialize)
		{
			$permission_data = $this->_serialize($permission_data);
		}
		
		$data['data'] = $permission_data;
		
		return $this->set_permission($role_id, $data);
	}

	function set_permission_value($role_id, $key, $value)
	{
		// Get role_id permission data first. 
		// So the previously set permission array key won't be overwritten with new array with key $key only, 
		// when calling set_permission_data later.
		$permission_data = $this->get_permission_data($role_id);
	
		// Set value in permission data array
		$permission_data[$key] = $value;
		
		// Set permission data for role_id
		return $this->set_permission_data($role_id, $permission_data);
	}

	function check_permission($role_id)
	{
		$this->db->select('1', FALSE);
		$this->db->where('role_id', $role_id);
		
		return $this->db->get($this->_table);
	}
}