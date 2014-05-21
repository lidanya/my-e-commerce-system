<?php 
	$this->load->view('yonetim/header_view');
?>
<link rel="stylesheet" type="text/css" href="<?php echo yonetim_js(); ?>/jquery/dd/css/dd.css" />
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

			<div id="tabs" class="htabs">
				<a tab="#tab_general">
					Genel
					<?php
						$tab_general_error = FALSE;
						if(
							form_error('product_id') OR
							form_error('user_id') OR
							form_error('email') OR
							form_error('author') OR
							form_error('text') OR
							form_error('rating') OR
							form_error('status')
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
							Ürün
						</td>
						<td>
							<?php if($product_info) { ?>
								Ürün Adı&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <a href="<?php echo site_url($product_info->seo . '--product'); ?>" target="_blank" title="Ürünü görmek için tıklayın" /><?php echo $product_info->name; ?></a><br />
								Ürün Kodu&nbsp;&nbsp;&nbsp;: <?php echo $product_info->model; ?>
							<?php } ?>
							<div class="clear"></div>
							<br />
							<select name="product_id" id="product_id" style="width:310px;">
								<?php
									foreach ($products as $product) {
										$selected = ($product->product_id == $product_id) ? 'selected="selected"' : NULL;
										echo '<option value="'. $product->product_id .'" '. $selected .' title="'. show_image($product->image, 50, 50) .'">'. character_limiter($product->name, 30) .'</option>';
									}
								?>
							</select>
							<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
						</td>
					</tr>
					<tr>
						<td>
							<span class="required">*</span> Yazar
						</td>
						<td>
							<input type="text" name="author" value="<?php echo $author; ?>" style="width:300px;">
							<?php if (form_error('author')) { ?>
								<span class="error"><?php echo form_error('author'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							<span class="required">*</span> E-Posta
						</td>
						<td>
							<input type="text" name="email" value="<?php echo $email; ?>" style="width:300px;">
							<?php if (form_error('email')) { ?>
								<span class="error"><?php echo form_error('email'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							<span class="required">*</span> Oy
						</td>
						<td>
							Kötü
							&nbsp; <input type="radio" name="rating" value="1" <?php echo set_radio('rating', '1', ($rating == 1)); ?>>
							&nbsp; <input type="radio" name="rating" value="2" <?php echo set_radio('rating', '2', ($rating == 2)); ?>>
							&nbsp; <input type="radio" name="rating" value="3" <?php echo set_radio('rating', '3', ($rating == 3)); ?>>
							&nbsp; <input type="radio" name="rating" value="4" <?php echo set_radio('rating', '4', ($rating == 4)); ?>>
							&nbsp; <input type="radio" name="rating" value="5" <?php echo set_radio('rating', '5', ($rating == 5)); ?>>
							&nbsp; İyi
							<?php if (form_error('email')) { ?>
								<span class="error"><?php echo form_error('email'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							<span class="required">*</span> Yorum
						</td>
						<td>
							<textarea name="text" rows="12" cols="100"><?php echo $text; ?></textarea>
							<?php if (form_error('email')) { ?>
								<span class="error"><?php echo form_error('email'); ?></span>
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
				</table>
			</div>

		</form>
	</div>
</div>
<script type="text/javascript" src="<?php echo yonetim_js(); ?>jquery/dd/jquery.dd.js" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$("#product_id").msDropDown();
	});
</script>
<?php $this->load->view('yonetim/footer_view');   ?>