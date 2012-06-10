<div id="orta" class="sola">
	<?php
		if($usr_ide_inf->num_rows()) {
			$usr 				= $usr_ide_inf->row();
			$name 				= $usr->ide_adi;
			$surname 			= $usr->ide_soy;
			$email				= $this->dx_auth->get_username();
			$id_number 			= $usr->ide_tckimlik;
			$cins 				= $usr->ide_cins;
			$mobile_phone		= $usr->ide_cep;
			$web_site			= $usr->ide_web_site;
		} else {
			$name 				= '';
			$surname 			= '';
			$email				= $this->dx_auth->get_username();
			$id_number 			= '';
			$cins 				= 'e';
			$mobile_phone		= '';
			$web_site			= '';
		}

		if($usr_adr_inf->num_rows()) {
			$usr_adr 			= $usr_adr_inf->row();
			$address 			= $usr_adr->adr_is_ack;
			$home_phone			= $usr_adr->adr_is_tel1;
			$work_phone			= $usr_adr->adr_is_tel2;
			$fax_phone			= $usr_adr->adr_is_fax;
		} else {
			$address 			= '';
			$home_phone			= '';
			$work_phone			= '';
			$fax_phone			= '';
		}
	?>
	<h1 id="sayfa_baslik"><?php echo lang('messages_member_information_title'); ?></h1>
	<form action="<?php echo ssl_url('uye/bilgi'); ?>" method="post" name="uye_bilgi" id="uye_bilgi">
		<div id="uye_giris_container">
			<div style="margin: 20px auto;width:700px;" class="u_bilgi"><?php echo lang('messages_member_information_message'); ?></div>
			<div id="uye_form">

				<div class="sola uye_text"><?php echo lang('messages_member_information_form_old_password_text'); ?></div> 
				<div class="sola">
					<input type="password" name="old_password" class="uye_box" autocomplete="off" />
				</div>
				<div class="clear"></div>
				<?php 
					if(form_error('old_password')) {
						echo '<div style="line-height: 20px; color: red; margin-left: 140px;">'. form_error('old_password') .'</div><div class="clear"></div>';
					}
				?>

				<div class="sola uye_text"><?php echo lang('messages_member_information_form_password_text'); ?></div>
				<div class="sola">
					<input type="password" name="password"class="uye_box" autocomplete="off" />
				</div>
				<div class="clear"></div>
				<?php 
					if(form_error('password')) {
						echo '<div style="line-height: 20px; color: red; margin-left: 140px;">'. form_error('password') .'</div><div class="clear"></div>';
					}
				?>

				<div class="sola uye_text"><?php echo lang('messages_member_information_form_confirm_password_text'); ?></div>
				<div class="sola">
					<input type="password" name="confirm_password" class="uye_box" autocomplete="off" />
				</div>
				<div class="clear"></div>
				<?php 
					if(form_error('confirm_password')) {
						echo '<div style="line-height: 20px; color: red; margin-left: 140px;">'. form_error('confirm_password') .'</div><div class="clear"></div>';
					}
				?>

				<div class="sola uye_text">&nbsp;</div>
                <div class="sola">&nbsp;</div>
				<div class="clear"></div>

				<div class="sola uye_text"><?php echo lang('messages_member_information_form_name_text'); ?></div>
				<div class="sola">
					<input type="text" name="name" value="<?php echo $name; ?>" class="uye_box" />
				</div>
				<div class="clear"></div>
				<?php 
					if(form_error('name')) {
						echo '<div style="line-height: 20px; color: red; margin-left: 140px;">'. form_error('name') .'</div><div class="clear"></div>';
					}
				?>

				<div class="sola uye_text"><?php echo lang('messages_member_information_form_surname_text'); ?></div>
				<div class="sola">
					<input type="text" name="surname" value="<?php echo $surname; ?>" class="uye_box" />
				</div>
				<div class="clear"></div>
				<?php 
					if(form_error('surname')) {
						echo '<div style="line-height: 20px; color: red; margin-left: 140px;">'. form_error('surname') .'</div><div class="clear"></div>';
					}
				?>

				<div class="sola uye_text"><?php echo lang('messages_member_information_form_email_text'); ?></div>
				<div class="sola">
					<input type="text" disabled="disabled" class="uye_box" name="email" value="<?php echo $email;?>" />
					<input type="hidden" class="uye_box" name="email" value="<?php echo $email; ?>" />
				</div>
				<div class="clear"></div>
				<?php 
					if(form_error('email')) {
						echo '<div style="line-height: 20px; color: red; margin-left: 140px;">'. form_error('email') .'</div><div class="clear"></div>';
					}
				?>

				<div class="sola uye_text"><?php echo lang('messages_member_information_form_id_number_text'); ?></div>
				<div class="sola">
					<input type="text" class="uye_box" maxlength="11" name="id_number" id="id_number" value="<?php echo $id_number; ?>" />
				</div>
				<div class="clear"></div>
				<?php 
					if(form_error('id_number')) {
						echo '<div style="line-height: 20px; color: red; margin-left: 140px;">'. form_error('id_number') .'</div><div class="clear"></div>';
					}
				?>

				<div class="sola uye_text"><?php echo lang('messages_member_information_form_gender_text'); ?></div>
				<div class="sola" style="padding-top:5px;">
					<input name="gender" value="e" type="radio" <?php echo ($cins == 'e') ? 'checked="checked"' : NULL; ?> />
					<span style="margin-right:50px;"><?php echo lang('messages_member_information_form_gender_male_text'); ?></span> 
					<input name="gender"  value="k" type="radio" <?php echo ($cins == 'k') ? 'checked="checked"' : NULL; ?> />
					<?php echo lang('messages_member_information_form_gender_female_text'); ?>
				</div>
				<div class="clear"></div>
				<?php 
					if(form_error('gender')) {
						echo '<div style="line-height: 20px; color: red; margin-left: 140px;">'. form_error('gender') .'</div><div class="clear"></div>';
					}
				?>

				<div class="sola uye_text"><?php echo lang('messages_member_information_form_address_text'); ?></div>
				<div class="sola">
					<textarea class="uye_box_multiline" name="address"><?php echo $address; ?></textarea>
				</div>
				<div class="clear"></div>
				<?php 
					if(form_error('address')) {
						echo '<div style="line-height: 20px; color: red; margin-left: 140px;">'. form_error('address') .'</div><div class="clear"></div>';
					}
				?>

				<div class="sola uye_text"><?php echo lang('messages_member_information_form_home_phone_text'); ?></div>
				<div class="sola">
					<input type="text" class="uye_box" name="home_phone" id="home_phone" value="<?php echo $home_phone; ?>" />
				</div>
				<div class="clear"></div>
				<?php 
					if(form_error('home_phone')) {
						echo '<div style="line-height: 20px; color: red; margin-left: 140px;">'. form_error('home_phone') .'</div><div class="clear"></div>';
					}
				?>

				<div class="sola uye_text"><?php echo lang('messages_member_information_form_work_phone_text'); ?></div>
				<div class="sola">
					<input type="text" class="uye_box" name="work_phone" id="work_phone" value="<?php echo $work_phone; ?>" />
				</div>
				<div class="clear"></div>
				<?php 
					if(form_error('work_phone')) {
						echo '<div style="line-height: 20px; color: red; margin-left: 140px;">'. form_error('work_phone') .'</div><div class="clear"></div>';
					}
				?>

				<div class="sola uye_text"><?php echo lang('messages_member_information_form_mobile_phone_text'); ?></div>
				<div class="sola">
					<input type="text" class="uye_box" name="mobile_phone" id="mobile_phone" value="<?php echo $mobile_phone; ?>" />
				</div>
				<div class="clear"></div>
				<?php 
					if(form_error('mobile_phone')) {
						echo '<div style="line-height: 20px; color: red; margin-left: 140px;">'. form_error('mobile_phone') .'</div><div class="clear"></div>';
					}
				?>

				<div class="sola uye_text"><?php echo lang('messages_member_information_form_fax_phone_text'); ?></div>
				<div class="sola">
					<input type="text" class="uye_box" name="fax_phone" id="fax_phone" value="<?php echo $fax_phone; ?>" />
				</div>
				<div class="clear"></div>
				<?php 
					if(form_error('fax_phone')) {
						echo '<div style="line-height: 20px; color: red; margin-left: 140px;">'. form_error('fax_phone') .'</div><div class="clear"></div>';
					}
				?>

				<div class="sola uye_text"><?php echo lang('messages_member_information_form_web_site_text'); ?></div>
				<div class="sola">
					<input type="text" class="uye_box" name="web_site" value="<?php echo $web_site; ?>" />
				</div>
				<div class="clear"></div>
				<?php 
					if(form_error('web_site')) {
						echo '<div style="line-height: 20px; color: red; margin-left: 140px;">'. form_error('web_site') .'</div><div class="clear"></div>';
					}
				?>

				<div class="sola uye_text">&nbsp;</div> <div class="saga">
					<a class="butonum" href="javascript:;" onclick="$('#uye_bilgi').submit();">
						<span class="butsol"></span>
						<span class="butor"><?php echo lang('messages_button_save'); ?></span>
						<span class="butsag"></span>
					</a>
				</div>
				<div class="clear"></div>

			</div>
		</div>
	</form>
</div>
<script>
	jQuery(function($){jQuery("#home_phone, #work_phone, #mobile_phone, #fax_phone").mask("(9999) 999 99 99");});
	jQuery(function($){jQuery("#id_number").mask("99999999999");});
</script>