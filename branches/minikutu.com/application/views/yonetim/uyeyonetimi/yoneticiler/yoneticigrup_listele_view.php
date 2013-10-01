<?php
	$this->load->view('yonetim/header_view');
?>

<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('<?php echo yonetim_resim(); ?>customer.png');">Yönetici Grupları</h1>
		<div class="butons" style="float:right;margin-top:5px;">
			<a onclick="location = '<?php echo yonetim_url('uye_yonetimi/yonetici_grup/ekle'); ?>'" class="buton"><span>Ekle</span></a>
			<a onclick="$('form').submit();" class="buton" style="margin-left:10px;"><span>Sil</span></a>
		</div>
	</div>
	<div class="content">
		<form action="<?php echo yonetim_url('uye_yonetimi/yonetici_grup/sil'); ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="list">
				<thead>
					<tr>
						<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
						<td class="left">Yönetici Grup Adı</td>
						<td class="left">Oran</td>
						<td class="right">İşlemler</td>
					</tr>
				</thead>
	        	<tbody>
	          	<?php if ($customer_groups) { ?>
					<?php foreach ($customer_groups as $customer_group) { ?>
	    				<tr>
	            			<td style="text-align: center;">
	            				<?php if ($customer_group['selected']) { ?>
									<input type="checkbox" name="selected[]" <?php echo ($customer_group['durum'] == 1) ? 'disabled="disabled"' : NULL;?> value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
								<?php } else { ?>
									<input type="checkbox" name="selected[]" <?php echo ($customer_group['durum'] == 1) ? 'disabled="disabled"' : NULL;?> value="<?php echo $customer_group['customer_group_id']; ?>" />
								<?php } ?>
							</td>
	            			<td class="left"><?php echo $customer_group['name']; ?> ( <?php echo $customer_group['toplam_musteri'];?> ) <?php echo ($customer_group['durum'] == 1) ? '<span style="color:red;">Silinemez</span>' : NULL;?></td>
	            			<td class="left"><?php echo ($customer_group['fiyat_tip'] == 1) ? '<strong>+</strong>' : '<strong>-</strong>'; ?> <?php echo $customer_group['fiyat_orani']; ?></td>
	            			<td class="right">
	            				<?php foreach ($customer_group['action'] as $action) { ?>
								[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
								<?php } ?>
	        				</td>
						</tr>
					<?php } ?>
				<?php } else { ?>
					<tr>
	            		<td class="center" colspan="3">Yönetici Grubu Bulunamadı</td>
					</tr>
				<?php } ?>
	        	</tbody>
			</table>
		</form>
		<?php echo $this->pagination->create_links();  ?>
  	</div>
</div>
<?php
	$this->load->view('yonetim/footer_view');
?>