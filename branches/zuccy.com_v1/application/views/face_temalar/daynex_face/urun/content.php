<div id="orta" class="sola">
<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
		var jqzoom_options = {
			preloadText: '<?php echo lang("messages_product_detail_jqzoom_loading"); ?>',
			title: false
		};
		$('.jqzoom').jqzoom(jqzoom_options);
		$("#sosyal_paylasimlar").jsocial({
			highlight: true,
			buttons: "facebook,twitter,yahoo,google,live,digg,linkedin,newsvine,delicious,bligg,technorati", 
			imagedir: "<?php echo face_resim(); ?>sosyal_paylasimlar/kucuk/", 
			imageextension: "gif", 
			blanktarget: true,
			longurl: "<?php echo face_site_url($product_info->seo . '--product'); ?>"
		});
		$('#urun_resim a').lightBox();
	});
</script>
<style>
	.jsocial_button {padding: 2px !important;display:block !important;float: left !important;}
	.jsocial_button img {}
	#sosyal_paylasimlar {height: 16px !important;}
</style>
<?php
	if($this->session->flashdata($product_info->product_id . '_hata')) {
		echo '<div class="uy_mesaj u_kirmizi_bg">'. $this->session->flashdata($product_info->product_id . '_hata') .'</div>';
	}
?>
<div id="urun">
	<h1 id="urun_baslik"><?php echo $product_info->name; ?></h1>

	<div id="urun_resim_cont" class="sola">
		<div id="urun_resim" style="position:relative;">
			<?php
				if($product_info->image) {
					if(file_exists(DIR_IMAGE . $product_info->image)) {
						$resim = show_image($product_info->image, 300, 300);
					} else {
						$resim = show_image('no-image.jpg', 300, 300);
					}
				} else {
					$resim = show_image('no-image.jpg', 300, 300);
				}
				if($product_info->image) {
			?>
				<a href="<?php echo base_url(ssl_status()) . 'upload/editor/' . $product_info->image; ?>" id="buyuk_resim_a" title="<?php echo $product_info->name; ?>" class="jqzoom"> 
					<img src="<?php echo $resim; ?>" id="buyuk_resim"> 
				</a>
			<?php } else { ?>
				<a href="<?php echo $resim; ?>" id="buyuk_resim_a" title="<?php echo $product_info->name; ?>" class="jqzoom"> 
					<img src="<?php echo $resim; ?>" alt="<?php echo $product_info->name; ?>" id="buyuk_resim"> 
				</a>
			<?php } ?>
		</div>
		<?php
			$_r_kucuk = show_image($product_info->image, 70, 66);
			$_r_buyuk = show_image($product_info->image, 300, 300);
			echo '<a class="urun_diger_resim sola" href="javascript:;" onclick="$(\'#buyuk_resim\').attr(\'src\',\''. $_r_buyuk .'\');$(\'#buyuk_resim_a\').attr(\'href\',\''. base_url(ssl_status()) . 'upload/editor/' .  $product_info->image .'\');"><image src="'. $_r_kucuk .'"/></a>';
			if($product_images) {
				foreach($product_images as $image) {
					if($image->image != '') {
						if(file_exists(DIR_IMAGE . $image->image)) {
							$resim_buyuk = show_image($image->image, 300, 300);
							$resim = show_image($image->image, 70, 66);
						} else {
							$resim = show_image('no-image.jpg', 70, 66);
							$resim_buyuk = show_image('no-image.jpg', 300, 300);
						}
					} else {
						$resim = show_image('no-image.jpg', 70, 66);
						$resim_buyuk = show_image('no-image.jpg', 300, 300);
					}
					echo '<a class="urun_diger_resim sola" href="javascript:;" onclick="$(\'#buyuk_resim\').attr(\'src\',\''. $resim_buyuk .'\');$(\'#buyuk_resim_a\').attr(\'href\',\''. base_url(ssl_status()) . 'upload/editor/' .  $image->image .'\');"><image src="'. $resim .'"/></a>';
				}
			}
		?>
		<div class="clear"></div>
	</div>
		<div id="hidden_ids" style="display:none;">
		<?php
			if($product_info->quantity) {
				echo '<form action="'. ssl_url('sepet/ekle/urun_ekle/index') .'" name="sepet_ekle" id="sepet_ekle" method="post" target="_top">';
				echo '<input type="hidden" name="stok_kod" id="stok_kod" value="'. $product_info->model .'" />';
				echo '<input type="hidden" name="stok_id" id="stok_id" value="'. $product_info->product_id .'" />';
				echo '<input type="hidden" name="stok_adet" id="stok_adet" value="1" />';
				echo '<input type="hidden" name="redirect_url" id="redirect_url" value="'. current_url(ssl_status()) .'" />';
			}
		?>
		</div>
		<div id="urun_detay" class="saga">
			<?php
			if(config('site_ayar_urun_kodu_goster') == '1') {
				echo '<div class="urun_detay_oge"><span class="u_baslik sola">'. lang('messages_product_detail_product_code') .'</span>';
				echo '<span class="u_oge sola" style="width: 250px;">'. $product_info->model .'</span></div>';
			}
			?>
			<?php 
			if($product_info->manufacturer != '') {
				?>
			<div class="urun_detay_oge">
				<span class="u_baslik sola"><?php echo lang('messages_product_detail_product_brand'); ?></span>
				<span class="u_oge sola"><?php echo $product_info->manufacturer; ?></span>
			</div>
			<?php } ?>
			<?php
				$fiyat_bilgi = fiyat_hesapla($product_info->model, 1, kur_oku('usd'), kur_oku('eur'), true);

				$fiyat_b = format_number($fiyat_bilgi['fiyat_t']);
				$bul_b = strpos($fiyat_b, ',');
				$kurus_b = substr($fiyat_b, $bul_b+1);
				$tl_b = substr($fiyat_b, 0, $bul_b);

				$fiyat_g = format_number($fiyat_bilgi['stok_gercek_fiyat_t']);
				$bul_g = strpos($fiyat_g, ',');
				$kurus_g = substr($fiyat_g, $bul_g+1);
				$tl_g = substr($fiyat_g, 0, $bul_g);

				$fiyat_k = format_number($fiyat_bilgi['fiyat_t'] + $fiyat_bilgi['kdv_fiyat']);
				$bul_k = strpos($fiyat_k, ',');
				$kurus_k = substr($fiyat_k, $bul_k+1);
				$tl_k = substr($fiyat_k, 0, $bul_k);

				$kdv_goster = (config('site_ayar_kdv_goster')) ? ' + ' . lang('messages_product_detail_vat_text') : NULL;

				if(config('site_ayar_fiyat_goster') == '1' && $this->dx_auth->is_logged_in()) {
					if($fiyat_bilgi['stok_indirim']) {
						$fiyat = '<span class="sola"><b class="u_cizgili">'. $tl_g .'<span>.'. $kurus_g . ' ' . $fiyat_bilgi['fiyat_tur'] . $kdv_goster .'</span></b>
						&nbsp; <b class="siterenk">' . $tl_b . '<span>.' . $kurus_b . ' ' . $fiyat_bilgi['fiyat_tur'] . $kdv_goster . '</span></b>
						</span>';
					} else {
						$fiyat = '<b class="siterenk">'. $tl_b .'<span>.'. $kurus_b . ' ' . $fiyat_bilgi['fiyat_tur'] . $kdv_goster .'</span></b>';
					}
				} else {
					if(config('site_ayar_fiyat_goster') == '0') {
						if($fiyat_bilgi['stok_indirim']) {
							$fiyat = '<span class="sola"><b class="u_cizgili">'. $tl_g .'<span>.'. $kurus_g . ' ' . $fiyat_bilgi['fiyat_tur'] . $kdv_goster .'</span></b></span> &nbsp; <b class="u_yesil">' . $tl_b . '<span>.' . $kurus_b . ' ' . $fiyat_bilgi['fiyat_tur'] . $kdv_goster . '</span></b></span>';
						} else {
							$fiyat = '<b class="siterenk">'. $tl_b .'<span>.'. $kurus_b . ' ' . $fiyat_bilgi['fiyat_tur'] . $kdv_goster .'</span></b>';
						}
					} else {
						$fiyat = '<span style="font-size: 11px;">'. lang('messages_product_detail_product_price_reg') .'</span>';
					}
				}
				?>
			<div class="urun_detay_oge">
				<span class="u_baslik sola"><?php echo lang('messages_product_detail_product_price'); ?></span>
				<span class="u_oge sola" style="width: 250px;"><?php echo $fiyat; ?></span>
			</div>
			<?php
				$kampanya = NULL;
				$kampanya_kontrol = $this->campaign_model->get_campaign($product_info->product_id);
				if($kampanya_kontrol) {
					$kampanya_bilgi = $kampanya_kontrol;

					$fiyat_kp = format_number($kampanya_bilgi['price']);
					$bul_kp = strpos($fiyat_kp, ',');
					$kurus_kp = substr($fiyat_kp, $bul_kp+1);
					$tl_kp = substr($fiyat_kp, 0, $bul_kp);

					$kdv_goster = (config('site_ayar_kdv_goster') == '1') ? ' + ' . lang('messages_product_detail_vat_text') : NULL;

					if(config('site_ayar_fiyat_goster') == '1' && $this->dx_auth->is_logged_in()) {
						$kampanya = '<div class="urun_detay_oge"><span class="u_baslik sola">'. lang('messages_product_detail_product_campaign') .'</span>';
						$_kf_1 = strtr(lang('messages_product_detail_product_campaign_price'), array('{price}' => '<b class="u_yesil">' . $tl_kp . '<span>.' . $kurus_kp . ' ' . $fiyat_bilgi['fiyat_tur'] . $kdv_goster . '</span></b>', '{piece}' => $kampanya_bilgi['quantity']));
						$kampanya .= '<span class="u_oge sola" style="font-size: 10px; width:250px;">' . $_kf_1 . '</span>';
						$kampanya .= '</span></span></div>';
					} else {
						if(config('site_ayar_fiyat_goster') == '0') {
							$kampanya = '<div class="urun_detay_oge"><span class="u_baslik sola">'. lang('messages_product_detail_product_campaign') .'</span>';
							$_kf_2 = strtr(lang('messages_product_detail_product_campaign_price'), array('{price}' => ' &nbsp; <b class="u_yesil">' . $tl_kp . '<span>.' . $kurus_kp . ' ' . $fiyat_bilgi['fiyat_tur'] . $kdv_goster . '</span></b>', '{piece}' => $kampanya_bilgi['quantity']));
							$kampanya .= '<span class="u_oge sola" style="font-size: 10px; width:250px;">' . $_kf_2 . '</span>';
							$kampanya .= '</span></span></div>';
						} else {
							$kampanya = '<div class="urun_detay_oge"><span class="u_baslik sola">'. lang('messages_product_detail_product_campaign') .'</span>';
							$kampanya .= '<span class="u_oge sola" style="font-size: 11px; width:250px;">'. lang('messages_product_detail_product_price_reg') .'</span>';
							$kampanya .= '</span></span></div>';
						}
					}
				}
				echo $kampanya
			?>
			<?php
			if(config('site_ayar_fiyat_goster') == '1' AND $this->dx_auth->is_logged_in()) {
				if(config('site_ayar_kdv_goster') == '1') {
			?>
			<div class="urun_detay_oge">
				<span class="u_baslik sola"><?php echo lang('messages_product_detail_with_vat'); ?></span>
				<?php if(config('site_ayar_fiyat_goster') == '1' && $this->dx_auth->is_logged_in()) { ?>
				<span class="u_oge sola"><b class="siterenk"><?php echo $tl_k; ?><span>.<?php echo $kurus_k . ' ' .  $fiyat_bilgi['fiyat_tur']; ?></span></b></span>
				<?php } else { ?>
					<span class="u_oge sola" style="width: 250px;">
						<span style="font-size: 11px;"><?php echo lang('messages_product_detail_product_price_reg'); ?></span>
					</span>
				<?php } ?>
			</div>
			<?php
				}
			} else {
				if(config('site_ayar_fiyat_goster') == '0')	{
					if(config('site_ayar_kdv_goster') == '1') {
				?>
				<div class="urun_detay_oge">
					<span class="u_baslik sola"><?php echo lang('messages_product_detail_with_vat'); ?></span>
					<span class="u_oge sola"><b class="siterenk"><?php echo $tl_k; ?><span>.<?php echo $kurus_k . ' ' . $fiyat_bilgi['fiyat_tur']; ?></span></b></span>
				</div>
				<?php
					}
				} else {
					if(config('site_ayar_kdv_goster') == '1') {
				?>
				<div class="urun_detay_oge">
					<span class="u_baslik sola"><?php echo lang('messages_product_detail_with_vat'); ?></span>
					<span class="u_oge sola" style="width: 250px;">
						<span style="font-size: 11px;"><?php echo lang('messages_product_detail_product_price_reg'); ?></span>
					</span>
				</div>
				<?php
					}
				}
			}
			?>
			<div class="urun_detay_oge">
				<span class="u_baslik sola"><?php echo lang('messages_product_detail_popularity'); ?></span>
				<?php if($product_review_avg) { ?>
					<span class="u_oge sola"><img src="<?php echo face_resim(); ?>stars_<?php echo $product_review_avg; ?>.png" alt="Oy <?php echo $product_review_avg; ?>" /></span>
				<?php } else { ?>
					<span class="u_oge sola"><?php echo lang('messages_product_detail_popularity_no_votes'); ?></span>
				<?php } ?>
			</div>
			<div class="urun_detay_oge">
				<span class="u_baslik sola"><?php echo lang('messages_product_detail_stock_status'); ?></span>
				<?php if($product_info->quantity) { ?>
				<span class="u_oge sola stok_var"><?php echo lang('messages_product_detail_stock_in_stocks'); ?></span>
				<?php } else { ?>
				<span class="u_oge sola stok_yok"><?php echo lang('messages_product_detail_stock_no_stocks'); ?></span>
				<?php } ?>
			</div>
			<?php if ($product_option) { ?>
			<div class="urun_detay_oge" style="height:auto;width:390px;padding:0 10px 10px 0;margin-top:2px;margin-bottom:15px;">
				<div id="product_options">
				<span class="u_baslik sola"><?php echo lang('messages_product_detail_options'); ?></span>
				<span class="u_oge sola" style="width:250px;">
					<?php foreach ($product_option as $option) { ?>
						<?php if ($option['type'] == 'select') { ?>
							<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
								<?php if ($option['required']) { ?>
									<span class="required">*</span>
								<?php } ?>
								<b><?php echo $option['name']; ?>:</b><br />
								<select name="stok_secenek[<?php echo $option['product_option_id']; ?>]">
									<option value=""><?php echo lang('messages_select_select'); ?></option>
									<?php foreach ($option['option_value'] as $option_value) { ?>
										<option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
										<?php if ($option_value['price']) { ?>
											<?php if($option_value['price'] > 0) { ?>
												(<?php echo $option_value['price_prefix']; ?><?php echo format_number($option_value['price']); ?>)
											<?php } ?>
										<?php } ?>
										</option>
									<?php } ?>
								</select>
							</div>
							<br />
						<?php } ?>
						<?php if ($option['type'] == 'radio') { ?>
							<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
								<?php if ($option['required']) { ?>
									<span class="required">*</span>
								<?php } ?>
								<b><?php echo $option['name']; ?>:</b><br />
								<?php foreach ($option['option_value'] as $option_value) { ?>
									<input type="radio" name="stok_secenek[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
									<label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
										<?php if ($option_value['price']) { ?>
											<?php if($option_value['price'] > 0) { ?>
												(<?php echo $option_value['price_prefix']; ?><?php echo format_number($option_value['price']); ?>)
											<?php } ?>
										<?php } ?>
									</label>
									<br />
								<?php } ?>
							</div>
							<br />
						<?php } ?>
						<?php if ($option['type'] == 'checkbox') { ?>
							<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
								<?php if ($option['required']) { ?>
									<span class="required">*</span>
								<?php } ?>
								<b><?php echo $option['name']; ?>:</b><br />
								<?php foreach ($option['option_value'] as $option_value) { ?>
									<input type="checkbox" name="stok_secenek[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
									<label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"> <?php echo $option_value['name']; ?>
										<?php if ($option_value['price']) { ?>
											<?php if($option_value['price'] > 0) { ?>
												(<?php echo $option_value['price_prefix']; ?><?php echo format_number($option_value['price']); ?>)
											<?php } ?>
										<?php } ?>
									</label>
									<br />
								<?php } ?>
							</div>
							<br />
						<?php } ?>
						<?php if ($option['type'] == 'text') { ?>
							<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
								<?php if ($option['required']) { ?>
									<span class="required">*</span>
								<?php } ?>
								<b><?php echo $option['name']; ?>:</b><br />
								<input type="text" name="stok_secenek[<?php echo $option['product_option_id']; ?>]" maxlength="<?php echo $option['character_limit']; ?>" value="<?php echo $option['option_value']; ?>" onclick="if(this.value==this.defaultValue){this.value='';}" onblur="if(this.value==''){this.value=this.defaultValue;}" /><span title="max <?php echo $option['character_limit']; ?> karakter" style="margin-left:5px;"><?php echo $option['character_limit']; ?></span>
							</div>
							<br />
						<?php } ?>
						<?php if ($option['type'] == 'textarea') { ?>
							<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
								<?php if ($option['required']) { ?>
									<span class="required">*</span>
								<?php } ?>
								<b><?php echo $option['name']; ?>:</b><br />
								<textarea name="stok_secenek[<?php echo $option['product_option_id']; ?>]" cols="40" rows="5"><?php echo $option['option_value']; ?></textarea>
							</div>
							<br />
						<?php } ?>
						<?php if ($option['type'] == 'file') { ?>
							<div id="option-<?php echo $option['product_option_id']; ?>" class="option">
								<?php if ($option['required']) { ?>
									<span class="required">*</span>
								<?php } ?>
								<b><?php echo $option['name']; ?>:</b><br />
								<div style="float:left;margin-top:5px;margin-right:5px;" id="button-option-<?php echo $option['product_option_id']; ?>" class="button"><span>Dosya Yükle</span></div> - <span style="color:red;">1024 x 768 - 1MB</span>
								<input type="hidden" name="stok_secenek[<?php echo $option['product_option_id']; ?>]" value="" />
							</div>
							<br />
						<?php } ?>
					<?php } ?>
				</span>
				</div>	
			</div>
			<div class="clear"></div>
			<?php } ?>
			<div id="urun_detay_satinal_butonlar">
				<?php if(config('site_ayar_fiyat_goster') == '1' AND $this->dx_auth->is_logged_in()) { ?>
					<?php if($product_info->quantity) { ?>
					<?php if(!$product_option) { ?>
					<a class="buton_hizli_al sola" href="javascript:;" rel="nofollow" onclick="redirect('<?php echo ssl_url('odeme/adim_1/hizli/'. $product_info->model); ?>');"><?php echo lang('messages_product_detail_buy_now'); ?></a>
					<?php } ?>
					<a class="buton_sepete_ekle sola" href="javascript:;" rel="nofollow" onclick="sepete_ekle();">Satın Al<?php //echo lang('messages_product_detail_add_to_cart'); ?></a>
					<?php } else { ?>
					<div class="buton_stok_yok sola" style="display:inline-block;"><?php echo lang('messages_product_detail_stock_no_stocks'); ?></div>
					<?php } ?>
				<?php } else { ?>
					<?php if(config('site_ayar_fiyat_goster') == '0') { ?>
					<?php if($product_info->quantity) { ?>
					<?php if(!$product_option) { ?>
					<a class="buton_hizli_al sola" href="javascript:;" rel="nofollow" onclick="redirect('<?php echo ssl_url('odeme/adim_1/hizli/'. $product_info->model); ?>');"><?php echo lang('messages_product_detail_buy_now'); ?></a>
					<?php } ?>
					<a class="buton_sepete_ekle sola" href="javascript:;" rel="nofollow" onclick="sepete_ekle();">Satın Al<?php //echo lang('messages_product_detail_add_to_cart'); ?></a>
					<?php } else { ?>
					<div class="buton_stok_yok sola" style="display:inline-block;"><?php echo lang('messages_product_detail_stock_no_stocks'); ?></div>
					<?php } ?>
					<?php } else { ?>
					<span style="font-weight: bold;"><?php echo lang('messages_product_detail_product_price_reg'); ?></span>
					<?php } ?>
				<?php } ?>
				<div class="clear"></div>
			</div>
			<div id="urun_detay_butonlar">
				<a href="<?php echo face_site_url($product_info->seo . '--product'); ?>" class="u_favori jqbookmark"><?php echo lang('messages_product_detail_add_to_favorites'); ?></a>
				<a href="Javascript:;" onclick="window.print();" class="u_yazdir"><?php echo lang('messages_product_detail_print'); ?></a>
				<a href="<?php echo current_url(); ?>#Yorumlar" onclick="$('#linkYorumlar').click();" class="u_yorum"><?php echo lang('messages_product_detail_add_comment'); ?></a>
			</div>
			<div id="urun_paylas_metin" class="sola"><?php echo lang('messages_product_detail_share_product'); ?> : </div>
			<div id="urun_paylas" class="sola">
				<p id="sosyal_paylasimlar"></p>
			</div>
			<div class="clear"></div>
		</div>
