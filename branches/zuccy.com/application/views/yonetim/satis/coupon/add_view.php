<?php $this->load->view('yonetim/header_view'); ?>

<!-- Validation Errors -->
<?php if(validation_errors()): ?>
<div class="warning"><?php echo validation_errors() ?></div>
<?php endif ?>
<!-- // Validation Errors -->

<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('<?php echo yonetim_resim(); ?>customer.png');">Yeni Kupon Kodu Oluştur</h1>
		<div class="buttons">
			<a onclick="javascript:$('form#form').submit()" class="buton"><span>Kaydet</span></a>
			<a onclick="$('form').attr('action', '<?php echo yonetim_url('satis/coupon/delete'); ?>'); $('form').submit();" class="buton" style="margin-left:10px;"><span>Sil</span></a>
		</div>
	</div>
	
	<div class="content">

    <form action="<?php echo yonetim_url('satis/coupon/add') ?>" method="post" id="form">

    	<table class="form">
    		<tr>
    			<td>Başlangıç Tarihi<span class="help">Gün / Ay / Yıl</span></td>
    			<td>
    				<input type="text" name="date_start" value="<?php echo set_value('date_start') ?>" id="date_start" />
    			</td>
    		</tr>

    		<tr>
    			<td>Bitiş Tarihi<span class="help">Gün / Ay / Yıl</span></td>
    			<td>
    				<input type="text" name="date_end" value="<?php echo set_value('date_end') ?>" id="date_end" />
    			</td>
    		</tr>

    		<tr>
    			<td>İndirim</td>
    			<td>
    				<input type="text" name="value" value="<?php echo set_value('value') ?>" />
    				<?php echo form_dropdown('type', $this->type, set_value('type')) ?>
				</td>
    		</tr>

    		<tr>
    			<td>Kod Oluşturma</td>
    			<td>
    				<input type="radio" name="generating_type" value="auto" <?php echo set_radio('generating_type', 'auto', true); ?> /> Kupon kodlarını sistem otomatik olarak oluştursun<br />
    				<input type="radio" name="generating_type" value="manuel" <?php echo set_radio('generating_type', 'manuel', false); ?> /> Kupon kodlarını ben manuel olarak gireceğim
    			</td>
    		</tr>

    		<tr id="codes" style="display: none">
    			<td>Kodlar<span class="help">Kodlarınızı satır satır olarak yandaki kutucuğa giriniz. 1 Kupon kodu en az 8 karekterden oluşmalıdır</span></td>
    			<td><textarea name="codes" rows="10" cols="60"></textarea></td>
    		</tr>
    		
    		<tr id="qty">
    			<td>Adet<span class="help">Yukarıdaki özelliklerde kaç adet kupon kodu oluşturulsun ? Bir kerede en fazla 99 adet oluşturabilirsiniz.</span></td>
    			<td><input type="text" name="qty" value="<?php echo set_value('qty') ?>" /></td>
    		</tr>
    	</table>

	</form>

	</div><!-- // .content -->

</div><!-- // .box -->

<?php $this->load->view('yonetim/footer_view'); ?>

<script type="text/javascript"><!--
	$(document).ready(function() {
		$('#date_start, #date_end').datepicker({dateFormat: 'yy-mm-dd'});

		if($(':radio[name="generating_type"]:checked').val() == 'manuel') {
			$('#qty').fadeOut();
			$('#codes').fadeIn();
		} else {
			$('#codes').fadeOut();
			$('#qty').fadeIn();
		}

		$(':radio[name="generating_type"]').click(amele);

		//$.mask.definitions['~']='[+-]';
		$('input[name="qty"]').mask("9?9");
	});

	function amele () {
		if($(this).val() == 'manuel') {
			$('#qty').fadeOut();
			$('#codes').fadeIn();
		} else {
			$('#codes').fadeOut();
			$('#qty').fadeIn();
		}
	}
//--></script>