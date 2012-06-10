<?php $this->load->view('yonetim/header_view'); ?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
    	<h1 style="background-image: url('<?php echo yonetim_resim(); ?>module.png');">Modüller</h1>
	</div>
		<div class="content"> 
			<table class="list"> 
				<thead> 
					<tr> 
						<td class="left">Modül Adı</td>
						<td class="left">Pozisyonu</td>
						<td class="left">Durumu</td>
						<td class="right">Sırlama</td>
						<td class="right">Aksiyon</td>
					</tr> 
				</thead>
				<tbody>
					<?php
						foreach($moduller->result() as $modul) {
						$unserialize_baslik = @unserialize($modul->eklenti_baslik);
					?>
					<tr>
						<td class="left"><?php echo $unserialize_baslik[$this->fonksiyonlar->get_language('language_id', config('site_ayar_yonetim_dil'))]; ?></td>
						<?php
						$yerler = array();
						$yerler = @unserialize($modul->eklenti_yer);
						?>
						<td class="left">
						<?php
						$_posizyon_yerler = array('Sol','Anasayfa','Ust');
						$_pos = array();
						foreach($_posizyon_yerler as $_posizyon_yer)
						{
							if(in_array(strtolower($_posizyon_yer), $yerler)) {
								$_pos[] = $_posizyon_yer;
							}
						}
						echo implode(', ', $_pos);
						?>
						</td>
						<td class="left"><?php echo ($modul->eklenti_durum == '1') ? 'Açık':'Kapalı'; ?></td>
						<td class="right"><?php echo $modul->eklenti_sira; ?></td>
						<td class="right">
							[ <a href="<?php echo yonetim_url('moduller/modul/duzenle/'. $modul->eklenti_id); ?>">Düzenle</a> ]
						</td>
					</tr>
					<?php } ?>
				</tbody> 
			</table>
		</div>
	</div>
</div>
<?php $this->load->view('yonetim/footer_view');  ?>