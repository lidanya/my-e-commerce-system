<?php
if(!defined('BASEPATH'))
{
	header('Location: http://'. getenv('SERVER_NAME') .'/');
}

/**
 * @package E-Ticaret
 * @author E-Ticaret Sistemim
 **/

class ekle extends Public_Controller {

	/**
	 * isimsiz construct
	 *
	 * @return void
	 **/

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Sepet Ekle Controller Yüklendi');
	}

	function urun_ekle()
	{
		//print_r($_POST); return;
		$stok_kod			= $this->input->post('stok_kod');
		$stok_id			= $this->input->post('stok_id');
		$stok_adet			= $this->input->post('stok_adet');
		$stok_secenek		= $this->input->post('stok_secenek');
		$redirect_url		= $this->input->post('redirect_url');
		
		// added by serkanKOCH // sepetteki ürünlerin  resmi görüntüleniyor.
        $basket_image = $this->input->post('basket_image');
		//exit(debug($_POST));

		$product_id			= $stok_id;
		$options			= $stok_secenek;
		$option_data 		= array();
		$option_price		= 0;
		$language_id		= get_language('language_id');

		if($options) {
			foreach ($options as $product_option_id => $option_value) {
	
				$this->db->select(
					get_fields_from_table('product_option', 'po.', array('product_option_id', 'option_id'), ', ') .
					get_fields_from_table('option', 'o.', array('type'), ', ') .
					get_fields_from_table('option_description', 'od.', array('name'), '')
				);
				$this->db->from('product_option po');
				$this->db->join('option o', 'po.option_id = o.option_id', 'left');
				$this->db->join('option_description od', 'o.option_id = od.option_id', 'left');
				$this->db->where('po.product_option_id', (int) $product_option_id);
				$this->db->where('po.product_id', (int) $product_id);
				$this->db->where('od.language_id', (int) $language_id);
				$option_query = $this->db->get();
	
				if ($option_query->num_rows()) {
					$option_info = $option_query->row_array();
	
					if ($option_info['type'] == 'select' OR $option_info['type'] == 'radio') {
						$this->db->select(
							get_fields_from_table('product_option_value', 'pov.', array('option_value_id', 'quantity', 'subtract', 'price', 'price_prefix'), ', ') .
							get_fields_from_table('option_value_description', 'ovd.', array('name'), '')
						);
						$this->db->from('product_option_value pov');
						$this->db->join('option_value ov', 'pov.option_value_id = ov.option_value_id', 'left');
						$this->db->join('option_value_description ovd', 'ov.option_value_id = ovd.option_value_id', 'left');
						$this->db->where('pov.product_option_value_id', (int) $option_value);
						$this->db->where('pov.product_option_id', (int) $product_option_id);
						$this->db->where('ovd.language_id', (int) $language_id);
						$option_value_query = $this->db->get();
	
						if ($option_value_query->num_rows()) {
							$option_value_info = $option_value_query->row_array();
	
							if ($option_value_info['price_prefix'] == '+') {
								$option_price += $option_value_info['price'];
							} elseif ($option_value_info['price_prefix'] == '-') {
								$option_price -= $option_value_info['price'];
							}
	
							$option_data[] = array(
								'product_option_id'			=> $product_option_id,
								'product_option_value_id'	=> $option_value,
								'option_id'					=> $option_info['option_id'],
								'option_value_id'			=> $option_value_info['option_value_id'],
								'name'						=> $option_info['name'],
								'option_value'				=> $option_value_info['name'],
								'type'						=> $option_info['type'],
								'quantity'					=> $option_value_info['quantity'],
								'subtract'					=> $option_value_info['subtract'],
								'price'						=> $option_value_info['price'],
								'price_prefix'				=> $option_value_info['price_prefix']
							);
						}
					} elseif ($option_info['type'] == 'checkbox' AND is_array($option_value)) {
	
							foreach ($option_value as $product_option_value_id) {
								$this->db->select(
									get_fields_from_table('product_option_value', 'pov.', array('option_value_id', 'quantity', 'subtract', 'price', 'price_prefix'), ', ') .
									get_fields_from_table('option_value_description', 'ovd.', array('name'), '')
								);
								$this->db->from('product_option_value pov');
								$this->db->join('option_value ov', 'pov.option_value_id = ov.option_value_id', 'left');
								$this->db->join('option_value_description ovd', 'ov.option_value_id = ovd.option_value_id', 'left');
								$this->db->where('pov.product_option_value_id', (int) $product_option_value_id);
								$this->db->where('pov.product_option_id', (int) $product_option_id);
								$this->db->where('ovd.language_id', (int) $language_id);
								$option_value_query = $this->db->get();
	
								if ($option_value_query->num_rows()) {
									$option_value_info = $option_value_query->row_array();
	
									if ($option_value_info['price_prefix'] == '+') {
										$option_price += $option_value_info['price'];
									} elseif ($option_value_info['price_prefix'] == '-') {
										$option_price -= $option_value_info['price'];
									}
	
									$option_data[] = array(
										'product_option_id'			=> $product_option_id,
										'product_option_value_id'	=> $product_option_value_id,
										'option_id'					=> $option_info['option_id'],
										'option_value_id'			=> $option_value_info['option_value_id'],
										'name'						=> $option_info['name'],
										'option_value'				=> $option_value_info['name'],
										'type'						=> $option_info['type'],
										'quantity'					=> $option_value_info['quantity'],
										'subtract'					=> $option_value_info['subtract'],
										'price'						=> $option_value_info['price'],
										'price_prefix'				=> $option_value_info['price_prefix']
									);
								}
						}
					} elseif ($option_info['type'] == 'text' OR $option_info['type'] == 'textarea' OR $option_info['type'] == 'file') {
						$option_data[] = array(
							'product_option_id'			=> $product_option_id,
							'product_option_value_id'	=> '',
							'option_id'					=> $option_info['option_id'],
							'option_value_id'			=> '',
							'name'						=> $option_info['name'],
							'option_value'				=> $option_value,
							'type'						=> $option_info['type'],
							'quantity'					=> '',
							'subtract'					=> '',
							'price'						=> '',
							'price_prefix'				=> ''
						);
					}
				}
			}
		}
		$language_id = get_language('language_id');
		$this->db->distinct();
		$this->db->select(
			get_fields_from_table('product', 'p.', array(), ', ') . 
			get_fields_from_table('product_description', 'pd.', array(), ', ')
		, FALSE);
		$this->db->from('product p');
		$this->db->join('product_description pd', 'p.product_id = pd.product_id', 'left');
		$this->db->where('p.model', $stok_kod);
		$this->db->where('pd.language_id', $language_id);
		$this->db->where('p.date_available >= UNIX_TIMESTAMP()');
		$this->db->where('p.status', '1');
		$this->db->limit(1);
		$sorgu = $this->db->get();
		if($sorgu->num_rows()) {
			$stok_bilgi = $sorgu->row();

			$fiyat_bilgi = fiyat_hesapla($stok_kod, $stok_adet, kur_oku('usd'), kur_oku('eur'));

			$fiyat = $fiyat_bilgi['fiyat'];
			$kdv_orani = $fiyat_bilgi['kdv_orani'];

			$toplam = $fiyat + $option_price;

			$eksi_kontrol = substr($toplam, 0, 1);
			if(abs($toplam) != $toplam OR $toplam == 0 OR $toplam < 0) {
				$this->session->set_flashdata($stok_id . '_hata', 'Ürün bu seçenekler ile satın alınamaz. Bu ürünü satın almak istiyorsanız lütfen '. anchor('site/iletisim', 'çağrı merkezimizden') . ' bize ulaşın.');
				redirect($redirect_url);
			}

			if(config('site_ayar_kdv_goster')) {
				$kdv_orani_insert = (float) $kdv_orani;
				$kdv_fiyati_insert = (float) ($stok_adet * ($fiyat * $kdv_orani));
			} else {
				$kdv_orani_insert = (float) 0.00;
				$kdv_fiyati_insert = (float) ($stok_adet * $fiyat);
			}

			$data = array(
				'id' => $stok_bilgi->model,
				'qty' => $stok_adet,
				'allowed_qty' => $stok_bilgi->quantity,
				'price' => $toplam,
				'name' => $stok_bilgi->name,
				'tip' => $stok_bilgi->stock_type,
				'stok_kodu' => $stok_kod,
				'durum' => '1', // 1 Aktif 0 Pasif
				'kdv_orani' => $kdv_orani_insert,
				'kdv_fiyati' => $kdv_fiyati_insert,
				'stok_id' => $stok_bilgi->product_id,
				'secenek' => $option_data,
				'secenek_fiyat' => $option_price,
				'basket_image'=>$basket_image
			);

			if($this->cart->insert($data)) {
				
				$output='';
					
					if ($this->cart->contents()) {
						
					$cart_item = ($this->cart->total_items()) ? $this->cart->total_items() : 0;
					$output.="<big>".strtr(lang('header_large_cart_items'), array('{product_count}' => $cart_item))."</big>";
					$output.="<small><strong>".lang('header_large_cart_product_title')."</strong><em>".lang('header_large_cart_price_title')."</em></small>";	 
					$output.="<ul>";
						foreach ($this->cart->contents() as $items) { 
							 if ($items['durum']) { 
								$output .="<li>";
									$output .="<dl>";
										$output .="<dt>";
											$output .="<font title=".$items['name'].">".character_limiter($items['name'], 30)."</font> -"; 
											$output .="<i class='siterenk'>";
												$output .="(";
													$output .= $items['qty'];
													
														$tanim_bilgi = $this->yonetim_model->tanimlar_bilgi('stok_birim', $items['tip']);
														if($tanim_bilgi->num_rows() > 0)
														{
															$tanim_bilgi_b = $tanim_bilgi->row();
															$output .="<font style='cursor:pointer;' title='".$tanim_bilgi_b->tanimlar_adi."'>".$tanim_bilgi_b->tanimlar_kod."</font>";
														} else {
															$output .="<font style='cursor:pointer;' title='Ürün Birimi Bulunamadý'>bln</font>";
														}
												
												$output .=")";
											$output .="</i>";
										$output .="</dt>";
										$output .="<dd>".$this->cart->format_number($items['price'])." TL</dd>";
									$output .="</dl>";
								$output .="</li>";
							 } 
						 } 
					$output .="</ul>";
					$output .="<u class='saga siterenk'>";
						$output .="<em>".lang('header_large_cart_total_price').":</em>";
						$output .="<cite>".$this->cart->format_number($this->cart->total())." TL</cite>";
					$output .="</u>";
					} 
					$output .="<div class='clear'></div>";
					
					$output .="<div class='saltlinkler'>";
						$output .="<a title=".lang('header_large_cart_close')." href='javascript:;' onclick='sepet_kapa();' id='sepetkapa' style='position:relative;bottom:-10px;' class='sitelink sola'><b>x</b>".lang('header_large_cart_close')."</a>";
						$output .="<a href='javascript:;' onclick='redirect(\"".ssl_url("sepet/goster")."\");' class='butonum saga'>";
							$output .="<span class='butsol'></span>";
							$output .="<span class='butor'>".lang('header_large_cart_go_cart')."</span>";
							$output .="<span class='butsag'></span>";
						$output .="</a>";
					$output .="</div>";
					//salt linkler son
				$output .="</div>";
					
				$json['output'] = $output;
				
				$json['total'] = strtr(lang('header_cart_items'), array('{product_count}' => $this->cart->total_items())); 
				
				//$json['ic_total'] = strtr(lang('header_large_cart_items'), array('{product_count}' => $this->cart->total_items()));
				
				
				//echo $_POST['where']; return;
				
				//$json['ttl'] = $this->cart->format_number($this->cart->total());
                       
				//echo config('s_ayar_sepete_git'); return;
				if(config('site_ayar_sepete_git')=='1'):
					ssl_redirect('sepet/goster');
				elseif(config('site_ayar_sepete_git')=='0'):
					if(isset($_POST['where'])&& $_POST['where']=='urun_detay'):
					ssl_redirect('sepet/goster');
					endif;
				echo json_encode($json);
				endif;
				
			} else {
				redirect('');
			}
		} else {
			redirect('');
		}
	}

}

/* End of file isimsiz.php */
/*  */

?>