<?php 
$this->load->view('yonetim/header_view');  
	$val = $this->validation; 
	
	if ($this->uri->segment(5)){ $sort_lnk=$this->uri->segment(5); } else {$sort_lnk='name_asc';}
	
	if ($sort_lnk=='ok'){$sort_lnk='name_asc';}
	
	$sort_lnk_e=explode('_',$sort_lnk);
	$sort  = $sort_lnk_e[0];
	$order = $sort_lnk_e[1];
	?>
	<?php 
	if ($order){
		if ($order=='asc'){$order_lnk='desc';} else if ($order=='desc'){$order_lnk='asc';}
	} else{
		$order_lnk = 'asc';		
		$order = 'desc';		
	}
?>

<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('<?php echo yonetim_resim(); ?>shipping.png');">Kargolar</h1>
    	<div class="buttons"><a onclick="location = '<?php echo yonetim_url('sistem/kargo/ekle'); ?>'" class="buton"><span>Ekle</span></a><a onclick="$('form').submit();" class="buton" style="margin-left:10px;"><span>Sil</span></a></div>
  	</div>
  	<div class="content">
    	<form action="<?php echo yonetim_url('sistem/kargo/sil'); ?>" method="post" enctype="multipart/form-data" id="form">
      	<table class="list">
        	<thead>
          		<tr>
					<td width="1" style="text-align: center;">
						<input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
					</td>
            		<td class="center">Kargo Logo</td>
					<td class="left">
					<?php if ($sort == 'name') { ?>
						<a href="<?php echo yonetim_url('sistem/kargo/listele/name_'. $order_lnk .'/1'); ?>" class="<?php echo strtolower($order); ?>">
							Kargo Adı
						</a>
					 <?php } else { ?>
						<a href="<?php echo yonetim_url('sistem/kargo/listele/name_'. $order_lnk .'/1'); ?>">Kargo Adı</a>
					<?php } ?>
					</td>
					<td class="right">
					<?php if ($sort == 'order') { ?>
						<a href="<?php echo yonetim_url('sistem/kargo/listele/order_'. $order_lnk .'/1'); ?>" class="<?php echo strtolower($order); ?>">
							Sıralama
						</a>
					<?php } else { ?>
					  	<a href="<?php echo yonetim_url('sistem/kargo/listele/order_'. $order_lnk .'/1'); ?>">Sıralama</a>
					<?php } ?>
					</td>
					<td class="right">
					<?php if ($sort == 'status') { ?>
						<a href="<?php echo yonetim_url('sistem/kargo/listele/status_'. $order_lnk .'/1'); ?>" class="<?php echo strtolower($order); ?>">
							Durum
						</a>
					<?php } else { ?>
					  	<a href="<?php echo yonetim_url('sistem/kargo/listele/status_'. $order_lnk .'/1'); ?>">
					  		Durum
				  		</a>
					<?php } ?>
					</td>
					<td class="right">Eylem</td>
				</tr>
			</thead>
        	<tbody>
          	<?php 
				if ($kargolar) 
				{
					foreach ($kargolar as $kargolar_row) 
					{ ?>

						<tr>
							<td style="text-align: center; margin:0px; padding:0px;">
								<?php if ($kargolar_row['selected']) { ?>
									<input type="checkbox" name="selected[]" value="<?php echo $kargolar_row['kargo_id']; ?>" checked="checked" />
								<?php } else { ?>
									<input type="checkbox" name="selected[]" value="<?php echo $kargolar_row['kargo_id']; ?>" />
								<?php } ?>
							</td>
            				<td class="center" style="margin:0px; padding:0px;">
								<?php 
        							if ($kargolar_row['kargo_logo'])
        							{
        								$resim_yol = $kargolar_row['kargo_logo'];
        							}else{
        								$resim_yol='no_image.jpg';
    								}
            					?>
								<img src="<?php echo $this->image_model->resize($resim_yol, 50, 50); ?>" alt="" class="image"  />
        					</td>
							<td class="left"><?php echo $kargolar_row['kargo_adi']; ?></td>
							<td class="right">
								<?php 
								echo ('[ '.$kargolar_row["kargo_sira"].' ]'); 
								echo('<a href="'. yonetim_url('sistem/kargo/sira/'.$kargolar_row['kargo_id'].'/kargo_enust?red='.$this->uri->uri_string()) .'"><img src="'.yonetim_resim().'move3.gif" border="0" ></a>');
								echo('<a href="'. yonetim_url('sistem/kargo/sira/'.$kargolar_row['kargo_id'].'/kargo_ust?red='.$this->uri->uri_string()) .'"><img src="'.yonetim_resim().'move1.gif" border="0" ></a>');
								echo('<a href="'. yonetim_url('sistem/kargo/sira/'.$kargolar_row['kargo_id'].'/kargo_alt?red='.$this->uri->uri_string()) .'"><img src="'.yonetim_resim().'move2.gif" border="0" ></a>');
								echo('<a href="'. yonetim_url('sistem/kargo/sira/'.$kargolar_row['kargo_id'].'/kargo_enalt?red='.$this->uri->uri_string()) .'"><img src="'.yonetim_resim().'move4.gif" border="0" ></a>');
								?>
							</td>
							<td class="right"><?php 
								if ($kargolar_row['kargo_flag']=='1' ){
									echo('<a href="'. yonetim_url('sistem/kargo/durum/'.$kargolar_row["kargo_id"].'/gizle?red='.$this->uri->uri_string()) .'" title="Kapat">');
										echo ('<img src="'.yonetim_resim().'eye_minus.png" border="0" >'); 
									echo('</a>');
								} else if ($kargolar_row['kargo_flag']=='2' ){
									echo('<a href="'. yonetim_url('sistem/kargo/durum/'.$kargolar_row["kargo_id"].'/goster?red='.$this->uri->uri_string()) .'" title="Aç">');
										echo ('<img src="'.yonetim_resim().'eye_plus.png" border="0" >'); 
									echo('</a>');
								} 
								?>
							</td>
							<td class="right">
								<?php foreach ($kargolar_row['action'] as $action) { ?>
								[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
								<?php } ?>
							</td>
						</tr>
					<?php }
				} else { ?>
          			<tr>
            			<td class="center" colspan="6">Gösterilecek sonuç yok!</td>
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
<?php $this->load->view('yonetim/footer_view');  ?>