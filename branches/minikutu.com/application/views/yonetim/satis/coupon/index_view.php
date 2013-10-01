<?php $this->load->view('yonetim/header_view'); ?>

<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('<?php echo yonetim_resim(); ?>customer.png');">Kuponlar</h1>
		<div class="buttons">
			<a onclick="location.href = '<?php echo yonetim_url('satis/coupon/add'); ?>';" class="buton" style="margin-left:10px;"><span>Yeni Kod Oluştur</span></a>
			<a onclick="$('#delete_coupon').submit();" class="buton" style="margin-left:10px;"><span>Sil</span></a>
		</div>
	</div>
	
	<div class="content">
    	
		<table class="list">
			
		<thead>
			<tr>
				<td width="1" style="text-align: center;"><input type="checkbox" onclick="$(':input.coupon_id').attr('checked', this.checked);" /></td>
				<td class="left">Kod</td>
				<td class="left">Açıklama</td>
				<td class="left">Başlangıç Tarihi</td>
				<td class="left">Bitiş Tarihi</td>
				<td class="left">Oluşturulma Tarihi</td>
				<td class="left">Durum</td>
				<td class="left">İşlemler</td>
			</tr>
		</thead>
		
		<tbody>
			
			<form action="<?php echo site_url(uri_string()) ?>" method="get" id="filter">
			
			<tr class="filter">
				<td>&nbsp;</td>
				<td><input type="text" name="code" value="<?php echo _get('code') ?>" /></td>
				<td>&nbsp;</td>
				<td><input type="text" name="date_start" value="<?php echo _get('date_start') ?>" id="date_start" /></td>
				<td><input type="text" name="date_end" value="<?php echo _get('date_end') ?>" id="date_end" /></td>
				<td><input type="text" name="date_add" value="<?php echo _get('date_add') ?>" id="date_add" /></td>
				<td><?php echo form_dropdown('status', $this->status, _get('status', 'all')) ?></td>
				<td><a onclick="javascript: $('form#filter').submit()" class="buton"><span>Filtre</span></a></td>
			</tr>
			
			</form><!-- // #filter -->
			
			<form action="<?php echo yonetim_url('satis/coupon/delete') ?>" method="post" id="delete_coupon">
			
			<?php if ($result['total_rows'] > 0): ?>
				
				<?php foreach ($result['rows'] as $row): ?>
					
					<tr>
						<td width="1" style="text-align: center;"><input type="checkbox" name="coupon_id[]" class="coupon_id" value="<?php echo $row->id ?>" /></td>
						<td class="left"><?php echo $row->code ?></td>
						<td class="left">
							<?php if ($row->type == '1') { ?>
								%<?php echo $row->value; ?>
							<?php } else { ?>
								<?php echo $row->value; ?> TL
							<?php } ?>
							İndirim
						</td>
						<td class="left"><?php echo standard_date('DATE_TR1', strtotime($row->date_start), 'tr') ?></td>
						<td class="left"><?php echo standard_date('DATE_TR1', strtotime($row->date_end), 'tr') ?></td>
						<td class="left"><?php echo standard_date('DATE_TR1', strtotime($row->date_add), 'tr') ?></td>
						<td class="left"><?php echo $this->coupon_model->get_icon($row->status) ?></td>
						<td class="left"></td>
					</tr>
					
				<?php endforeach ?>	
				
			<?php else: ?>
				
				<tr>
					<td colspan="8"><p style="margin: 10px 0; text-align: center">Hiçbir kayıt bulunamadı.</p></td>
				</tr>
				
			<?php endif ?>
			
			</form><!-- // #delete_coupon -->
		
		</tbody>
		
		</table>
	
	</div><!-- // .content -->
	
</div><!-- // .box -->

<?php $this->load->view('yonetim/footer_view'); ?>


<script type="text/javascript"><!--
$(document).ready(function() {
	
	$('input#date_start, input#date_end, input#date_add').datepicker({dateFormat: 'yy-mm-dd'});
	
});

//--></script>