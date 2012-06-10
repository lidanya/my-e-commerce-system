<?php 
	$this->load->view('yonetim/header_view');
?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('<?php echo yonetim_resim(); ?>information.png');"><?php echo $title; ?></h1>
		<div class="buttons">
			<a onclick="location = '<?php echo yonetim_url($add_url); ?>'" class="buton"><span>Ekle</span></a>
			<a onclick="$('form').submit();" class="buton" style="margin-left:10px;"><span>Sil</span></a>
		</div>
	</div>
	<div class="content">
		<form action="<?php echo yonetim_url($delete_url); ?>" method="post" enctype="multipart/form-data" id="form">
		<table class="list">
			<thead>
				<tr>
					<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
					<td class="left">
						<?php $_id_title_url = yonetim_url('content_management/information/lists/'. $information_type .'/id.title-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_id_title_url; ?>"<?php echo ($sort == 'id.title') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Başlık</a>
					</td>
					<td class="left">
						<?php $_id_seo_url = yonetim_url('content_management/information/lists/'. $information_type .'/id.seo-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_id_seo_url; ?>"<?php echo ($sort == 'id.seo') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Seo Linki</a>
					</td>
					<td class="right">
						<?php $_id_date_added_url = yonetim_url('content_management/information/lists/'. $information_type .'/i.date_added-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_id_date_added_url; ?>"<?php echo ($sort == 'i.date_added') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Ekleme Tarihi</a>
					</td>
					<td class="right">
						<?php $_id_date_modified_url = yonetim_url('content_management/information/lists/'. $information_type .'/i.date_modified-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_id_date_modified_url; ?>"<?php echo ($sort == 'i.date_modified') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Güncelleme Tarihi</a>
					</td>
					<td class="right">
						<?php $_id_status_url = yonetim_url('content_management/information/lists/'. $information_type .'/i.status-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_id_status_url; ?>"<?php echo ($sort == 'i.status') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Durum</a>
					</td>
					<td class="right">İşlemler</td>
				</tr>
			</thead>
			<tbody>

				<tr class="filter">
					<td>&nbsp;</td>
					<td>
						<input type="text" name="filter_title" value="<?php echo isset($filter_id_title) ? $filter_id_title : NULL; ?>" />
					</td>
					<td>
						<input type="text" name="filter_seo" value="<?php echo isset($filter_id_seo) ? $filter_id_seo : NULL; ?>" />
					</td>
					<td align="right">
						<input type="text" name="filter_date_added" class="date" value="<?php echo isset($filter_i_date_added) ? $filter_i_date_added : NULL; ?>" />
					</td>
					<td align="right">
						<input type="text" name="filter_date_modified" class="date" value="<?php echo isset($filter_i_date_modified) ? $filter_i_date_modified : NULL; ?>" />
					</td>
					<td align="right">
						<?php
							$_filter_status_types = array(
								''		=> '',
								'1'		=> 'Aktif',
								'0'		=> 'Pasif'
							);
							$_filter_status = isset($filter_i_status) ? $filter_i_status : '';
							echo form_dropdown('filter_status', $_filter_status_types, $_filter_status);
						?>
					</td>
					<td align="right" style="width: 90px;">
						<a onclick="filter();" class="buton"><span>Filtre</span></a>
					</td>
				</tr>

				<?php 
				if ($informations) {
					foreach ($informations as $information) 
					{ ?>
						<tr>
							<td style="text-align: center;">
								<?php if ($information['selected']) { ?>
									<input type="checkbox" name="selected[]" value="<?php echo $information['information_id']; ?>" checked="checked" />
								<?php } else { ?>
									<input type="checkbox" name="selected[]" value="<?php echo $information['information_id']; ?>" />
								<?php } ?>
							</td>
							<td class="left"><?php echo $information['title']; ?></td>
							<td class="left"><?php echo $information['seo']; ?></td>
							<td class="right"><?php echo standard_date('DATE_TR1', mysql_to_unix($information['date_added']), 'tr'); ?></td>
							<td class="right"><?php echo standard_date('DATE_TR1', mysql_to_unix($information['date_modified']), 'tr'); ?></td>
							<td class="right"><?php echo ($information['status']) ? 'Aktif' : 'Pasif'; ?></td>
							<td class="right">
								<?php foreach ($information['action'] as $action) { ?>
								[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
								<?php } ?>
							</td>
						</tr>
						<?php 
					} 
				} else { ?>
				<tr>
					<td class="center" colspan="8"><?php echo $title; ?> Bulunamadı</td>
		  		</tr>
		  		<?php } ?>
			</tbody>
	  	</table>
		</form>
		<?php
			echo $this->pagination->create_links(); 
		?>
	</div>
</div>

<script type="text/javascript"><!--
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});

	function filter() {
		url = '<?php echo yonetim_url(); ?>/content_management/information/lists/<?php echo $information_type; ?>/<?php echo $sort_link; ?>/';
		
		var filter_title = $('input[name=\'filter_title\']').attr('value');
		if (filter_title) {
			url += 'id.title|' + encodeURIComponent(filter_title) + ']';
		}
		
		var filter_seo = $('input[name=\'filter_seo\']').attr('value');
		if (filter_seo) {
			url += 'id.seo|' + encodeURIComponent(filter_seo) + ']';
		}
	
		var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
		if (filter_date_added) {
			url += 'i.date_added|' + encodeURIComponent(filter_date_added) + ']';
		}
	
		var filter_date_modified = $('input[name=\'filter_date_modified\']').attr('value');
		if (filter_date_modified) {
			url += 'i.date_modified|' + encodeURIComponent(filter_date_modified) + ']';
		}
	
		var filter_status = $('select[name=\'filter_status\']').attr('value');
		if (filter_status) {
			url += 'i.status|' + encodeURIComponent(filter_status) + ']';
		}

		url +=  '/0';
		document.location.href = url;
	}

	$('#form').keydown(function(e) {
		if (e.keyCode == 13) {
			filter();
		}
	});

//--></script>
<?php $this->load->view('yonetim/footer_view');   ?>