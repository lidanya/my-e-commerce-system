<meta http-equiv="content-type" content="text/html;charset=utf-8">
<?php
	$this->load->view(tema() . 'mail_sablon/ust_sablon');
	$_1cirenk = $this->config->item('1_renk');
	$_2cirenk = $this->config->item('2_renk');

	$this->db->select_sum('stok_tfiyat');
	$toplam_tfiyat_sorgu = $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_no));
	$toplam_tfiyat_bilgi = $toplam_tfiyat_sorgu->row();
	$stok_toplam_fiyat = $toplam_tfiyat_bilgi->stok_tfiyat;

	$siparis_bilgi = false;
	$siparis_bilgi_sorgu = $this->db->get_where('siparis', array('siparis_id' => $siparis_no), 1);
	if($siparis_bilgi_sorgu->num_rows() > 0)
	{
		$siparis_bilgi = $siparis_bilgi_sorgu->row();
	}

	$siparis_data = false;
	if($siparis_bilgi->siparis_data)
	{
		if(is_serialized($siparis_bilgi->siparis_data))
		{
			$siparis_data = @unserialize($siparis_bilgi->siparis_data);
			//echo debug($siparis_data);
		}
	}

	/* İndirim Oran & Fiyat Hesaplaması */
	$indirim_orani = '00';
	if($siparis_data)
	{
		if(array_key_exists('teslimat_bilgileri', $siparis_data))
		{
			if(array_key_exists('indirim_orani', $siparis_data['teslimat_bilgileri']))
			{
				$indirim_orani = $siparis_data['teslimat_bilgileri']['indirim_orani'];
			}
		}
	}

	$indirim_ucret = 0;
	if($indirim_orani != '00')
	{
		$indirim_ucret_hesapla = ($stok_toplam_fiyat * ((100-$indirim_orani)/100));
		$indirim_ucret = (float) ($stok_toplam_fiyat - $indirim_ucret_hesapla);
		$stok_toplam_fiyat = ($stok_toplam_fiyat - $indirim_ucret);
	}
	/* İndirim Oran & Fiyat Hesaplaması */

	/* Kargo Ücret Hesaplaması */
	$kargo_ucret = 0;
	if($siparis_data)
	{
		if(array_key_exists('teslimat_bilgileri', $siparis_data))
		{
			if(array_key_exists('kargo_ucret', $siparis_data['teslimat_bilgileri']))
			{
				$kargo_ucret = (float) $siparis_data['teslimat_bilgileri']['kargo_ucret'];
			}
		}
	}
	/* Kargo Ücret Hesaplaması */

	/* Kapıda Ödeme Ücret Hesaplaması */
	$kapida_odeme_ucret = 0;
	if($siparis_data)
	{
		if(array_key_exists('teslimat_bilgileri', $siparis_data))
		{
			if(array_key_exists('kapida_odeme_ucret', $siparis_data['teslimat_bilgileri']))
			{
				$kapida_odeme_ucret = (float) $siparis_data['teslimat_bilgileri']['kapida_odeme_ucret'];
			}
		}
	}
	/* Kapıda Ödeme Ücret Hesaplaması */

	/* Kupon İndirim Ücret Hesaplaması */
	$kupon_ucret = 0;
	if($siparis_data)
	{
		if(array_key_exists('teslimat_bilgileri', $siparis_data))
		{
			if(array_key_exists('kupon_indirim', $siparis_data['teslimat_bilgileri']))
			{
				if(array_key_exists('fiyat', $siparis_data['teslimat_bilgileri']['kupon_indirim']))
				{
					$kupon_ucret = (float) $siparis_data['teslimat_bilgileri']['kupon_indirim']['fiyat'];
				}
			}
		}
	}
	/* Kupon İndirim Ücret Hesaplaması */

	/* Kdv Hesaplama Başlangıç */
	$toplam_kdv_fiyati = 0;
	/* Kdv Hesaplama Başlangıç */
?>
   <!--Sipariş Bildirimi-->
 <div style="width:480px;margin:auto;font-size:15px;color:<?php echo $_2cirenk; ?>  !important;font-weight:bold;">Sn <b style="font-size:25px;color:<?php echo $_1cirenk; ?>  !important;"><?php echo $ad; ?> <?php echo $soyad; ?></b>,</div>
 <div style="width:480px;font-size:12px;margin:10px auto;"><?php echo $siparis_tarih; ?> tarihli siparişiniz alınmıştır. Sipariş Numaranız: <b style="color:<?php echo $_1cirenk; ?>  !important;"><?php echo $siparis_no; ?></b></div>
