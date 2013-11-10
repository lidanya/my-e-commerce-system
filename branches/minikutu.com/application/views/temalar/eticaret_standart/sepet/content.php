<style type="text/css" media="screen">
	#coupon_box{	
		border: solid 1px #eee;
		width: 320px;
		padding: 10px;
		margin-top: 30px;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		border-radius: 5px;	
	}
	#coupon_box #title{
		margin-bottom: 15px;
	}
</style>
<script type="text/javascript" charset="utf-8">
	$(function() {
		$(this).bind("contextmenu", function(e) {
			e.preventDefault();
		});
		$('#submit_wait').hide();
	});

	function ctrlCEngelle(e)
	{
		olay = document.all ? window.event : e;
		tus = document.all ? olay.keyCode : olay.which;
		if(olay.ctrlKey && (tus==99 || tus==67 || tus==118 || tus==86)) {
			if(document.all) {
				olay.returnValue = false;
			} else {
				olay.preventDefault();
			}
		}
	}

	function SadeceRakam(e, allowedchars)
	{
		var key = e.charCode == undefined ? e.keyCode : e.charCode;
		if ( (/^[0-9]+$/.test(String.fromCharCode(key))) || key==0 || key==13 || isPassKey(key,allowedchars) ) {
			return true;
		} else {
			return false;
		}
	}

	function isPassKey(key,allowedchars)
	{
		if (allowedchars != null) {
			for (var i = 0; i < allowedchars.length; i++) {
				if (allowedchars[i]  == String.fromCharCode(key))			 
					return true;
			}
		}
		return false;
	}

	function SadeceRakamBlur(e,clear)
	{
		var nesne = e.target ? e.target : e.srcElement;
		var val = nesne.value;
		val = val.replace(/^\s+|\s+$/g, "");
		if (clear)val = val.replace(/\s{2,}/g, " ");
		nesne.value = val;
	}

	function sepet_urun_pasif_et(hash_id)
	{
		$.ajax({
			type: "POST",
			url: site_url('sepet/ajax/urun_pasif'),
			data: "hash_id="+ hash_id,
			dataType: 'json',
			beforeSend: function()
			{
				$('#sepet_urun_durum_' + hash_id).html('<img src="' + resim_url + 'loader.gif" style="margin: 7px 0 0 0;" alt="İşleminiz Yapılıyor.." title="İşleminiz Yapılıyor.." />');
			},
			success: function(data)
			{
				if (data.basarisiz)
				{
					sepet_toplam_fiyat_getir();
					sepet_kdv_fiyat_getir();
					sepet_kdv_toplam_fiyat_getir();
					sepet_indirim_toplam_fiyat_getir();
					$('#sepet_urun_durum_' + hash_id).html('<a onclick="sepet_urun_pasif_et(\''+ hash_id +'\');" title="Bu Ürünü Sepetten Kaldır"><img style="margin: 7px 0 0 0;" src="' + resim_url + 'sepet_sil.png" alt="Sil" /></a>');
					sepet_ilerleme_kontrol(hash_id);
				}
				if (data.basarili)
				{
					sepet_toplam_fiyat_getir();
					sepet_kdv_fiyat_getir();
					sepet_kdv_toplam_fiyat_getir();
					sepet_indirim_toplam_fiyat_getir();
					$('#sepet_urun_durum_' + hash_id).html('<a onclick="sepet_urun_aktif_et(\''+ hash_id +'\');" title="Bu Ürünü Geri Al"><img style="margin: 7px 0 0 0;" src="' + resim_url + 'sepet_yenile.png" alt="Geri Getir" /></a>');
					$('#qty_' + hash_id).attr('disabled','disabled');
					$('#sepet_oge_' + hash_id).addClass('sepet_iptal');
					sepet_ilerleme_kontrol(hash_id);
				}
			}
		});
	}

	function sepet_urun_aktif_et(hash_id)
	{
		$.ajax({
			type: "POST",
			url: site_url('sepet/ajax/urun_aktif'),
			data: "hash_id="+ hash_id,
			dataType: 'json',
			beforeSend: function()
			{
				$('#sepet_urun_durum_' + hash_id).html('<img src="' + resim_url + 'loader.gif" style="margin: 7px 0 0 0;" alt="İşleminiz Yapılıyor.." title="İşleminiz Yapılıyor.." />');
			},
			success: function(data)
			{
				if (data.basarisiz)
				{
					sepet_toplam_fiyat_getir();
					sepet_kdv_fiyat_getir();
					sepet_ilerleme_kontrol(hash_id);
					$('#sepet_urun_durum_' + hash_id).html('<a onclick="sepet_urun_aktif_et(\''+ hash_id +'\');" title="Bu Ürünü Geri Al"><img style="margin: 7px 0 0 0;" src="' + resim_url + 'sepet_yenile.png" alt="Geri Getir" /></a>');
					sepet_ilerleme_kontrol(hash_id);
				}
				if (data.basarili)
				{
					sepet_toplam_fiyat_getir();
					sepet_kdv_fiyat_getir();
					sepet_kdv_toplam_fiyat_getir();
					sepet_indirim_toplam_fiyat_getir();
					$('#sepet_urun_durum_' + hash_id).html('<a onclick="sepet_urun_pasif_et(\''+ hash_id +'\');" title="Bu Ürünü Sepetten Kaldır"><img style="margin: 7px 0 0 0;" src="' + resim_url + 'sepet_sil.png" alt="Sil" /></a>');
					$('#qty_' + hash_id).attr('disabled','');
					$('#sepet_oge_' + hash_id).removeClass('sepet_iptal');
					sepet_ilerleme_kontrol(hash_id);
				}
			}
		});
	}

	function sepet_urun_deger_gir(hash_id)
	{
		var input = $('#qty_' + hash_id).val();
		var tip = $('#tip_' + hash_id).val();
		if(input == '0' || input == '')
		{
			var input = '1';
			$('#qty_' + hash_id).val('1');
		} else {
			var input = $('#qty_' + hash_id).val();
		}
		$.ajax({
			type: "POST",
			url: site_url('sepet/ajax/urun_deger_gir'),
			data: "qty="+ input
			+"&tip="+ tip
			+"&hash_id="+ hash_id,
			dataType: 'json',
			success: function(data)
			{
				if (data.basarisiz)
				{
					sepet_urun_fiyat_getir(hash_id, input);
					sepet_toplam_fiyat_getir();
					sepet_kdv_fiyat_getir();
					sepet_kdv_toplam_fiyat_getir();
					sepet_indirim_toplam_fiyat_getir();
					sepet_urun_toplam_fiyat(hash_id);
					sepet_ilerleme_kontrol(hash_id);
				}
				if (data.basarili)
				{
					sepet_urun_fiyat_getir(hash_id, input);
					sepet_toplam_fiyat_getir();
					sepet_kdv_fiyat_getir();
					sepet_kdv_toplam_fiyat_getir();
					sepet_indirim_toplam_fiyat_getir();
					sepet_urun_toplam_fiyat(hash_id);
					sepet_ilerleme_kontrol(hash_id);
				}
			}
		});
	}

	function sepet_urun_fiyat_getir (hash_id, qty) {
		$.ajax({
			type: "POST",
			url: site_url('sepet/ajax/urun_deger_kontrol'),
			data: "hash_id="+ hash_id + "&qty=" + qty,
			dataType: 'json',
			success: function(data)
			{
				if (data.basarili.qty) {
					$('#qty_' + hash_id).attr('value', data.basarili.qty);
					if (data.basarili.message != '') {
						var message = '<?php echo lang('messages_cart_no_quantity'); ?>';
						var new_message = message.replace("{_qty_}", data.basarili.message);
						alert(new_message);	
					}
				}
			}
		});
	}

	function sepet_ilerleme_kontrol(hash_id)
	{
		$.ajax({
			type: "POST",
			url: site_url('sepet/ajax/urun_ilerleme_kontrol'),
			data: "hash_id="+ hash_id,
			dataType: 'json',
			success: function(data)
			{
				if (data.basarili)
				{
					$('#sepet_fiyat').show();
					$('#sepet_butonlar').show();
					$('#sepet_bosalt').show();
				}
				if (data.basarisiz)
				{
					$('#sepet_fiyat').hide();
					$('#sepet_butonlar').hide();
					$('#sepet_bosalt').hide();
				}
			}
		});
	}

	function sepet_urun_toplam_fiyat(hash_id)
	{
		$.ajax({
			type: "POST",
			url: site_url('sepet/ajax/urun_toplam_fiyat'),
			data: "hash_id=" + hash_id,
			dataType: 'json',
			beforeSend: function()
			{
				$('#sepet_urun_toplam_fiyat_' + hash_id).html('<img src="' + resim_url + 'loader.gif" style="margin: 3px 0 0 0;" alt="İşleminiz Yapılıyor.." title="İşleminiz Yapılıyor.." />');
			},
			success: function(data)
			{
				var onceki_fiyat = $('#sepet_urun_toplam_fiyat_' + hash_id).html();
				if (data.basarili)
				{
					$('#sepet_urun_toplam_fiyat_' + hash_id).html(data.basarili);
				} else {
					$('#sepet_urun_toplam_fiyat_' + hash_id).html(onceki_fiyat);
				}
			}
		});
	}

	function sepet_toplam_fiyat_getir()
	{
		$.ajax({
			type: "POST",
			url: site_url('sepet/ajax/toplam_fiyat'),
			data: "hash_id=deneme",
			dataType: 'json',
			beforeSend: function()
			{
				$('#sepet_toplam_fiyat').html('<img src="' + resim_url + 'loader.gif" style="margin: 3px 0 0 0;" alt="İşleminiz Yapılıyor.." title="İşleminiz Yapılıyor.." />');
				$('#sepet_toplam_fiyat2').html('<img src="' + resim_url + 'loader.gif" style="margin: 3px 0 0 0;" alt="İşleminiz Yapılıyor.." title="İşleminiz Yapılıyor.." />');
			},
			success: function(data)
			{
				var onceki_fiyat = $('#sepet_toplam_fiyat').html();
				if (data.basarili)
				{
					$('#sepet_toplam_fiyat').html(data.basarili + ' TL');
					$('#sepet_toplam_fiyat2').html(data.basarili + ' TL');
				} else {
					$('#sepet_toplam_fiyat').html(onceki_fiyat);
					$('#sepet_toplam_fiyat2').html(onceki_fiyat);
				}
			}
		});
	}

	function sepet_kdv_fiyat_getir()
	{
		$.ajax({
			type: "POST",
			url: site_url('sepet/ajax/kdv_fiyat'),
			data: "hash_id=deneme",
			dataType: 'json',
			beforeSend: function()
			{
				$('#sepet_kdv_fiyat').html('<img src="' + resim_url + 'loader.gif" style="margin: 3px 0 0 0;" alt="İşleminiz Yapılıyor.." title="İşleminiz Yapılıyor.." />');
			},
			success: function(data)
			{
				var onceki_fiyat = $('#sepet_kdv_fiyat').html();
				if (data.basarili)
				{
					$('#sepet_kdv_fiyat').html(data.basarili + ' TL');
				} else {
					$('#sepet_kdv_fiyat').html(onceki_fiyat);
				}
			}
		});
	}

	function sepet_kdv_toplam_fiyat_getir()
	{
		$.ajax({
			type: "POST",
			url: site_url('sepet/ajax/kdv_toplam_fiyat'),
			data: "hash_id=deneme",
			dataType: 'json',
			beforeSend: function()
			{
				$('#sepet_kdv_toplam_fiyat').html('<img src="' + resim_url + 'loader.gif" style="margin: 3px 0 0 0;" alt="İşleminiz Yapılıyor.." title="İşleminiz Yapılıyor.." />');
			},
			success: function(data)
			{
				var onceki_fiyat = $('#sepet_kdv_toplam_fiyat').html();
				if (data.basarili)
				{
					$('#sepet_kdv_toplam_fiyat').html(data.basarili + ' TL');
				} else {
					$('#sepet_kdv_toplam_fiyat').html(onceki_fiyat);
				}
			}
		});
	}

	function sepet_indirim_toplam_fiyat_getir() {
		$.ajax({
			type: "POST",
			url: site_url('sepet/ajax/indirim_toplam_fiyat'),
			data: "hash_id=deneme",
			dataType: 'json',
			beforeSend: function()
			{
				$('#sepet_indirim_toplam_fiyat').html('<img src="' + resim_url + 'loader.gif" style="margin: 3px 0 0 0;" alt="İşleminiz Yapılıyor.." title="İşleminiz Yapılıyor.." />');
			},
			success: function(data)
			{
				var onceki_fiyat = $('#sepet_indirim_toplam_fiyat').html();
				if (data.basarili)
				{
					$('#sepet_indirim_toplam_fiyat').html(data.basarili + ' TL');
				} else {
					$('#sepet_indirim_toplam_fiyat').html(onceki_fiyat);
				}
			}
		});
	}

	// kupon kodu ajax işlemini yürütür.
	var before;
	var after;
	function apply_coupon_code () {
		
		$('#coupon_ajax_response').hide();
		before = $('span#button').html();
		after = '<img src="<?php echo site_resim() ?>loader.gif" alt="" />';
		var code = $('input[name="coupon_code"]').val();
		$('span#button').html(after);
		$.post(
			site_url('sepet/ajax/apply_coupon_code'),
			{'coupon_code': code},
			function(response){
				$('span#button').html(before);
				if (response.error === true) {
					$('#coupon_ajax_response').html('<div style="color: red">' + response.msg + '</div>').show();
				} else {
					//$('#coupon_ajax_response').html('kod başarılı').show();
					kupon_ekle();
					console.log(response.coupon);
				}
			},
			'json'
		);
	}

	function kupon_ekle()
	{
		var kupon_kodu = $('input[name="coupon_code"]').val();
		$.ajax({
			type: "POST",
			url:  "<?php echo ssl_url('sepet/ajax/kupon_kontrol'); ?>",
			data: 'code='+kupon_kodu,
			dataType: 'json',
			beforeSend: function() {},
			complete: function() {},
			success: function(data) {
				$('#coupon_active').show();
				$('#coupon_inactive').hide();
				sepet_toplam_fiyat_getir();
				sepet_kdv_fiyat_getir();
				sepet_kdv_toplam_fiyat_getir();
				sepet_indirim_toplam_fiyat_getir();
				$('input[name="coupon_code"]').val('');
				$('#coupon_active_message').html(data.aciklama);
				$('#sepet_indirim').show();
			}
		});
	}

	function kuponu_sil()
	{
		$.ajax({
			type: "POST",
			url:  "<?php echo ssl_url('sepet/ajax/kupon_iptal'); ?>",
			data: 'code=',
			dataType: 'json',
			beforeSend: function() {},
			complete: function() {},
			success: function() {
				$('#coupon_active').hide();
				$('#coupon_inactive').show();
				sepet_toplam_fiyat_getir();
				sepet_kdv_fiyat_getir();
				sepet_kdv_toplam_fiyat_getir();
				sepet_indirim_toplam_fiyat_getir();
				$('#sepet_indirim').hide();
			}
		});
	}

	function sepet_adet_guncelle (hash_id) {
		setTimeout("sepet_urun_deger_gir('" + hash_id + "');", 1000);
	}

	function next_step () {
		var obj = $('.hash_ids');
		var arr = $.makeArray(obj);
		$.each(arr, function(index, value) { 
			sepet_urun_deger_gir(value.id);
		});
		$('#submit_wait').html('<img src="<?php echo site_resim() ?>loader.gif" alt="" />&nbsp;<?php echo lang('messages_please_wait'); ?>');
		$('#submit_wait').show();
		setTimeout("redirect('<?php echo ssl_url('odeme/adim_1'); ?>');", 5000);
	}
