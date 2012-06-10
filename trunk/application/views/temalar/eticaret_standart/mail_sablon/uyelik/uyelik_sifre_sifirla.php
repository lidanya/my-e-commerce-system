<?php
	$this->load->view(tema() . 'mail_sablon/ust_sablon');
	$_1cirenk = $this->config->item('1_renk');
	$_2cirenk = $this->config->item('2_renk');
?>


    <div style="width:480px;margin:auto;font-size:15px;color:<?php echo $_2cirenk; ?> !important;font-weight:bold;">Sn <b style="font-size:25px;color:<?php echo $_1cirenk; ?> !important;"><?php echo $adsoyad; ?></b>, bizi tercih ettiğiniz için teşekkür ederiz</div>
    <div style="width:480px;font-size:12px;margin:10px  auto 10px auto;">Sistemimizden yeni şifre talebinde bulundunuz.</div>
    <div style="width:90px;font-size:12px;text-align:right;float:left;font-weight:bold;color:<?php echo $_1cirenk; ?> !important;">Yeni Şifreniz:</div>
    <div style="font-size:12px;text-align:right;float:left;margin-left:10px;color:<?php echo $_2cirenk; ?> !important;"><?php echo $sifre; ?></div>
    <div style="clear:both"></div>
    <div style="width:480px;font-size:12px;margin:10px  auto 5px auto;color:#ff0000 !important">Yeni şifrenizi aktif etmek için öncelikle aşağıdaki aktivasyon linkine tıklamanız gerekmektedir:</div>
    <div style="width:480px;margin:5px auto;">
    	<a style="color:#000000 !important;font-size:12px;" href="<?php echo $link; ?>"><?php echo $link; ?></a></div>
    <div style="width:480px;margin:10px auto;font-size:10px;">Bu aktivasyon linki 24 saat geçerli olup, 24 saat sonra kullanılmadığı taktirde geçerliliğini yitirecektir.</div>
    <div style="width:480px;margin:10px auto;font-size:12px;">Bildirimle alakalı yardıma ihtiyaç duyarsanız <a href="<?php echo site_url('site/iletisim'); ?>" target="_blank" style="color:<?php echo $_1cirenk; ?> !important;">yardım merkezimizden</a> bize ulaşabilirsiniz. <br />Sabır ve anlayışınız için teşekkür ederiz</div>

<?php
	$this->load->view(tema() . 'mail_sablon/alt_sablon');
?>