<div style="width:480px;font-size:12px;margin:10px auto;">Siparişlerinizi takip etmek için <a href="<?php echo ssl_url('uye/siparisler/detay/' . $siparis_no); ?>" style="color:<?php echo $_1cirenk; ?>  !important">buradan</a> yönetim panelinize ulaşabilirsiniz. Siparişinizin Detayı Aşağıdaki Gibidir:</div>


<div style="width:580px;font-size:12px;margin:10px auto;">
     <div style="width:190px;font-size:12px;text-align:left;float:left;font-weight:bold;color:<?php echo $_1cirenk; ?>  !important;text-decoration:underline;">Ürün Adı</div>
     <div style="width:150px;font-size:12px;text-align:center;float:left;font-weight:bold;color:<?php echo $_1cirenk; ?>  !important;text-decoration:underline;">Adedi</div>
     <div style="width:150px;font-size:12px;text-align:center;float:left;font-weight:bold;color:<?php echo $_1cirenk; ?>  !important;text-decoration:underline;">Tutarı</div>
     <div style="clear:both"></div>
<?php 
	$toplamtutar=0;
	foreach($sip_det_q->result() as $row):
	
	/* Kdv Hesaplama Başlangıç */
	if(config('site_ayar_kdv_goster') == '1')
	{
		$kdv_fiyati = kdv_hesapla($row->stok_tfiyat, $row->stok_kdv_orani, true);
		$toplam_kdv_fiyati += $kdv_fiyati;
	}
	/* Kdv Hesaplama Başlangıç */
?>
     <div style="width:190px;font-size:12px;text-align:left;float:left;font-weight:bold;color:#000000;margin-top:5px;background-color:#eeeeee;"><?php echo $row->name; ?></div>
     <div style="width:150px;font-size:12px;text-align:center;float:left;font-weight:bold;color:#000000;margin-top:5px;background-color:#eeeeee;"><?php echo $row->stok_miktar; ?>
     	<?php if ($row->stock_type == '1')
     	{
     		echo ' Adet';
     	} else if ($row->stock_type == '2') {
     		echo ' Yıl';
     	}
     	?>
     </div>
     <div style="width:120px;font-size:12px;text-align:center;float:left;font-weight:bold;color:<?php echo $_2cirenk; ?>  !important;margin-top:5px;background-color:#eeeeee;"><?php 
     	 echo $this->cart->format_number($row->stok_tfiyat); 
     	 ?> TL</div>
     <div style="clear:both"></div>

