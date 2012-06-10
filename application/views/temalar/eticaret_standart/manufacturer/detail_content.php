    <!--orta -->
    <div id="orta" class="sola">
	<h1 id="sayfa_baslik"><?php echo $manufacturer_info->name; ?></h1>
	<div style="float:right;display:inline;margin-top:15px;">
		<?php
			$_option_array = array(
				'price-desc' => lang('messages_select_stok_fiyat-desc'),
				'price-asc' => lang('messages_select_stok_fiyat-asc'),
				'name-desc' => lang('messages_select_stok_adi-desc'),
				'name-asc' => lang('messages_select_stok_adi-asc'),
				'viewed-desc' => lang('messages_select_stok_hit-desc'),
				'viewed-asc' => lang('messages_select_stok_hit-asc'),
				'product_id-desc' => lang('messages_select_stok_id-desc'),
				'product_id-asc' => lang('messages_select_stok_id-asc')
			);

			$information_type = config('information_types');
			$_uri = strtr($information_type['manufacturer']['url'], array('{url}' => $manufacturer_info->seo)) . '/';
			echo form_dropdown('urun_filtreleme', $_option_array, $sort_link, 'onchange="redirect(site_url(\''. $_uri .'\' + this.value));"');
		?>
	</div>
	<div class="clear"></div>
	<?php
		if($urunler) {
			$i = 0;
			if($pagination) {
				echo $pagination['links'];
			}
		?>
		<div class="clear"></div>
		<!-- Navigasyon üst Son-->
		<div class="liste_container">
		<?php
			foreach($urunler['query'] as $yeni_urunlerimiz) {
				$i = $i+1;
				$sablon_gonder->product_id		= $yeni_urunlerimiz->product_id;
				$sablon_gonder->model			= $yeni_urunlerimiz->model;
				$sablon_gonder->name			= $yeni_urunlerimiz->name;
				$sablon_gonder->new_product		= $yeni_urunlerimiz->new_product;
				$sablon_gonder->quantity		= $yeni_urunlerimiz->quantity;
				$sablon_gonder->seo				= $yeni_urunlerimiz->seo;
				$sablon_gonder->image			= $yeni_urunlerimiz->image;
				$this->product_model->stock_shema($sablon_gonder, 'normal_liste');
				if($i == '3') {
					$i = 0;
					echo '<div class="clear"></div>';
				}
			}
		?>
		</div>
		<div class="clear"></div>
		<?php
			if($pagination) {
				echo $pagination['links'];
			}
		?>
	<?php } else { ?>
			<!-- Hata -->
			 <div id="onay_mesaj">
			 	<div class="onay_image sola"><img src="<?php echo site_resim();?>unlem.png" alt="<?php echo lang('messages_not_new_products_title'); ?>" title="<?php echo lang('messages_not_new_products_title'); ?>"></div>
			 	<div class="onay_aciklama sola" style="padding-top:40px;">
			 		<b><?php echo lang('messages_not_new_products_title'); ?></b>
			 		<br /><?php echo lang('messages_not_new_products_content'); ?>
		 		</div>
			 	<div class="clear"></div>
			 	<p class="onay_buton">
			 		<a href="javascript:history.back();" title="<?php echo lang('messages_button_back'); ?>" class="butonum">
			 			<span class="butsol"></span>
			 			<span class="butor"><img src="<?php echo site_resim();?>btn_img_geri.png" alt="<?php echo lang('messages_button_back'); ?>" />&nbsp;<?php echo lang('messages_button_back'); ?></span>
			 			<span class="butsag"></span>
		 			</a>
					<a class="butonum" style="margin-left:10px;" href="<?php echo site_url(); ?>" title="<?php echo lang('messages_button_back_home'); ?>">
						<span class="butsol"></span>
						<span class="butor"><img src="<?php echo site_resim();?>btn_img_anasayfa.png" alt="<?php echo lang('messages_button_back_home'); ?>" />&nbsp;<?php echo lang('messages_button_back_home'); ?></span>
						<span class="butsag"></span>
					</a>
			 	</p>
			 </div>
			 <!-- Hata SON -->
	<?php } ?>

    <!--orta son -->
</div>