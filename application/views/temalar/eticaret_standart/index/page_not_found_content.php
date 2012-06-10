<!--orta -->
<div id="orta" class="sola">
	<h1 id="sayfa_baslik"><?php echo lang('messages_page_not_found_title'); ?></h1>
	<div class="clear"></div>
		<!-- Hata -->
		 <div id="onay_mesaj">
		 	<div class="onay_image sola"><img src="<?php echo site_resim();?>unlem.png" alt="<?php echo lang('messages_page_not_found_title'); ?>" title="<?php echo lang('messages_page_not_found_title'); ?>"></div>
		 	<div class="onay_aciklama sola" style="padding-top:40px;">
		 		<b><?php echo lang('messages_page_not_found_title'); ?></b>
		 		<br /><?php echo strtr(lang('messages_page_not_found_content'), array('{_contact_url_}' => site_url('site/iletisim'))); ?>
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
</div>