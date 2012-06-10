<?php $this->load->view('yonetim/header_view'); ?>
<?php
	if(form_error('column')) {
		echo '<div class="warning" style="margin-top: 10px;">' . form_error('column') . '</div>';
	}
?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('<?php echo yonetim_resim();?>category.png');">Ürün İçeri Aktar</h1>
		<div class="buttons">
			<a onclick="$('#form').submit();" class="buton"><span>Kaydet</span></a>
			<a onclick="location = '<?php echo yonetim_url('urunler/product_import'); ?>';" class="buton" style="margin-left:10px;"><span>İptal</span></a>
		</div>
	</div>
	<div class="content">
		<form action="<?php echo yonetim_url('urunler/product_import/select_column'); ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="form">
				<tr>
					<td>
						<span class="required">*</span> Sütunlar
						<span class="help">Ürün Resim Yolu kısmındaki resimleri resim yöneticisinden xml_resimleri klasörüne ekleyebilirsiniz.</span>
					</td>
					<td>
						<table style="width:900px;">
							<?php
								$value	= array();
								$write	= '';
								$i		= 0;
								foreach($csv_datas as $csv_key => $csv_value) {
									$write .= '<tr>';
									$write .= '<td style="width:400px;">' . mb_convert_encoding($csv_value, 'UTF-8', 'auto') . '</td>';
									$write .= '<td style="width:300px;">';
									$post_data = $this->input->post('column');
									if($post_data AND isset($post_data[$csv_key])) {
										$check = $post_data[$i];
									} else {
										$check = NULL;
									}
									$select = array_merge(array('' => 'Kullanma'), $select_variables);
									$write .= form_dropdown('column[]', $select, $check);
									$write .= (isset($errors[$i]) AND is_array($errors) AND $errors[$i] === TRUE) ? '&nbsp;<span class="error" style="display:inline;">Bu alan birden fazla seçilmiş!</span>' : '';
									$write .= '</td>';
									$write .= '</tr>';
									$i++;
								}
								echo $write;
							?>
						</table>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>
<?php $this->load->view('yonetim/footer_view'); ?>