<?php
	$this->load->view('yonetim/header_view');
	$uye_inf = $this->yoneticiler_model->yonetici_inf($customer_inf->id);
	$uye_ilt_inf = $this->yoneticiler_model->yonetici_ilt_inf($customer_inf->id);
	$uye_sec_inf = $this->yoneticiler_model->yonetici_sec_inf($customer_inf->id);
	$val = $this->validation;

	if($val->error_string)
	{
		echo '<div class="warning">';
		echo 'Düzenleme yapılırken hata oluştu, tüm tabları kontrol edin.';
		echo '</div>';
	}
?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('<?php echo yonetim_resim(); ?>customer.png');">Yöneticiler</h1>
		<div class="butons" style="float:right;margin-top:5px;"><a onclick="$('#form').submit();" class="buton"><span>Kaydet</span></a> &nbsp; <a onclick="location = '<?php echo yonetim_url('uye_yonetimi/yoneticiler'); ?>';" class="buton"><span>İptal</span></a></div>
	</div>
	<div class="content">
	<div style="display: inline-block; width: 100%;">
		<div id="vtabs" class="vtabs">
			<a tab="#tab_ozet">Özet</a>
			<a tab="#tab_kimlik">Kimlik Bilgileri</a>
			<a tab="#tab_iletisim">İletişim Bilgileri</a>
			<a tab="#tab_guvenlik">Güvenlik Bilgileri</a>
		</div>
	<form action="<?php echo yonetim_url('uye_yonetimi/yoneticiler/duzenle/'. $customer_inf->id); ?>" method="post" enctype="multipart/form-data" id="form">
		<?php echo form_hidden('musteri_id', $customer_inf->id); ?>
		<div id="tab_ozet" class="vtabs-content">
			<table class="form">
				<tr>
					<td><span class="entry">Üye Numarası:</span></td>
					<td><?php echo $customer_inf->id; ?></td>
				</tr>
				<tr>
					<td><span class="entry">Adı:</span></td>
					<td><?php echo $uye_inf->ide_adi; ?></td>
				</tr>
				<tr>
					<td><span class="entry">Soyadı:</span></td>
					<td><?php echo $uye_inf->ide_soy; ?></td>
				</tr>
				<tr>
					<td>E-Posta:</td>
					<td><?php echo $customer_inf->email; ?></td>
				</tr>
				<tr>
					<td>Telefon:</td>
					<td><?php echo $uye_inf->ide_cep; ?></td>
				</tr>
				<tr>
				<td>Müşteri Grubu:</td>
					<td>
				<?php foreach ($customer_groups as $customer_group) { ?>
				<?php if ($customer_group->id == $customer_inf->role_id) { ?>
					<?php echo $customer_group->name; ?>
				<?php } ?>
				<?php } ?>
					</td>
				</tr>
			</table>
		</div>
		<div id="tab_kimlik" class="vtabs-content">
			<table class="form">
				<tr>
					<td><span class="required">*</span> <span class="entry">Adı:</span></td>
					<td><input type="text" name="ide_adi" value="<?php echo ($val->ide_adi) ? $val->ide_adi:$uye_inf->ide_adi; ?>" />
					<?php if (!empty($val->ide_adi_error)) { ?>
						<span class="error"><?php echo $val->ide_adi_error; ?></span>
					<?php } ?>
					</td>
				</tr>
				<tr>
					<td><span class="required">*</span> <span class="entry">Soyadı:</span></td>
					<td><input type="text" name="ide_soy" value="<?php echo ($val->ide_soy) ? $val->ide_soy:$uye_inf->ide_soy; ?>" />
					<?php if (!empty($val->ide_soy_error)) { ?>
						<span class="error"><?php echo $val->ide_soy_error; ?></span>
					<?php } ?>
					</td>
				</tr>
				<tr>
					<td><span class="required">*</span> E-Posta:</td>
					<td><input type="text" name="email" value="<?php echo ($val->email) ? $val->email:$customer_inf->email; ?>" />
					<?php if (!empty($val->email_error)) { ?>
						<span class="error"><?php echo $val->email_error; ?></span>
					<?php  } ?>
					</td>
				</tr>
				<tr>
					<td>Cinsiyet</td>
					<td>
						<select name="ide_cins">
						<?php if ($uye_inf->ide_cins == 'e') { ?>
							<option value=""> - Seçiniz - </option>
							<option value="e" selected="selected">Bay</option>
							<option value="k">Bayan</option>
						<?php } elseif($uye_inf->ide_cins == 'k') { ?>
							<option value=""> - Seçiniz - </option>
							<option value="e">Bay</option>
							<option value="k" selected="selected">Bayan</option>
						<?php } else { ?>
							<option value="" selected="selected"> - Seçiniz - </option>
							<option value="e">Bay</option>
							<option value="k">Bayan</option>
						<?php } ?>
						</select>
						<?php if (!empty($val->ide_cins_error)) { ?>
							<span class="error"><?php echo $val->ide_cins_error; ?></span>
						<?php  } ?>
					</td>
				</tr>
				<tr>
					<td><span class="required">*</span> Yönetici Grubu:</td>
					<td>
						<?php
							$_roles = array();
							$this->db->order_by('name','asc');
							$sorgu = $this->db->get_where('roles', array('parent_id' => 0));
							foreach($sorgu->result() as $roles)
							{
								$this->db->order_by('name','asc');
								$sorgu_opt = $this->db->get_where('roles', array('parent_id' => $roles->id));
								if($sorgu_opt->num_rows() > 0)
								{
									foreach($sorgu_opt->result() as $roles_opt)
									{
										$_roles[$roles->name][$roles_opt->id] = $roles_opt->name;
									}
								} else {
									$_roles[$roles->id] = $roles->name;
								}
							}

							echo form_dropdown('role_id', $_roles, $customer_inf->role_id, 'style="width: 137px;"');
						?>
					</td>
				</tr>
				<tr>
					<td><span class="entry">TC Kimlik No</span></td>
					<td><input type="text" name="ide_tckimlik" value="<?php echo ($val->ide_tckimlik) ? $val->ide_tckimlik:$uye_inf->ide_tckimlik; ?>" />
					<?php if (!empty($val->ide_tckimlik_error)) { ?>
						<span class="error"><?php echo $val->ide_tckimlik_error; ?></span>
					<?php } ?>
					</td>
				</tr>
				<tr>
					<td>Web Sitesi:</td>
					<td><input type="text" name="ide_web_site" value="<?php echo ($val->ide_web_site) ? $val->ide_web_site:$uye_inf->ide_web_site; ?>" />
					<?php if (!empty($val->ide_web_site_error)) { ?>
						<span class="error"><?php echo $val->ide_web_site_error; ?></span>
					<?php  } ?>
					</td>
				</tr>
			</table>
		</div>
		<div id="tab_iletisim" class="vtabs-content">
			<table class="form">
				<tr>
					<td><span class="entry">Cep Telefonu:</span></td>
					<td><input type="text" name="ide_cep" class="phone" value="<?php echo isset($val->ide_cep) ? $val->ide_cep:$uye_inf->ide_cep; ?>" />
					<?php if (!empty($val->ide_cep_error)) { ?>
						<span class="error"><?php echo $val->ide_cep_error; ?></span>
					<?php } ?>
					</td>
				</tr>
				<tr>
					<td><span class="entry">İş Telefonu:</span></td>
					<td><input type="text" name="adr_is_tel1" class="phone" value="<?php echo isset($val->adr_is_tel1) ? $val->adr_is_tel1:$uye_ilt_inf->adr_is_tel1; ?>" />
					<?php if (!empty($val->adr_is_tel1_error)) { ?>
						<span class="error"><?php echo $val->adr_is_tel1_error; ?></span>
					<?php } ?>
					</td>
				</tr>
				<tr>
					<td><span class="entry">İş Faks:</span></td>
					<td><input type="text" name="adr_is_fax" class="phone" value="<?php echo isset($val->adr_is_fax) ? $val->adr_is_fax:$uye_ilt_inf->adr_is_fax; ?>" />
					<?php if (!empty($val->adr_is_fax_error)) { ?>
						<span class="error"><?php echo $val->adr_is_fax_error; ?></span>
					<?php } ?>
					</td>
				</tr>
				<tr>
					<td><span class="entry">Ev Telefonu:</span></td>
					<td><input type="text" name="adr_is_tel2" class="phone" value="<?php echo isset($val->adr_is_tel2) ? $val->adr_is_tel2:$uye_ilt_inf->adr_is_tel2; ?>" />
					<?php if (!empty($val->adr_is_tel2_error)) { ?>
						<span class="error"><?php echo $val->adr_is_tel2_error; ?></span>
					<?php } ?>
					</td>
				</tr>
				<tr>
					<td><span class="entry">İş Adresi:</span></td>
					<td><textarea name="adr_is_ack" style="width: 200px; height: 100px;"><?php echo isset($val->adr_is_ack) ? $val->adr_is_ack:$uye_ilt_inf->adr_is_ack; ?></textarea>
					<?php if (!empty($val->adr_is_ack_error)) { ?>
						<span class="error"><?php echo $val->adr_is_ack_error; ?></span>
					<?php } ?>
					</td>
				</tr>
			</table>
		</div>
		<div id="tab_guvenlik" class="vtabs-content">
			<table class="form">
				<tr>
					<td><span class="entry">Üyelik Tarihi:</span></td>
					<td><?php echo standard_date('DATE_TR', mysql_to_unix($customer_inf->created), 'tr'); ?></td>
				</tr>
				<tr>
					<td><span class="entry">Düzenleme Tarihi:</span></td>
					<td><?php echo standard_date('DATE_TR', mysql_to_unix($customer_inf->modified), 'tr'); ?></td>
				</tr>
				<tr>
					<td><span class="entry">Son Giriş Tarihi:</span></td>
					<td><?php echo standard_date('DATE_TR', mysql_to_unix($customer_inf->last_login), 'tr'); ?></td>
				</tr>
				<tr>
					<td><span class="entry">Son Giriş IP:</span></td>
					<td><?php echo $customer_inf->last_ip; ?></td>
				</tr>
				<tr>
					<td>Parola:</td>
					<td><input type="password" name="password" value="" autocomplete="off" />
					<?php if (!empty($val->password_error)) { ?>
						<span class="error"><?php echo $val->password_error; ?></span>
					<?php  } ?>
					</td>
				</tr>
				<tr>
					<td>Parola(tekrar):</td>
					<td><input type="password" name="confirm" value="" autocomplete="off" />
					<?php if (!empty($val->confirm_error)) { ?>
						<span class="error"><?php echo $val->confirm_error; ?></span>
					<?php  } ?>
					</td>
				</tr>
			</table>
		</div>
	</form>
	</div>
</div>
</div>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('#ide_dogtar').datepicker({dateFormat: 'yy-mm-dd'});
	$('.date').datepicker({dateFormat:'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
$('.vtabs a').tabs();
//--></script>
<?php $this->load->view('yonetim/footer_view'); ?>