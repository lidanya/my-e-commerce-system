<?php
		$slider_sorgu = $this->eklentiler_slider_model->slider_listele();
		if($slider_sorgu->num_rows()) {
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo site_css(TRUE); ?>flexslider.css"/>
	<script type="text/javascript" src="<?php echo site_js(); ?>jquery.flexslider-min.js"></script>
	<script type="text/javascript" charset="utf-8">
		$(window).load(function() {
			 $('.flexslider').flexslider({
               animation: "slide",
			   controlNav: false 
            });
		});
	</script>
	<?php
		$width = 960;
		$height = 315;
	?>
		<div id="Slider">
       	<div class="flexslider">
              <ul class="slides">
				<?php foreach($slider_sorgu->result() as $row) { ?>  
                <li>
					<?php $link = ($row->slider_link) ? $row->slider_link : 'javascript:;'; ?>
					<a href="<?php echo $link; ?>"><img src="<?php echo show_image($row->slider_img, $width, $height);?>" width="960" height="315"/></a>
                </li>
				 <?php } ?>
              </ul>
          </div>
		</div>
	<?php } ?>
