<?php 
$this->load->view('yonetim/header_view');
?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('<?php echo yonetim_resim(); ?>customer.png');">Reklamlar</h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="buton"><span>Kaydet</span></a><a onclick="location = '<?php echo yonetim_url('satis/e_posta'); ?>';" class="buton" style="margin-left:10px;"><span>İptal</span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo yonetim_url('satis/e_posta/reklam_ekle'); ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">

        <tr>
          <td><span class="required">*</span> Reklam Adı:</td>
          <td colspan="2"><input type="text" name="reklam_adi" value="<?php echo ($val->reklam_adi) ? $val->reklam_adi : NULL ;?>"   style="width:400px;" />
            <?php echo ($val->reklam_adi_error) ? '<span class="error">'.$val->reklam_adi_error.'</span>' : NULL ;?>
		  </td>
        </tr>

        <tr>
          <td><span class="required">*</span> Reklam Linki:</td>
          <td colspan="2">
          	<input type="text" name="reklam_link" value="<?php echo ($val->reklam_link) ? $val->reklam_link : base_url() ;?>"  style="width:400px;" />
          	<?php echo ($val->reklam_link_error) ? '<span class="error">'.$val->reklam_link_error.'</span>' : NULL ;?>
		  </td>
        </tr>

        <tr>
          <td><span class="required">*</span> Reklam Metni:</td>
          <td>
          	<textarea onkeyup="metni_yaz(this,'#metin')" name="reklam_metni" style="width:400px; height:120px;"><?php echo ($val->reklam_metni) ? $val->reklam_metni : NULL ;?></textarea>
          	<?php echo ($val->reklam_metni_error) ? '<span class="error">'.$val->reklam_metni_error.'</span>' : NULL ;?>
		  </td>
		  <td style="text-align:center;"><p style="font-weight:bold:color:#000;margin:0px auto 5px; text-align:center;width:400px;">Reklam Metni Gönderilen E-Postada Aşağıdaki Gibi Görüntülenecektir:</p>
		  	<div id="metin" style="width:400px;margin:auto;height:45px;background-color:#f1f1f1;text-align:center;padding-top:15px;font-size:16px;color:#008fff;overflow:hidden;"><?php echo ($val->reklam_metni) ? $val->reklam_metni : NULL; ?></div>
		  </td>
        </tr>

        <tr>
          <td>Reklam Durum:</td>
          <td colspan="2">
          	<?php
          		$durum = ($val->reklam_durum) ? $val->reklam_durum : 1;
          		$durum_array = array('1' => 'Açık', '0' => 'Kapalı');
          		echo form_dropdown('reklam_durum', $durum_array, $durum);
          	?>
          	<?php echo ($val->reklam_durum_error) ? '<span class="error">'.$val->reklam_durum_error.'</span>' : NULL ;?>
		  </td>

        </tr>
        </table>
    </form>
  </div>
</div>
<script>
function metni_yaz(input, div)
{
	$(div).html($(input).val().replace(/\n/gi,'<br />'));
}
</script>
<?php $this->load->view('yonetim/footer_view');  ?>