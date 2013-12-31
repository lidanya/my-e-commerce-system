<div class="clear"></div>
</div>
<!-- main son-->
<?php
$this->db->where('odeme_model', 'kredi_karti');
$this->db->where('odeme_durum', '1');
$kredi_karti_acikmi = $this->db->count_all_results('odeme_secenekleri');

$kredi_karti_durum = '';//'<a href="'. site_url('urun/kampanyali') .'" title="Kampanyalı Ürünler"><img style="height:80px;" src="'. site_resim() .'f_kampanyali.png" alt="Kampanyalı Ürünler"></a>';

$kredi_karti_durum_if = false;

if($kredi_karti_acikmi > 0)
{
	$kredi_karti_durum = '';//'<img src="'. site_resim() .'footer_guvenli_alisveris.png" alt="Güvenli Alışveriş Logo" title="İnternette Güvenli Alışveriş">';
	$kredi_karti_durum_if = true;	
}

$_3d_guvenlik = '';//'<a href="'. site_url('urun/yeni') .'" title="Yeni Ürünler"><img style="height:80px;" src="'. site_resim() .'f_yeni.png" alt="Yeni Ürünler"></a>';

if($kredi_karti_durum_if)
{
	$_3dtipleri = array('3dpay', '3dmodel', '3dhosting', '3dfull', '3dhalf', '3doosfull', '3dooshalf', '3doospay');
	$this->db->where_in('kk_banka_pos_tipi', $_3dtipleri);
	$this->db->where('kk_banka_durum', '1');
	$_3dp_durum = $this->db->count_all_results('odeme_secenek_kredi_karti');
	if($_3dp_durum > 0)
	{
		$_3d_guvenlik = '<img src="'. site_resim() .'footer_3dsecure.png" alt="3d Secure Logo" title="3 Boyutlu Güvenlik">';
	}
}

$_ssl_kodu = '';//'<a href="'. site_url('urun/indirimli') .'" title="İndirimli Ürünler"><img style="height:80px;" src="'. site_resim() .'f_indirimli.png" alt="İndirimli Ürünler"></a>';

if(config('site_ayar_ssl_kod') != '')
{
	$_ssl_kodu = config('site_ayar_ssl_kod');
}
?>
<!-- footer-->
<div id="footer">
	<div class="footust">
		<div class="foustmenu sola">
			<ul>
				<li><a class="h_ilk<?php $this->menu->menu_class(2,3,'site','index',' h_aktif',3);?>" href="<?php echo site_url('site/index'); ?>" title="<?php echo lang('header_middle_menu_mainpage'); ?>"><?php echo lang('header_middle_menu_mainpage'); ?></a></li>
				<li><a class="<?php $this->menu->menu_class(2,3,'urun','yeni','h_aktif',3);?>" href="<?php echo site_url('urun/yeni'); ?>" title="<?php echo lang('header_middle_menu_new_products'); ?>"><?php echo lang('header_middle_menu_new_products'); ?></a></li>
				<li><a class="<?php $this->menu->menu_class(2,3,'urun','kampanyali','h_aktif',3);?>" href="<?php echo site_url('urun/kampanyali'); ?>" title="<?php echo lang('header_middle_menu_campaign_products'); ?>"><?php echo lang('header_middle_menu_campaign_products'); ?></a></li>
				<li><a class="h_son<?php $this->menu->menu_class(2,3,'urun','indirimli',' h_aktif',3);?>" href="<?php echo site_url('urun/indirimli'); ?>" title="<?php echo lang('header_middle_menu_discount_products'); ?>"><?php echo lang('header_middle_menu_discount_products'); ?></a></li>
			</ul>
		</div>
		<!--fomenu SON -->
		<div id="footer_iletisim" class="saga"><?php echo config('site_ayar_sirket_tel');?></div>
	</div>
	<!--foust SON -->
	<div class="footalt">
		<div class="fomenu sola">
			<ul>
				<li><?php echo show_page('6', '', '', ''); ?></li>
				<li><?php echo show_page('7', '', '', ''); ?></li>
				<li><a href="<?php echo site_url('site/banka_bilgileri'); ?>" title="<?php echo lang('footer_bank_information'); ?>"><?php echo lang('footer_bank_information'); ?></a></li>
				<li><a href="<?php echo site_url('site/odeme_secenekleri'); ?>" title="<?php echo lang('footer_payment_options'); ?>"><?php echo lang('footer_payment_options'); ?></a></li>
			</ul>
		</div>
		<!--fomenu SON -->
		<div class="fomenu sola">
			<ul>
				<li><a href="<?php echo site_url('site/musteri_hizmetleri'); ?>" title="<?php echo lang('footer_customer_services'); ?>"><?php echo lang('footer_customer_services'); ?></a></li>
				<li><a href="<?php echo site_url('site/iletisim'); ?>" title="<?php echo lang('footer_contact'); ?>"><?php echo lang('footer_contact'); ?></a></li>
				<li><a href="<?php echo site_url('site/iletisim'); ?>" title="<?php echo lang('footer_help'); ?>"><?php echo lang('footer_help'); ?></a></li>
			</ul>
		</div>
		<!--fomenu SON -->
		<div class="fologo saga">
			<a href="<?php echo site_url('site/index'); ?>">
				<img src="<?php echo base_url(ssl_status()); ?>upload/editor/<?php echo config('site_ayar_logo'); ?>" width="140" alt="<?php echo config('site_ayar_baslik'); ?>" title="<?php echo config('site_ayar_baslik'); ?>" />
			</a>
		</div>
		<!--fologo SON -->
		<div class="tree3 saga">
			<div ><?php echo $_3d_guvenlik; ?></div>
			<div ><?php echo $kredi_karti_durum; ?></div>
			<div ><?php echo $_ssl_kodu; ?></div>
		</div>
		<!--tree3 SON -->
	</div>
	<!--foalt SON -->
	
	
</div>
<!--footer SON -->
<div id="fobildis">
	<div id="footer_bilgi">
		<div class="sola"><?php echo anchor(site_url(), config('site_ayar_copyright')); ?></div>
		<div class="saga">
			<a href="#" target="_blank" title="<?php echo lang('footer_system_name'); ?>">
				<?php echo strtr(lang('footer_copyright_text'), array('{version}' => ETICARET_VERSION)); ?>
			</a>
		</div>
	</div>
	<!-- footer_bilgi son-->
</div>
<!--fobildis SON-->
	
</body>
</html>