</div>
<?php 
	if($product_info->quantity) {
		echo '</form>';
	}
?>
		<!--tab menu baş -->
		<div id="urun_tablar">
			<a href="<?php echo current_url(); ?>#Urun_Aciklamasi" tab="#tab_aciklama" class="sola"><span class="u_detayli"><?php echo lang('messages_product_detail_description'); ?></span></a>
			<?php
				if($product_info->feature_status) {
			?>
			<a href="<?php echo current_url(ssl_status()); ?>#Urun_Ozellikleri" tab="#tab_ozellikler" class="sola"><span class="u_ozellikli"><?php echo lang('messages_product_detail_properties'); ?></span></a>
			<?php
				}
			?>
			<a href="<?php echo current_url(ssl_status()); ?>#Yorumlar" tab="#tab_yorumlar" class="sola" id="linkYorumlar"><span class="u_yorumlu"><?php echo lang('messages_product_detail_comments'); ?></span></a>
			<a href="<?php echo current_url(ssl_status()); ?>#Taksit_Secenekleri" tab="#tab_taksit" class="sola"><span class="u_taksitli"><?php echo lang('messages_product_detail_instalment'); ?></span></a>
			<a href="<?php echo current_url(ssl_status()); ?>#Diger_Fotograflar" tab="#tab_fotograflar" class="sola"><span class="u_fotolu"><?php echo lang('messages_product_detail_pictures'); ?></span></a>
			<a href="<?php echo current_url(ssl_status()); ?>#Videolar" tab="#tab_videolar" class="sola"><span class="u_videolu"><?php echo lang('messages_product_detail_videos'); ?></span></a>
			<div class="u_bosluk sola"></div>
			<div class="clear"></div>
		</div>

		<div id="urun_tab_cont">
			<?php
				if($product_info->feature_status) {
			?>
			<!-- urun_ozellikleri -->
			<div class="urun_tab_container" id="tab_ozellikler">
				<?php
					if($product_feature) {
						$oi = 0;
						foreach($product_feature as $feature) {
							$z = $oi%2;
							if ($z==0) {
								$uo_span_class = " uo_gri";
							} else {
								$uo_span_class = "";	
							}
							if($feature->name AND $feature->value) {
								$oi++;
				?>
				<div class="uo_oge<?php echo $uo_span_class; ?>">
					<span class="uo_baslik sola"><?php echo $feature->name; ?></span>
					<span class="uo_aciklama sola"><?php echo $feature->value; ?></span>
				</div>
				<?php
							}
						}
					}
				?>
			</div>
			<!-- urun ozellikleri SON-->
			<?php
				}
			?>

			<!-- urun_videolar -->
			<div class="urun_tab_container" id="tab_videolar" style="text-align:center;">
				<?php echo $product_info->video; ?>
			</div>
			<!-- urun_videolar SON-->

			<!-- urun aciklamasi -->
			<div class="urun_tab_container" id="tab_aciklama">
				<div class="urun_ozellik_aciklama">
					<?php echo $product_info->description; ?>
				</div>				
			</div>
			<!-- urun aciklamasi SON-->

			<!-- urun yorumları -->
			<div class="urun_tab_container" id="tab_yorumlar">
				<?php
					if($product_review) {
						foreach($product_review as $review) {
				?>
				<div class="urun_yorum">
					<div class="uy_yazan"><?php echo $review->author; ?> | <img src="<?php echo face_resim(); ?>stars_<?php echo $review->rating; ?>.png" alt="<?php echo lang('messages_product_detail_comments_vote') . $review->rating; ?>" />
					</div>
					<div><?php echo standard_date('DATE_TR1', mysql_to_unix($review->date_added), get_language('code')); ?></div>
					<div class="uy_yazi"><?php echo nl2br($review->text); ?></div>
				</div>
				<?php
						}
					} else { ?>
				<?php echo lang('messages_product_detail_comments_no_comment'); ?>
				<?php } ?>
				<form id="review_form">
				<div class="uy_yorum_baslik"><?php echo lang('messages_product_detail_comments_form_title'); ?></div>
				<div class="urun_yorum_yaz">
					<div id="urun_yorum_sonuc"></div>
					<input type="hidden" name="review_product_id" value="<?php echo $product_info->product_id; ?>" />
					<input type="hidden" name="review_user_id" value="<?php echo ($this->dx_auth->is_logged_in()) ? $this->dx_auth->get_user_id() : 0; ?>" />
					<span class="uy_baslik"><?php echo lang('messages_product_detail_comments_form_name'); ?></span>
					<span class="uy_box"><input type="text" name="review_author" /></span>
					<span class="uy_baslik"><?php echo lang('messages_product_detail_comments_form_email'); ?></span>
					<span class="uy_box"><input type="text" name="review_email" /></span>
					<span class="uy_baslik"><?php echo lang('messages_product_detail_comments_form_comment'); ?></span>
					<span class="uy_box"><textarea name="review_text"></textarea></span>
					<span class="uy_radio">
						<b><?php echo lang('messages_product_detail_comments_form_rate'); ?> : </b>
						<?php echo lang('messages_product_detail_comments_form_rate_bad'); ?>
						&nbsp; <input type="radio" name="review_rating" value="1">
						&nbsp; <input type="radio" name="review_rating" value="2">
						&nbsp; <input type="radio" name="review_rating" value="3">
						&nbsp; <input type="radio" name="review_rating" value="4">
						&nbsp; <input type="radio" name="review_rating" value="5" checked="checked">
						&nbsp; <?php echo lang('messages_product_detail_comments_form_rate_good'); ?>
					</span>
					<span class="uy_baslik sola" style="padding-top:5px;"><?php echo lang('messages_product_detail_comments_form_security_code'); ?> : </span>
					<span class="uy_chapta sola"><img id="yorum_guvenlik_kodu_img" src="<?php echo site_url('site/img_kontrol/urun_yorum_yaz', ssl_status()); ?>" alt="<?php echo lang('messages_product_detail_comments_form_security_code'); ?>" title="<?php echo lang('messages_product_detail_comments_form_security_code'); ?>" /></span>
					<span class="uy_box sola" style="margin:10px 0 0 20px;"><input type="text" name="review_security_code" style="width:100px;"></span>
					
					 <a id="yorum_ekle_buton" onclick="urun_yorum_yolla();" class="butonum" title="<?php echo lang('messages_product_detail_comments_form_add_comment'); ?>" style="margin-top:5px;">
					 	<span class="butsol"></span>
						<span class="butor"><?php echo lang('messages_product_detail_comments_form_add_comment'); ?></span>
						<span class="butsag"></span>
					</a>
					<div class="clear"></div>
				</div>
				</form>
			</div>
			<!-- urun yorumları SON-->
