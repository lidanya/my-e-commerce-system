<?php
	$this->load->view('yonetim/header_view');
?>

<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('<?php echo yonetim_resim(); ?>customer.png');">Şubeler</h1>
    <div class="buttons">
    	<a href="<?php echo yonetim_url('sistem/bayiler/ekle'); ?>" class="buton" style="margin-left:10px;"><span>Şube Ekle</span></a>
    	<a onclick="$(form).submit();" class="buton" style="margin-left:10px;"><span>Sil</span></a>
	</div>
  </div>
  <div class="content">
    <form action="<?php echo yonetim_url('sistem/bayiler/sil'); ?>" method="post" name="form" id="form">
      <table class="list">
		<thead>
			<tr>
				<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
				<td class="left">
					Şube Adı
				</td>
				<td class="left">
					Telefon
				</td>
				<td class="left">
					Fax
				</td>
				<td class="left">
					Ekleme Tarihi
				</td>
				<td class="right">
					İşlemler
				</td>
			</tr>
		</thead>
        <tbody>
        <?php
        if($bayiler) {
        	foreach($bayiler as $bayi):
        ?>
          <tr>
            <td style="text-align: center;"><input type="checkbox" name="selected[]" value="<?php echo $bayi["id"]?>" /></td>
            <td class="left"><a href="<?php foreach($bayi['action'] as $action){ echo ($action['text'] == 'Düzenle') ? $action['href'] : '#'; }?>"><?php echo $bayi['bayi'];?></a></td>
            <td class="left"><?php echo $bayi['tel'];?></td>
            <td class="left"><?php echo $bayi['fax'];?></td>
            <td class="left"><?php echo $bayi['tarih'];?></td>
            <td class="right">
            	<?php
            	foreach($bayi['action'] as $action){
            	?>
	            	[ <a href="<?php echo $action['href'];?>"><?php echo $action['text'];?></a> ]
            	<?php 
            	}
            	?>
        	</td>
            	
          </tr>
        <?php
        	endforeach;
      	} else {
        ?>
          <tr>
            <td class="center" colspan="8">Gösterilecek sonuç yok</td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </form>

  </div>
</div>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date_created').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<?php
	$this->load->view('yonetim/footer_view');
?>