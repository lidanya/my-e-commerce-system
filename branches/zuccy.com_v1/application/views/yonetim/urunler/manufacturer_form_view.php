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
		<h1 style="background-image: url('<?php echo yonetim_resim(); ?>category.png');"><?php echo $title; ?></h1>
		<div class="buttons">
			<a onclick="$('#form').submit();" class="buton"><span>Kaydet</span></a>
			<a onclick="location = '<?php echo yonetim_url($cancel_url); ?>';" class="buton" style="margin-left:10px;"><span>İptal</span></a>
		</div>
	</div>

	<div class="content">
		<form action="<?php echo yonetim_url($action_url); ?>" method="post" id="form">

			<div id="tabs" class="htabs">
				<a tab="#tab_general">
					Genel
					<?php
						$tab_general_error = FALSE;
						if(
							form_error('name') OR
							form_error('image') OR
							form_error('seo') OR
							form_error('meta_description') OR
							form_error('meta_keywords') OR
							form_error('description') OR
							form_error('status') OR
							form_error('sort_order')
						) {
							$tab_general_error = TRUE;
						}

						echo ($tab_general_error) ? '<img src="'. yonetim_resim() .'warning.png" width="13" />' : NULL;
					?>
				</a>
			</div>

			<div id="tab_general">
				<table class="form">
					<tr>
						<td>
							Resim
						</td>
						<td>
							<input type="hidden" name="image" value="<?php echo $preview_input; ?>" id="image">
							<img src="<?php echo $preview; ?>" alt="" id="preview" onmouseover="$(this).attr('src','<?php echo $this->image_model->resize('resim_ekle_hover.jpg', 100, 100); ?>');" onmouseout="$(this).attr('src','<?php echo $preview; ?>');" title="Resim eklemek yada değiştirmek için tıklayınız." onclick="image_upload('image', 'preview');" style="cursor: pointer; border: 1px solid #EEEEEE;">
        					<img src="<?php echo yonetim_resim(); ?>image.png" alt="" title="Resim eklemek yada değiştirmek için tıklayınız." style="cursor: pointer;" align="top" onclick="image_upload('image', 'preview');">
						</td>
					</tr>
					<tr>
						<td><span class="required">*</span> Durum</td>
						<td>
							<?php
								$_status_array = array('0' => ' - Pasif - ', '1' => ' - Aktif - ');
								echo form_dropdown('status', $_status_array, $status);
							?>
							<?php if (form_error('status')) { ?>
								<span class="error"><?php echo form_error('status'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td><span class="required">*</span> Sıralama</td>
						<td>
							<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" />
							<?php if (form_error('sort_order')) { ?>
								<span class="error"><?php echo form_error('sort_order'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							<span class="required">*</span> Başlık
						</td>
						<td>
							<input type="text" name="name" size="100" value="<?php echo $name; ?>" />
							<?php if (form_error('name')) { ?>
								<span class="error"><?php echo form_error('name'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							Meta Keywords
						</td>
						<td>
							<input type="text" name="meta_keywords" size="100" value="<?php echo $meta_keywords; ?>" />
							<?php if (form_error('meta_keywords')) { ?>
								<span class="error"><?php echo form_error('meta_keywords'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							Meta Description
						</td>
						<td>
							<input type="text" name="meta_description" size="100" value="<?php echo $meta_description; ?>" />
							<?php if (form_error('meta_description')) { ?>
								<span class="error"><?php echo form_error('meta_description'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							Seo Adresi <br /><span class="help">Seo adresi girilmesi zorunlu değildir, girilen seo adresi gereçli olur. Girilmez ise otomatik olarak Başlık kısmını referans alarak oluşturulur.</span>
						</td>
						<td>
							<input type="text" name="seo" size="100" value="<?php echo $seo; ?>" />
							<?php if (form_error('seo')) { ?>
								<span class="error"><?php echo form_error('seo'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							Açıklama
						</td>
						<td>
							<textarea name="description" id="description"><?php echo $description; ?></textarea>
							<?php if (form_error('description')) { ?>
								<span class="error"><?php echo form_error('description'); ?></span>
							<?php } ?>
						</td>
					</tr>
				</table>
			</div>

		</form>
	</div>
</div>
<script type="text/javascript"><!--
$('#tabs a').tabs();
CKEDITOR.replace('description', {
	filebrowserBrowseUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
	filebrowserImageBrowseUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
	filebrowserFlashBrowseUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
	filebrowserUploadUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
	filebrowserImageUploadUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
	filebrowserFlashUploadUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>'
});
//--></script>

<script type="text/javascript">
<!--
function image_upload(field, preview) {
	$('#dialog').remove();
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="<?php echo yonetim_url(); ?>/dosya_yonetici?field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	$('#dialog').dialog({
		title: 'Resim Yükle',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: '<?php echo yonetim_url("dosya_yonetici/image"); ?>',
					type: 'POST',
					data: 'image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" onmouseover="$(this).attr(\'src\', \'<?php echo $this->image_model->resize("resim_ekle_hover.jpg", 100, 100); ?>\');" onmouseout="$(this).attr(\'src\', \'' + data + '\');" title="Resim eklemek yada değiştirmek için tıklayınız." class="image" onclick="image_upload(\'' + field + '\', \'' + preview + '\', \'' + data + '\');" style="cursor: pointer; border: 1px solid #EEEEEE;" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 700,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script>

<?php $this->load->view('yonetim/footer_view');   ?>