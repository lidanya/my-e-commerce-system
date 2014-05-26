<div id="orta" class="sola">
	<h1 id="sayfa_baslik">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</h1>
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

			$_uri = $seo . '--category/';
			if($category_products AND !$sub_category) {
				echo form_dropdown('urun_filtreleme', $_option_array, $sort_link, 'onchange="redirect(site_url(\''. $_uri .'\' + this.value));"');
			}
		?>
	</div>
	<div class="clear"></div>
	<?php
		if($sub_category) {
			$i = 0;
	?>
	<div class="liste_container">
		<?php
			foreach($sub_category as $sub_categories) {
				$i = $i + 1;
				if($sub_categories->image != 'resim_ekle.jpg') {
					if(file_exists(DIR_IMAGE . $sub_categories->image)) {
						$resim = show_image($sub_categories->image, 210, 160);
					} else {
						$random_image = $this->category_model->get_image_product_by_random($sub_categories->category_id);
						if($random_image) {
							$resim = show_image($random_image->image, 210, 160);
						} else {
							$resim = show_image('no-image.jpg', 210, 160);
						}
					}
				} else {
					$random_image = $this->category_model->get_image_product_by_random($sub_categories->category_id);
					if($random_image) {
						$resim = show_image($random_image->image, 210, 160);
					} else {
						$resim = show_image('no-image.jpg', 210, 160);
					}
				}
				$total_products = $this->category_model->get_product_count($sub_categories->category_id);
				$stok_say = ' ('. $total_products .')';

				if($this->uri->segment(3) == 'tum_kategoriler--category') {
					$category_url = str_replace('tum_kategoriler--category', '', $this->uri->segment(3)) . $sub_categories->seo . '--category';
				} else {
					$category_url = str_replace('--category', '', $this->uri->segment(3)) . '---' . $sub_categories->seo . '--category';
				}
		?>
			<div class="kategori_liste_oge sola">
				<div class="kategori_liste_resim">
					<a href="<?php echo face_site_url($category_url); ?>">
						<img src="<?php echo $resim; ?>" alt="<?php echo $sub_categories->name; ?>" title="<?php echo $sub_categories->name; ?>" />
					</a>
				</div>
				<a href="<?php echo face_site_url($category_url); ?>" class="kategori_liste_baslik sitelink2" title="<?php echo $sub_categories->name; ?>">
					<?php echo character_limiter($sub_categories->name, 50) . $stok_say; ?>
				</a>
			</div>
	<?php
			if($i == '3') {
				$i = 0;
				echo '<div class="clear"></div>';
			}
		}
	?>
	</div>
<?php
	} else if($category_products) {
		$i = 0;
?>
	<div class="clear"></div>
	<?php
		if($category_products_pagination) {
			echo $category_products_pagination['links'];
		}
	?>
	<div class="clear"></div>
	<div class="liste_container">
	<?php foreach($category_products['query'] as $kategori_urunler) { ?>
		<?php
			$i = $i+1;
				$sablon_gonder->product_id		= $kategori_urunler->product_id;
				$sablon_gonder->model			= $kategori_urunler->model;
				$sablon_gonder->name			= $kategori_urunler->name;
				$sablon_gonder->new_product		= $kategori_urunler->new_product;
				$sablon_gonder->quantity		= $kategori_urunler->quantity;
				$sablon_gonder->seo				= $kategori_urunler->seo;
				$sablon_gonder->image			= $kategori_urunler->image;
				$this->product_model->stock_shema($sablon_gonder, 'normal_liste', 'face');
			if($i == '3') {
				$i = 0;
				echo '<div class="clear"></div>';
			}
		?>
	<?php } ?>
	</div>
	<div class="clear"></div>
	<?php
		if($category_products_pagination) {
			echo $category_products_pagination['links'];
		}
	?>
<?php } else { ?>
	<!-- Hata -->
	 <div id="onay_mesaj">
	 	<div class="onay_image sola"><img src="<?php echo face_resim();?>unlem.png" alt="<?php echo lang('messages_not_new_products_title'); ?>" title="<?php echo lang('messages_not_new_products_title'); ?>"></div>
	 	<div class="onay_aciklama sola" style="padding-top:40px;">
	 		<b><?php echo lang('messages_not_new_products_title'); ?></b>
	 		<br /><?php echo lang('messages_not_new_products_content'); ?>
 		</div>
	 	<div class="clear"></div>
	 	<p class="onay_buton">
	 		<a href="javascript:history.back();" title="<?php echo lang('messages_button_back'); ?>" class="butonum">
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

</div>