<script type="text/javascript" charset="utf-8">
	function urun_yorum_yolla()
	{
		$.ajax({
			type: "POST",
			url: '<?php echo site_url('urun/detay/yorum_ekle'); ?>',
			data: $('#review_form').serialize(),
			dataType: 'json',
			beforeSend: function() {
				$('#yorum_ekle_buton').css('visibility', 'hidden');
				$('#urun_yorum_sonuc').html('<div class="uy_mesaj u_sari_bg"><img src="'+ resim_url +'loader.gif" /> &nbsp; <?php echo lang('messages_please_wait'); ?></div>');
			},
			complete: function() {
				$('#yorum_ekle_buton').css('visibility', 'visible');
			},
			success: function(data) {
				if (data.basarisiz) {
					$('#yorum_guvenlik_kodu_img').attr('src', '<?php echo site_url('site'); ?>/img_kontrol/urun_yorum_yaz?' + (new Date).getTime());
					$('#urun_yorum_sonuc').html('<div class="uy_mesaj u_kirmizi_bg">' + data.basarisiz + '</div>');
				}
		
				if (data.basarili) {
					$('#yorum_guvenlik_kodu_img').attr('src', '<?php echo site_url('site'); ?>/img_kontrol/urun_yorum_yaz?' + (new Date).getTime());
					$('#urun_yorum_sonuc').html('<div class="uy_mesaj u_yesil_bg">' + data.basarili + '</div>');
		
					$('input[name=\'review_author\']').val('');
					$('input[name=\'review_email\']').val('');
					$('input[name=\'review_security_code\']').val('');
					$('textarea[name=\'review_text\']').val('');
				}
			}
		});
	}
