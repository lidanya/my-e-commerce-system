<meta http-equiv="content-type" content="text/html;charset=utf-8">
<?php
	$this->load->view(tema() . 'mail_sablon/ust_sablon');
	$_1cirenk = $this->config->item('1_renk');
	$_2cirenk = $this->config->item('2_renk');
?>
<!-- Üye Bilgileri Maili -->
	<div style="width:480px;margin:auto;font-size:15px;color:<?php echo $_2cirenk; ?> !important;font-weight:bold;">Bizi tercih ettiğiniz için teşekkür ederiz.</div>
	<div style="width:480px;font-size:12px;margin:10px auto;">Şifreniz Başarıyla Değiştirildi. Artık Yeni Şifrenizi Kullanarak sistemimize <a href="<?php echo site_url('uyelik/giris'); ?>" style="color:<?php echo $_1cirenk; ?> !important;">buradan</a> Giriş yapabilirsiniz: </div>
	<div style="margin:10px auto;width:480px;">
	 <div style="width:100px;font-size:12px;text-align:right;float:left;font-weight:bold;color:<?php echo $_1cirenk; ?> !important;">Yeni Şifreniz:</div>
	 <div style="font-size:12px;text-align:right;float:left;margin-left:10px;color:<?php echo $_2cirenk; ?> !important;"><?php echo $sifre; ?></div>
	 <div style="clear:both"></div>
	</div>
	<div style="width:480px;margin:10px auto;font-size:12px;">Bildirimle alakalı yardıma ihtiyaç duyarsanız <a href="<?php echo site_url('site/iletisim'); ?>" target="_blank" style="color:<?php echo $_1cirenk; ?>;" target="_blank">yardım merkezimizden</a> bize ulaşabilirsiniz. <br />Sabır ve anlayışınız için teşekkür ederiz.</div>
<!-- Üye Bilgileri Maili SON -->
<?php
	$this->load->view(tema() . 'mail_sablon/alt_sablon');
?>