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
		<form action="<?php echo yonetim_url($action_url); ?>" method="post" enctype="multipart/form-data" id="form">

		<div id="tabs" class="htabs">
			<a tab="#tab_general">
				Genel
				<?php
					$tab_general_error = FALSE;
					foreach ($languages as $_language) {
						if (
							form_error('information_description['. $_language['language_id'] .'][title]') OR 
							form_error('information_description['. $_language['language_id'] .'][description]') OR 
							form_error('information_description['. $_language['language_id'] .'][meta_keywords]') OR 
							form_error('information_description['. $_language['language_id'] .'][meta_description]') OR 
							form_error('information_description['. $_language['language_id'] .'][seo]')
						) {
							$tab_general_error = TRUE;
							break;
						}
					}
					if(!$tab_general_error) {
						if (
							form_error('sort_order') OR form_error('category_id') OR form_error('status') OR form_error('image')
						) {
							$tab_general_error = TRUE;
						}
					}
					echo ($tab_general_error) ? '<img src="'. yonetim_resim() .'warning.png" width="13" />' : NULL;
				?>
			</a>
		</div>

		<div id="tab_general">
			<div id="languages" class="htabs">
				<?php foreach ($languages as $_language) { ?>
					<a tab="#language_<?php echo $_language['language_id']; ?>">
						<img src="<?php echo yonetim_resim(); ?>flags/<?php echo $_language['image']; ?>" title="<?php echo $_language['name']; ?>" /> <?php echo $_language['name']; ?>
						<?php
							if (
								form_error('information_description['. $_language['language_id'] .'][title]') OR 
								form_error('information_description['. $_language['language_id'] .'][description]') OR 
								form_error('information_description['. $_language['language_id'] .'][meta_keywords]') OR 
								form_error('information_description['. $_language['language_id'] .'][meta_description]') OR 
								form_error('information_description['. $_language['language_id'] .'][seo]')
							) {
						?>
							<img src="<?php echo yonetim_resim(); ?>warning.png" width="13" />
						<?php } ?>
					</a>
				<?php } ?>
			</div>

			<div>
				<table class="form">
					<tr>
						<td>
							Kapak Resmi
						</td>
        				<td>
        					<input type="hidden" name="image" value="<?php echo $preview_input; ?>" id="image">
        					<img src="<?php echo $preview; ?>" alt="" id="preview" onmouseover="$(this).attr('src','<?php echo $this->image_model->resize('resim_ekle_hover.jpg', 100, 100); ?>');" onmouseout="$(this).attr('src','<?php echo $preview; ?>');" title="Resim eklemek yada değiştirmek için tıklayınız." onclick="image_upload('image', 'preview');" style="cursor: pointer; border: 1px solid #EEEEEE;">
        					<img src="<?php echo yonetim_resim(); ?>image.png" alt="" title="Resim eklemek yada değiştirmek için tıklayınız." style="cursor: pointer;" align="top" onclick="image_upload('image', 'preview');">
							<?php if (form_error('image')) { ?>
								<span class="error"><?php echo form_error('image'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td><span class="required">*</span> Kategori</td>
						<td>
							<?php
								$_categories_data_once = array('0' => ' - Yok - ');
								$categories = array_merge($_categories_data_once, $categories);
								echo form_dropdown('category_id', $categories, $category_id);
							?>
							<?php if (form_error('category_id')) { ?>
								<span class="error"><?php echo form_error('category_id'); ?></span>
							<?php } ?>
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
				</table>
			</div>

			<?php foreach ($languages as $language) { ?>
				<div id="language_<?php echo $language['language_id']; ?>">
					<table class="form">
						<tr>
							<td>
								<span class="required">*</span> <?php echo $language['name']; ?> Başlık
							</td>
							<td>
								<?php
									if(isset($information_description[$language['language_id']]['title'])) {
										$_information_title = $information_description[$language['language_id']]['title'];
									} else {
										$_information_title = NULL;
									}
								?>
								<input type="text" name="information_description[<?php echo $language['language_id']; ?>][title]" size="100" value="<?php echo $_information_title; ?>" />
								<?php if (form_error('information_description['. $language['language_id'] .'][title]')) { ?>
									<span class="error"><?php echo form_error('information_description['. $language['language_id'] .'][title]'); ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td>
								 <?php echo $language['name']; ?> Meta Keywords
							</td>
							<td>
								<?php
									if(isset($information_description[$language['language_id']]['meta_keywords'])) {
										$_information_meta_keywords = $information_description[$language['language_id']]['meta_keywords'];
									} else {
										$_information_meta_keywords = NULL;
									}
								?>
								<input type="text" name="information_description[<?php echo $language['language_id']; ?>][meta_keywords]" size="100" value="<?php echo $_information_meta_keywords; ?>" />
								<?php if (form_error('information_description['. $language['language_id'] .'][meta_keywords]')) { ?>
									<span class="error"><?php echo form_error('information_description['. $language['language_id'] .'][meta_keywords]'); ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td>
								 <?php echo $language['name']; ?> Meta Description
							</td>
							<td>
								<?php
									if(isset($information_description[$language['language_id']]['meta_description'])) {
										$_information_meta_description = $information_description[$language['language_id']]['meta_description'];
									} else {
										$_information_meta_description = NULL;
									}
								?>
								<input type="text" name="information_description[<?php echo $language['language_id']; ?>][meta_description]" size="100" value="<?php echo $_information_meta_description; ?>" />
								<?php if (form_error('information_description['. $language['language_id'] .'][meta_description]')) { ?>
									<span class="error"><?php echo form_error('information_description['. $language['language_id'] .'][meta_description]'); ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td>
								<?php echo $language['name']; ?> Seo Adresi<br /><span class="help">Seo adresi girilmesi zorunlu değildir, girilen seo adresi gereçli olur. Girilmez ise otomatik olarak Başlık kısmını referans alarak oluşturulur.</span>
							</td>
							<td>
								<?php
									if(isset($information_description[$language['language_id']]['seo'])) {
										$_information_seo = $information_description[$language['language_id']]['seo'];
									} else {
										$_information_seo = NULL;
									}
								?>
								<input type="text" name="information_description[<?php echo $language['language_id']; ?>][seo]" size="100" value="<?php echo $_information_seo; ?>" />
								<?php if (form_error('information_description['. $language['language_id'] .'][seo]')) { ?>
									<span class="error"><?php echo form_error('information_description['. $language['language_id'] .'][seo]'); ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td>
								<span class="required">*</span> <?php echo $language['name']; ?> Açıklama
							</td>
							<td>
								<?php
									if(isset($information_description[$language['language_id']]['description'])) {
										$_information_description = $information_description[$language['language_id']]['description'];
									} else {
										$_information_description = NULL;
									}
								?>
								<textarea name="information_description[<?php echo $language['language_id']; ?>][description]" id="description_<?php echo $language['language_id']; ?>"><?php echo $_information_description; ?></textarea>
								<?php if (form_error('information_description['. $language['language_id'] .'][description]')) { ?>
									<span class="error"><?php echo form_error('information_description['. $language['language_id'] .'][description]'); ?></span>
								<?php } ?>
							</td>
						</tr>
					</table>
				</div>
			<?php } ?>
		</div>

		</form>
	</div>
</div>

<script type="text/javascript"><!--
$('#tabs a').tabs();
$('#languages a').tabs();
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description_<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
	filebrowserImageBrowseUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
	filebrowserFlashBrowseUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
	filebrowserUploadUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
	filebrowserImageUploadUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
	filebrowserFlashUploadUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>'
});
<?php } ?>
//--></script>
<?php $this->load->view('yonetim/footer_view');   ?>

<script type="text/javascript"><!--
$('#tabs a').tabs();
$('#languages a').tabs();
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description_<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
	filebrowserImageBrowseUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
	filebrowserFlashBrowseUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
	filebrowserUploadUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
	filebrowserImageUploadUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
	filebrowserFlashUploadUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>'
});
<?php } ?>
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