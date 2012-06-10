    <!--orta -->
    <div id="orta" class="sola">
	<h1 id="sayfa_baslik"><?php echo lang('messages_campaign_products_title'); ?></h1>
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

			$_uri = 'urun/kampanyali/index/';

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
		<div class="liste_container">
		<?php
			foreach($urunler['query'] as $indirimli) {
				$i = $i+1;
				$sablon_gonder->product_id		= $indirimli->product_id;
				$sablon_gonder->model			= $indirimli->model;
				$sablon_gonder->name			= $indirimli->name;
				$sablon_gonder->new_product		= $indirimli->new_product;
				$sablon_gonder->quantity		= $indirimli->quantity;
				$sablon_gonder->seo				= $indirimli->seo;
				$sablon_gonder->image			= $indirimli->image;
				$this->product_model->stock_shema($sablon_gonder, 'normal_liste', 'face');
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
			 	<div class="onay_image sola"><img src="<?php echo face_resim();?>unlem.png" alt="<?php echo lang('messages_not_campaign_products_title'); ?>" title="<?php echo lang('messages_not_campaign_products_title'); ?>"></div>
			 	<div class="onay_aciklama sola" style="padding-top:40px;">
			 		<b><?php echo lang('messages_not_campaign_products_title'); ?></b>
			 		<br /><?php echo lang('messages_not_campaign_products_content'); ?>
		 		</div>
			 	<div class="clear"></div>
			 	<p class="onay_buton">
			 		<a href="javascript:history.back();" class="butonum" title="<?php echo lang('messages_button_back'); ?>">
			 			<span class="butsol"></span>
			 			<span class="butor"><img src="<?php echo face_resim();?>btn_img_geri.png" alt="<?php echo lang('messages_button_back'); ?>" />&nbsp;<?php echo lang('messages_button_back'); ?></span>
			 			<span class="butsag"></span>
		 			</a>
					<a class="butonum" style="margin-left:10px;" href="<?php echo face_site_url('site/index'); ?>" title="<?php echo lang('messages_button_back_home'); ?>" target="_top">
						<span class="butsol"></span>
						<span class="butor"><img src="<?php echo face_resim();?>btn_img_anasayfa.png" alt="<?php echo lang('messages_button_back_home'); ?>" />&nbsp;<?php echo lang('messages_button_back_home'); ?></span>
						<span class="butsag"></span>
					</a>
			 	</p>
			 </div>
			 <!-- Hata SON -->
	<?php } ?>
    
    <!--orta son -->
</div>