</script>

<div id="orta" class="sola">

	<!-- Sepet BAŞ -->
		<h1 id="sayfa_baslik"><?php echo lang('messages_cart_title'); ?></h1>
		<div id="sepet">
			<p style="text-align:right;">
			<?php if($this->cart->total_items() > 0) { ?>
				<?php if($this->cart->total()) { ?>
				<span id="sepet_bosalt">
					<a href="<?php echo ssl_url('sepet/bosalt/tumu'); ?>" class="butonum">
						<span class="butor"><image src="<?php echo site_resim(); ?>sepet_mini.png" alt="" /> <?php echo lang('messages_cart_empty_cart_button'); ?></span>
					</a>
				</span>
				<?php } else { ?>
				<span id="sepet_bosalt" style="display:none;">
					<a href="<?php echo ssl_url('sepet/bosalt/tumu'); ?>" class="butonum">
						<span class="butor"><image src="<?php echo site_resim(); ?>sepet_mini.png" alt="" /> <?php echo lang('messages_cart_empty_cart_button'); ?></span>
					</a>
				</span>
				<?php } ?>
			<div class="sepet_oge sepet_tablo_baslik">
				<span class="s_tablo01 sola" style="width:370px;text-align:left;"><?php echo lang('messages_cart_product_name'); ?></span>
				<span class="s_tablo02 sola" style="width:80px;"><?php echo lang('messages_cart_product_quantity'); ?></span>
				<span class="s_tablo03 sola"><?php echo lang('messages_cart_product_unit_price'); ?></span>
				<span class="s_tablo04 sola"><?php echo lang('messages_cart_product_total_price'); ?></span>
				<span class="s_tablo05 sola">&nbsp;</span>
				<div class="clear"></div>
			</div>
			<?php } else { ?>
			<!-- Hata -->
			 <div id="onay_mesaj">
			 	<div class="onay_image sola"><img src="<?php echo site_resim(); ?>unlem.png" alt="Hata" title="Hata Başlığı"></div>
			 	<div class="onay_aciklama sola" style="padding-top:40px;"><b><?php echo lang('messages_cart_empty'); ?></b></div>
			 	<div class="clear"></div>
			 	<p class="onay_buton">
			 		<a href="javascript:history.back(1);" class="butonum">
			 			<span class="butor"><img src="<?php echo site_resim(); ?>btn_img_geri.png" alt="Geri Dön"  /> <?php echo lang('messages_button_back'); ?></span>
		 			</a>
					<a href="<?php echo site_url(); ?>" class="butonum" style="margin-left:10px;" href="<?php echo site_url(); ?>">
						<span class="butor"><img src="<?php echo site_resim(); ?>btn_img_anasayfa.png" alt="Anasayfaya Dön" /> <?php echo lang('messages_button_back_home'); ?></span>
					</a>
			 	</p>
			 </div>
			 <!-- Hata SON -->
			<?php }?>
			</p>

			<?php if($this->cart->total_items() > 0) { ?>
			<?php $i = 1; ?>
			<?php
				foreach($this->cart->contents() as $items) {
				if (count($items) > 10) {
				$i++;
				$z = $i%2;
				if ($z==0) {
					$div_oge_class = NULL;
				} else {
					$div_oge_class = " sepet_gri";
				}
			?>
            <?php if(config('site_ayar_sepet_resim_goster')=='1'): //$resim = show_image($items['basket_image'], $w = 40, $h = 40); // sepette resim gösterilsin mi gösterilmesin mi? ?>
            <div id="spt_image" style="display:inline; float:left;"><img src="<?php echo $items['basket_image']; ?>" width="40" height="40"  /></div>
            <?php endif; ?>
			<div class="sepet_oge<?php echo $div_oge_class; ?><?php echo ($items['durum'] == '0') ? ' sepet_iptal':NULL; ?>" id="sepet_oge_<?php echo $items['rowid']; ?>">
				<div class="hash_ids" id="<?php echo $items['rowid']; ?>" style="display:none;"></div>
				<span class="s_tablo01 sola" style="width:370px;height:auto;margin-bottom:5px;">
					<font title="<?php echo $items['name']; ?>"><?php echo character_limiter($items['name'], 50); ?></font>
					<?php if ($this->cart->has_options($items['rowid']) == TRUE) { ?>
						<br /><span>
						<?php foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value) { ?>
							<?php echo character_limiter($option_value, 25); ?><br />
						<?php } ?>
						</span>
					<?php } ?>
					<?php if ($this->cart->has_secenek($items['rowid']) == TRUE) { ?>
						<br /><span>
						<?php foreach ($this->cart->stok_secenek($items['rowid']) as $option_name => $option_value) { ?>
							&nbsp;&nbsp;&nbsp;&nbsp;<?php echo character_limiter($option_value['name'], 25); ?> : <?php
							if(isset($option_value['price_prefix'])) {
									if($option_value['price'] > 0) {
										$_onek = ' (' . $option_value['price_prefix'] . format_number($option_value['price']) . ') TL';
									} else {
										$_onek = null;
									}
								echo $option_value['option_value'] . $_onek;
							}
							?><br />
						<?php } ?>
						</span>
					<?php } ?>
				</span>
				<span class="s_tablo02 sola" style="width:80px;">
					<input<?php echo ($items['durum'] == '0') ? ' disabled="disabled"':NULL; ?> class="sadece_rakam" type="text" onkeypress="return SadeceRakam(event);" onblur="SadeceRakam(event,false);" onkeydown="ctrlCEngelle(event);" onkeyup="sepet_adet_guncelle('<?php echo $items['rowid']; ?>');" name="qty_<?php echo $items['rowid']; ?>" id="qty_<?php echo $items['rowid']; ?>" value="<?php echo $items['qty']; ?>" size="3" maxlength="3">&nbsp;<a href="javascript:;" onclick="sepet_urun_deger_gir('<?php echo $items['rowid']; ?>');" title="<?php echo lang('messages_cart_update'); ?>"><img src="<?php echo site_resim(TRUE); ?>sync.png" height="13" alt="<?php echo lang('messages_cart_update'); ?>" /></a>&nbsp;<?php
						$tanim_bilgi = $this->yonetim_model->tanimlar_bilgi('stok_birim', $items['tip']);
						if($tanim_bilgi->num_rows() > 0)
						{
							$tanim_bilgi_b = $tanim_bilgi->row();
							echo '<font style="cursor:pointer;" title="'. $tanim_bilgi_b->tanimlar_adi .'">' . $tanim_bilgi_b->tanimlar_kod . '</font>';
						} else {
							echo '<font style="cursor:pointer;" title="Ürün Birimi Bulunamadı">bln</font>';
						}
					?>
				</span>
				<span class="s_tablo03 sola"><?php echo $this->cart->format_number($items['price']); ?> <b class="s_birim">TL</b></span>
				<span class="s_tablo04 sola">
					<b class="siterenk" id="sepet_urun_toplam_fiyat_<?php echo $items['rowid']; ?>">
						<?php echo $this->cart->format_number($items['subtotal']); ?>
					</b>
					<b class="s_birim">TL</b>
				</span>
				<input type="hidden" name="tip_<?php echo $items['rowid']; ?>" id="tip_<?php echo $items['rowid']; ?>" value="<?php echo $items['tip']; ?>">
				<span class="s_tablo_05 sola" id="sepet_urun_durum_<?php echo $items['rowid']; ?>">
					<?php if($items['durum'] == '1') { ?>
					<a onclick="sepet_urun_pasif_et('<?php echo $items['rowid']; ?>');" title="Bu Ürünü Sepetten Kaldır">
						<img style="margin: 7px 0 0 0;" src="<?php echo site_resim(); ?>sepet_sil.png" alt="Bu Ürünü Sepetten Kaldır" />
					</a>
					<?php } else if($items['durum'] == '0') { ?>
					<a onclick="sepet_urun_aktif_et('<?php echo $items['rowid']; ?>');" title="Bu Ürünü Geri Al">
						<img style="margin: 7px 0 0 0;" src="<?php echo site_resim(); ?>sepet_yenile.png" alt="Bu Ürünü Geri Al" />
					</a>
					<?php } ?>
				</span>
			</div>
			<?php } ?>
			<?php } ?>
			<?php } ?>

			<div class="sepet_alt">
			<?php if($this->cart->total_items() > 0) { ?>
				<?php if($this->cart->total()) { ?>
				<div id="sepet_butonlar" class="sola" style="width:400px;">
					<a href="<?php echo site_url(); ?>" class="butonum">
						<span class="butor"><?php echo lang('messages_cart_continue_shopping'); ?></span>
					</a>
					<a style="margin-left:5px;" href="javascript:;" rel="nofollow" onclick="next_step();" class="butonum">
						<span class="butor"><?php echo lang('messages_cart_checkout'); ?></span>
					</a>
					<span id="submit_wait" style="display:none;"></span>
					<?php if (config('site_ayar_coupon_status')) { ?>
					<div class="clear"></div>
					
					<!-- Kupon Kodu -->					
					<div id="coupon_box">
						
						<div id="title">
							<h3>&raquo; <?php echo lang('messages_cart_coupon_code'); ?></h3>
							<span><?php echo lang('messages_cart_coupon_help'); ?></span>
						</div>

						<div id="coupon_content">
							<?php $inactive = ($this->cart->toplam_indirim() == '' AND $this->cart->toplam_indirim() == 0) ? ' style="display:block;margin-top:5px;"' : ' style="display:none;margin-top:5px;"'; ?>
							<div id="coupon_inactive"<?php echo $inactive; ?>>
								<input type="text" name="coupon_code" size="32" style="" />
								<div class="clear"></div>
								<span id="button" style="margin-top:10px;display:inline-block;">
									<a href="javascript:;" onclick="return apply_coupon_code()" class="butonum">
										<span class="butor"><?php echo lang('messages_cart_apply_code') ?></span>
									</a>
								</span>
							</div>

							<?php $active = ($this->cart->toplam_indirim() != '') ? ' style="display:block;margin-top:5px;"' : ' style="display:none;margin-top:5px;"'; ?>
							<div id="coupon_active"<?php echo $active; ?>>
								<div id="coupon_active_message" style="color:green;font-weight:bold;"><?php echo $this->cart->kupon_mesaj(); ?></div>
								<div class="clear"></div>
								<div id="name" style="margin-top:15px;">
									<a href="javascript:;" onclick="return kuponu_sil()" class="butonum">
										<span class="butor"><?php echo lang('messages_cart_delete_code') ?></span>
									</a>
								</div>
							</div>
						</div>

						<!-- ajax sonucu bu divin içine basılacak -->
						<div id="coupon_ajax_response" style="display:none;margin-top:5px;"></div>
						
					</div>					
					<!-- // Kupon Kodu -->
					
					<?php } ?>		
					
				</div>
				
				<?php } else { ?>
				<div id="sepet_butonlar" class="sola" style="display:none;width:400px;">
					<a href="<?php echo site_url(); ?>" class="butonum">
						<span class="butor"><?php echo lang('messages_cart_continue_shopping'); ?></span>
					</a>
					<a style="margin-left:5px;" href="javascript:;" rel="nofollow" onclick="next_step();" class="butonum">
						<span class="butor"><?php echo lang('messages_cart_checkout'); ?></span>
					</a>
					<span id="submit_wait" style="display:none;"></span>
				</div>
				<?php } ?>
			<?php } ?>
			<?php if($this->cart->total_items() > 0) { ?>
				<?php
					$kdv_orani = (floatval('0.' . $items['kdv_orani'])) ? floatval('0.' . $items['kdv_orani']):'00';
					$kdv_dahil_fiyat = floatval('1.' . $items['kdv_orani']);
				?>
				<?php if($this->cart->total()) { ?>
				<?php
				if(config('site_ayar_kdv_goster') == '1')
				{
				?>
				<div id="sepet_fiyat" class="saga" style="width:300px;">
					<span class="s_fiyat saga" style="width:115px;text-align:right;padding-right:10px;">
						<b class="siterenk" id="sepet_toplam_fiyat"><?php echo $this->cart->format_number($this->cart->total()); ?> TL</b>
					</span>
					<span class="s_fiyat_baslik saga"><?php echo lang('messages_cart_sub_total'); ?> :</span>
					<div class="clear"></div>
					<span class="s_fiyat saga" style="width:115px;text-align:right;padding-right:10px;">
						<b class="siterenk" id="sepet_kdv_fiyat"><?php echo $this->cart->format_number($this->cart->toplam_kdv()); ?> TL</b>
					</span>
					<span class="s_fiyat_baslik saga"><?php echo lang('messages_cart_vat_total'); ?> :</span>
					<div class="clear"></div>
					<div id="sepet_indirim" style="display:<?php echo ($this->cart->toplam_indirim()) ? 'block;' : 'none;'; ?>">
						<span class="s_fiyat saga" style="width:115px;text-align:right;padding-right:10px;">
							<b class="siterenk" id="sepet_indirim_toplam_fiyat"><?php echo $this->cart->format_number($this->cart->toplam_indirim()); ?> TL</b>
						</span>
						<span class="s_fiyat_baslik saga"><?php echo lang('messages_cart_discount_total'); ?> : </span>
					</div>
					<div class="clear"></div>
					<span class="s_fiyat saga" style="width:115px;text-align:right;padding-right:10px;">
						<b class="siterenk" style="font-size:16px" id="sepet_kdv_toplam_fiyat">
						<?php
							$genel_toplam = $this->cart->toplam_kdv() + $this->cart->total() - $this->cart->toplam_indirim();
							$toplam = ($genel_toplam > 0) ? $genel_toplam : 0.01;
						?>
						<?php echo $this->cart->format_number($toplam); ?> TL
						</b>
					</span>
					<span class="s_fiyat_baslik saga" style="padding-top:2px;"><?php echo lang('messages_cart_total'); ?> :</span>
					<div class="clear"></div>
				</div>
				<?php } else { ?>
				<div id="sepet_fiyat" class="saga" style="width:300px;">
					<span class="s_fiyat saga" style="width:115px;text-align:right;padding-right:10px;"><b class="s_turuncu" id="sepet_toplam_fiyat"><?php echo $this->cart->format_number($this->cart->total()); ?> TL</b></span>
					<span class="s_fiyat_baslik saga"><?php echo lang('messages_cart_sub_total'); ?> :</span>
					<div class="clear"></div>
					<div id="sepet_indirim" style="display:<?php echo ($this->cart->toplam_indirim()) ? 'block;' : 'none;'; ?>">
						<span class="s_fiyat saga" style="width:115px;text-align:right;padding-right:10px;"><b class="s_yesil" id="sepet_indirim_toplam_fiyat"><?php echo $this->cart->format_number($this->cart->toplam_indirim()); ?> TL</b></span>
						<span class="s_fiyat_baslik saga"><?php echo lang('messages_cart_discount_total'); ?> : </span>
					</div>
					<div class="clear"></div>
					<span class="s_fiyat saga" style="width:115px;text-align:right;padding-right:10px;"><b class="s_yesil" id="sepet_toplam_fiyat2">
						<?php
							$genel_toplam = $this->cart->total() - $this->cart->toplam_indirim();
							$toplam = ($genel_toplam > 0) ? $genel_toplam : 0.01;
						?>
						<?php echo $this->cart->format_number($toplam); ?> TL
					</b></span>
					<span class="s_fiyat_baslik saga"><?php echo lang('messages_cart_total'); ?> :</span>
					<div class="clear"></div>
				</div>
				<?php } ?>
				<?php } else { ?>
				<?php
				if(config('site_ayar_kdv_goster') == '1')
				{
				?>
				<div id="sepet_fiyat" class="saga" style="display:none;width:300px;">
					<span class="s_fiyat saga" style="width:115px;text-align:right;padding-right:10px;"><b class="s_turuncu" id="sepet_toplam_fiyat"><?php echo $this->cart->format_number($this->cart->total()); ?> TL</b></span>
					<span class="s_fiyat_baslik saga"><?php echo lang('messages_cart_sub_total'); ?> :</span>
					<div class="clear"></div>
					<span class="s_fiyat saga" style="width:115px;text-align:right;padding-right:10px;"><b class="s_sari" id="sepet_kdv_fiyat"><?php echo $this->cart->format_number($this->cart->toplam_kdv()); ?> TL</b></span>
					<span class="s_fiyat_baslik saga"><?php echo lang('messages_cart_vat_total'); ?> :</span>
					<div class="clear"></div>
					<div id="sepet_indirim" style="display:<?php echo ($this->cart->toplam_indirim()) ? 'block;' : 'none;'; ?>">
						<span class="s_fiyat saga" style="width:115px;text-align:right;padding-right:10px;"><b class="s_yesil" id="sepet_indirim_toplam_fiyat"><?php echo $this->cart->format_number($this->cart->toplam_indirim()); ?> TL</b></span>
						<span class="s_fiyat_baslik saga"><?php echo lang('messages_cart_discount_total'); ?> : </span>
					</div>
					<div class="clear"></div>
					<span class="s_fiyat saga" style="width:115px;text-align:right;padding-right:10px;"><b class="s_yesil" style="font-size:16px" id="sepet_kdv_toplam_fiyat"><?php echo $this->cart->format_number($this->cart->toplam_kdv() + $this->cart->total()); ?> TL</b></span>
					<span class="s_fiyat_baslik saga" style="padding-top:2px;"><?php echo lang('messages_cart_total'); ?> :</span>
					<div class="clear"></div>
				</div>
				<?php } else { ?>
				<div id="sepet_fiyat" class="saga" style="display:none;width:300px;">
					<span class="s_fiyat saga" style="width:115px;text-align:right;padding-right:10px;"><b class="s_yesil" id="sepet_toplam_fiyat"><?php echo $this->cart->format_number($this->cart->total() - $this->cart->toplam_indirim()); ?> TL</b></span>
					<span class="s_fiyat_baslik saga"><?php echo lang('messages_cart_total'); ?> :</span>
					<div class="clear"></div>
				</div>
				<?php } ?>
				<?php } ?>
				<div class="clear"></div>
			<?php } ?>
			</div>
		</div>
	<!-- Sepet SON -->
	</div>
<div class="clear"></div>
</div>