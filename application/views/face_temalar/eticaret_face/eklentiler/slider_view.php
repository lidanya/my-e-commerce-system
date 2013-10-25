<?php if(eklenti_ayar('slider', 'tip') == '1' || eklenti_ayar('slider', 'tip') == NULL) { ?>
	<?php
		$slider_sorgu = $this->eklentiler_slider_model->slider_listele();
		if($slider_sorgu->num_rows()) {
	?>
	<link media="screen" rel="stylesheet" type="text/css" href="<?php echo site_js(); ?>nivo/nivo_slider.css" />
	<link media="screen" rel="stylesheet" type="text/css" href="<?php echo site_js(); ?>nivo/custom.nivo.css" />
	<script type="text/javascript" src="<?php echo site_js(); ?>nivo/nivo.min.js" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
		$(window).load(function() {
			$('#slider_<?php echo $yer; ?>').nivoSlider({
				controlNav : false
			});
		});
	</script>
	<?php
		$width = ($yer == 'anasayfa') ? '760' : '760';
		$height = ($yer == 'anasayfa') ? '240' : '240';
	?>
	<div id="slider_<?php echo $yer; ?>" style="width:<?php echo $width; ?>px;height:<?php echo $height; ?>px;" class="dynamic_slider nivoSlider">
		<?php foreach($slider_sorgu->result() as $row) { ?>
		<?php $link = ($row->slider_link) ? $row->slider_link : 'javascript:;'; ?>
		<a href="<?php echo $link; ?>" target="_top">
		<?php
			$resim = show_image($row->slider_img, $width, $height);
		?>
			<img style="display:none;" src="<?php echo $resim; ?>" alt="Slider" />
		</a>
		<?php } ?>
	</div>
	<?php } ?>
<?php } ?>