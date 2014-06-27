<?php if(eklenti_ayar('slider', 'tip') == '1' || eklenti_ayar('slider', 'tip') == NULL) { ?>
	<?php
		$slider_sorgu = $this->eklentiler_slider_model->slider_listele();
		if($slider_sorgu->num_rows()) {
	?>
	<link media="screen" rel="stylesheet" type="text/css" href="<?php echo site_js(); ?>flexslider/flexslider.css" />
	<script type="text/javascript" src="<?php echo site_js(); ?>flexslider/jquery.flexslider.js" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
        $(window).load(function() {
            $('.flexslider').flexslider({
                animation: "slide"
            });
        });
	</script>
	<?php
		$width = ($yer == 'anasayfa' || $yer == 'slider') ? '821' : '980';
		$height = ($yer == 'anasayfa' || $yer == 'slider') ? '474' : '314';
	?>
	<div id="slider_<?php echo $yer; ?>" style="width:<?php echo $width; ?>px;height:<?php echo $height; ?>px;" class="flexslider">
        <ul class="slides">
		<?php foreach($slider_sorgu->result() as $row) { ?>
		<?php $link = ($row->slider_link) ? $row->slider_link : 'javascript:;'; ?>
        <li>
		<a href="<?php echo $link; ?>">
		<?php
			$resim = show_image($row->slider_img, $width, $height);
		?>
			<img src="<?php echo $resim; ?>" alt="Slider" />
		</a>
        </li>
		<?php } ?>
        </ul>
	</div>
	<?php } ?>
<?php } ?>