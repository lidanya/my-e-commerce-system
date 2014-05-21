<?php 

if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * Serkan Koch Sitemap Generator eticaretsistemim.com
 */

 

 function generate()
 {
 	$ci =& get_instance();
	
	$ci->load->model('sitemap_model');
 	$ci->load->helper('xml_serkan');
		$ci->load->helper('file');
		
		
		$urunler = $ci->sitemap_model->sitemapUrunGetir();
		$kategoriler = $ci->sitemap_model->sitemapKategoriGetir();
		//$say = count($urunler);
		
		$dom = xml_dom();
		
		
		$urlset = xml_add_child($dom, 'urlset');
		xml_add_attribute($urlset, 'xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		//xml_add_attribute($urlset, 'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
		//xml_add_attribute($urlset, 'xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9
		//http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');
		
		foreach($urunler as $urun):
			
			$seo = $urun->seo.'--product';
			$url = xml_add_child($urlset, 'url');
			xml_add_child($url, 'loc',site_url($seo));
			xml_add_child($url, 'lastmod',date('Y-m-d H:i:s',$urun->date_modified));
			xml_add_child($url, 'changefreq','daily');
			xml_add_child($url, 'priority','0.8');
			
		endforeach;
		//print_r($kategoriler); return;
		foreach($kategoriler as $kategori):
			
			$seo = '';
			if($kategori->parent_id == '0')
			{
				$seo = $kategori->seo.'--category';
			}
			elseif($kategori->parent_id != '0')
			{
				//$seo ='';
				$part[] = $kategori->seo;
			    
			    $parent = $kategori->parent_id;
				while($parent != '0')
				{
					// id'si parent olanın bilgisini getir
					$q = $ci->sitemap_model->sitemapKategoriTekGetir($parent);
				    //$seo = $parent->seo.'---'.$kategori_seo.'--category';
				    $part[]= $q->seo;
				    $parent = $q->parent_id;
				}
				
				//print_r($part); return;
				
				$parts = array_reverse($part);
				
				$i = 1;
				foreach($parts as $prt)
				{
					if($i != count($parts))
					$seo .=$prt.'---';
					else
					$seo .=$prt;	
					
					++$i;
				}
				$seo = $seo.'--category';
				
				$part = array();
			}
			$url = xml_add_child($urlset, 'url');
			xml_add_child($url, 'loc',site_url($seo));
			xml_add_child($url, 'lastmod',$kategori->date_modified);
			xml_add_child($url, 'changefreq','daily');
			xml_add_child($url, 'priority','0.8');
		
		endforeach;
		
		
		 
		//xml_add_child($book, 'title', 'Hyperion');
		//$author = xml_add_child($book, 'author', 'Dan Simmons');		
		//xml_add_attribute($author, 'birthdate', '1948-04-04');
		 
		$data = xml_print($dom,TRUE);
		//$data = xml_print($dom2,TRUE)
		//$data = 'asdasd';
	    //echo $data;

	    write_file('sitemap.xml', $data);
		
		//$this->campaigns_products(); 
		
		// create a new cURL resource
		$ch = curl_init();
		
		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, "http://www.pingsitemap.com/?action=submit&url==".base_url()."/sitemap.xml");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_NOBODY,1);
		
		// grab URL and pass it to the browser
		curl_exec($ch);
		
		// close cURL resource, and free up system resources
		curl_close($ch);
	
	    //redirect('http://www.google.com/webmasters/sitemaps/ping?sitemap=http://www.akmagaza.com/sitemap.xml');
		//redirect('http://www.pingsitemap.com/?action=submit&url=http://www.akmagaza.com/sitemap.xml');
 
 }


?>