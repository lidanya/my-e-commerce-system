<?php
	$this->load->view('yonetim/header_view');
?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('<?php echo yonetim_resim(); ?>customer.png');">Reklamlar</h1>
    <div class="buttons">
    	<a onclick="location.href = '<?php echo yonetim_url('satis/e_posta/reklam_ekle'); ?>';" class="buton" style="margin-left:10px;"><span>Ekle</span></a>
    	<a onclick="$('form').attr('action', '<?php echo yonetim_url('satis/e_posta/reklam_sil'); ?>'); $('form').submit();" class="buton" style="margin-left:10px;"><span>Sil</span></a>
	</div>
  </div>
  <div class="content">
    <form action="" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
		<thead>
			<tr>
				<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
				<td class="left">Reklam Adı</td>
				<td class="left">Reklam Linki</td>
				<td class="left">Durum</td>
				<td class="right">İşlemler</td>
			</tr>
		</thead>
        <tbody>
		<?php 
		if($reklamlar)
		{
			foreach($reklamlar as $reklam):
		?>
          <tr>
            <td style="text-align: center;"><input type="checkbox" name="selected[]" value="<?php echo $reklam['id'];?>"/></td>
            <td class="left"><?php echo $reklam['adi'];?></td>
            <td class="left"><?php echo $reklam['link'];?></td>
            <td class="left">
            <?php 
            	$resim = ($reklam['durum'] == 0 ) ? 'eye_minus.png' : 'eye_plus.png';
            ?>
            <?php 
            if($reklam['durum'] == 1)
            {
            ?>
            	<a title="" href="<?php echo yonetim_url('satis/e_posta/reklam_durum/'. $reklam['id']); ?>">Kapat</a>
            <?php 
        	} else {
            ?>
				<a title="" href="<?php echo yonetim_url('satis/e_posta/reklam_durum/'. $reklam['id']); ?>">Aç</a>
            <?php 
            }
        	?>
            </td>
            <td class="right">
            		[ <a href="<?php echo yonetim_url('satis/e_posta/reklam_duzenle/'. $reklam['id']); ?>">Düzenle</a> ]
        	</td>
          </tr>
		<?php 
			endforeach;
	    } else {
		?>
          <tr>
            <td class="center" colspan="8">Gösterilecek sonuç yok!</td>
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
<?php
	$this->load->view('yonetim/footer_view');
?>