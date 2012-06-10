<?php 
$this->load->view('yonetim/header_view');  
	$val = $this->validation; 

	$kargo_adi = array(
		'name' 	=> 'kargo_adi',
		'id' 	=> 'kargo_adi',
		'class' => 'text',
		'value' => (($val->kargo_adi)) ? $val->kargo_adi:$kargo_veri->kargo_adi,
		'size' => '50'
	);
	foreach ($kargo_ucret_veri->result() as $kargo_ucret_veri_row)
	{
		if ($kargo_ucret_veri_row->kargo_ucret_tip=='ucret_tip1'){$ucret_tip1_b=$kargo_ucret_veri_row->kargo_ucret_ucret;} else
		if ($kargo_ucret_veri_row->kargo_ucret_tip=='ucret_tip2'){$ucret_tip2_b=$kargo_ucret_veri_row->kargo_ucret_ucret;} else
		if ($kargo_ucret_veri_row->kargo_ucret_tip=='ucret_tip3'){$ucret_tip3_b=$kargo_ucret_veri_row->kargo_ucret_ucret;} else
		if ($kargo_ucret_veri_row->kargo_ucret_tip=='ucret_tip4'){$ucret_tip4_b=$kargo_ucret_veri_row->kargo_ucret_ucret;} else
		if ($kargo_ucret_veri_row->kargo_ucret_tip=='ucret_tip5'){$ucret_tip5_b=$kargo_ucret_veri_row->kargo_ucret_ucret;} else
		if ($kargo_ucret_veri_row->kargo_ucret_tip=='ucret_tip6'){$ucret_tip6_b=$kargo_ucret_veri_row->kargo_ucret_ucret;} else
		if ($kargo_ucret_veri_row->kargo_ucret_tip=='ucret_tip7'){$ucret_tip7_b=$kargo_ucret_veri_row->kargo_ucret_ucret;} else
		if ($kargo_ucret_veri_row->kargo_ucret_tip=='ucret_tip8'){$ucret_tip8_b=$kargo_ucret_veri_row->kargo_ucret_ucret;} 
	}
	$ucret_tip1 = array(
		'name' 	=> 'ucret_tip1',
		'id' 	=> 'ucret_tip1',
		'class' => 'text',
		'value' => (($val->ucret_tip1)) ? $val->ucret_tip1:$ucret_tip1_b,
		'size' => '50'
	);
	$ucret_tip2 = array(
		'name' 	=> 'ucret_tip2',
		'id' 	=> 'ucret_tip2',
		'class' => 'text',
		'value' => (($val->ucret_tip2)) ? $val->ucret_tip2:$ucret_tip2_b,
		'size' => '50'
	);
	$ucret_tip3 = array(
		'name' 	=> 'ucret_tip3',
		'id' 	=> 'ucret_tip3',
		'class' => 'text',
		'value' => (($val->ucret_tip3)) ? $val->ucret_tip3:$ucret_tip3_b,
		'size' => '50'
	);
	$ucret_tip4 = array(
		'name' 	=> 'ucret_tip4',
		'id' 	=> 'ucret_tip4',
		'class' => 'text',
		'value' => (($val->ucret_tip4)) ? $val->ucret_tip4:$ucret_tip4_b,
		'size' => '50'
	);
	$ucret_tip5 = array(
		'name' 	=> 'ucret_tip5',
		'id' 	=> 'ucret_tip5',
		'class' => 'text',
		'value' => (($val->ucret_tip5)) ? $val->ucret_tip5:$ucret_tip5_b,
		'size' => '50'
	);
	$ucret_tip6 = array(
		'name' 	=> 'ucret_tip6',
		'id' 	=> 'ucret_tip6',
		'class' => 'text',
		'value' => (($val->ucret_tip6)) ? $val->ucret_tip6:$ucret_tip6_b,
		'size' => '50'
	);
	$ucret_tip7 = array(
		'name' 	=> 'ucret_tip7',
		'id' 	=> 'ucret_tip7',
		'class' => 'text',
		'value' => (($val->ucret_tip7)) ? $val->ucret_tip7:$ucret_tip7_b,
		'size' => '50'
	);
	$ucret_tip8 = array(
		'name' 	=> 'ucret_tip8',
		'id' 	=> 'ucret_tip8',
		'class' => 'text',
		'value' => (($val->ucret_tip8)) ? $val->ucret_tip8:$ucret_tip8_b,
		'size' => '50'
	);
	
	
	if ($kontrol_data==TRUE){
		echo('<div class="success">İşlem Başarılı</div>');
	} else{
		if ($val->error_string) 
		{ 
			echo('<div class="warning">İşlem Başarısız</div>');
		} 
	}
