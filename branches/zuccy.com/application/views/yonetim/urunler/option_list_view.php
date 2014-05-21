<?php 
	$this->load->view('yonetim/header_view');
?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('<?php echo yonetim_resim(); ?>order.png');"><?php echo $title; ?></h1>
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
						<?php $_name_url = yonetim_url('urunler/option/lists/od.name-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_name_url; ?>"<?php echo ($sort == 'od.name') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Başlık</a>
					</td>
					<td class="left">
						<?php $_sort_order_url = yonetim_url('urunler/option/lists/o.sort_order-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_sort_order_url; ?>"<?php echo ($sort == 'o.sort_order') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Sırası</a>
					</td>
					<td class="right">İşlemler</td>
				</tr>
			</thead>
			<tbody>

				<tr class="filter">
					<td>&nbsp;</td>
					<td>
						<input type="text" name="filter_name" value="<?php echo isset($filter_od_name) ? $filter_od_name : NULL; ?>" />
					</td>
					<td>
						<input type="text" name="filter_sort_order" value="<?php echo isset($filter_o_sort_order) ? $filter_o_sort_order : NULL; ?>" />
					</td>
					<td align="right" style="width: 90px;">
						<a onclick="filter();" class="buton"><span>Filtre</span></a>
					</td>
				</tr>

				<?php 
				if ($options) {
					foreach ($options as $option) 
					{ ?>
						<tr>
							<td style="text-align: center;">
								<?php if ($option['selected']) { ?>
									<input type="checkbox" name="selected[]" value="<?php echo $option['option_id']; ?>" checked="checked" />
								<?php } else { ?>
									<input type="checkbox" name="selected[]" value="<?php echo $option['option_id']; ?>" />
								<?php } ?>
							</td>
							<td class="left"><?php echo $option['name']; ?></td>
							<td class="left"><?php echo $option['sort_order']; ?></td>
							<td class="right">
								<?php foreach ($option['action'] as $action) { ?>
								[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
								<?php } ?>
							</td>
						</tr>
						<?php 
					}
				} else { ?>
				<tr>
					<td class="center" colspan="4"><?php echo $title; ?> Bulunamadı</td>
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
	function filter() {
		url = '<?php echo yonetim_url(); ?>/urunler/option/lists/<?php echo $sort_link; ?>/';
		
		var filter_name = $('input[name=\'filter_name\']').attr('value');
		if (filter_name) {
			url += 'od.name|' + encodeURIComponent(filter_name) + ']';
		}

		var filter_sort_order = $('input[name=\'filter_sort_order\']').attr('value');
		if (filter_sort_order) {
			url += 'o.sort_order|' + encodeURIComponent(filter_sort_order) + ']';
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