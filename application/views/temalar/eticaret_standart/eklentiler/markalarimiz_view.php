<?php if ($yer != 'anasayfa'): ?>
<div class="kutu" style="position:relative;">
<?php
if($eklenti_baslik_goster)
{
	echo '<div class="modul_ust">'. $eklenti_baslik .'</div>' . "\n";
	echo '<div class="modul_ic">' . "\n";
}
?>
<?php endif ?>
<?php if(eklenti_ayar('markalarimiz', 'tip') == '1' || eklenti_ayar('markalarimiz', 'tip') == NULL) { ?>
	<?php if ($yer == 'sol') { ?>
		<div id="y_marka_cont">
			<?php
				$information_type = config('information_types');
				$markalarimiz = $this->eklentiler_markalarimiz_model->marka_getir();
				if($markalarimiz)
				{
					echo '<ul>' . "\n";
					foreach($markalarimiz['query'] as $markalar)
					{
						if($markalar->image) {
							if(file_exists(DIR_IMAGE . $markalar->image)) {
								$resim = $this->image_model->resize($markalar->image, 75, 35);
							} else {
								$resim = $this->image_model->resize('no-image.jpg', 75, 35);
							}
						} else {
							$resim = $this->image_model->resize('no-image.jpg', 75, 35);
						}
						echo '<li><img src="'. $resim .'" alt="'. $markalar->name .'" /><a href="'. site_url(strtr($information_type['manufacturer']['url'], array('{url}' => $markalar->seo))) .'" title="'. $markalar->name .'">'. character_limiter($markalar->name, 10) .'</a></li>';
					}
					echo '</ul>' . "\n";
				}
			?>
		</div>
	<?php } elseif($yer == 'anasayfa') { ?>
		<?php
			$information_type = config('information_types');
			$manufacturers = $this->eklentiler_markalarimiz_model->marka_getir();
			if($manufacturers)
			{
		?>
		<div id="carouse" style="margin-top:20px;width:760px;">
			<ul class="jcarousel-skin-eticaret">
				<?php $i = 0; ?>
				<?php foreach ($manufacturers['query'] as $manufacturer): ?>
					<li>
						<a href="<?php echo site_url(strtr($information_type['manufacturer']['url'], array('{url}' => $manufacturer->seo))) ?>" target="_top">
							<img src="<?php echo show_image($manufacturer->image, 80, 80); ?>" alt="<?php echo $manufacturer->name; ?>" title="<?php echo $manufacturer->name; ?>">
						</a>
					</li>
				<?php $i++; ?>
				<?php endforeach ?>
			</ul>
		</div>
		<link rel="stylesheet" type="text/css" href="<?php echo site_css(); ?>jcarousel/carousel.css" media="screen" />
		<script type="text/javascript" src="<?php echo site_js(); ?>jquery.jcarousel.min.js"></script>
		<script type="text/javascript" charset="utf-8">
			$('#carouse ul').jcarousel({
				auto: 5,
				vertical: false,
				visible: 5,
				scroll: 3,
				wrap: 'circular',
				initCallback: mycarousel_initCallback
			});
	
			function mycarousel_initCallback(carousel)
			{
				// Disable autoscrolling if the user clicks the prev or next button.
				carousel.buttonNext.bind('click', function() {
					carousel.startAuto(0);
				});
				carousel.buttonPrev.bind('click', function() {
					carousel.startAuto(0);
				});
				// Pause autoscrolling if the user moves with the cursor over the clip.
				carousel.clip.hover(function() {
					carousel.stopAuto();
				}, function() {
					carousel.startAuto();
				});
			};
		</script>
		<?php } ?>
	<?php } ?>
<?php } ?>
<?php if ($yer != 'anasayfa'): ?>
<?php
if($eklenti_baslik_goster)
{
	echo '</div>' . "\n";
	echo '<div class="modul_alt"></div>' . "\n";
}
?>
</div>
<?php endif ?>