?>


<div class="box">
	<div class="left"></div>	
  	<div class="right"></div>
  	
  	<div class="heading">
    	<h1 style="background-image: url('<?php echo yonetim_resim(); ?>shipping.png');">Kargolar</h1>
    	<div class="buttons">
    		<a onclick="$('#form').submit();" class="buton"><span>Kaydet</span></a>
    		<a onclick="location = '<?php echo yonetim_url('sistem/kargo'); ?>';" class="buton" style="margin-left:10px;"><span>İptal</span></a>
		</div>
  	</div>
  
	<div class="content">
		<div id="tabs" class="htabs">
			<a tab="#tab_general">Genel</a>
			<a tab="#tab_cost">Ücretlendirme</a>
		</div>
		
    	<form action="<?php echo yonetim_url('sistem/kargo/duzenle/'. $kargo_veri->kargo_id); ?>" method="post" enctype="multipart/form-data" id="form">
    		<input type="hidden" name="kargo_id" value="<?php echo $kargo_veri->kargo_id; ?>" >
		<div id="tab_general">
			<table class="form">
		        <tr>
		        	<td><span class="required">*</span>Adı:</td>
		            <td>
		            	<?php echo form_input($kargo_adi);?>
						<?php if ($val->kargo_adi_error) { echo ('<span class="error">'.$val->kargo_adi_error.'</span>'); } ?>
            		</td>
		    	</tr>
				<tr>
					<td>Logo:<br><span class="help">Logo yüklemek için resmin üzerine tıklayın.</span></td>
					<td>
						<?php
							if($this->input->post('product_image'))
							{
								$preview = $this->image_model->resize($this->input->post('product_image'), 100, 100);
							} else if ($kargo_veri->kargo_logo){
								$preview = $this->image_model->resize($kargo_veri->kargo_logo, 100, 100);
							} else {
								$preview = $this->image_model->resize('no_image.jpg', 100, 100);
							}
							if($this->input->post('product_image'))
							{
								$preview_input = $this->image_model->resize($this->input->post('product_image'), 100, 100);
							} else if ($kargo_veri->kargo_logo){
								$preview_input = $kargo_veri->kargo_logo;
							} else {
								$preview_input = $this->image_model->resize('no_image.jpg', 100, 100);
							}
						?>
						<input type="hidden" name="product_image" value="<?php echo $preview_input; ?>" id="product_image" />
			           	 <img src="<?php echo $preview; ?>" alt="aa" id="preview" class="image" onclick="image_upload('product_image', 'preview');" />
					</td>
				</tr>
				<tr>
					<td>Durum:</td>
					<td>
						<select name="kargo_durum">
							<?php if (($val->kargo_durum=='2') or ($kargo_veri->kargo_flag=='2')) { ?>
								<option value="1">Açık</option>
								<option value="2" selected="selected">Kapalı</option>
							<?php } else { ?>
								<option value="1" selected="selected">Açık</option>
								<option value="2">Kapalı</option>						
							<?php } ?>
						</select>
					</td>
				</tr>
				<?php /* ?>
				<tr>
					<td>Ürün Başına Kargo:<br><span class="help">Eğer kargo ücreti ürün başına ücretlendirilecekse seçeneği evet olarak seçiniz. Kaç adet ürün varsa kargo toplamı ürün ile çarpılır.</span></td>
					<td>
						<select name="kargo_parca">
							<?php if (($val->kargo_parca=='1')  or ($kargo_veri->kargo_parca=='1')) { ?>
								<option value="1" selected="selected">Evet</option>
								<option value="2">Hayır</option>						
							<?php } else { ?>
								<option value="1">Evet</option>
								<option value="2" selected="selected">Hayır</option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<?php */ ?>
				<tr>
					<td>Ücretlendirme</td>
					<td>
						<select name="kargo_ucret_tip">
							<?php if (($val->kargo_ucret_tip=='2') or ($kargo_veri->kargo_ucret_tip=='2')) { ?>
								<option value="1">Sabit Ücretlendirme</option>
								<option value="2" selected="selected">Desi ile Ücretlendirme</option>
							<?php } else { ?>
								<option value="1" selected="selected">Sabit Ücretlendirme</option>
								<option value="2">Desi ile Ücretlendirme</option>						
							<?php } ?>
						</select>
					</td>
				</tr>
			</table>
		</div>
		
		<div id="tab_cost">
			<table class="form">
		        <tr>
		        	<td><span class="required">*</span> Önemli Uyarı</td>
		            <td>
		            	Eğer <span style="font-weight:bold;">Desi ile Ücretlendirme</span> seçilirse desi oranı bulunamadığında <span style="font-weight:bold;">Sabit Kargo Ücreti</span> geçerli olacaktır.
		            </td>
		    	</tr>
		        <tr>
		        	<td><span class="required">*</span> Sabit Kargo Ücreti:</td>
		            <td>
		            	<?php echo form_input($ucret_tip1);?>
		            	<?php if ($val->ucret_tip1_error) { echo ('<span class="error">'.$val->ucret_tip1_error.'</span>'); } ?>
		            </td>
		    	</tr>
		        <tr>
		        	<td>1 - 5 Desi Arası Ücret:</td>
		            <td><?php echo form_input($ucret_tip2);?>
		            	<?php if ($val->ucret_tip2_error) { echo ('<span class="error">'.$val->ucret_tip2_error.'</span>'); } ?>
		            </td>
		    	</tr>
		        <tr>
		        	<td>6 - 10 Desi Arası Ücret:</td>
		            <td><?php echo form_input($ucret_tip3);?>
		            	<?php if ($val->ucret_tip3_error) { echo ('<span class="error">'.$val->ucret_tip3_error.'</span>'); } ?>
		            </td>
		    	</tr>
		        <tr>
		        	<td>11 - 20 Desi Arası Ücret:</td>
		            <td><?php echo form_input($ucret_tip4);?>
		            	<?php if ($val->ucret_tip4_error) { echo ('<span class="error">'.$val->ucret_tip4_error.'</span>'); } ?>
		            </td>
		    	</tr>
		        <tr>
		        	<td>21 - 30 Desi Arası Ücret:</td>
		            <td><?php echo form_input($ucret_tip5);?>
		            	<?php if ($val->ucret_tip5_error) { echo ('<span class="error">'.$val->ucret_tip5_error.'</span>'); } ?>
		            </td>
		    	</tr>
		        <tr>
		        	<td>31 - 40 Desi Arası Ücret:</td>
		            <td><?php echo form_input($ucret_tip6);?>
		            	<?php if ($val->ucret_tip6_error) { echo ('<span class="error">'.$val->ucret_tip6_error.'</span>'); } ?>
		            </td>
		    	</tr>
		        <tr>
		        	<td>41 - 50 Desi Arası Ücret:</td>
		            <td><?php echo form_input($ucret_tip7);?>
		            	<?php if ($val->ucret_tip7_error) { echo ('<span class="error">'.$val->ucret_tip7_error.'</span>'); } ?>
		            </td>
		    	</tr>
		        <tr>
		        	<td>51 - 60 Desi Arası Ücret:</td>
		            <td><?php echo form_input($ucret_tip8);?>
		            	<?php if ($val->ucret_tip8_error) { echo ('<span class="error">'.$val->ucret_tip8_error.'</span>'); } ?>
		            </td>
		    	</tr>
			</table>
		</div>
		
    	</form>
	</div>
</div>
<script type="text/javascript"><!--
function image_upload(field, preview) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="<?php echo yonetim_url(); ?>/dosya_yonetici?field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: 'Resim Yöneticisi',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: '<?php echo yonetim_url("dosya_yonetici/image"); ?>',
					type: 'POST',
					data: 'image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(data) {
						$('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" class="image" onclick="image_upload(\'' + field + '\', \'' + preview + '\');" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 700,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script>

<script type="text/javascript"><!--
$('#tabs a').tabs(); 
$('#languages a').tabs(); 
//--></script>
<?php $this->load->view('yonetim/footer_view');  ?>