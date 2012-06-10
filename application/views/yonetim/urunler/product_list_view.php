<?php 
	$this->load->view('yonetim/header_view');
?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('<?php echo yonetim_resim(); ?>product.png');"><?php echo $title; ?></h1>
		<div class="buttons">
			<a onclick="toplu_duzenle_func();" class="buton"><span>Toplu Düzenleme</span></a>
			<a onclick="location = '<?php echo yonetim_url($add_url); ?>'" class="buton" style="margin-left:10px;"><span>Ekle</span></a>
			<a onclick="$('#formlist').submit();" class="buton" style="margin-left:10px;"><span>Sil</span></a>
		</div>
	</div>
	<div class="content">
		<form action="<?php echo yonetim_url($delete_url); ?>" method="post" enctype="multipart/form-data" id="formlist">
		<table class="list">
			<thead>
				<tr>
					<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
					<td class="center">Ürün Resmi</td>
					<td class="left">
						<?php $_name_url = yonetim_url('urunler/product/lists/pd.name-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_name_url; ?>"<?php echo ($sort == 'pd.name') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Başlık</a>
					</td>
					<td class="left">
						<?php $_name_url = yonetim_url('urunler/product/lists/p.model-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_name_url; ?>"<?php echo ($sort == 'p.model') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Stok Kodu</a>
					</td>
					<td class="left">
						<?php $_seo_url = yonetim_url('urunler/product/lists/pd.seo-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_seo_url; ?>"<?php echo ($sort == 'pd.seo') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Seo Linki</a>
					</td>
					<td class="right">
						<?php $_quantity_url = yonetim_url('urunler/product/lists/p.quantity-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_quantity_url; ?>"<?php echo ($sort == 'p.quantity') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Miktar</a>
					</td>
					<td class="right">
						<?php $_date_added_url = yonetim_url('urunler/product/lists/p.date_added-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_date_added_url; ?>"<?php echo ($sort == 'p.date_added') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Ekleme Tarihi</a>
					</td>
					<td class="right">
						<?php $_date_modified_url = yonetim_url('urunler/product/lists/p.date_modified-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_date_modified_url; ?>"<?php echo ($sort == 'p.date_modified') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Güncelleme Tarihi</a>
					</td>
					<td class="right">
						<?php $_show_homepage_url = yonetim_url('urunler/product/lists/p.show_homepage-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_show_homepage_url; ?>"<?php echo ($sort == 'p.show_homepage') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Anasayfada Göster</a>
					</td>
					<td class="right">
						<?php $_status_url = yonetim_url('urunler/product/lists/p.status-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_status_url; ?>"<?php echo ($sort == 'p.status') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Durum</a>
					</td>
					<td class="right">İşlemler</td>
				</tr>
			</thead>
			<tbody>

				<tr class="filter">
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>
						<input type="text" name="filter_name" value="<?php echo isset($filter_pd_name) ? $filter_pd_name : NULL; ?>" />
					</td>
					<td>
						<input type="text" name="filter_model" value="<?php echo isset($filter_p_model) ? $filter_p_model : NULL; ?>" />
					</td>
					<td>
						<input type="text" name="filter_seo" value="<?php echo isset($filter_pd_seo) ? $filter_pd_seo : NULL; ?>" />
					</td>
					<td align="right">
						<input type="text" name="filter_quantity" value="<?php echo isset($filter_p_quantity) ? $filter_p_quantity : NULL; ?>" />
					</td>
					<td align="right">
						<input type="text" name="filter_date_added" class="date" value="<?php echo isset($filter_p_date_added) ? $filter_p_date_added : NULL; ?>" />
					</td>
					<td align="right">
						<input type="text" name="filter_date_modified" class="date" value="<?php echo isset($filter_p_date_modified) ? $filter_p_date_modified : NULL; ?>" />
					</td>
					<td align="right">
						<?php
							$_filter_show_homepage_types = array(
								''		=> '',
								'1'		=> 'Evet',
								'0'		=> 'Hayır'
							);
							$_show_homepage_status = isset($filter_p_show_homepage) ? $filter_p_show_homepage : '';
							echo form_dropdown('filter_show_homepage', $_filter_show_homepage_types, $_show_homepage_status);
						?>
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
				if ($products) {
					foreach ($products as $product) 
					{ ?>
						<tr>
							<td style="text-align: center;">
								<?php if ($product['selected']) { ?>
									<input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
								<?php } else { ?>
									<input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
								<?php } ?>
							</td>
							<td class="center">
								<img src="<?php echo show_image($product['image'], 40, 40); ?>" alt="<?php echo $product['name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" />
							</td>
							<td class="left"><?php echo $product['name']; ?></td>
							<td class="left"><?php echo $product['model']; ?></td>
							<td class="left" style="width:100px;"><?php echo character_limiter($product['seo'], 50); ?></td>
							<td class="right">
								<?php if ($product['quantity'] <= 0) { ?>
								<span style="color: #FF0000;"><?php echo $product['quantity']; ?></span>
								<?php } elseif ($product['quantity'] <= 5) { ?>
								<span style="color: #FFA500;"><?php echo $product['quantity']; ?></span>
								<?php } else { ?>
								<span style="color: #008000;"><?php echo $product['quantity']; ?></span>
								<?php } ?>
							</td>
							<td class="right"><?php echo standard_date('DATE_TR1', $product['date_added'], 'tr'); ?></td>
							<td class="right"><?php echo standard_date('DATE_TR1', $product['date_modified'], 'tr'); ?></td>
							<td class="right" style="width:125px;">
								<?php if($product['show_homepage']) { ?>
									<a href="<?php echo yonetim_url(); ?>/urunler/product/show_homepage/<?php echo $product['product_id']; ?>/hide?redirect=<?php echo current_url(); ?>" title="Anasayfada Göster"><img src="<?php echo yonetim_resim() ?>eye_minus.png" /></a> [ <?php echo $product['show_homepage']; ?> ] 
									<a href="<?php echo yonetim_url(); ?>/urunler/product/show_homepage/<?php echo $product['product_id']; ?>/first?redirect=<?php echo current_url(); ?>" title="İlk Sıraya Taşı"><img src="<?php echo yonetim_resim(); ?>move3.gif" /></a>
									<a href="<?php echo yonetim_url(); ?>/urunler/product/show_homepage/<?php echo $product['product_id']; ?>/previous?redirect=<?php echo current_url(); ?>" title="Önceki Sıraya Taşı"><img src="<?php echo yonetim_resim(); ?>move1.gif" /></a>
									<a href="<?php echo yonetim_url(); ?>/urunler/product/show_homepage/<?php echo $product['product_id']; ?>/next?redirect=<?php echo current_url(); ?>" title="Sonraki Sıraya Taşı"><img src="<?php echo yonetim_resim(); ?>move2.gif" /></a>
									<a href="<?php echo yonetim_url(); ?>/urunler/product/show_homepage/<?php echo $product['product_id']; ?>/last?redirect=<?php echo current_url(); ?>" title="Son Sıraya Taşı"><img src="<?php echo yonetim_resim(); ?>move4.gif" /></a>
								<?php } else { ?>
									<a href="<?php echo yonetim_url(); ?>/urunler/product/show_homepage/<?php echo $product['product_id']; ?>/show?redirect=<?php echo current_url(); ?>" title="Anasayfada Göster"><img src="<?php echo yonetim_resim() ?>eye_plus.png" /></a>
								<?php } ?>
							</td>
							<td class="right">
								<?php echo ($product['status']) ? 'Aktif' : 'Pasif'; ?>
							</td>
							<td class="right">
								<?php foreach ($product['action'] as $action) { ?>
								[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
								<?php } ?>
							</td>
						</tr>
						<?php 
					}
				} else { ?>
				<tr>
					<td class="center" colspan="10"><?php echo $title; ?> Bulunamadı</td>
		  		</tr>
		  		<?php } ?>
			</tbody>
	  	</table>
		</form>
		<?php
			echo $this->pagination->create_links(); 
		?>

		<!-- ui-dialog -->
		<div id="dialog" title="Toplu Ürün İşlemleri" style="display:none;">
			<form name="toplu_duzenleme" id="toplu_duzenleme">
				<table class="form" width="100%">
					<tr>
						<td style="" colspan="2">Aşağıda toplu düzenleme yapacağınız değişiklikler listelenmektedir.</td>
					</tr>
					<tr>
						<td>Satış Fiyatı : <br /><span class="help">Ürünün satış fiyatı.</span></td>
						<td>
							<input type="text" name="price" id="toplu_duzenleme_fiyat" />
							<?php
								$_price_type_array = array('seciniz' => ' - Seçiniz - ', '1' => 'TL', '2' => '$', '3' => '€');
								echo form_dropdown('price_type', $_price_type_array);
							?>
						</td>
					</tr>
					<tr>
						<td>Miktar : <br /><span class="help">Üründen kaç adet olacağını belirler. Bu miktar 0 olarak girilirse ürün sitede "stokta yok" ibareleriyle listelenecektir.</span></td>
						<td>
							<input type="text" name="quantity" value="" class="text" size="5">
							<?php
								echo form_dropdown('stock_type', $stock_types);
							?>
						</td>
					</tr>
					<tr>
						<td>
							Vergi Oranı : <br /><span class="help">Ürünlerin vergi oranı.</span>
						</td>
						<td>
							%
							<?php
								echo form_dropdown('tax', $taxes);
							?>
						</td>
					</tr>
					<tr>
						<td>Özellik Tabını Göster : <br /><span class="help">Ürünlerin özellik tabını gösterin yada göstermeyin.</span></td>
						<td>
							<?php
								$_feature_status_array = array('seciniz' => ' - Seçiniz - ', '0' => ' - Gösterme - ', '1' => ' - Göster - ');
								echo form_dropdown('feature_status', $_feature_status_array);
							?>
						</td>
					</tr>
					<tr>
						<td>Durum : <br /><span class="help">Ürünleri aktif yada pasif edin.</span></td>
						<td>
							<?php
								$_status_array = array('seciniz' => ' - Seçiniz - ', '0' => ' - Kapalı - ', '1' => ' - Açık - ');
								echo form_dropdown('status', $_status_array);
							?>
						</td>
					</tr>
					<tr>
						<td>Stoktan Düş : <br /><span class="help">Ürün satıldıktan sonra ürün miktarı eksilir.</span></td>
						<td>
							<?php
								$_subtract_array = array('seciniz' => ' - Seçiniz - ', '0' => ' - Hayır - ', '1' => ' - Evet - ');
								echo form_dropdown('subtract', $_subtract_array);
							?>
						</td>
					</tr>
					<tr>
						<td>Marka : </td>
						<td>
							<?php
								echo form_dropdown('manufacturer_id', $manufacturers);
							?>
						</td>
					</tr>
					<tr>
						<td>Kategoriler : </td>
						<td>
							<div class="scrollbox" id="scrollbox_0" style="width:350px;height:100px;">
								<?php $class = 'odd'; ?>
								<?php foreach ($categories as $category) { ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div class="<?php echo $class; ?>">
										<input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" />
										<?php echo $category['name']; ?>
									</div>
								<?php } ?>
							</div>
							<span>
								<a onclick="$('input[name*=\'product_category\']').attr('checked', 'checked');"><u>Hepsini Seç</u></a> / <a onclick="$('input[name*=\'product_category\']').removeAttr('checked');"><u>Seçimi Kaldır</u></a>
							</span>
						</td>
					</tr>
				</table>
			</form>
		</div>

	</div>
</div>

<script type="text/javascript"><!--
	jQuery(document).ready(function() {
		jQuery("#dialog").dialog({
			bgiframe: true,
			autoOpen: false,
			height: 500,
			width: 600,
			modal: true,
			buttons: {
				"Kaydet": function() {
					var toplu_duzenle = toplu_duzenle_();
					if(toplu_duzenle.error !== '')
					{
						alert(toplu_duzenle.error);
					}

					if(toplu_duzenle.success !== '')
					{
						alert(toplu_duzenle.success);
						$(this).dialog("close");
					}
				}, 
				"İptal": function() { 
					$(this).dialog("close");
				} 
			}
		});
	});

	function toplu_duzenle_()
	{
		var post = $.fn.ajax_post({
			aksiyon_adresi					: '<?php echo yonetim_url(); ?>/urunler/product/batch_edit',
			aksiyon_tipi					: 'POST',
			aksiyon_data					: $('input[name*=\'selected\']:checked, #toplu_duzenleme').serialize(),
			aksiyon_data_tipi				: 'json',
			aksiyon_data_sonuc_degeri		: false,
			aksiyon_sonucu_bekle			: false,
			aksiyon_yapilirken_islem		: function() {},
			aksiyon_tamamlaninca_islem		: function() {},
			aksiyon_sonucu_islem			: function(data) {}
		});
		return post;
	}

	function toplu_duzenle_func()
	{
		if($('input[name*=\'selected\']:checked').length > 0)
		{
			$('select[name=\'price_type\']').val('seciniz');
			$('input[name=\'price\']').val('');
			$('input[name=\'quantity\']').val('');
			$('select[name=\'stock_type\']').val('seciniz');
			$('select[name=\'tax\']').val('seciniz');
			$('select[name=\'feature_status\']').val('seciniz');
			$('select[name=\'status\']').val('seciniz');
			$('select[name=\'subtract\']').val('seciniz');
			$('select[name=\'manufacturer_id\']').val('seciniz');
			$('input[name*=\'product_category\']').removeAttr('checked');

			$('#dialog').dialog('open');
			return false;
		} else {
			alert('Toplu düzenleme yapmak için lütfen ürün seçin!');
			return false;
		}
	}

	$('.date').datepicker({dateFormat: 'yy-mm-dd'});

	function filter() {
		url = '<?php echo yonetim_url(); ?>/urunler/product/lists/<?php echo $sort_link; ?>/';
		
		var filter_name = $('input[name=\'filter_name\']').attr('value');
		if (filter_name) {
			url += 'pd.name|' + encodeURIComponent(filter_name) + ']';
		}

		var filter_model = $('input[name=\'filter_model\']').attr('value');
		if (filter_model) {
			url += 'p.model|' + encodeURIComponent(filter_model) + ']';
		}

		var filter_seo = $('input[name=\'filter_seo\']').attr('value');
		if (filter_seo) {
			url += 'pd.seo|' + encodeURIComponent(filter_seo) + ']';
		}

		var filter_quantity = $('input[name=\'filter_quantity\']').attr('value');
		if (filter_quantity) {
			url += 'p.quantity|' + encodeURIComponent(filter_quantity) + ']';
		}

		var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
		if (filter_date_added) {
			url += 'p.date_added|' + encodeURIComponent(filter_date_added) + ']';
		}
	
		var filter_date_modified = $('input[name=\'filter_date_modified\']').attr('value');
		if (filter_date_modified) {
			url += 'p.date_modified|' + encodeURIComponent(filter_date_modified) + ']';
		}

		var filter_show_homepage = $('select[name=\'filter_show_homepage\']').attr('value');
		if (filter_show_homepage) {
			url += 'p.show_homepage|' + encodeURIComponent(filter_show_homepage) + ']';
		}
	
		var filter_status = $('select[name=\'filter_status\']').attr('value');
		if (filter_status) {
			url += 'p.status|' + encodeURIComponent(filter_status) + ']';
		}

		url +=  '/0';
		document.location.href = url;
	}

	$('#form').keydown(function(e) {
		if (e.keyCode == 13) {
			filter();
		}
	});

	function show_home_page(product_id, place) {
		$.ajax({
			type: "POST",
			url: site_url('urun/detay/takip_durum'),
			data: 'product_id=' + product_id + '&place=' + place,
			dataType: 'json',
			success: function(data) {
				
			}
		});
	}

//--></script>
<?php $this->load->view('yonetim/footer_view');   ?>