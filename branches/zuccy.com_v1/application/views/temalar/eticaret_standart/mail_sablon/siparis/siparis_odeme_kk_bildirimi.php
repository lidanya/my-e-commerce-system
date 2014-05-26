<meta http-equiv="content-type" content="text/html;charset=utf-8">
<?php
	$this->load->view(tema() . 'mail_sablon/ust_sablon');
	$_1cirenk = $this->config->item('1_renk');
	$_2cirenk = $this->config->item('2_renk');
?>
   <!--Sipariş Bildirimi-->
 <div style="width:480px;margin:auto;font-size:15px;color:<?php echo $_2cirenk; ?>  !important;font-weight:bold;">Sn <b style="font-size:25px;color:<?php echo $_1cirenk; ?>  !important;"><?php echo $ad; ?> <?php echo $soyad; ?></b>,</div>
 <div style="width:480px;font-size:12px;margin:10px auto;"><?php echo $siparis_tarih; ?> tarihinde yaptığınız alışveriş başarıyla tamamlanmıştır. Sipariş Numaranız: <b style="color:<?php echo $_1cirenk; ?>  !important;"><?php echo $siparis_no; ?></b></div>
<div style="width:480px;font-size:12px;margin:10px auto;">Siparişlerinizi takip etmek için <a href="<?php echo ssl_url('uye_yonetim_paneli'); ?>" style="color:<?php echo $_1cirenk; ?>  !important">buradan</a> yönetim panelinize ulaşabilirsiniz. Alışverişinizin Detayı Aşağıdaki Gibidir:</div>


<div style="width:480px;font-size:12px;margin:10px auto;">
     <div style="width:220px;font-size:12px;text-align:left;float:left;font-weight:bold;color:<?php echo $_1cirenk; ?>  !important;text-decoration:underline;">Ürün Adı</div>
     <div style="width:120px;font-size:12px;text-align:center;float:left;font-weight:bold;color:<?php echo $_1cirenk; ?>  !important;text-decoration:underline;">Adedi</div>
     <div style="width:120px;font-size:12px;text-align:center;float:left;font-weight:bold;color:<?php echo $_1cirenk; ?>  !important;text-decoration:underline;">Tutarı</div>
     <div style="clear:both"></div>
<?php 
$toplamtutar=0;

$toplam_kdv_fiyati = 0;
$this->db->select('stok_kdv_orani, stok_tfiyat');
$siparis_detay_sorgu = $this->db->get_where('siparis_detay', array('siparis_id' => $siparis_no));
foreach($siparis_detay_sorgu->result() as $siparis_detay)
{
	$toplam_kdv_fiyati += kdv_hesapla($siparis_detay->stok_tfiyat, $siparis_detay->stok_kdv_orani, true);
}

foreach($sip_det_q->result() as $row): 
	?>
     <div style="width:220px;font-size:12px;text-align:left;float:left;font-weight:bold;color:#000000;margin-top:5px;background-color:#eeeeee;"><?php echo $row->stok_aciklama; ?></div>
     <div style="width:120px;font-size:12px;text-align:center;float:left;font-weight:bold;color:#000000;margin-top:5px;background-color:#eeeeee;"><?php echo $row->stok_miktar; ?>
     	<?php if ($row->stok_tip == '1')
     	{
     		echo ' Adet';
     	} else if ($row->stok_tip == '2') {
     		echo ' Yıl';
     	}
     	?>
     </div>
     <div style="width:120px;font-size:12px;text-align:center;float:left;font-weight:bold;color:<?php echo $_2cirenk; ?>  !important;margin-top:5px;background-color:#eeeeee;"><?php 
     	 echo $this->cart->format_number($row->stok_tfiyat); 
     	 ?> TL</div>
     <div style="clear:both"></div>

<?php 
$toplamtutar=$toplamtutar+$row->stok_tfiyat;
endforeach;
?>
     
     <div style="width:220px;font-size:12px;text-align:left;float:left;font-weight:bold;color:#000000;margin-top:5px;">&nbsp;</div>
     <div style="width:120px;font-size:12px;text-align:right;float:left;font-weight:bold;color:#000000;margin-top:5px;"><b>Toplam Tutar:</b></div>
     <div style="width:120px;font-size:12px;text-align:center;float:left;font-weight:bold;color:<?php echo $_1cirenk; ?>  !important;margin-top:5px;"><?php echo $toplam; ?> TL</div>
     <div style="clear:both"></div>

	<div style="width:220px;font-size:12px;text-align:left;float:left;font-weight:bold;color:#000000;margin-top:5px;">&nbsp;</div>
	<div style="width:120px;font-size:12px;text-align:right;float:left;font-weight:bold;color:#000000;margin-top:5px;"><b>KDV Toplamı:</b></div>
	<div style="width:120px;font-size:12px;text-align:center;float:left;font-weight:bold;color:<?php echo $_1cirenk; ?>  !important;margin-top:5px;"><?php echo $this->cart->format_number($toplam_kdv_fiyati); ?> TL</div>

	<div style="clear:both"></div>

	<div style="width:220px;font-size:12px;text-align:left;float:left;font-weight:bold;color:#000000;margin-top:5px;">&nbsp;</div>
	<div style="width:120px;font-size:12px;text-align:right;float:left;font-weight:bold;color:#000000;margin-top:5px;"><b>KDV Dahil Toplam:</b></div>
	<div style="width:120px;font-size:12px;text-align:center;float:left;font-weight:bold;color:<?php echo $_1cirenk; ?>  !important;margin-top:5px;"><?php echo $this->cart->format_number(($toplam_kdv_fiyati + $genel_toplam_t)); ?> TL</div>

	<div style="clear:both"></div>
</div>
     
<div style="width:480px;margin:10px auto;font-size:12px;">Bildirimle alakalı yardıma ihtiyaç duyarsanız <a href="<?php echo site_url('ana_sayfa/iletisim'); ?>" target="_blank" style="color:<?php echo $_1cirenk; ?>  !important;">yardım merkezimizden</a> bize ulaşabilirsiniz. <br />Sabır ve anlayışınız için teşekkür ederiz</div>
<!--Sipariş Bildirimi SON-->

<?php
	$this->load->view(tema() . 'mail_sablon/alt_sablon');
?>