</script>
			<!-- urun taksit secenekleri -->
			<div class="urun_tab_container" style="text-align:center;" id="tab_taksit">
<?php
	$this->db->where('odeme_model', 'kredi_karti');
	$this->db->where('odeme_durum', '1');
	$kredi_karti_acikmi = $this->db->count_all_results('odeme_secenekleri');

	if(config('site_ayar_fiyat_goster') AND $this->dx_auth->is_logged_in() AND $kredi_karti_acikmi) {
		$this->db->select_max('kkts_taksit_sayisi');
		$en_yuksek_taksit_sayisi_sorgu = $this->db->get_where('odeme_secenek_kredi_karti_taksit_secenekleri', array('kkts_durum' => '1'));
		$en_yuksek_taksit_sayisi_bilgi = $en_yuksek_taksit_sayisi_sorgu->row();

		$banka_sorgu = $this->db->get_where('odeme_secenek_kredi_karti', array('kk_banka_durum' => '1', 'kk_banka_taksit' => '1'));

		if($banka_sorgu->num_rows()) {
			foreach($banka_sorgu->result() as $bankalar) {
				?>
				<div class="u_taksit_container">
					<div class="u_taksit_ust"></div>
					<div class="u_taksit_ic">
						<div class="u_taksit_banka"><img height="35" src="<?php echo face_resim(); ?><?php echo $bankalar->kk_banka_resim; ?>" alt="<?php echo $bankalar->kk_banka_adi; ?>" title="<?php echo $bankalar->kk_banka_adi; ?>" /></div>
						<?php
						$tii = 0;
						$oi = 0;
						for($iii = 2; $iii <= $en_yuksek_taksit_sayisi_bilgi->kkts_taksit_sayisi; $iii++) {
							$tii++;
							$oi++;
							$z = $oi%2;
							if ($z==0) {
								$ut_div_class = " ut_gri";
							} else {
								$ut_div_class = "";	
							}
							$taksit_sayisi_sorgu = $this->db->get_where('odeme_secenek_kredi_karti_taksit_secenekleri', array('kk_id' => $bankalar->kk_id, 'kkts_durum' => '1', 'kkts_taksit_sayisi' => $iii));
							if($taksit_sayisi_sorgu->num_rows()) {
								$fiyat_bilgi = fiyat_hesapla($product_info->model, 1, kur_oku('usd'), kur_oku('eur'));

								$kamp_kk   = $fiyat_bilgi['fiyat_t'];
								$kdv_fiyat = ($kamp_kk * $fiyat_bilgi['kdv_orani']);
								$fiyat_kdv = ($kamp_kk + $kdv_fiyat);
								
								$taksit_sayisi_bilgi = $taksit_sayisi_sorgu->row();
								$komisyon = $taksit_sayisi_bilgi->kkts_komisyon;
								$komisyon_ucreti_hesapla = ($komisyon > 0) ? floatval('00.' . $komisyon):(float)00.00;
								$komisyon_ucreti		 = ($fiyat_kdv * $komisyon_ucreti_hesapla);
								$komisyon_ucreti_fiyat   = ($fiyat_kdv + $komisyon_ucreti);
								$komisyon_ucreti_fiyat_t = (($fiyat_kdv + $komisyon_ucreti)/$iii);
								echo '
								<div class="u_taksit'. $ut_div_class .'">
									<span class="ut_sayi sola">'. $iii .'</span>
									<span class="ut_taksit sola"> '. format_number($komisyon_ucreti_fiyat_t) .' </span>
									<span class="ut_taksit sola"> '. format_number($komisyon_ucreti_fiyat) .' </span>
								</div>
								';
							} else {
								echo '
								<div class="u_taksit ut_gri">
									<span class="ut_sayi sola">'. $iii .'</span>
									<span class="ut_taksit sola"> - </span>
									<span class="ut_taksit sola"> - </span>
								</div>
								';
							}
						}
						?>
					</div>
					<div class="u_taksit_alt"></div>
				</div>
				<?php
				}
			} else {
				echo lang('messages_product_detail_dont_instalment');
			}
} elseif((!config('site_ayar_fiyat_goster')) AND $kredi_karti_acikmi) {
	$this->db->select_max('kkts_taksit_sayisi');
	$en_yuksek_taksit_sayisi_sorgu = $this->db->get_where('odeme_secenek_kredi_karti_taksit_secenekleri', array('kkts_durum' => '1'));
	$en_yuksek_taksit_sayisi_bilgi = $en_yuksek_taksit_sayisi_sorgu->row();
	$banka_sorgu = $this->db->get_where('odeme_secenek_kredi_karti', array('kk_banka_durum' => '1', 'kk_banka_taksit' => '1'));
			if($banka_sorgu->num_rows())
			{
				foreach($banka_sorgu->result() as $bankalar)
				{
				?>
				<div class="u_taksit_container">
					<div class="u_taksit_ust"></div>
					<div class="u_taksit_ic">
						<div class="u_taksit_banka"><img height="35" src="<?php echo face_resim(); ?><?php echo $bankalar->kk_banka_resim; ?>" alt="<?php echo $bankalar->kk_banka_adi; ?>" title="<?php echo $bankalar->kk_banka_adi; ?>" /></div>
						<?php
						$tii = 0;
						$oi = 0;
						for($iii = 2; $iii <= $en_yuksek_taksit_sayisi_bilgi->kkts_taksit_sayisi; $iii++) {
							$tii++;
							$oi++;
							$z = $oi%2;
							if ($z==0) {
								$ut_div_class = " ut_gri";
							} else {
								$ut_div_class = "";	
							}
							$taksit_sayisi_sorgu = $this->db->get_where('odeme_secenek_kredi_karti_taksit_secenekleri', array('kk_id' => $bankalar->kk_id, 'kkts_durum' => '1', 'kkts_taksit_sayisi' => $iii));
							if($taksit_sayisi_sorgu->num_rows())
							{
								$fiyat_bilgi = fiyat_hesapla($product_info->model, 1, kur_oku('usd'), kur_oku('eur'));

								$kamp_kk   = $fiyat_bilgi['fiyat_t'];
								$kdv_fiyat = ($kamp_kk * $fiyat_bilgi['kdv_orani']);
								$fiyat_kdv = ($kamp_kk + $kdv_fiyat);
								
								$taksit_sayisi_bilgi = $taksit_sayisi_sorgu->row();
								$komisyon = $taksit_sayisi_bilgi->kkts_komisyon;
								$komisyon_ucreti_hesapla = ($komisyon > 0) ? floatval('00.' . $komisyon):(float)00.00;
								$komisyon_ucreti		 = ($fiyat_kdv * $komisyon_ucreti_hesapla);
								$komisyon_ucreti_fiyat   = ($fiyat_kdv + $komisyon_ucreti);
								$komisyon_ucreti_fiyat_t = (($fiyat_kdv + $komisyon_ucreti)/$iii);
								echo '
								<div class="u_taksit'. $ut_div_class .'">
									<span class="ut_sayi sola">'. $iii .'</span>
									<span class="ut_taksit sola"> '. format_number($komisyon_ucreti_fiyat_t) .' </span>
									<span class="ut_taksit sola"> '. format_number($komisyon_ucreti_fiyat) .' </span>
								</div>
								';
							} else {
								echo '
								<div class="u_taksit ut_gri">
									<span class="ut_sayi sola">'. $iii .'</span>
									<span class="ut_taksit sola"> - </span>
									<span class="ut_taksit sola"> - </span>
								</div>
								';
							}
						}
						?>
					</div>
					<div class="u_taksit_alt"></div>
				</div>
				<?php
				}
			} else {
				echo lang('messages_product_detail_dont_instalment');
			}
	} else if($kredi_karti_acikmi < 0) {
		echo lang('messages_product_detail_dont_instalment');
	} else if(config('site_ayar_fiyat_goster') == '1' && !$this->dx_auth->is_logged_in()) {
		echo lang('messages_product_detail_instalment_reg');
	} else {
		echo lang('messages_product_detail_dont_instalment');
	}
