<?php 
	$this->load->view('yonetim/header_view');
?>
<?php if(isset($errors) AND $errors) { ?>
	<div class="warning" style="margin-top: 10px;">
		Hata oluştu lütfen kontrol edin!
	</div>
<?php } ?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('<?php echo yonetim_resim(); ?>customer.png');"><?php echo $title; ?></h1>
		<div class="buttons">
			<a onclick="$('#form').submit();" class="buton"><span>Kaydet</span></a>
			<a onclick="location = '<?php echo yonetim_url($cancel_url); ?>';" class="buton" style="margin-left:10px;"><span>İptal</span></a>
		</div>
	</div>
	<div class="content">
		<form action="<?php echo yonetim_url($action_url); ?>" method="post" enctype="multipart/form-data" id="form">
			<div id="vtabs" class="vtabs">
				<a tab="#tab_summary">Özet</a>
				<a tab="#tab_identity">
					Kimlik Bilgileri
					<?php
						if (
							form_error('identity_name') OR 
							form_error('identity_surname') OR 
							form_error('identity_email') OR
							form_error('identity_sex') OR
							form_error('identity_role_id') OR
							form_error('identity_number') OR
							form_error('identity_website') OR
							form_error('identity_birthday')
						) {
					?>
						<img src="<?php echo yonetim_resim(); ?>warning.png" width="13" />
					<?php } ?>
				</a>
				<a tab="#tab_contact">
					İletişim Bilgileri
					<?php
						if (
							form_error('contact_gsm') OR
							form_error('contact_work') OR
							form_error('contact_work_fax') OR
							form_error('contact_home') OR
							form_error('contact_work_address')
						) {
					?>
						<img src="<?php echo yonetim_resim(); ?>warning.png" width="13" />
					<?php } ?>
				</a>
				<a tab="#tab_security">
					Güvenlik Bilgileri
					<?php
						if (
							form_error('security_password') OR
							form_error('security_password_confirm')
						) {
					?>
						<img src="<?php echo yonetim_resim(); ?>warning.png" width="13" />
					<?php } ?>
				</a>
			</div>

			<div id="tab_summary" class="vtabs-content">
				<table class="form">
					<tr>
						<td><span class="entry">Müşteri Numarası : </span></td>
						<td><?php echo $user_id; ?></td>
					</tr>
					<tr>
						<td>Müşteri Grubu : </td>
						<td>
							<?php echo $role_name; ?>
						</td>
					</tr>
					<tr>
						<td><span class="entry">Adı : </span></td>
						<td><?php echo $name; ?></td>
					</tr>
					<tr>
						<td><span class="entry">Soyadı : </span></td>
						<td><?php echo $surname; ?></td>
					</tr>
					<tr>
						<td>E-Posta : </td>
						<td><?php echo $username; ?></td>
					</tr>
					<tr>
						<td>Telefon : </td>
						<td><?php echo $adr_is_tel1; ?></td>
					</tr>
				</table>
			</div>

			<div id="tab_identity" class="vtabs-content">
				<table class="form">
					<tr>
						<td>
							<span class="entry">Adı : </span>
						</td>
						<td>
							<input type="text" name="identity_name" value="<?php echo $identity_name; ?>" />
							<?php if (form_error('identity_name')) { ?>
								<span class="error"><?php echo form_error('identity_name'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							<span class="entry">Soyadı : </span>
						</td>
						<td>
							<input type="text" name="identity_surname" value="<?php echo $identity_surname; ?>" />
							<?php if (form_error('identity_surname')) { ?>
								<span class="error"><?php echo form_error('identity_surname'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							<span class="required">*</span> E-Posta : 
						</td>
						<td>
							<input type="text" name="identity_email" value="<?php echo $identity_email; ?>" />
							<?php if (form_error('identity_email')) { ?>
								<span class="error"><?php echo form_error('identity_email'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							Cinsiyet : 
						</td>
						<td>
							<?php
								$_identity_sex_array = array('e' => 'Bay', 'k' => 'Bayan');
								echo form_dropdown('identity_sex', $_identity_sex_array, $identity_sex, 'style="width: 137px;"');
							?>
							<?php if (form_error('identity_sex')) { ?>
								<span class="error"><?php echo form_error('identity_sex'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							<span class="entry">Doğum Tarihi : </span>
						</td>
						<td>
							<input type="text" name="identity_birthday" class="date" value="<?php echo $identity_birthday; ?>" />
							<?php if (form_error('identity_birthday')) { ?>
								<span class="error"><?php echo form_error('identity_birthday'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							<span class="required">*</span> Müşteri Grubu : 
						</td>
						<td>
							<?php echo form_dropdown('identity_role_id', $customer_groups, $identity_role_id, 'style="width: 137px;"'); ?>
							<?php if (form_error('identity_role_id')) { ?>
								<span class="error"><?php echo form_error('identity_role_id'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							<span class="entry">TC Kimlik Numarası : </span>
						</td>
						<td>
							<input type="text" name="identity_number" value="<?php echo $identity_number; ?>" maxlength="11" />
							<?php if (form_error('identity_number')) { ?>
								<span class="error"><?php echo form_error('identity_number'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							Web Sitesi : 
						</td>
						<td>
							<input type="text" name="identity_website" value="<?php echo $identity_website; ?>" />
							<?php if (form_error('identity_website')) { ?>
								<span class="error"><?php echo form_error('identity_website'); ?></span>
							<?php } ?>
						</td>
					</tr>
				</table>
			</div>

			<div id="tab_contact" class="vtabs-content">
				<table class="form">
					<tr>
						<td><span class="entry">Cep Telefonu : </span></td>
						<td>
							<input type="text" name="contact_gsm" class="phone" value="<?php echo $contact_gsm; ?>" />
							<?php if (form_error('contact_gsm')) { ?>
								<span class="error"><?php echo form_error('contact_gsm'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td><span class="entry">İş Telefonu : </span></td>
						<td>
							<input type="text" name="contact_work" class="phone" value="<?php echo $contact_work; ?>" />
							<?php if (form_error('contact_work')) { ?>
								<span class="error"><?php echo form_error('contact_work'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td><span class="entry">İş Faks : </span></td>
						<td>
							<input type="text" name="contact_work_fax" class="phone" value="<?php echo $contact_work_fax; ?>" />
							<?php if (form_error('contact_work_fax')) { ?>
								<span class="error"><?php echo form_error('contact_work_fax'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td><span class="entry">Ev Telefonu : </span></td>
						<td>
							<input type="text" name="contact_home" class="phone" value="<?php echo $contact_home; ?>" />
							<?php if (form_error('contact_home')) { ?>
								<span class="error"><?php echo form_error('contact_home'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td><span class="entry">İş Adresi : </span></td>
						<td>
							<textarea name="contact_work_address" style="width: 200px; height: 100px;"><?php echo $contact_work_address; ?></textarea>
							<?php if (form_error('contact_work_address')) { ?>
								<span class="error"><?php echo form_error('contact_work_address'); ?></span>
							<?php } ?>
						</td>
					</tr>
				</table>
			</div>

			<div id="tab_security" class="vtabs-content">
				<table class="form">
					<tr>
						<td>
							<span class="entry">Üyelik Tarihi : </span>
						</td>
						<td>
							<?php echo standard_date('DATE_TR', mysql_to_unix($security_created), 'tr'); ?>
						</td>
					</tr>
					<tr>
						<td>
							<span class="entry">Düzenleme Tarihi : </span>
						</td>
						<td>
							<?php echo standard_date('DATE_TR', mysql_to_unix($security_modified), 'tr'); ?>
						</td>
					</tr>
					<tr>
						<td>
							<span class="entry">Son Giriş Tarihi : </span>
						</td>
						<td>
							<?php echo standard_date('DATE_TR', mysql_to_unix($security_last_login), 'tr'); ?>
						</td>
					</tr>
					<tr>
						<td>
							<span class="entry">Son Giriş IP : </span>
						</td>
						<td>
							<?php echo $security_last_ip; ?>
						</td>
					</tr>
					<tr>
						<td>
							Parola :
						</td>
						<td>
							<input type="password" name="security_password" value="" autocomplete="off" />
							<?php if (form_error('security_password')) { ?>
								<span class="error"><?php echo form_error('security_password'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							<span class="entry">Parola (tekrar) : </span>
						</td>
						<td>
							<input type="password" name="security_password_confirm" value="" autocomplete="off" />
							<?php if (form_error('security_password_confirm')) { ?>
								<span class="error"><?php echo form_error('security_password_confirm'); ?></span>
							<?php } ?>
						</td>
					</tr>
				</table>
			</div>

		</form>
	</div>
</div>

<script type="text/javascript" src="<?php echo yonetim_js(); ?>jquery/ui/ui.datepicker.js" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('.date').datepicker({dateFormat:'yy-mm-dd'});
		$('.phone').mask("(9999) 999 99 99");
	});
	$('.vtabs a').tabs();
</script>
<?php $this->load->view('yonetim/footer_view');   ?>