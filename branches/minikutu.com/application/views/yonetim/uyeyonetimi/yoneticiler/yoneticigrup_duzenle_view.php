<?php 
$this->load->view('yonetim/header_view');  
	$val = $this->validation; 

	$name = array(
		'name'=>'name',
		'size'=>'43',
		'id'=>'name',
		'value' => ($val->name) ? $val->name :$grup_veri->name
	);
	$price = array(
		'name'=>'price',
		'size'=>'43',
		'id'=>'price',
		'value' => ($val->price) ? $val->price :$grup_veri->fiyat_orani
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
    <h1 style="background-image: url('<?php echo yonetim_resim(); ?>customer.png');">Yönetici Grupları</h1>
    <div class="butons" style="float:right;margin-top:5px;"><a onclick="$('#form').submit();" class="buton"><span>Kaydet</span></a><a onclick="location = '<?php echo yonetim_url('uye_yonetimi/yonetici_grup'); ?>';" class="buton" style="margin-left:10px;"><span>İptal</span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo yonetim_url('uye_yonetimi/yonetici_grup/duzenle/'. $this->uri->segment(5)); ?>" method="post" enctype="multipart/form-data" id="form">
    	<input type="hidden" name="id" value="<?php echo $grup_veri->id; ?>" />
      <table class="form">
        <tr>
          <td><span class="required">*</span> Yönetici Grup Adı:</td>
          <td><?php echo form_input($name); ?>
            <?php if ($val->name_error) { ?>
            <span class="error"><?php echo $val->name_error; ?></span>
            <?php  } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> Oran:</td>
          <td>
          	<select name="price_tip">
          		<option value="0" <?php echo ($grup_veri->fiyat_tip == 0) ? 'selected="selected"' : $val->price_tip ;?> >-</option>
          		<option value="1" <?php echo ($grup_veri->fiyat_tip == 1) ? 'selected="selected"' : $val->price_tip ;?> >+</option>
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
				echo form_dropdown('price', $indirim, ($val->price) ? $val->price:$grup_veri->fiyat_orani);
          	?>
            <?php if ($val->price_error) { ?>
            <span class="error"><?php echo $val->price_error; ?></span>
            <?php  } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> Erişebileceği Sayfalar:</td>
          <td>
			<?php
				$_izinli_sayfalar = $this->config->item('yetki_sayfalari');

				$_yetki = @unserialize($grup_veri->yetki);

				foreach($_izinli_sayfalar as $sayfa_url => $sayfa_adi)
				{
					$_checked = NULL;

					if(is_array($_yetki))
					{
						if(array_key_exists($sayfa_url, $_yetki))
						{
							if(array_key_exists('izin', $_yetki[$sayfa_url]))
							{
								if($_yetki[$sayfa_url]['izin'] == '1')
								{
									$_checked = 'checked="checked"';
								} else {
									$_checked = NULL;
								}
							} else {
								$_checked = NULL;
							}
						} else {
							$_checked = NULL;
						}
					} else {
						$_checked = NULL;
					}

					echo '<div style="margin:5px;"><input type="checkbox" name="yetki['. $sayfa_url .'][izin]" value="1" '. $_checked .' /> ' . $sayfa_adi . ' </div>' . "\n";
				}
			?>
            <?php if ($val->yetki_error) { ?>
            <span class="error"><?php echo $val->yetki_error; ?></span>
            <?php  } ?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php $this->load->view('yonetim/footer_view');  ?>