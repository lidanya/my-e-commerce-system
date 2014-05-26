<?php
/* Tanımlamalar */
$_1cirenk = $this->config->item('1_renk');
$_2cirenk = $this->config->item('2_renk');

$url_temizle = array(
	'http://' => '',
	'/' => '',
);
?>
	</div>
<!-- Sablon Alt Son-->
<div style="width:700px;background-color:#f1f1f1 !important;font-size:11px !important;color:#000000 !important;font-family:arial !important;text-align:center !important;border-top:solid 1px #d4d4d4 !important;padding-top:5px !important;padding-bottom:5px !important;">
	<a href="<?php echo site_url();?>" style="color:#008FFF !important;"><?php echo strtr(site_url(), $url_temizle); ?></a> 
	<?php echo config('firma_adi'); ?> <br />
	Tel: <?php echo config('site_ayar_sirket_tel'); ?> Faks: <?php echo config('site_ayar_sirket_fax'); ?> <a href="mailto:<?php echo config('site_ayar_email_destek'); ?>" style="color:#008FFF !important;"><?php echo config('site_ayar_email_destek'); ?></a>
</div>
<!--Ana Sablon SON-->
</body>
</html>