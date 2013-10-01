<div class="kutu" style="position:relative;word-wrap:break-word;">
<?php
if($eklenti_baslik_goster)
{
	echo '<div class="modul_ust">'. $eklenti_baslik .'</div>' . "\n";
	echo '<div class="modul_ic">' . "\n";
}
?>
<?php if(eklenti_ayar('reklam_5', 'tip') == '1' || eklenti_ayar('reklam_5', 'tip') == NULL) { ?>
	<?php echo eklenti_ayar('reklam_5', 'icerik'); ?>
<?php } ?>
<?php
if($eklenti_baslik_goster)
{
	echo '</div>' . "\n";
	echo '<div class="modul_alt"></div>' . "\n";
}
?>
</div>