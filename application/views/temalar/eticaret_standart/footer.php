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
<div style="width: 990px; margin: 10px auto; height: 35px;"><img src="<?php echo site_resim();?>kargo_foot.png"/></div>
<div style="width: 990px; margin: 0px auto;">
    <div class="sola" style="margin-right: 10px;"><a href="#"><img src="<?php echo site_resim();?>kombin.png"/></a></div>
    <div class="sola" style="margin-right: 10px;"><a href="#"><img src="<?php echo site_resim();?>exclusive.png"/></a></div>
    <div class="sola"><a href="#"><img src="<?php echo site_resim();?>blog.png"/></a></div>
</div>
<div class="clear"></div>
<div style="width: 991px; margin: 10px auto 0;"><img src="<?php echo site_resim();?>foot-banks.png"/></div>
<div id="footer">
	<!--foust SON -->

	<div class="footalt">

		<div class="fomenu sola">
			<ul>
				<li><b>ZUCCY</b></li>
				<li><?php echo show_page('6', '', '', ''); ?></li>
				<li><?php echo show_page('7', '', '', ''); ?></li>
				<li><a href="<?php echo site_url('site/banka_bilgileri'); ?>" title="<?php echo lang('footer_bank_information'); ?>"><?php echo lang('footer_bank_information'); ?></a></li>
				<li><a href="<?php echo site_url('site/odeme_secenekleri'); ?>" title="<?php echo lang('footer_payment_options'); ?>"><?php echo lang('footer_payment_options'); ?></a></li>
			</ul>
		</div>
		<!--fomenu SON -->
		<div class="fomenu sola">
			<ul>
                <li><b>BİZE ULAŞIN</b></li>
				<li><a href="<?php echo site_url('site/musteri_hizmetleri'); ?>" title="<?php echo lang('footer_customer_services'); ?>"><?php echo lang('footer_customer_services'); ?></a></li>
				<li><a href="<?php echo site_url('site/iletisim'); ?>" title="<?php echo lang('footer_contact'); ?>"><?php echo lang('footer_contact'); ?></a></li>
				<li><a href="<?php echo site_url('site/iletisim'); ?>" title="<?php echo lang('footer_help'); ?>"><?php echo lang('footer_help'); ?></a></li>
			</ul>
		</div>
		<!--fomenu SON -->
		<div class="fologo saga">
			<a href="<?php echo site_url('site/index'); ?>">
                <img src="<?php echo site_resim();?>foot-sag.png" width="143" height="100" alt="Güvenli Alışveriş" title="3 Boyutlu Güvenlik"/>
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
	<div class="blockOverlay" style="
z-index: 1000;
border: none;
margin: 0px;
padding: 0px;
width: 100%;
height: 100%;
top: 0px;
left: 0px;
cursor: pointer;
position: fixed;
opacity: 0.6;
background-color: rgb(0, 0, 0);
display: none;;
"></div>
</body>
</html>