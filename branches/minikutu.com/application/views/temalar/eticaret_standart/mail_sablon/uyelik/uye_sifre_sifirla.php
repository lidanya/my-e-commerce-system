<meta http-equiv="content-type" content="text/html;charset=utf-8">
<?php
	$this->load->view(tema() . 'mail_sablon/ust_sablon');
	$_1cirenk = $this->config->item('1_renk');
	$_2cirenk = $this->config->item('2_renk');
?>
	<!-- Sifremi Unuttum -->
		<br />
		<div style="margin-left:35px;font-weight:bold;font-size:14px !important;color:#ff6400 !important; !important;">Sn <b style="color:#008fff !important;font-size:18px !important;"><?php echo $adsoyad ;?></b> Bizi Tercih Ettiğiniz İçin Teşekkür Ederiz</div>
		<br />
		<div style="margin-left:35px;font-size:13px; color:#414141 !important; !important;line-height:20px;">
			Sistemimizden yeni şifre talebinde bulundunuz.<br />
			Yeni Şifreniz : <b style="color:#ff6400 !important"><?php echo $sifre;?></b>
		</div>
		<br />
		<div style="line-height:18px;font-size:16px;color:#000000 !important;background-color:#f1f1f1 !important;padding-left:35px;padding-right:15px;padding-top:12px;padding-bottom:12px;">
			<b>ÖNEMLİ: Yeni şifrenizi aktif etmek için aşağıda belirtilen aktivasyon linkine tıklamanız gerekmektedir:</b><br /><br />
			<a style="color:#ff6400 !important;" href="<?php echo $link;?>" target="_blank"><?php echo $link;?></a><br>
			<span style="color:#888888 !important;font-size:11px;">Bu aktivasyon linki 24 saat geçerli olup, 24 saat sonra geçerliliğini yitirecektir.</span>
		</div>
	<!-- Sifremi Unuttum Son -->

	<?php
		$new_products = $this->product_model->get_new_product();
		if($new_products) {
			echo '<div style="background-color:#f1f1f1 !important;font-size:18px;font-weight:bold;color:#ff6400 !important;padding-left:35px;padding-top:12px;padding-bottom:12px;">Bu Ürünlerimizi Gördünüz mü?</div><br />';
			foreach($new_products['query'] as $i => $new_product) {
				if ($new_product->price_type == '1') { //tl
					$kod		= 'TL';
					$yer		= 'sag';
				} elseif($new_product->price_type == '2') { // dolar 
					$kod		= '$';
					$yer		= 'sol';
				} elseif ($new_product->price_type == '3') { // euro
					$kod		= '€';
					$yer		= 'sol';
				}

				$resim = show_image($new_product->image, 120, 95);

				$fiyat_bilgi = fiyat_hesapla($new_product->model, 1, kur_oku('usd'), kur_oku('eur'));

					if(config('site_ayar_fiyat_goster') == '1' && $this->dx_auth->is_logged_in()) {
						if($fiyat_bilgi['stok_kampanya']) {
							if(config('site_ayar_kdv_goster')) {
								$indirim = ($fiyat_bilgi['stok_indirim']) ? '<b style="text-decoration:line-through;font-size:11px;color:#414141 !important">'. format_number($fiyat_bilgi['stok_gercek_fiyat_t']) .'</b> <span style="font-size:13px;font-weight:bold;color:#04a200 !important">'. format_number($fiyat_bilgi['fiyat_t']) .'</span>':'<span style="font-size:13px;font-weight:bold;color:#04a200 !important">'. format_number($fiyat_bilgi['fiyat_t']) .'</span>';
								$fiyat = $indirim . ' <span style="color:#414141;font-size:7px;">'. $kod .' + KDV</span>';
							} else {
								$indirim = ($fiyat_bilgi['stok_indirim']) ? '<b style="text-decoration:line-through;font-size:11px;color:#414141 !important">'. format_number($fiyat_bilgi['stok_gercek_fiyat_t']) .'</b> <span style="font-size:13px;font-weight:bold;color:#04a200 !important">'. format_number($fiyat_bilgi['fiyat_t']) .'</span>':'<span style="font-size:13px;font-weight:bold;color:#04a200 !important">'. format_number($fiyat_bilgi['fiyat_t']) .'</span>';
								$fiyat = $indirim . ' <span style="color:#414141;font-size:7px;">'. $kod .'</span>';
							}
						} else {
							if(config('site_ayar_kdv_goster')) {
								$indirim = ($fiyat_bilgi['stok_indirim']) ? '<b style="text-decoration:line-through;font-size:11px;color:#414141 !important">'. format_number($fiyat_bilgi['stok_gercek_fiyat_t']) .'</b> <span style="font-size:13px;font-weight:bold;color:#04a200 !important">'. format_number($fiyat_bilgi['fiyat_t']) .'</span>':'<span style="font-size:13px;font-weight:bold;color:#04a200 !important">'. format_number($fiyat_bilgi['fiyat_t']) .'</span>';
								$fiyat = $indirim . ' <span style="color:#414141;font-size:7px;">'. $kod .' + KDV</span>';
							} else {
								$indirim = ($fiyat_bilgi['stok_indirim']) ? '<b style="text-decoration:line-through;font-size:11px;color:#414141 !important">'. format_number($fiyat_bilgi['stok_gercek_fiyat_t']) .'</b> <span style="font-size:13px;font-weight:bold;color:#04a200 !important">'. format_number($fiyat_bilgi['fiyat_t']) .'</span>':'<span style="font-size:13px;font-weight:bold;color:#04a200 !important">'. format_number($fiyat_bilgi['fiyat_t']) .'</span>';
								$fiyat = $indirim . ' <span style="color:#414141;font-size:7px;">'. $kod .'</span>';
							}
						}
					} else {
						if(!config('site_ayar_fiyat_goster')) {
							if($fiyat_bilgi['stok_kampanya']) {
								if(config('site_ayar_kdv_goster')) {
									$indirim = ($fiyat_bilgi['stok_indirim']) ? '<b style="text-decoration:line-through;font-size:11px;color:#414141 !important">'. format_number($fiyat_bilgi['stok_gercek_fiyat_t']) .'</b> <span style="font-size:13px;font-weight:bold;color:#04a200 !important">'. format_number($fiyat_bilgi['fiyat_t']) .'</span>':'<span style="font-size:13px;font-weight:bold;color:#04a200 !important">'. format_number($fiyat_bilgi['fiyat_t']) .'</span>';
									$fiyat = $indirim . ' <span style="color:#414141;font-size:7px;">'. $kod .' + KDV</span>';
								} else {
									$indirim = ($fiyat_bilgi['stok_indirim']) ? '<b style="text-decoration:line-through;font-size:11px;color:#414141 !important">'. format_number($fiyat_bilgi['stok_gercek_fiyat_t']) .'</b> <span style="font-size:13px;font-weight:bold;color:#04a200 !important">'. format_number($fiyat_bilgi['fiyat_t']) .'</span>':'<span style="font-size:13px;font-weight:bold;color:#04a200 !important">'. format_number($fiyat_bilgi['fiyat_t']) .'</span>';
									$fiyat = $indirim . ' <span style="color:#414141;font-size:7px;">'. $kod .'</span>';
								}
							} else {
								if(config('site_ayar_kdv_goster')) {
									$indirim = ($fiyat_bilgi['stok_indirim']) ? '<b style="text-decoration:line-through;font-size:11px;color:#414141 !important">'. format_number($fiyat_bilgi['stok_gercek_fiyat_t']) .'</b> <span style="font-size:13px;font-weight:bold;color:#04a200 !important">'. format_number($fiyat_bilgi['fiyat_t']) .'</span>':'<span style="font-size:13px;font-weight:bold;color:#04a200 !important">'. format_number($fiyat_bilgi['fiyat_t']) .'</span>';
									$fiyat = $indirim . ' <span style="color:#414141;font-size:7px;">'. $kod .' + KDV</span>';
								} else {
									$indirim = ($fiyat_bilgi['stok_indirim']) ? '<b style="text-decoration:line-through;font-size:11px;color:#414141 !important">'. format_number($fiyat_bilgi['stok_gercek_fiyat_t']) .'</b> <span style="font-size:13px;font-weight:bold;color:#04a200 !important">'. format_number($fiyat_bilgi['fiyat_t']) .'</span>':'<span style="font-size:13px;font-weight:bold;color:#04a200 !important">'. format_number($fiyat_bilgi['fiyat_t']) .'</span>';
									$fiyat = $indirim . ' <span style="color:#414141;font-size:7px;">'. $kod .'</span>';
								}
							}
						} else {
							$fiyat = '<div class="urun_liste_fiyat" style="font-size: 12px;margin-top: 10px;">Ürün fiyatını görmek<br /> için lütfen üye olun.</div>';
						}
					}
				?>
				<div style="border:solid 1px #d2d2d2;width:140px;float:left;margin-left:15px;margin-right:7px;text-align:center;padding:5px;">
					<a href="<?php echo site_url($new_product->seo . '--product'); ?>" target="_blank"><img style="border:none;width:120px;height:95px;" src="<?php echo $resim; ?>" alt="<?php echo $new_product->name; ?>" title="<?php echo $new_product->name; ?>"/></a><br />
					<a style="text-decoration:none;font-size:10px;color:#ff6400 !important" href="<?php echo site_url($new_product->seo . '--product'); ?>" target="_blank"><?php echo $new_product->name;?></a><br />
					<?php echo $fiyat;?>
				</div>
				<?php 
				if(($i+1)%4 == 0){
					echo '<div style="clear:both;"></div><br />';
				}
			}
		}
	?>

	<div style="clear:both;"></div><br />
	<!-- Ürünler Son -->
	
<?php
	$this->load->view(tema() . 'mail_sablon/alt_sablon');
?>