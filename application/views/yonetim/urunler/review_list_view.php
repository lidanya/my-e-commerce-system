<?php 
	$this->load->view('yonetim/header_view');
?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('<?php echo yonetim_resim(); ?>review.png');"><?php echo $title; ?></h1>
		<div class="buttons">
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
						<?php $_email_url = yonetim_url('urunler/review/lists/r.email-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_email_url; ?>"<?php echo ($sort == 'r.email') ? ' class="'. strtolower($order) .'"' : NULL; ?>>E-Posta</a>
					</td>
					<td class="left">
						<?php $_author_url = yonetim_url('urunler/review/lists/r.author-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_author_url; ?>"<?php echo ($sort == 'r.author') ? ' class="'. strtolower($order) .'"' : NULL; ?>>İsim</a>
					</td>
					<td class="left">
						<?php $_text_url = yonetim_url('urunler/review/lists/r.text-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_text_url; ?>"<?php echo ($sort == 'r.text') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Yorum</a>
					</td>
					<td class="right">
						<?php $_rating_url = yonetim_url('urunler/review/lists/r.rating-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_rating_url; ?>"<?php echo ($sort == 'r.rating') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Oy</a>
					</td>
					<td class="right">
						<?php $_date_added_url = yonetim_url('urunler/review/lists/r.date_added-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_date_added_url; ?>"<?php echo ($sort == 'r.date_added') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Ekleme Tarihi</a>
					</td>
					<td class="right">
						<?php $_date_modified_url = yonetim_url('urunler/review/lists/r.date_modified-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_date_modified_url; ?>"<?php echo ($sort == 'r.date_modified') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Güncelleme Tarihi</a>
					</td>
					<td class="right">
						<?php $_status_url = yonetim_url('urunler/review/lists/r.status-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_status_url; ?>"<?php echo ($sort == 'r.status') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Durum</a>
					</td>
					<td class="right">İşlemler</td>
				</tr>
			</thead>
			<tbody>

				<tr class="filter">
					<td>&nbsp;</td>
					<td>
						<input type="text" name="filter_email" value="<?php echo isset($filter_r_email) ? $filter_r_email : NULL; ?>" />
					</td>
					<td>
						<input type="text" name="filter_author" value="<?php echo isset($filter_r_author) ? $filter_r_author : NULL; ?>" />
					</td>
					<td>
						<input type="text" name="filter_text" value="<?php echo isset($filter_r_text) ? $filter_r_text : NULL; ?>" />
					</td>
					<td align="right">
						<input type="text" name="filter_rating" value="<?php echo isset($filter_r_rating) ? $filter_r_rating : NULL; ?>" />
					</td>
					<td align="right">
						<input type="text" name="filter_date_added" class="date" value="<?php echo isset($filter_r_date_added) ? $filter_r_date_added : NULL; ?>" />
					</td>
					<td align="right">
						<input type="text" name="filter_date_modified" class="date" value="<?php echo isset($filter_r_date_modified) ? $filter_r_date_modified : NULL; ?>" />
					</td>
					<td align="right">
						<?php
							$_filter_status_types = array(
								''		=> '',
								'1'		=> 'Aktif',
								'0'		=> 'Pasif'
							);
							$_filter_status = isset($filter_p_status) ? $filter_p_status : '';
							echo form_dropdown('filter_status', $_filter_status_types, $_filter_status);
						?>
					</td>
					<td align="right" style="width: 90px;">
						<a onclick="filter();" class="buton"><span>Filtre</span></a>
					</td>
				</tr>

				<?php 
				if ($reviews) {
					foreach ($reviews as $review) 
					{ ?>
						<tr>
							<td style="text-align: center;">
								<?php if ($review['selected']) { ?>
									<input type="checkbox" name="selected[]" value="<?php echo $review['review_id']; ?>" checked="checked" />
								<?php } else { ?>
									<input type="checkbox" name="selected[]" value="<?php echo $review['review_id']; ?>" />
								<?php } ?>
							</td>
							<td class="left"><?php echo $review['email']; ?></td>
							<td class="left"><?php echo $review['author']; ?></td>
							<td class="left" style="width:300px;"><?php echo character_limiter(strip_tags($review['text']), 100); ?></td>
							<td class="right">
								<?php if ($review['rating'] <= 0) { ?>
								<span style="color: #FF0000;"><?php echo $review['rating']; ?></span>
								<?php } elseif ($review['rating'] <= 3) { ?>
								<span style="color: #FFA500;"><?php echo $review['rating']; ?></span>
								<?php } else { ?>
								<span style="color: #008000;"><?php echo $review['rating']; ?></span>
								<?php } ?>
							</td>
							<td class="right"><?php echo standard_date('DATE_TR1', mysql_to_unix($review['date_added']), 'tr'); ?></td>
							<td class="right"><?php echo standard_date('DATE_TR1', mysql_to_unix($review['date_modified']), 'tr'); ?></td>
							<td class="right"><?php echo ($review['status']) ? 'Aktif' : 'Pasif'; ?></td>
							<td class="right">
								<?php foreach ($review['action'] as $action) { ?>
								[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
								<?php } ?>
							</td>
						</tr>
						<?php 
					}
				} else { ?>
				<tr>
					<td class="center" colspan="9"><?php echo $title; ?> Bulunamadı</td>
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
		url = '<?php echo yonetim_url(); ?>/urunler/review/lists/<?php echo $sort_link; ?>/';
		
		var filter_email = $('input[name=\'filter_email\']').attr('value');
		if (filter_email) {
			url += 'r.email|' + encodeURIComponent(filter_email) + ']';
		}

		var filter_author = $('input[name=\'filter_author\']').attr('value');
		if (filter_author) {
			url += 'r.author|' + encodeURIComponent(filter_author) + ']';
		}

		var filter_text = $('input[name=\'filter_text\']').attr('value');
		if (filter_text) {
			url += 'r.text|' + encodeURIComponent(filter_text) + ']';
		}

		var filter_rating = $('input[name=\'filter_rating\']').attr('value');
		if (filter_rating) {
			url += 'r.rating|' + encodeURIComponent(filter_rating) + ']';
		}

		var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
		if (filter_date_added) {
			url += 'r.date_added|' + encodeURIComponent(filter_date_added) + ']';
		}
	
		var filter_date_modified = $('input[name=\'filter_date_modified\']').attr('value');
		if (filter_date_modified) {
			url += 'r.date_modified|' + encodeURIComponent(filter_date_modified) + ']';
		}
	
		var filter_status = $('select[name=\'filter_status\']').attr('value');
		if (filter_status) {
			url += 'r.status|' + encodeURIComponent(filter_status) + ']';
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