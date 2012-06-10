<div class="kutu" style="position:relative;">
<?php
if($eklenti_baslik_goster)
{
	echo '<div class="modul_ust">'. $eklenti_baslik .'</div>' . "\n";
	echo '<div class="modul_ic">' . "\n";
}
?>
<?php if(eklenti_ayar('doviz_kurlari', 'tip') == '1' || eklenti_ayar('doviz_kurlari', 'tip') == NULL) { ?>

<style>
/*=========================Yan Finans Menu ===============================*/
#finans {width:200px;margin:auto;font-size:16px;}
#finans span {display:block;}
.fin_oge {width:160px;border-bottom:1px dashed #dedede;padding:5px 0;}
.fin_dolar {background:url(<?php echo site_resim(); ?>modul_finans_dolar.png) no-repeat 0 0;}
.fin_euro {background:url(<?php echo site_resim(); ?>modul_finans_euro.png) no-repeat 0 0;}
.fin_up {background:url(<?php echo site_resim(); ?>modul_finans_up.png) no-repeat 0 0;}
.fin_down {background:url(<?php echo site_resim(); ?>modul_finans_down.png) no-repeat 0 0;}
.fin_baslik {color:#373535;font-weight:bold;width:75px;text-align:right;height:22px;padding-top:3px;}
.fin_deger {color:#777777;width:55px;text-align:left;height:22px;padding-top:3px;margin-left:10px;}
.fin_ok {width:14px;height:19px;margin-top:3px;}
</style>

	<div id="finans" style="width:170px;">
		<div class="fin_oge">
			<span class="fin_baslik fin_dolar sola">Dolar : </span>
			<span class="fin_deger sola"><?php echo number_format(kur_oku('usd', 'satis'), 4, ',', '.'); ?>
			<?php
				$usd_durum = NULL;
				$kur_sorgu = $this->db->get_where('kurlar', array('kur_adi' => 'usd'), 1);
				if($kur_sorgu->num_rows() > 0)
				{
					$kur_bilgi = $kur_sorgu->row();
					if(config('site_ayar_kur') == '3')
					{
						$_satis_0 = $kur_bilgi->kur_satis_manuel;
					} else {
						$_satis_0 = $kur_bilgi->kur_satis;
					}
					if($kur_bilgi->kur_satis_eski <= $_satis_0)
					{
						$usd_durum = '<span class="fin_ok fin_up sola">&nbsp;</span>';
					} else {
						$usd_durum = '<span class="fin_ok fin_down sola">&nbsp;</span>';
					}
				}
			?>
			</span>
			<?php echo $usd_durum; ?>
			<div class="clear"></div>
		</div>

		<div class="fin_oge" style="border:none;">
			<span class="fin_baslik fin_euro sola">Euro : </span>
			<span class="fin_deger sola"><?php echo number_format(kur_oku('eur', 'satis'), 4, ',', '.'); ?>
			<?php
				$eur_durum = NULL;
				$kur_sorgu_1 = $this->db->get_where('kurlar', array('kur_adi' => 'eur'), 1);
				if($kur_sorgu_1->num_rows() > 0)
				{
					$kur_bilgi_1 = $kur_sorgu_1->row();
					if(config('site_ayar_kur') == '3')
					{
						$_satis_1 = $kur_bilgi_1->kur_satis_manuel;
					} else {
						$_satis_1 = $kur_bilgi_1->kur_satis;
					}
					if($kur_bilgi_1->kur_satis_eski <= $_satis_1)
					{
						$eur_durum = '<span class="fin_ok fin_up sola">&nbsp;</span>';
					} else {
						$eur_durum = '<span class="fin_ok fin_down sola">&nbsp;</span>';
					}
				}
			?>
			</span>
			<?php echo $eur_durum; ?>
			<div class="clear"></div>
		</div>
	</div>
<?php } ?>
<?php
if($eklenti_baslik_goster)
{
	echo '</div>' . "\n";
	echo '<div class="modul_alt"></div>' . "\n";
}
?>
</div>