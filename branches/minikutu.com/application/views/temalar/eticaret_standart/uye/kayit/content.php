<div id="orta" class="sola" style="margin-top:0;">
	<form action="<?php echo ssl_url('uye/kayit'); ?>" method="post" name="uye_kayit" id="uye_kayit">
	<div id="uye_giris_container">
		<h1 id="sayfa_baslik"><?php echo lang('messages_user_register_title'); ?></h1>
		<div id="uye_not"><?php echo lang('messages_user_register_information'); ?></div>
		<div id="uye_form">
			<div class="sola uye_text"><?php echo lang('messages_user_register_form_email'); ?></div>
			<div class="sola"><input type="text" name="email" value="<?php echo set_value('email'); ?>" class="uye_box" /></div>
			<div class="clear"></div>
			<?php
				echo (form_error('email')) ? '<div style="line-height: 20px;color:red;">'. form_error('email') .'</div><div class="clear"></div>':NULL;
			?>
			<div class="sola uye_text"><?php echo lang('messages_user_register_form_name'); ?></div>
			<div class="sola"><input type="text" name="adiniz" value="<?php echo set_value('adiniz'); ?>" class="uye_box" /></div>
			<div class="clear"></div>
			<?php
				echo (form_error('adiniz')) ? '<div style="line-height: 20px;color:red;">'. form_error('adiniz') .'</div><div class="clear"></div>':NULL;
			?>
			<div class="sola uye_text"><?php echo lang('messages_user_register_form_surname'); ?></div>
			<div class="sola"><input type="text" name="soyadiniz" value="<?php echo set_value('soyadiniz'); ?>" class="uye_box" /></div>
			<div class="clear"></div>
			<?php
				echo (form_error('soyadiniz')) ? '<div style="line-height: 20px;color:red;">'. form_error('soyadiniz') .'</div><div class="clear"></div>':NULL;
			?>
			<div class="sola uye_text"><?php echo lang('messages_user_register_form_password'); ?></div>
			<div class="sola"><input type="password" name="password" class="uye_box_sifre" autocomplete="off" /></div>
			<div class="clear"></div>
			<?php
				echo (form_error('password')) ? '<div style="line-height: 20px;color:red;">'. form_error('password') .'</div><div class="clear"></div>':NULL;
			?>
			<div class="sola uye_text"><?php echo lang('messages_user_register_form_confirm_password'); ?></div>
			<div class="sola"><input type="password" name="confirm_password" class="uye_box_sifre" autocomplete="off" /></div>
			<div class="clear"></div>
			<?php
				echo (form_error('confirm_password')) ? '<div style="line-height: 20px;color:red;">'. form_error('confirm_password') .'</div><div class="clear"></div>':NULL;
			?>
			<div class="sola uye_text"><?php echo lang('messages_user_register_form_captcha'); ?></div>
			<div class="sola"><input type="text" name="captcha" class="uye_box_sifre" autocomplete="off" /></div>
			<div class="sola uye_captcha"><img id="uye_kayit_guvenlik_kodu" alt="<?php echo lang('messages_user_register_form_captcha'); ?>" title="<?php echo lang('messages_user_register_form_captcha'); ?>" /></div>
			<div class="clear"></div>
			<?php
				echo (form_error('captcha')) ? '<div style="line-height: 20px;color:red;">'. form_error('captcha') .'</div><div class="clear"></div>':NULL;
			?>
                         
			<div class="sola uye_text">&nbsp;</div>
			<div class="sola uye_checkbox">
				<input type="checkbox" value="1" name="satis_sozlesmesi"/>
				<?php
					$information_type = config('information_types');
					$information = $this->information_model->get_information_by_id(config('site_ayar_sozlesme_id'));
					$seo = NULL;
					if($information) {
						$seo = strtr($information_type[$information->type]['url'], array('{url}' => $information->seo));
					}
				?>
				<?php echo strtr(lang('messages_user_register_form_agree'), array('{url}' => site_url($seo))); ?>
			</div>
			<div class="clear"></div>
			<?php
				echo (form_error('satis_sozlesmesi')) ? '<div style="line-height: 20px;color:red;">'. form_error('satis_sozlesmesi') .'</div><div class="clear"></div>':NULL;
			?>
			<div class="sola uye_text">&nbsp;</div>
			<div class="sola uye_checkbox">
				<a class="butonum sola" onclick="$('#uye_kayit').submit();" href="javascript:;">
					<span class="butor"><?php echo lang('messages_user_register_form_button'); ?></span>
				</a>
				<?php if (config('site_ayar_facebook_status')) { ?>
					<a href="javascript:;" onclick="FBLogin();" title="Facebook İle Giriş Yapmak İçin Tıklayın" class="facelogin sola">
						Facebook İle Giriş Yap
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
		$('#uye_kayit_guvenlik_kodu').attr('src', site_url('site/img_kontrol/uye_kayit?' + (new Date).getTime()));
		$('#uye_kayit').keydown(function(e) {
			if (e.keyCode == 13) {
				$('#uye_kayit').submit();
			}
		});
	});
</script>

<?php //echo $this->session->userdata('uye_kayit'); ?>