<?php
endforeach;
?>
	<div style="width:190px;font-size:12px;text-align:left;float:left;font-weight:bold;color:#000000;margin-top:5px;">&nbsp;</div>
	<div style="width:150px;font-size:12px;text-align:right;float:left;font-weight:bold;color:#000000;margin-top:5px;"><b>Ara Toplamı:</b></div>
	<div style="width:120px;font-size:12px;text-align:center;float:left;font-weight:bold;color:<?php echo $_1cirenk; ?>  !important;margin-top:5px;"><?php echo $this->cart->format_number($toplam_tfiyat_bilgi->stok_tfiyat) .' TL';  ?></div>
	<div style="clear:both"></div>

	<?php if($indirim_ucret != '0') { ?>
	<div style="width:190px;font-size:12px;text-align:left;float:left;font-weight:bold;color:#000000;margin-top:5px;">&nbsp;</div>
	<div style="width:150px;font-size:12px;text-align:right;float:left;font-weight:bold;color:#000000;margin-top:5px;"><b>İndirim Toplamı:</b></div>
	<div style="width:120px;font-size:12px;text-align:center;float:left;font-weight:bold;color:<?php echo $_1cirenk; ?> !important;margin-top:5px;"><?php echo '-' . $this->cart->format_number($indirim_ucret) .' TL'; ?></div>
	<div style="clear:both"></div>
	<?php } ?>

	<?php if($kargo_ucret != '0') { ?>
	<div style="width:190px;font-size:12px;text-align:left;float:left;font-weight:bold;color:#000000;margin-top:5px;">&nbsp;</div>
	<div style="width:150px;font-size:12px;text-align:right;float:left;font-weight:bold;color:#000000;margin-top:5px;"><b>Kargo Toplamı:</b></div>
	<div style="width:120px;font-size:12px;text-align:center;float:left;font-weight:bold;color:<?php echo $_1cirenk; ?> !important;margin-top:5px;"><?php echo $this->cart->format_number($kargo_ucret) .' TL'; ?></div>
	<div style="clear:both"></div>
	<?php } ?>

	<?php if($kapida_odeme_ucret != '0') { ?>
	<div style="width:190px;font-size:12px;text-align:left;float:left;font-weight:bold;color:#000000;margin-top:5px;">&nbsp;</div>
	<div style="width:150px;font-size:12px;text-align:right;float:left;font-weight:bold;color:#000000;margin-top:5px;"><b>Kapıda Ödeme Toplamı:</b></div>
	<div style="width:120px;font-size:12px;text-align:center;float:left;font-weight:bold;color:<?php echo $_1cirenk; ?> !important;margin-top:5px;"><?php echo $this->cart->format_number($kapida_odeme_ucret) .' TL'; ?></div>
	<div style="clear:both"></div>
	<?php } ?>

	<?php if($kupon_ucret != '0') { ?>
	<div style="width:190px;font-size:12px;text-align:left;float:left;font-weight:bold;color:#000000;margin-top:5px;">&nbsp;</div>
	<div style="width:150px;font-size:12px;text-align:right;float:left;font-weight:bold;color:#000000;margin-top:5px;"><b>Kupon İndirim Toplamı:</b></div>
	<div style="width:120px;font-size:12px;text-align:center;float:left;font-weight:bold;color:<?php echo $_1cirenk; ?> !important;margin-top:5px;"><?php echo '-' . $this->cart->format_number($kupon_ucret) .' TL'; ?></div>
	<div style="clear:both"></div>
	<?php } ?>

	<?php if(config('site_ayar_kdv_goster') == '1') { ?>
	<div style="width:190px;font-size:12px;text-align:left;float:left;font-weight:bold;color:#000000;margin-top:5px;">&nbsp;</div>
	<div style="width:150px;font-size:12px;text-align:right;float:left;font-weight:bold;color:#000000;margin-top:5px;"><b>KDV Toplamı:</b></div>
	<div style="width:120px;font-size:12px;text-align:center;float:left;font-weight:bold;color:<?php echo $_1cirenk; ?>  !important;margin-top:5px;"><?php echo $this->cart->format_number($toplam_kdv_fiyati) .' TL'; ?></div>
	<div style="clear:both"></div>
	<?php } ?>

	<div style="width:190px;font-size:12px;text-align:left;float:left;font-weight:bold;color:#000000;margin-top:5px;">&nbsp;</div>
	<div style="width:150px;font-size:12px;text-align:right;float:left;font-weight:bold;color:#000000;margin-top:5px;"><b>Genel Toplamı:</b></div>
	<div style="width:120px;font-size:12px;text-align:center;float:left;font-weight:bold;color:<?php echo $_1cirenk; ?>  !important;margin-top:5px;">
		<?php
			$total_price = 0;
			$total_price += $stok_toplam_fiyat;
			if(config('site_ayar_kdv_goster') == '1') {
				$total_price += $toplam_kdv_fiyati;
			}
			if($kargo_ucret > 0) {
				$total_price += $kargo_ucret;
			}
			if($kapida_odeme_ucret > 0) {
				$total_price += $kapida_odeme_ucret;
			}
			if($kupon_ucret > 0) {
				$total_price -= $kupon_ucret;
			}
			if($total_price <= 0) {
				$total_price = 0.01;
			}
			echo format_number($total_price) . ' TL';
		?>
	</div>
	<div style="clear:both"></div>

</div>

<div style="width:480px;margin:10px auto;font-size:12px;">Bildirimle alakalı yardıma ihtiyaç duyarsanız <a href="<?php echo site_url('site/iletisim'); ?>" target="_blank" style="color:<?php echo $_1cirenk; ?>  !important;">yardım merkezimizden</a> bize ulaşabilirsiniz. <br />Sabır ve anlayışınız için teşekkür ederiz</div>
<!--Sipariş Bildirimi SON-->

<?php
	$this->load->view(tema() . 'mail_sablon/alt_sablon');
?>