<div id="orta" class="sola">
	<form action="<?php echo ssl_url('uye/giris'); ?>" method="post" name="uye_giris" id="uye_giris">
	<div id="uye_giris_container">
		<h1 id="sayfa_baslik"><?php echo lang('messages_member_login_title'); ?></h1>
		<div id="uye_not">
			<a class="sitelink2" href="javascript:;"><?php echo lang('messages_member_login_desc_1'); ?></a>
			<br/>
			<?php echo strtr(lang('messages_member_login_desc_2'), array('{_register_url_}' => ssl_url('uye/kayit'))); ?>
			<br/>
			<?php echo strtr(lang('messages_member_login_desc_3'), array('{_lost_password_url_}' => ssl_url('uye/sifre_hatirlat'))); ?>
		</div>
		<div id="uye_form">
			<?php if( $this->dx_auth->get_auth_error() ) { ?>
				<div style="line-height: 40px;color:red; text-align: center;"><?php echo $this->dx_auth->get_auth_error(); ?></div><div class="clear"></div>
			<?php } ?>

			<div class="sola uye_text"><?php echo lang('messages_member_login_email') ?></div>
			<div class="sola"><input type="text" name="email" value="<?php echo ($val->email) ? $val->email:NULL; ?>" class="uye_box" /></div>
			<div class="clear"></div>
			<?php
				echo ($val->email_error) ? '<div style="line-height: 20px;color:red;">'. $val->email_error .'</div><div class="clear"></div>':NULL;
			?>

			<div class="sola uye_text"><?php echo lang('messages_member_login_password'); ?></div>
			<div class="sola"><input type="password" name="password" class="uye_box" /></div>
			<div class="clear"></div>
			<?php
				echo ($val->password_error) ? '<div style="line-height: 20px;color:red;">'. $val->password_error .'</div><div class="clear"></div>':NULL;
			?>
			<div class="sola uye_text">&nbsp;</div>
			<div class="sola uye_checkbox"><input type="checkbox" name="remember" value="1" /> <?php echo lang('messages_member_login_remember'); ?></div>
			<div class="clear"></div>

			<div class="sola uye_text">&nbsp;</div>
				 <div class="sola uye_checkbox">
					<a class="butonum sola" href="javascript:;" onclick="$('#uye_giris').submit();" rel="nofollow">
						<span class="butor"><?php echo lang('messages_member_login_button'); ?></span>
					</a>
					<?php if (config('site_ayar_facebook_status')) { ?>
						<a href="javascript:;" class="facelogin sola" onclick="FBLogin();" title="<?php echo lang('messages_member_login_facebook'); ?>" >
							<?php echo lang('messages_member_login_facebook'); ?>
						</a>
					<?php } ?>
				</div>
			<div class="clear"></div>
		</div>
	</div>
	</form>

</div>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
		$('#uye_giris_guvenlik_kodu').attr('src', site_url('site/img_kontrol/uye_giris?' + (new Date).getTime()));
		$('#uye_giris').keydown(function(e) {
			if (e.keyCode == 13) {
				$('#uye_giris').submit();
			}
		});
	});
</script>