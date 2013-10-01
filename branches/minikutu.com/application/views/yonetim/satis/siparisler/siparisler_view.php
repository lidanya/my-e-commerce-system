<?php $this->load->view('yonetim/header_view'); ?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
    <h1 style="background-image: url('<?php echo yonetim_resim(); ?>order.png');">Siparişler</h1>
		<div class="buttons"><a onclick="yazdir_bakim();" class="buton"><span>Yazdır</span></a></div>
	</div>
	<div class="content">

	<form action="" method="post" enctype="multipart/form-data" id="form" target="_blank">
	<table class="list">
		<thead>
			<tr>
				<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
				<td class="left">
					<?php if ($sort == 's.siparis_id') { ?>
					<a href="<?php echo yonetim_url('satis/siparisler/listele/s.siparis_id-'. ($order_link . '/' . $filt_link . '/' . $page_link)); ?>" class="<?php echo strtolower($order); ?>">Sipariş No</a>
					<?php } else { ?>
					<a href="<?php echo yonetim_url('satis/siparisler/listele/s.siparis_id-'. ($order_link . '/' . $filt_link . '/' . $page_link)); ?>">Siparis No</a>
					<?php } ?>
				</td>
				<td class="left">
					<?php if ($sort == 's.kayit_tar') { ?>
					<a href="<?php echo yonetim_url('satis/siparisler/listele/s.kayit_tar-'. ($order_link . '/' . $filt_link . '/' . $page_link)); ?>" class="<?php echo strtolower($order); ?>">Sipariş Zamanı</a>
					<?php } else { ?>
					<a href="<?php echo yonetim_url('satis/siparisler/listele/s.kayit_tar-'. ($order_link . '/' . $filt_link . '/' . $page_link)); ?>">Sipariş Zamanı</a>
					<?php } ?>
				</td>
				<td class="left">
					<?php if ($sort == 'u.user_id') { ?>
					<a href="<?php echo yonetim_url('satis/siparisler/listele/u.user_id-'. ($order_link . '/' . $filt_link . '/' . $page_link)); ?>" class="<?php echo strtolower($order); ?>">Müşteri Adı Soyadı</a>
					<?php } else { ?>
					<a href="<?php echo yonetim_url('satis/siparisler/listele/u.user_id-'. ($order_link . '/' . $filt_link . '/' . $page_link)); ?>">Müşteri Adı Soyadı</a>
					<?php } ?>
				</td>
				<td class="left">
					<?php if ($sort == 's.siparis_flag') { ?>
					<a href="<?php echo yonetim_url('satis/siparisler/listele/s.siparis_flag-'. ($order_link . '/' . $filt_link . '/' . $page_link)); ?>" class="<?php echo strtolower($order); ?>">Sipariş Durum</a>
					<?php } else { ?>
					<a href="<?php echo yonetim_url('satis/siparisler/listele/s.siparis_flag-'. ($order_link . '/' . $filt_link . '/' . $page_link)); ?>">Sipariş Durum</a>
					<?php } ?>
				</td>
				<td class="right">İşlemler</td>
			</tr>
		</thead>

		<tbody>
			<tr class="filter">
				<td></td>
				<td>
					<input type="text" style="width: 110px;" name="filter_siparis_id" value="<?php echo isset($filter_siparis_id) ? $filter_siparis_id : NULL; ?>" />
				</td>
				<td>
					<input type="text" name="filter_kayit_tar" value="<?php echo isset($filter_kayit_tar) ? $filter_kayit_tar : NULL; ?>" id="filter_kayit_tar" />
				</td>
				<td>
					<input type="text" name="filter_namesurname" value="<?php echo isset($filter_uii_namesurname) ? $filter_uii_namesurname : NULL; ?>" />
				</td>
				<td>
					<?php
					$secili = isset($filter_s_siparis_flag) ? $filter_s_siparis_flag : '';
					$once = array('' => '');
					echo form_dropdown_from_db('filter_siparis_flag', "SELECT siparis_durum_tanim_id,siparis_durum_baslik FROM ". $this->db->dbprefix('siparis_durum') ."", $once, $secili);
					?>
				</td>
		    	<td align="right" style="width: 90px;">
		    		<a onclick="filter();" class="buton"><span>Filtre</span></a>
	    		</td>
		  </tr>

		<?php if ($siparisler) { ?>
			<?php foreach ($siparisler as $siparis) { ?>
			<tr>
				<td style="text-align: center;">
				<?php if ($siparis['selected']) { ?>
					<input type="checkbox" name="selected[]" value="<?php echo $siparis['siparis_id']; ?>" checked="checked" />
				<?php } else { ?>
					<input type="checkbox" name="selected[]" value="<?php echo $siparis['siparis_id']; ?>" />
				<?php } ?>
				</td>
				<td class="left">
					<?php echo $siparis['siparis_id']; ?>
				</td>
				<td class="left">
					<?php echo standard_date('DATE_TR7', $siparis['siparis_zamani'], 'tr'); ?>
				</td>
				<td class="left">
					<a href="<?php echo yonetim_url('customer_management/customer/edit/'. $siparis['musteri_id']); ?>" target="blank"><?php echo $siparis['musteri_adi']; ?></a>
				</td>
				<td class="left">
					<?php
					echo $siparis['siparis_durum'];
					?>
				</td>
				<td class="right">
				<?php foreach ($siparis['islemler'] as $action) { ?>
					[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
				<?php } ?>
				</td>
			<?php } ?>
		<?php } else { ?>
			<tr>
				<td class="center" colspan="9">Gösterilecek sonuç yok!</td>
			</tr>
		<?php } ?>

	</table>
	</form>
	<?php
		echo $this->pagination->create_links(); 
	?>

	</div>
<script type="text/javascript"><!--
function filter() {
	url = '<?php echo yonetim_url("satis/siparisler/listele/". $sort_link); ?>/';

	var filter_siparis_id = $('input[name=\'filter_siparis_id\']').attr('value');
	
	if (filter_siparis_id) {
		url += 's.siparis_id|' + encodeURIComponent(filter_siparis_id) + ']';
	}
	
	var filter_kayit_tar = $('input[name=\'filter_kayit_tar\']').attr('value');
	
	if (filter_kayit_tar) {
		url += 's.kayit_tar|' + encodeURIComponent(filter_kayit_tar) + ']';
	}
	
	var filter_namesurname = $('input[name=\'filter_namesurname\']').attr('value');
	
	if (filter_namesurname) {
		url += 'uii.namesurname|' + encodeURIComponent(filter_namesurname) + ']';
	}

	var filter_siparis_flag = $('select[name=\'filter_siparis_flag\']').attr('value');
	
	if (filter_siparis_flag) {
		url += 's.siparis_flag|' + encodeURIComponent(filter_siparis_flag) + ']';
	}
	
	if (!filter_kayit_tar && !filter_namesurname && !filter_siparis_flag)
	{
		url += 's.kayit_tar|]';
	}

	url +=  '/0';
	location = url;
}
//--></script>
<script type="text/javascript">
	function yazdir_bakim()
	{
		if($('input[name*=\'selected\']:checked').length > 0)
		{
			$('#form').attr('action','<?php echo yonetim_url("satis/siparisler/siparis_yazdir"); ?>');
			$('#form').submit();
		} else {
			alert('İşlemi gerçekleştirmek için lütfen sipariş seçiniz.');
		}
	}

</script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('#filter_kayit_tar').datepicker({dateFormat: 'yy-mm-dd'});
	$('#form').keydown(function(e) {
		if (e.keyCode == 13) {
			filter();
		}
	});
});

//--></script>
<?php $this->load->view('yonetim/footer_view'); ?>