?>
			</div>
			<!-- urun taksit secenekleri SON-->

			<!-- urun fotolari -->
			<div class="urun_tab_container" id="tab_fotograflar">
			<?php
				if($product_images_all) {
			?>
			<script type="text/javascript" charset="utf-8">
				$(document).ready(function(){
					$('#diger_fotograflar_lightbox a').lightBox();
				});
			</script>
			<?php
					echo '<div id="diger_fotograflar_lightbox">';
					foreach($product_images_all as $image) {
						if($image->image != '') {
							if(file_exists(DIR_IMAGE . $image->image)) {
								$resim_buyuk = show_image($image->image, 300, 300);
								$resim = show_image($image->image, 70, 66);
							} else {
								$resim = show_image('no-image.jpg', 70, 66);
								$resim_buyuk = show_image('no-image.jpg', 300, 300);
							}
						} else {
							$resim = show_image('no-image.jpg', 70, 66);
							$resim_buyuk = show_image('no-image.jpg', 300, 300);
						}
						echo '<a class="urun_diger_resim sola" href="' . base_url(ssl_status()) . 'upload/editor/'. $image->image .'"><image src="'. $resim .'" /></a>';
					}
					echo '</div>';
				} else {
			?>
			<center><?php echo lang('messages_product_detail_pictures_no_picture'); ?></center>
			<?php } ?>
			</div>
			<!-- urun fotolari SON-->
		</div>
		<?php
			if($product_related) {
		?>
		<!-- Benzer urunler sınır:5-->
		<div class="urun_diger_baslik"><?php echo lang('messages_product_detail_related_products'); ?></div>
		<div class="urun_diger_cont">
			<?php
				if($product_related) {
					$sii = 0;
					foreach($product_related as $benzer) {
						$k = $sii%2;
						if($k == 0) {
							$div_class = ' ud_gri';
						} else {
							$div_class = NULL;
						}

						$sablon_gonder->product_id				= $benzer->product_id;
						$sablon_gonder->model					= $benzer->model;
						$sablon_gonder->name					= $benzer->name;
						$sablon_gonder->new_product				= $benzer->new_product;
						$sablon_gonder->quantity				= $benzer->quantity;
						$sablon_gonder->seo						= $benzer->seo;
						$sablon_gonder->image					= $benzer->image;
						$sablon_gonder->div_class				= $div_class;

						$this->product_model->stock_shema($sablon_gonder, 'arama_sonuclari');
						$sii++;
					}
				}
			?>
		</div>
		<!--benzer urunler SON -->
		<?php }?>
