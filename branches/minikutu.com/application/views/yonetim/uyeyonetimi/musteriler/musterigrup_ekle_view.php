<?php 
$this->load->view('yonetim/header_view');  
	$val = $this->validation; 

	$name = array(
		'name'=>'name',
		'size'=>'43',
		'id'=>'name',
		'value' => ($val->name) ? $val->name :NULL
	);
	$price = array(
		'name'=>'price',
		'size'=>'43',
		'id'=>'price',
		'value' => ($val->price) ? $val->price :NULL
	);
	
	if ($kontrol_data==TRUE){
		?>
		<div class="success">İşlem Başarılı</div>
		<?php
	} else{
		if ($val->error_string) 
		{ 
		?>
		<div class="warning">İşlem Başarısız</div>
		<?php 
		} 
	}
?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('<?php echo yonetim_resim(); ?>customer.png');">Müşteri Grupları</h1>
    <div class="butons" style="float:right;margin-top:5px;"><a onclick="$('#form').submit();" class="buton"><span>Kaydet</span></a><a onclick="location = '<?php echo yonetim_url('uye_yonetimi/musteri_grup'); ?>';" class="buton" style="margin-left:10px;"><span>İptal</span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo yonetim_url('uye_yonetimi/musteri_grup/ekle'); ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> Müşteri Grup Adı:</td>
          <td><input type="text" name="name" value="" />
            <?php if ($val->name_error) { ?>
            <span class="error"><?php echo $val->name_error; ?></span>
            <?php  } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> Oran:</td>
          <td>
          	<select name="price_tip">
          		<option value="0" <?php echo ($val->price_tip == 0) ? 'selected="selected"' : NULL ;?> >-</option>
          		<option value="1" <?php echo ($val->price_tip == 1) ? 'selected="selected"' : NULL ;?> >+</option>
          	</select>
          	<?php
          	$indirim = array();
			$indirim['00'] = '0';
			$indirim['01'] = '1';
			$indirim['02'] = '2';
			$indirim['03'] = '3';
			$indirim['04'] = '4';
			$indirim['05'] = '5';
			$indirim['06'] = '6';
			$indirim['07'] = '7';
			$indirim['08'] = '8';
			$indirim['09'] = '9';
			for($i=10;$i<=99;$i++)
			{
				$indirim[$i] = $i;
			}
				echo form_dropdown('price', $indirim, $val->price);
          	?>
            <?php if ($val->price_error) { ?>
            <span class="error"><?php echo $val->price_error; ?></span>
            <?php  } ?></td>
        </tr>
      </table>
    </form>
  </div>
</div>

<?php $this->load->view('yonetim/footer_view');  ?>