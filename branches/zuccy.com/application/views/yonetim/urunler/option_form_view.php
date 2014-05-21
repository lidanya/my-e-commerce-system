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
		<h1 style="background-image: url('<?php echo yonetim_resim(); ?>information.png');"><?php echo $title; ?></h1>
		<div class="buttons">
			<a onclick="$('#form').submit();" class="buton"><span>Kaydet</span></a>
			<a onclick="location = '<?php echo yonetim_url($cancel_url); ?>';" class="buton" style="margin-left:10px;"><span>İptal</span></a>
		</div>
	</div>

	<div class="content">
		<form action="<?php echo yonetim_url($action_url); ?>" method="post" id="form">
			<table class="form">
				<tr>
					<td><span class="required">*</span> Seçenek Adı</td>
					<td>
						<?php foreach ($languages as $language) { ?>
							<?php
								if(isset($option_description[$language['language_id']]['name'])) {
									$_option_name = $option_description[$language['language_id']]['name'];
								} else {
									$_option_name = NULL;
								}
							?>
							<input type="text" name="option_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo $_option_name; ?>" />
							<img src="<?php echo yonetim_resim(); ?>flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
							<?php if (form_error('option_description['. $language['language_id'] .'][name]')) { ?>
								<span class="error"><?php echo form_error('option_description['. $language['language_id'] .'][name]'); ?></span>
							<?php } ?>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td><span class="required">*</span> Tip</td>
					<td>
						<select name="type">
							<optgroup label="Seçim">
								<?php if ($type == 'select') { ?>
								<option value="select" selected="selected">Seçim</option>
								<?php } else { ?>
								<option value="select">Seçim</option>
								<?php } ?>
								<?php if ($type == 'radio') { ?>
								<option value="radio" selected="selected">Seçim Kutusu</option>
								<?php } else { ?>
								<option value="radio">Seçim Kutusu</option>
								<?php } ?>
								<?php if ($type == 'checkbox') { ?>
								<option value="checkbox" selected="selected">İşaret Kutusu</option>
								<?php } else { ?>
								<option value="checkbox">İşaret Kutusu</option>
								<?php } ?>
							</optgroup>
							<optgroup label="Yazı">
								<?php if ($type == 'text') { ?>
								<option value="text" selected="selected">Yazı</option>
								<?php } else { ?>
								<option value="text">Yazı</option>
								<?php } ?>
								<?php if ($type == 'textarea') { ?>
								<option value="textarea" selected="selected">Yazı Alanı</option>
								<?php } else { ?>
								<option value="textarea">Yazı Alanı</option>
								<?php } ?>
							</optgroup>
							<optgroup label="Dosya">
								<?php if ($type == 'file') { ?>
								<option value="file" selected="selected">Dosya</option>
								<?php } else { ?>
								<option value="file">Dosya</option>
								<?php } ?>
							</optgroup>
						</select>
						<?php if (form_error('type')) { ?>
							<span class="error"><?php echo form_error('type'); ?></span>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td><span class="required">*</span> Sıralama</td>
					<td>
						<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" />
						<?php if (form_error('sort_order')) { ?>
							<span class="error"><?php echo form_error('sort_order'); ?></span>
						<?php } ?>
					</td>
				</tr>
			</table>

			<table id="option-value" class="list">
				<thead>
					<tr>
						<td class="left"><span class="required">*</span> Seçenek Değer Adı</td>
						<td class="right">Seçenek Değer Sırası</td>
						<td></td>
					</tr>
				</thead>
				<?php $option_value_row = 0; ?>
				<?php foreach ($option_values as $option_value) { ?>
					<tbody id="option-value-row<?php echo $option_value_row; ?>">
						<tr>
							<td class="left">
								<input type="hidden" name="option_value[<?php echo $option_value_row; ?>][option_value_id]" value="<?php echo $option_value['option_value_id']; ?>" />
								<?php foreach ($languages as $language) { ?>
									<input type="text" name="option_value[<?php echo $option_value_row; ?>][option_value_description][<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($option_value['option_value_description'][$language['language_id']]) ? $option_value['option_value_description'][$language['language_id']]['name'] : ''; ?>" />
									<img src="<?php echo yonetim_resim(); ?>flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
									<?php if (form_error('option_value['. $option_value_row .'][option_value_description]['. $language['language_id'] .'][name]')) { ?>
										<span class="error"><?php echo form_error('option_value['. $option_value_row .'][option_value_description]['. $language['language_id'] .'][name]'); ?></span>
									<?php } ?>
								<?php } ?>
							</td>
							<td class="right">
								<input type="text" name="option_value[<?php echo $option_value_row; ?>][sort_order]" value="<?php echo $option_value['sort_order']; ?>" size="1" />
								<?php if (form_error('option_value['. $option_value_row. '][sort_order]')) { ?>
									<span class="error"><?php echo form_error('option_value['. $option_value_row. '][sort_order]'); ?></span>
								<?php } ?>
							</td>
							<td class="left">
								<a onclick="$('#option-value-row<?php echo $option_value_row; ?>').remove();" class="buton"><span>Kaldır</span></a>
							</td>
						</tr>
					</tbody>
				<?php $option_value_row++; ?>
				<?php } ?>
				<tfoot>
					<tr>
						<td colspan="2"></td>
						<td class="left"><a onclick="add_option_value();" class="buton"><span>Seçenek Değer Ekle</span></a></td>
					</tr>
				</tfoot>
			</table>
		</form>
	</div>
</div>
<script type="text/javascript" charset="utf-8">

	<?php if($type != '') { ?>
		var post_type = '<?php echo $type; ?>';
		if (post_type == 'select' || post_type == 'radio' || post_type == 'checkbox') {
			$('#option-value').show();
		} else {
			$('#option-value').hide();
		}
	<?php } ?>
	$('select[name=\'type\']').bind('change', function() {
		if (this.value == 'select' || this.value == 'radio' || this.value == 'checkbox') {
			$('#option-value').show();
		} else {
			$('#option-value').hide();
		}
	});

	var option_value_row = <?php echo $option_value_row; ?>;
	function add_option_value()
	{
		html  = '<tbody id="option-value-row' + option_value_row + '">';
		html += '<tr>';	
		html += '<td class="left"><input type="hidden" name="option_value[' + option_value_row + '][option_value_id]" value="" />';
		<?php foreach ($languages as $language) { ?>
		html += '<input type="text" name="option_value[' + option_value_row + '][option_value_description][<?php echo $language['language_id']; ?>][name]" value="" /> <img src="<?php echo yonetim_resim(); ?>flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
		<?php } ?>
		html += '</td>';
		html += '<td class="right"><input type="text" name="option_value[' + option_value_row + '][sort_order]" value="" size="1" /></td>';
		html += '<td class="left"><a onclick="$(\'#option-value-row' + option_value_row + '\').remove();" class="buton"><span>Kaldır</span></a></td>';
		html += '</tr>';
		html += '</tbody>';

		$('#option-value tfoot').before(html);

		option_value_row++;
	}
</script>
<?php $this->load->view('yonetim/footer_view'); ?>