</div>
<script type="text/javascript" charset="utf-8">
	function sepete_ekle() {
		var _sepete_ekle_kontrol = $.fn.ajax_post({
			aksiyon_adresi					: site_url('urun/detay/sepete_ekle_kontrol'),
			aksiyon_tipi					: 'POST',
			aksiyon_data					: $('#product_options input[type=\'text\'], #product_options input[type=\'hidden\'], #product_options input[type=\'radio\']:checked, #product_options input[type=\'checkbox\']:checked, #product_options select, #product_options textarea, #hidden_ids input[type=\'hidden\']'),
			aksiyon_data_tipi				: 'json',
			aksiyon_data_sonuc_degeri		: false,
			aksiyon_sonucu_bekle			: false,
			aksiyon_sonucu_islem			: function(aksiyon_islem_sonuclari) {
				$('.success, .warning, .attention, information, .error').remove();
				if (aksiyon_islem_sonuclari.error != null) {
					for (i in aksiyon_islem_sonuclari.error) {
						$('#option-' + i).after('<span class="error">' + aksiyon_islem_sonuclari['error'][i] + '</span>');
					}
				}
			}
		});
		if(_sepete_ekle_kontrol.error == null) {
			$('#sepet_ekle').submit();
		}
	}
</script>
<?php if ($product_option) { ?>
<script type="text/javascript" src="<?php echo site_js(); ?>ajaxupload.js"></script>
<?php foreach ($product_option as $option) { ?>
<?php if ($option['type'] == 'file') { ?>
<script type="text/javascript">
	var button = $('#button-option-<?php echo $option['product_option_id']; ?>'), interval;
	new AjaxUpload(button, {
		action: site_url('urun/detay/upload'),
		name: 'file',
		autoSubmit: true,
		responseType: 'json',
		onSubmit: function(file, extension) {
			this.disable();
			$('.error').remove();
			$('#button-option-<?php echo $option['product_option_id']; ?>').after('<img src="'+ resim_url +'loader.gif" id="loading" style="padding-left:5px;padding-right:5px;" />');
			var old_data = $('input[name=\'stok_secenek[<?php echo $option['product_option_id']; ?>]\']').attr('value');
			if(old_data != '') {
				$.post(site_url('urun/detay/delete'), {'old_data' : old_data});
			}
		},
		onComplete: function(file, json) {
			this.enable();
			$('.error').remove();
			if (json.success) {
				alert(json.success);
				$('input[name=\'stok_secenek[<?php echo $option['product_option_id']; ?>]\']').attr('value', json.file);
			}
			if (json.error) {
				$('#option-<?php echo $option['product_option_id']; ?>').after('<span class="error">' + json.error + '</span>');
			}
			$('#loading').remove();	
		}
	});
</script>
<?php } ?>
<?php } ?>
<?php } ?>
<script type="text/javascript"><!--
$.tabs('#urun_tablar a', 'u_aktif');
//--></script>