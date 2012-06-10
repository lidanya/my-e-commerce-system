	<!--orta -->
	<div id="orta" class="sola">
		<div id="uye_giris_container">
	    	<h1 id="sayfa_baslik"><?php echo lang('messages_member_forget_password_title'); ?></h1>
	    	<!-- Form -->
	    	<div id="uye_not">
	    		<a href="Javascript:;"><?php echo lang('messages_member_forget_password_desc_1'); ?></a>
	    		<br/><?php echo lang('messages_member_forget_password_desc_2'); ?>
	    		<br/><?php echo strtr(lang('messages_member_forget_password_desc_3'), array('{_url_}' => ssl_url('uye/kayit'))); ?></div>
	        <div id="uye_form">
	        	<form action="<?php echo ssl_url('uye/sifre_hatirlat'); ?>" method="post" name="sifre_hatirlat" id="sifre_hatirlat">
				<div class="sola uye_text"><?php echo lang('messages_member_forget_password_email'); ?></div> 
				<div class="sola">
					<input type="text" name="eposta" class="uye_box" value="<?php $val->eposta; ?>" />
				</div>
				<div class="clear"></div>
				<?php
					echo ($val->eposta_error) ? '<div style="line-height: 20px;color:red;">'. $val->eposta_error .'</div><div class="clear"></div>':NULL;
				?>
				<div class="sola uye_text"><?php echo lang('messages_member_forget_password_security_code') ?></div> 
				<div class="sola" style="width: 205px;">
					<input type="text" class="uye_box_sifre" maxlength="6" style="width:89px;" name="captcha" autocomplete="off" /> 
					<div class="uye_captcha saga">
						<img id="sifre_hatirlat_guvenlik_kodu" alt="<?php echo lang('messages_member_forget_password_security_code'); ?>" title="<?php echo lang('messages_member_forget_password_security_code'); ?>" />
					</div>
				</div>
				<div class="clear"></div> 
				<?php
					echo ($val->captcha_error) ? '<div style="line-height: 20px;color:red;">'. $val->captcha_error .'</div><div class="clear"></div>':NULL;
				?>
				<div class="sola uye_text">&nbsp;</div> 
				<div class="sola uye_checkbox">
					<a class="butonum" href="javascript:;" onclick="$('#sifre_hatirlat').submit();">
						<span class="butsol"></span>
						<span class="butor"><?php echo lang('messages_member_forget_password_send_password'); ?></span>
						<span class="butsag"></span>
					</a>
				</div>
				<div class="clear"></div>
				</form>
	        </div>
	        <!-- Form SON-->			 
	    </div>
	</div>
	<!--orta son -->

<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
		$('#sifre_hatirlat_guvenlik_kodu').attr('src', site_url('site/img_kontrol/sifre_hatirlat?' + (new Date).getTime()));
		$('#sifre_hatirlat').keydown(function(e) {
			if (e.keyCode == 13) {
				$('#sifre_hatirlat').submit();
			}
		});
	});
</script>