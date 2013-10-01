<?php 
	$this->load->view('yonetim/header_view'); 
	$val = $this->validation; 
?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('<?php echo yonetim_resim(); ?>payment.png');">Ödeme Seçenekleri</h1>
	</div>
	<div class="content">
		<table class="list">
			<thead>
				<tr>
					<td class="left">Ödeme Metodu</td>
					<td>Açıklama</td>
					<td class="left">Durum</td>
					<td class="right">Sıralama</td>
					<td class="right">İşlemler</td>
				</tr>
			</thead>
			<tbody>
			<?php if ($odeme_secenekleri->num_rows() > 0) { ?>
			<?php foreach ($odeme_secenekleri->result() as $extension) { ?>
			<?php
				$unserialize_baslik = @unserialize($extension->odeme_baslik);
				$unserialize_aciklama = @unserialize($extension->odeme_aciklama);
			?>
			<tr>
				<td class="left"><?php echo $unserialize_baslik[get_language('language_id', config('site_ayar_yonetim_dil'))]; ?></td>
				<td class="center"><?php echo $unserialize_aciklama[get_language('language_id', config('site_ayar_yonetim_dil'))]; ?></td>
				<td class="left"><?php echo ($extension->odeme_durum == '1' && $extension->odeme_kurulum == '1') ? 'Açık':'Kapalı'; ?></td>
				<td class="right"><?php echo ($extension->odeme_durum == '1' && $extension->odeme_kurulum == '1') ? $extension->odeme_sira:NULL; ?></td>
				<td class="right">
				<?php
					if($extension->odeme_kurulum == '1')
					{
						echo '[ <a href="'. yonetim_url('sistem/odeme_secenekleri/duzenle/'. $extension->odeme_model) .'">Düzenle</a> ] ';
					}
					/*if($extension->odeme_kurulum == '1')
					{
						echo '[ <a href="'. yonetim_url('sistem/odeme_secenekleri/kaldir/'. $extension->odeme_model) .'">Kaldır</a> ]';
					} else {
						echo '[ <a href="'. yonetim_url('sistem/odeme_secenekleri/kur/'. $extension->odeme_model) .'">Kur</a> ]';
					}*/
				?>
				</td>
			</tr>
			<?php } ?>
			<?php } else { ?>
			<tr>
				<td class="center" colspan="6">Ödeme metodu bulunamadı</td>
			</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<?php $this->load->view('yonetim/footer_view'); ?>