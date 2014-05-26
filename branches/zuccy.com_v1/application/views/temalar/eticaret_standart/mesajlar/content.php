	<!--orta -->
	<div id="orta" class="sola">
		
		<?php if($this->input->get('tip') == '1') { ?>
		<h1 id="sayfa_baslik"><?php echo lang('messages_static_page_messages_success_title'); ?></h1>
			<!-- ...........................................Genel Onayı............................................ -->
			<!-- Onay -->
			 <div id="onay_mesaj">
			 	<div class="onay_image sola"><img src="<?php echo site_resim(); ?>okey.png" alt="<?php echo $baslik; ?>" title="<?php echo $baslik; ?>"></div>
			 	<div class="onay_aaciklama sola" style="width:500px;margin-left:20px;padding-top:40px;"><b><?php echo $baslik; ?></b><br /><?php echo $icerik; ?></div>
			 	<div class="clear"></div>
			 	<p class="onay_buton">
			 		<?php if($this->input->get('gd') == 'true') { ?>
			 		<a class="butonum" onclick="history.back();">
			 			<span class="butsol"></span>
			 			<span class="butor"><img src="<?php echo site_resim(); ?>btn_img_geri.png" alt="<?php echo lang('messages_button_back'); ?>" /> <?php echo lang('messages_button_back'); ?></span>
			 			<span class="butsag"></span>
		 			</a>
			 		<?php } ?>
					<a class="butonum" style="margin-left:10px;" href="<?php echo site_url('site/index'); ?>">
						<span class="butsol"></span>
						<span class="butor"><img src="<?php echo site_resim(); ?>btn_img_anasayfa.png" alt="Anasayfaya Dön" /> <?php echo lang('messages_button_back_home'); ?></span>
						<span class="butsag"></span>
					</a>
			 	</p>
			 </div>
			 <!-- Onay SON -->
		<?php } else if ($this->input->get('tip') == '2') { ?>
		<h1 id="sayfa_baslik"><?php echo lang('messages_static_page_messages_error_title'); ?></h1>
			<!-- ...........................................Genel Onayı............................................ -->
			<!-- Onay -->
			 <div id="onay_mesaj">
			 	<div class="onay_image sola"><img src="<?php echo site_resim(); ?>unlem.png" alt="Onay" title="<?php echo $baslik; ?>"></div>
			 	<div class="onay_aciklama sola" style="width:500px;margin-left:20px;padding-top:40px;"><b><?php echo $baslik; ?></b><br /><?php echo $icerik; ?></div>
			 	<div class="clear"></div>
			 	<p class="onay_buton">
			 		
			 		<?php if($this->input->get('gd') == 'true') { ?>
			 		<a class="butonum" onclick="history.back();">
			 			<span class="butsol"></span>
			 			<span class="butor"><img src="<?php echo site_resim(); ?>btn_img_geri.png" alt="Geri Dön" /> <?php echo lang('messages_button_back'); ?></span>
			 			<span class="butsag"></span>
		 			</a>
			 		<?php } ?>
					<a class="butonum" style="margin-left:10px;" href="<?php echo site_url('site/index'); ?>">
						<span class="butsol"></span>
						<span class="butor"><img src="<?php echo site_resim(); ?>btn_img_anasayfa.png" alt="Anasayfaya Dön" /> <?php echo lang('messages_button_back_home'); ?></span>
						<span class="butsag"></span>
					</a>
			 	</p>
			 </div>
			 <!-- Onay SON -->
		<?php } ?>

	</div>