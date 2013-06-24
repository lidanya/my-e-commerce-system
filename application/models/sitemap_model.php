<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim Serkankoch
 * 
 **/

class sitemap_model extends CI_Model{
		
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Sitemap Model Yüklendi');
	}
    
	
	function sitemapUrunGetir()
	{
		$this->db->select('*');
		$this->db->from('e_product');
		$this->db->join('e_product_description','e_product_description.product_id = e_product.product_id ');
		
		$q = $this->db->get();
		
		if($q->num_rows()>0)
		
		{
			return $q->result();
		}
		else
		{
			return false;
		}

	
	}
	
	function sitemapKategoriGetir()
	{
		$this->db->select('*');
		$this->db->from('e_category');
		$this->db->join('e_category_description','e_category_description.category_id = e_category.category_id ');
		
		$q = $this->db->get();
		
		if($q->num_rows()>0)
		
		{
			return $q->result();
		}
		else
		{
			return false;
		}

	
	}
	
	function sitemapKategoriTekGetir($id)
	{
		$this->db->select('*');
		$this->db->from('e_category');
		$this->db->join('e_category_description','e_category_description.category_id = e_category.category_id ');
		$this->db->where('e_category.category_id',$id);
		$q = $this->db->get();
		
		if($q->num_rows()>0)
		
		{
			return $q->row();
		}
		else
		{
			return false;
		}

	
	}
	
}	
?>