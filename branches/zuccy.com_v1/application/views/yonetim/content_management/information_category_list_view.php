<?php 
	$this->load->view('yonetim/header_view');
?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('<?php echo yonetim_resim(); ?>information.png');"><?php echo $title; ?> Kategori</h1>
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
						<?php $_ic_title_url = yonetim_url('content_management/information_category/lists/'. $information_category_type .'/icd.title-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_ic_title_url; ?>"<?php echo ($sort == 'icd.title') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Başlık</a>
					</td>
					<td class="left">
						<?php $_ic_seo_url = yonetim_url('content_management/information_category/lists/'. $information_category_type .'/icd.seo-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_ic_seo_url; ?>"<?php echo ($sort == 'icd.seo') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Seo Linki</a>
					</td>
					<td class="right">
						<?php $_ic_date_added_url = yonetim_url('content_management/information_category/lists/'. $information_category_type .'/ic.date_added-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_ic_date_added_url; ?>"<?php echo ($sort == 'ic.date_added') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Ekleme Tarihi</a>
					</td>
					<td class="right">
						<?php $_ic_date_modified_url = yonetim_url('content_management/information_category/lists/'. $information_category_type .'/ic.date_modified-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_ic_date_modified_url; ?>"<?php echo ($sort == 'ic.date_modified') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Güncelleme Tarihi</a>
					</td>
					<td class="right">
						<?php $_ic_status_url = yonetim_url('content_management/information_category/lists/'. $information_category_type .'/ic.status-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_ic_status_url; ?>"<?php echo ($sort == 'ic.status') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Durum</a>
					</td>
					<td class="right">İşlemler</td>
				</tr>
			</thead>
			<tbody>

				<tr class="filter">
					<td>&nbsp;</td>
					<td>
						<input type="text" name="filter_title" value="<?php echo isset($filter_icd_title) ? $filter_icd_title : NULL; ?>" />
					</td>
					<td>
						<input type="text" name="filter_seo" value="<?php echo isset($filter_icd_seo) ? $filter_icd_seo : NULL; ?>" />
					</td>
					<td align="right">
						<input type="text" name="filter_date_added" class="date" value="<?php echo isset($filter_ic_date_added) ? $filter_ic_date_added : NULL; ?>" />
					</td>
					<td align="right">
						<input type="text" name="filter_date_modified" class="date" value="<?php echo isset($filter_ic_date_modified) ? $filter_ic_date_modified : NULL; ?>" />
					</td>
					<td align="right">
						<?php
							$_filter_status_types = array(
								''		=> '',
								'1'		=> 'Aktif',
								'0'		=> 'Pasif'
							);
							$_filter_status = isset($filter_ic_status) ? $filter_ic_status : '';
							echo form_dropdown('filter_status', $_filter_status_types, $_filter_status);
						?>
					</td>
					<td align="right" style="width: 90px;">
						<a onclick="filter();" class="buton"><span>Filtre</span></a>
					</td>
				</tr>

				<?php 
				if ($information_categories) {
					foreach ($information_categories as $information_category) 
					{ ?>
						<tr>
							<td style="text-align: center;">
								<?php if ($information_category['selected']) { ?>
									<input type="checkbox" name="selected[]" value="<?php echo $information_category['information_category_id']; ?>" checked="checked" />
								<?php } else { ?>
									<input type="checkbox" name="selected[]" value="<?php echo $information_category['information_category_id']; ?>" />
								<?php } ?>
							</td>
							<td class="left"><?php echo $information_category['title']; ?></td>
							<td class="left"><?php echo $information_category['seo']; ?></td>
							<td class="right"><?php echo standard_date('DATE_TR1', mysql_to_unix($information_category['date_added']), 'tr'); ?></td>
							<td class="right"><?php echo standard_date('DATE_TR1', mysql_to_unix($information_category['date_modified']), 'tr'); ?></td>
							<td class="right"><?php echo ($information_category['status']) ? 'Aktif' : 'Pasif'; ?></td>
							<td class="right">
								<?php foreach ($information_category['action'] as $action) { ?>
								[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
								<?php } ?>
							</td>
						</tr>
						<?php 
					} 
				} else { ?>
				<tr>
					<td class="center" colspan="8"><?php echo $title; ?> Kategori Bulunamadı</td>
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
		url = '<?php echo yonetim_url(); ?>/content_management/information_category/lists/<?php echo $information_category_type; ?>/<?php echo $sort_link; ?>/';
		
		var filter_title = $('input[name=\'filter_title\']').attr('value');
		if (filter_title) {
			url += 'icd.title|' + encodeURIComponent(filter_title) + ']';
		}
		
		var filter_seo = $('input[name=\'filter_seo\']').attr('value');
		if (filter_seo) {
			url += 'icd.seo|' + encodeURIComponent(filter_seo) + ']';
		}
	
		var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
		if (filter_date_added) {
			url += 'ic.date_added|' + encodeURIComponent(filter_date_added) + ']';
		}
	
		var filter_date_modified = $('input[name=\'filter_date_modified\']').attr('value');
		if (filter_date_modified) {
			url += 'ic.date_modified|' + encodeURIComponent(filter_date_modified) + ']';
		}
	
		var filter_status = $('select[name=\'filter_status\']').attr('value');
		if (filter_status) {
			url += 'ic.status|' + encodeURIComponent(filter_status) + ']';
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