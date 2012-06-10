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
						if(form_error('parent_id') OR form_error('status') OR form_error('sort_order') OR form_error('sort_order')) {
							$tab_general_error = TRUE;
						}
						if($tab_general_error == FALSE) {
							foreach ($languages as $_language) {
								if (
									form_error('category_description['. $_language['language_id'] .'][name]') OR 
									form_error('category_description['. $_language['language_id'] .'][meta_keywords]') OR 
									form_error('category_description['. $_language['language_id'] .'][meta_description]') OR 
									form_error('category_description['. $_language['language_id'] .'][seo]') OR 
									form_error('category_description['. $_language['language_id'] .'][description]')
								) {
									$tab_general_error = TRUE;
									break;
								}
							}
						}
						echo ($tab_general_error) ? '<img src="'. yonetim_resim() .'warning.png" width="13" />' : NULL;
					?>
				</a>
				<a tab="#tab_feature">
					Özellikler
					<?php
						$tab_features_error = FALSE;
						if($tab_features_error == FALSE) {
							if($category_features) {
								foreach($category_features as $cf_key => $category_feature) {
									foreach ($languages as $_lang) {
										foreach($category_feature as $key => $value) {
											if (form_error('category_features['. $cf_key .']['. $_lang['language_id'] .'][name]')) {
												$tab_features_error = TRUE;
												break;
											}
										}
									}
								}
							}
						}
						echo ($tab_features_error) ? '<img src="'. yonetim_resim() .'warning.png" width="13" />' : NULL;
					?>
				</a>
			</div>

			<div id="tab_general">
				<div id="languages" class="htabs">
					<?php foreach ($languages as $_language) { ?>
						<a tab="#language_<?php echo $_language['language_id']; ?>"><img src="<?php echo yonetim_resim(); ?>flags/<?php echo $_language['image']; ?>" title="<?php echo $_language['name']; ?>" /> <?php echo $_language['name']; ?>
							<?php
								if (
									form_error('category_description['. $_language['language_id'] .'][name]') OR 
									form_error('category_description['. $_language['language_id'] .'][meta_keywords]') OR 
									form_error('category_description['. $_language['language_id'] .'][meta_description]') OR 
									form_error('category_description['. $_language['language_id'] .'][seo]') OR 
									form_error('category_description['. $_language['language_id'] .'][description]') OR 
									form_error('parent_id') OR form_error('status') OR form_error('sort_order') OR form_error('sort_order') OR form_error('top') OR form_error('column')
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
								Kategori Resmi
							</td>
	        				<td>
	        					<input type="hidden" name="image" value="<?php echo $preview_input; ?>" id="image">
	        					<img src="<?php echo $preview; ?>" alt="" id="preview" onmouseover="$(this).attr('src','<?php echo $this->image_model->resize('resim_ekle_hover.jpg', 100, 100); ?>');" onmouseout="$(this).attr('src','<?php echo $preview; ?>');" title="Resim eklemek yada değiştirmek için tıklayınız." onclick="image_upload('image', 'preview');" style="cursor: pointer; border: 1px solid #EEEEEE;">
	        					<img src="<?php echo yonetim_resim(); ?>image.png" alt="" title="Resim eklemek yada değiştirmek için tıklayınız." style="cursor: pointer;" align="top" onclick="image_upload('image', 'preview');">
							</td>
						</tr>
						<tr>
							<td><span class="required">*</span> Üst Kategori</td>
							<td>
								<?php
									echo form_dropdown('parent_id', $allowed_categories, $parent_id);
								?>
								<?php if (form_error('parent_id')) { ?>
									<span class="error"><?php echo form_error('parent_id'); ?></span>
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
						<!-- <tr>
							<td><span class="required">*</span> Üst Menüde Göster<span class="help">Üst menüde yer almasını istiyorsanız işaretleyin.( Sadece ana kategoriler için geçerlidir! )</span></td>
							<td>
								<?php
									$_top_array = array('0' => ' - Hayır - ', '1' => ' - Evet - ');
									echo form_dropdown('top', $_top_array, $top);
								?>
								<?php if (form_error('top')) { ?>
									<span class="error"><?php echo form_error('top'); ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td><span class="required">*</span> Üst Menüdeki Alt Kategori Sütun Sayısı<span class="help">Üst menüde yer alan kategorinin alt kategori listeleme şekli ve sayısı.</span></td>
							<td>
								<input type="text" name="column" value="<?php echo $column; ?>" />
								<?php if (form_error('column')) { ?>
									<span class="error"><?php echo form_error('column'); ?></span>
								<?php } ?>
							</td>
						</tr>
                        -->
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
										if(isset($category_description[$language['language_id']]['name'])) {
											$_category_name = $category_description[$language['language_id']]['name'];
										} else {
											$_category_name = NULL;
										}
									?>
									<input type="text" name="category_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo $_category_name; ?>" />
									<?php if (form_error('category_description['. $language['language_id'] .'][name]')) { ?>
										<span class="error"><?php echo form_error('category_description['. $language['language_id'] .'][name]'); ?></span>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									 <?php echo $language['name']; ?> Meta Keywords
								</td>
								<td>
									<?php
										if(isset($category_description[$language['language_id']]['meta_keywords'])) {
											$_category_meta_keywords = $category_description[$language['language_id']]['meta_keywords'];
										} else {
											$_category_meta_keywords = NULL;
										}
									?>
									<input type="text" name="category_description[<?php echo $language['language_id']; ?>][meta_keywords]" size="100" value="<?php echo $_category_meta_keywords; ?>" />
									<?php if (form_error('category_description['. $language['language_id'] .'][meta_keywords]')) { ?>
										<span class="error"><?php echo form_error('category_description['. $language['language_id'] .'][meta_keywords]'); ?></span>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									 <?php echo $language['name']; ?> Meta Description
								</td>
								<td>
									<?php
										if(isset($category_description[$language['language_id']]['meta_description'])) {
											$_category_meta_description = $category_description[$language['language_id']]['meta_description'];
										} else {
											$_category_meta_description = NULL;
										}
									?>
									<input type="text" name="category_description[<?php echo $language['language_id']; ?>][meta_description]" size="100" value="<?php echo $_category_meta_description; ?>" />
									<?php if (form_error('category_description['. $language['language_id'] .'][meta_description]')) { ?>
										<span class="error"><?php echo form_error('category_description['. $language['language_id'] .'][meta_description]'); ?></span>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									<?php echo $language['name']; ?> Seo Adresi <br /><span class="help">Seo adresi girilmesi zorunlu değildir, girilen seo adresi gereçli olur. Girilmez ise otomatik olarak Başlık kısmını referans alarak oluşturulur.</span>
								</td>
								<td>
									<?php
										if(isset($category_description[$language['language_id']]['seo'])) {
											$_category_seo = $category_description[$language['language_id']]['seo'];
										} else {
											$_category_seo = NULL;
										}
									?>
									<input type="text" name="category_description[<?php echo $language['language_id']; ?>][seo]" size="100" value="<?php echo $_category_seo; ?>" />
									<?php if (form_error('category_description['. $language['language_id'] .'][seo]')) { ?>
										<span class="error"><?php echo form_error('category_description['. $language['language_id'] .'][seo]'); ?></span>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									<?php echo $language['name']; ?> Açıklama
								</td>
								<td>
									<?php
										if(isset($category_description[$language['language_id']]['description'])) {
											$_category_description = $category_description[$language['language_id']]['description'];
										} else {
											$_category_description = NULL;
										}
									?>
									<textarea name="category_description[<?php echo $language['language_id']; ?>][description]" id="description_<?php echo $language['language_id']; ?>"><?php echo $_category_description; ?></textarea>
									<?php if (form_error('category_description['. $language['language_id'] .'][description]')) { ?>
										<span class="error"><?php echo form_error('category_description['. $language['language_id'] .'][description]'); ?></span>
									<?php } ?>
								</td>
							</tr>
						</table>
					</div>
				<?php } ?>
			</div>

			<div id="tab_feature">
				<table class="form">
					<tr>
						<td>Özellik Adı</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>
							<div id="feature_div">
							<div id="feature_error" style="display: none;" class="error">Lütfen geçerli bir özellik adı giriniz.</div>
							<div style="margin-top:5px;"></div>
							<?php $languages_i = 0; ?>
							<?php foreach ($languages as $language) { ?>
								<?php if($languages_i != 0) { ?>
								<div style="margin-top:5px;"></div>
								<?php } ?>
									<span style="display:inline-block;width:13px;"></span>
									<input type="text" class="feature_input" id="feature_input_<?php echo $language['language_id']; ?>">&nbsp;<img src="<?php echo yonetim_resim(); ?>flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
								<?php $languages_i++; ?>
							<?php } ?>
							</div>
						</td>
						<td>
							<div class="buttons"><a onclick="add_feature();" class="buton"><span>Ekle</span></a></div>
						</td>
					</tr>
					<tr>
						<td colspan="2" id="feature_list">
							<?php $features_row = 0; ?>
							<?php foreach($category_features as $cf_key => $cf_value) { ?>
								<div id="feature_<?php echo $features_row; ?>">
									<div style="float:left;">
										<?php $i = 0; ?>
										<?php foreach ($cf_value as $cs_desc_key => $cf_desc_value) { ?>
										<div style="float:left;margin-top:5px;margin-right:-15px;">
											<span style="display:inline-block;width:13px;">
												<?php if (form_error('category_features['. $cf_key .']['. $cs_desc_key .'][name]')) { ?>
													<img src="<?php echo yonetim_resim(); ?>warning.png" width="13" title="<?php echo str_replace(array('<p>', '</p>'), '', form_error('category_features['. $cf_key .']['. $cs_desc_key .'][name]')); ?>" />
												<?php } ?>
											</span>
											<input type="text" name="category_features[<?php echo $cf_key; ?>][<?php echo $cs_desc_key; ?>][name]" id="features[<?php echo $cf_key; ?>][<?php echo $cs_desc_key; ?>]" value="<?php echo $cf_desc_value['name']; ?>">&nbsp;<img src="<?php echo yonetim_resim(); ?>flags/<?php echo $languages[$cs_desc_key]['image']; ?>" title="<?php echo $languages[$cs_desc_key]['name']; ?>">
										</div>
										<div style="clear:both;"></div>
										<?php $i++; ?>
										<?php } ?>
										<div style="clear:both;"></div>
									</div>
									<div style="float:left;margin-left:50px;padding:12px;">
										<div class="buttons">
											<a onclick="feature_delete('<?php echo $cf_key; ?>', '<?php echo $features_row; ?>');" href="javascript:;" class="buton"><span>Sil</span></a>
										</div>
									</div>
									<div style="clear:both;"></div>
									<div style="margin-top:5px;width:300px;border-top: 1px dotted #CCC;"></div>
									<div style="clear:both;"></div>
								</div>
								<?php $features_row++; ?>
							<?php }	?>
						</td>
					</tr>
				</table>
			</div>

		</form>
	</div>
</div>

<script type="text/javascript" charset="utf-8">

	var last_count = <?php echo $features_row; ?>;

	function feature_delete (id, div_id) {
		$.ajax({
			type: "POST",
			url: "<?php echo yonetim_url('urunler/product_category/feature_delete'); ?>",
			data: "feature_id=" + id,
			dataType: 'json',
			beforeSend: function() {},
			complete: function() {},
			success: function(response) {
				if(response.success != '') {
					$('#feature_'+ div_id).remove();
				}

				if(response.error != '') {
					alert(response.error);
				}
			}
		});
	}

	function add_feature () {

		var check_add = 0;
		$.each($('#feature_div input'), function() {
			var check = $("#" + $(this).attr('id')).val();
			if(check != '') {
				check_add += 1;
			} else {
				check_add = 0;
			}
		});

		if(check_add == 0) {
			$('#feature_error').show();
		} else {
			$('#feature_error').hide();
		}

		if(check_add > 0) {
			var _yaz = '';
			last_count += 1;
			var _count = last_count;
			var sheme = '';
			<?php
				$i = 0;
				foreach ($languages as $key => $language) { ?>
				var value = $('#feature_input_<?php echo $language['language_id']; ?>').val();

				<?php if($i == 0) { ?>
				sheme += '<div id="feature_'+ _count +'">';
				sheme += '<div style="float:left;">';
				<?php } ?>

				sheme += '<div style="float:left;margin-top:5px;margin-right:-15px;">';
				sheme += '<span style="display:inline-block;width:13px;"></span>';
				sheme += '<input type="text" name="category_features['+ _count +'][<?php echo $language['language_id']; ?>][name]" value="'+ value +'" />';
				sheme += '&nbsp;<img src="<?php echo yonetim_resim(); ?>flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />';
				sheme += '</div>';
				sheme += '<div style="clear:both;"></div>';

				<?php if($i == 1) { ?>
				sheme += '</div>';
				sheme += '<div style="float:left;margin-left:50px;padding:12px;"><div class="buttons"><a onclick="feature_delete(\''+ _count +'\', \''+ _count +'\');" href="javascript:;" class="buton"><span>Sil</span></a></div></div>';
				sheme += '<div style="clear:both;"></div>';
				sheme += '<div style="margin-top:5px;width:300px;border-top: 1px dotted #CCC;"></div>';
				sheme += '<div style="clear:both;"></div>';
				sheme += '</div>';
				sheme += '<div style="clear:both;"></div>';
				<?php } ?>
				<?php $i++; ?>
			<?php } ?>
			_yaz += sheme;

			if(_yaz != '') {
				$('.feature_input').val('');
				$('#feature_list').append(_yaz);
			}
		}
	}
</script>

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

<?php $this->load->view('yonetim/footer_view');   ?>