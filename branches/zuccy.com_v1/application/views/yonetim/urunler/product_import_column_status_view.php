<?php $this->load->view('yonetim/header_view'); ?>
<script type="text/javascript" charset="utf-8">
	var timer;
	$(document).ready(function() {
		$("#progressbar").progressbar({value: 0});
 		write_data();
		timer = setInterval("sure_arttir()", 1000);
	});
	var satir = 0;
	var toplamsatir = <?php echo count($column); ?>;
	function write_data() {
		$.ajax({
			type: "POST",
			url: "<?php echo yonetim_url('urunler/product_import/convert_csv_to_mysql'); ?>",
			data: jQuery('#form_sutun_' + satir).serialize(),
			dataType: 'json',
			success: function(data) {
				yuzde = Math.floor(((satir + 1) * 100) / (toplamsatir));
				$("#progressbar").progressbar({value: yuzde});
				$('#pb1').html('%' + yuzde);
				if(satir < toplamsatir - 1) {
					satir++;
					write_data();
				}

				if(data.error_msg != '') {
					$('#mesaj_' + satir).html('<span style="color:red;display:block;font-size:12px;font-weight:normal;padding-top:3px;">' + data.error_msg + '</span>');
					$('#mesaj_' + satir).show();
				}

				if(data.success_msg != '') {
					$('#mesaj_' + satir).html('<span style="color:green;display:block;font-size:12px;font-weight:normal;padding-top:3px;">' + data.success_msg + '</span>');
					$('#mesaj_' + satir).show();
				}

				$('#scrollbox').scrollTo($('#mesaj_' + satir));

				$('#form_sutun_' + satir).remove();

				if(yuzde == '100') {
					$('#mesaj').show();
					clearInterval(timer);
				}
			}
		});
	}
	var saniye = 0, dakika = 0, saat = 0;
	function sure_arttir()
	{
		if(saniye < 59) {
			saniye = saniye + 1;
		} else {
			saniye = 0; 
			if(dakika < 59) {
				dakika = dakika + 1;
			} else {
				dakika = 0;
				saat = saat + 1;
			}
		}
		$("#gecen_sure").html(saat + ":" + dakika + ":" + saniye);
	}
</script>
<div class="box">
	<div id="mesaj" class="success" style="margin-top: 10px;display:none;">
		CSV içeri aktarma işleminiz başarılı bir şekilde gerçekleşmiştir, ürünlerinizi düzenlemek için <a href="<?php echo yonetim_url('urunler/product/lists'); ?>">tıklayın</a>.
	</div>
	<div style="clear:both;"></div>
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('<?php echo yonetim_resim();?>category.png');">Ürün İçeri Aktar</h1>
		<div class="buttons">
			<a onclick="location = '<?php echo yonetim_url('urunler/product_import'); ?>';" class="buton" style="margin-left:10px;"><span>İptal</span></a>
		</div>
	</div>
	<div class="content">
		<table class="form">
			<tr>
				<td>
					Veri İçeri Aktarma İşlemi
					<span class="help">Lütfen işleminiz tamamlanana kadar tarayıcınızı kapatmayın!</span>
				</td>
				<td>
					Geçen Süre : <span id="gecen_sure"></span><br />
					Dosya içeriği sisteme aktarılıyor.<br />
					<div style="width:725px;text-align:center;margin-bottom:5px;">Toplam satır sayısı : <?php echo count($column); ?>, Toplam ilerleme : <span id="pb1">0%</span></div>
					<div id="progressbar" style="width:725px;margin-bottom:5px;"></div>
					<div class="scrollbox" id="scrollbox" style="width:725px;height:200px;">
						<?php
							foreach($column as $key => $value)
							{
						?>
							<span id="mesaj_<?php echo $key; ?>" style="display:none;"></span>
							<?php
								echo '<form name="form_sutun_' . $key . '" id="form_sutun_' . $key . '" />';
								echo '<input type="hidden" name ="line" value="' . $key . '" />';
								foreach($value as $value_key => $value_value) {
									echo '<input type="hidden" name ="column['. $value_key .']" value="'. $value_value .'" />';
								}
								echo '</form>';
							?>
						<?php
							}
						?>
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>
<?php $this->load->view('yonetim/footer_view');  ?>