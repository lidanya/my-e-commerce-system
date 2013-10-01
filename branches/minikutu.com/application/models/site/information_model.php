<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 **/

class information_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Information Model Yüklendi');
	}

	function get_information_by_id($information_id, $limit = 1)
	{
		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('information', 'i.', array(), ', ') . 
			get_fields_from_table('information_description', 'id.', array(), ', ')
		);
		$this->db->from('information i');
		$this->db->join('information_description id', 'i.information_id = id.information_id', 'left');
		$this->db->where('id.language_id', $language_id);
		$this->db->where('i.information_id', $information_id);
		$this->db->where('i.status', '1');
		if($limit != '-1') {
			$this->db->limit($limit);
		}
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->row();
		} else {
			return FALSE;
		}
	}

	function count_information_by_id($information_id)
	{
		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->from('information i');
		$this->db->join('information_description id', 'i.information_id = id.information_id', 'left');
		$this->db->where('id.language_id', $language_id);
		$this->db->where('i.information_id', $information_id);
		$this->db->where('i.status', '1');
		$query = $this->db->count_all_results();
		if($query) {
			return $query;
		} else {
			return FALSE;
		}
	}

	function get_information_by_seo($seo, $limit = 1)
	{
		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('information', 'i.', array(), ', ') . 
			get_fields_from_table('information_description', 'id.', array(), ', ')
		);
		$this->db->from('information i');
		$this->db->join('information_description id', 'i.information_id = id.information_id', 'left');
		$this->db->where('id.language_id', $language_id);
		$this->db->where('id.seo', $seo);
		$this->db->where('i.status', '1');
		if($limit != '-1') {
			$this->db->limit($limit);
		}
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->row();
		} else {
			return FALSE;
		}
	}

	function count_information_by_seo($seo)
	{
		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->from('information i');
		$this->db->join('information_description id', 'i.information_id = id.information_id', 'left');
		$this->db->where('id.language_id', $language_id);
		$this->db->where('id.seo', $seo);
		$this->db->where('i.status', '1');
		$query = $this->db->count_all_results();
		if($query) {
			return $query;
		} else {
			return FALSE;
		}
	}
	
	function get_information_by_seo_type($seo, $type, $limit = 1)
	{
		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('information', 'i.', array(), ', ') . 
			get_fields_from_table('information_description', 'id.', array(), ', ')
		);
		$this->db->from('information i');
		$this->db->join('information_description id', 'i.information_id = id.information_id', 'left');
		$this->db->where('id.language_id', $language_id);
		$this->db->where('id.seo', $seo);
		$this->db->where('i.status', '1');
		$this->db->where('i.type', $type);
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->row();
		} else {
			return FALSE;
		}
	}

	function count_information_by_seo_type($seo, $type)
	{
		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->from('information i');
		$this->db->join('information_description id', 'i.information_id = id.information_id', 'left');
		$this->db->where('id.language_id', $language_id);
		$this->db->where('id.seo', $seo);
		$this->db->where('i.status', '1');
		$this->db->where('i.type', $type);
		$query = $this->db->count_all_results();
		if($query) {
			return $query;
		} else {
			return FALSE;
		}
	}

	function get_information_by_category_id($category_id, $limit = 10, $sort = 'i.information_id', $order = 'desc')
	{
		$_i_array = explode(', ', get_fields_from_table('information', 'i.'));
		$_id_array = explode(', ', get_fields_from_table('information_description', 'id.'));
		$_sort_allowed = array_merge($_i_array, $_id_array);

		if( ! in_array($sort, $_sort_allowed)) {
			$sort = 'i.information_id';
		}

		$_order_allowed = array('asc', 'desc', 'random');

		if( ! in_array($order, $_order_allowed)) {
			$sort = 'desc';
		}

		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('information', 'i.', array(), ', ') . 
			get_fields_from_table('information_description', 'id.', array(), ', ')
		);
		$this->db->from('information i');
		$this->db->join('information_description id', 'i.information_id = id.information_id', 'left');
		$this->db->where('id.language_id', $language_id);
		$this->db->where('i.category_id', $category_id);
		$this->db->where('i.status', '1');
		$this->db->order_by($sort, $order);
		if($limit != '-1') {
			$this->db->limit($limit);
		}
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	function count_information_by_category_id($category_id)
	{
		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->from('information i');
		$this->db->join('information_description id', 'i.information_id = id.information_id', 'left');
		$this->db->where('id.language_id', $language_id);
		$this->db->where('i.category_id', $category_id);
		$this->db->where('i.status', '1');
		$query = $this->db->count_all_results();
		if($query) {
			return $query;
		} else {
			return FALSE;
		}
	}

	function get_information_by_type($type, $limit = 10, $sort = 'i.information_id', $order = 'desc')
	{
		$_i_array = explode(', ', get_fields_from_table('information', 'i.'));
		$_id_array = explode(', ', get_fields_from_table('information_description', 'id.'));
		$_sort_allowed = array_merge($_i_array, $_id_array);

		if( ! in_array($sort, $_sort_allowed)) {
			$sort = 'i.information_id';
		}

		$_order_allowed = array('asc', 'desc', 'random');

		if( ! in_array($order, $_order_allowed)) {
			$sort = 'desc';
		}

		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('information', 'i.', array(), ', ') . 
			get_fields_from_table('information_description', 'id.', array(), ', ')
		);
		$this->db->from('information i');
		$this->db->join('information_description id', 'i.information_id = id.information_id', 'left');
		$this->db->where('id.language_id', $language_id);
		$this->db->where('i.type', $type);
		$this->db->where('i.status', '1');
		$this->db->order_by($sort, $order);
		if($limit != '-1') {
			$this->db->limit($limit);
		}
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	function count_information_by_type($type)
	{
		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->from('information i');
		$this->db->join('information_description id', 'i.information_id = id.information_id', 'left');
		$this->db->where('id.language_id', $language_id);
		$this->db->where('i.type', $type);
		$this->db->where('i.status', '1');
		$query = $this->db->count_all_results();
		if($query) {
			return $query;
		} else {
			return FALSE;
		}
	}

	function get_information_by_type_category_id($type, $category_id, $limit = 10, $sort = 'i.information_id', $order = 'desc')
	{
		$_i_array = explode(', ', get_fields_from_table('information', 'i.'));
		$_id_array = explode(', ', get_fields_from_table('information_description', 'id.'));
		$_sort_allowed = array_merge($_i_array, $_id_array);

		if( ! in_array($sort, $_sort_allowed)) {
			$sort = 'i.information_id';
		}

		$_order_allowed = array('asc', 'desc', 'random');

		if( ! in_array($order, $_order_allowed)) {
			$sort = 'desc';
		}

		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('information', 'i.', array(), ', ') . 
			get_fields_from_table('information_description', 'id.', array(), ', ')
		);
		$this->db->from('information i');
		$this->db->join('information_description id', 'i.information_id = id.information_id', 'left');
		$this->db->where('id.language_id', $language_id);
		$this->db->where('i.category_id', $category_id);
		$this->db->where('i.type', $type);
		$this->db->where('i.status', '1');
		$this->db->order_by($sort, $order);
		if($limit != '-1') {
			$this->db->limit($limit);
		}
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	function count_information_by_type_category_id($type, $category_id)
	{
		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->from('information i');
		$this->db->join('information_description id', 'i.information_id = id.information_id', 'left');
		$this->db->where('id.language_id', $language_id);
		$this->db->where('i.category_id', $category_id);
		$this->db->where('i.type', $type);
		$this->db->where('i.status', '1');
		$query = $this->db->count_all_results();
		if($query) {
			return $query;
		} else {
			return FALSE;
		}
	}

	function get_information_category_by_id($information_category_id)
	{
		$_i_array = explode(', ', get_fields_from_table('information_category', 'ic.'));
		$_id_array = explode(', ', get_fields_from_table('information_category_description', 'icd.'));

		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('information_category', 'ic.', array(), ', ') . 
			get_fields_from_table('information_category_description', 'icd.', array(), ', ')
		);
		$this->db->from('information_category ic');
		$this->db->join('information_category_description icd', 'ic.information_category_id = icd.information_category_id', 'left');
		$this->db->where('icd.language_id', $language_id);
		$this->db->where('ic.information_category_id', $information_category_id);
		$this->db->where('ic.status', '1');
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->row();
		} else {
			return FALSE;
		}
	}

	function get_information_category_by_type_category_id($type, $information_category_id)
	{
		$_i_array = explode(', ', get_fields_from_table('information_category', 'ic.'));
		$_id_array = explode(', ', get_fields_from_table('information_category_description', 'icd.'));

		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('information_category', 'ic.', array(), ', ') . 
			get_fields_from_table('information_category_description', 'icd.', array(), ', ')
		);
		$this->db->from('information_category ic');
		$this->db->join('information_category_description icd', 'ic.information_category_id = icd.information_category_id', 'left');
		$this->db->where('icd.language_id', $language_id);
		$this->db->where('ic.type', $type);
		$this->db->where('ic.information_category_id', $information_category_id);
		$this->db->where('ic.status', '1');
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->row();
		} else {
			return FALSE;
		}
	}

	function count_information_category_by_id($information_category_id)
	{
		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->from('information_category ic');
		$this->db->join('information_category_description icd', 'ic.information_category_id = icd.information_category_id', 'left');
		$this->db->where('icd.language_id', $language_id);
		$this->db->where('ic.information_category_id', $information_category_id);
		$this->db->where('ic.status', '1');
		$query = $this->db->count_all_results();
		if($query) {
			return $query;
		} else {
			return FALSE;
		}
	}

	function get_information_category_by_parent_id($parent_id, $limit = 10, $sort = 'ic.information_category_id', $order = 'desc')
	{
		$_i_array = explode(', ', get_fields_from_table('information_category', 'ic.'));
		$_id_array = explode(', ', get_fields_from_table('information_category_description', 'icd.'));
		$_sort_allowed = array_merge($_i_array, $_id_array);

		if( ! in_array($sort, $_sort_allowed)) {
			$sort = 'ic.information_category_id';
		}

		$_order_allowed = array('asc', 'desc', 'random');

		if( ! in_array($order, $_order_allowed)) {
			$sort = 'desc';
		}

		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('information_category', 'ic.', array(), ', ') . 
			get_fields_from_table('information_category_description', 'icd.', array(), ', ')
		);
		$this->db->from('information_category ic');
		$this->db->join('information_category_description icd', 'ic.information_category_id = icd.information_category_id', 'left');
		$this->db->where('icd.language_id', $language_id);
		$this->db->where('ic.parent_id', $parent_id);
		$this->db->where('ic.status', '1');
		$this->db->order_by($sort, $order);
		if($limit != '-1') {
			$this->db->limit($limit);
		}
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	function count_information_category_by_parent_id($parent_id)
	{
		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->from('information_category ic');
		$this->db->join('information_category_description icd', 'ic.information_category_id = icd.information_category_id', 'left');
		$this->db->where('icd.language_id', $language_id);
		$this->db->where('ic.parent_id', $parent_id);
		$this->db->where('ic.status', '1');
		$query = $this->db->count_all_results();
		if($query) {
			return $query;
		} else {
			return FALSE;
		}
	}

	function get_information_category_by_type($type, $limit = 10, $sort = 'ic.information_category_id', $order = 'desc')
	{
		$_i_array = explode(', ', get_fields_from_table('information_category', 'ic.'));
		$_id_array = explode(', ', get_fields_from_table('information_category_description', 'icd.'));
		$_sort_allowed = array_merge($_i_array, $_id_array);

		if( ! in_array($sort, $_sort_allowed)) {
			$sort = 'ic.information_category_id';
		}

		$_order_allowed = array('asc', 'desc', 'random');

		if( ! in_array($order, $_order_allowed)) {
			$sort = 'desc';
		}

		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('information_category', 'ic.', array(), ', ') . 
			get_fields_from_table('information_category_description', 'icd.', array(), ', ')
		);
		$this->db->from('information_category ic');
		$this->db->join('information_category_description icd', 'ic.information_category_id = icd.information_category_id', 'left');
		$this->db->where('icd.language_id', $language_id);
		$this->db->where('ic.type', $type);
		$this->db->where('ic.status', '1');
		$this->db->order_by($sort, $order);
		if($limit != '-1') {
			$this->db->limit($limit);
		}
		$query = $this->db->get();
		if($query->num_rows()) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	function count_information_category_by_type($type)
	{
		$language_id = $this->fonksiyonlar->get_language('language_id');
		$this->db->from('information_category ic');
		$this->db->join('information_category_description icd', 'ic.information_category_id = icd.information_category_id', 'left');
		$this->db->where('icd.language_id', $language_id);
		$this->db->where('ic.type', $type);
		$this->db->where('ic.status', '1');
		$query = $this->db->count_all_results();
		if($query) {
			return $query;
		} else {
			return FALSE;
		}
	}
}