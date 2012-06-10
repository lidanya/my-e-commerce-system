<div class="kutu" style="position:relative;z-index:996;">
<?php
if($eklenti_baslik_goster)
{
	echo '<div class="modul_ust">'. $eklenti_baslik .'</div>' . "\n";
	echo '<div class="modul_ic">' . "\n";
}
?>
<?php if(eklenti_ayar('urun_arama', 'tip') == '1' || eklenti_ayar('urun_arama', 'tip') == NULL) { ?>
	<form action="<?php echo site_url('urun/arama/index'); ?>" method="get">
		<input type="hidden" name="kategori" id="kategori_kriter2" value="0" />
		<div id="y_urun_arama_cont">
			<div id="y_arama_text"><input id="arama_box2" name="aranan" type="text" value="<?php echo lang('header_search_input'); ?>" onclick="if(this.value==this.defaultValue){this.value=''}" onblur="if(this.value==''){this.value=this.defaultValue}" /></div>
			<div id="y_arama_select_text" class="sola">
				<div id="y_arama_kategoriler">
					<ul>
						<li><a href="javascript:;" kategori="0"><?php echo lang('header_search_category_select'); ?></a></li>
						<?php 
						$urun_kategori = urun_ana_kategori();
						if($urun_kategori){
							foreach($urun_kategori as $kategori) 
							{
						?>
							<li><a href="javascript:;" kategori="<?php echo $kategori['urun_kat_id'];?>"><?php echo $kategori['urun_kat_adi'];?></a></li>
						<?php 
							}
						}
						?>
					</ul>
				</div>
				<input id="kategori_box2" name="kategori_box" type="text" value="<?php echo lang('header_search_category_select'); ?>" />
			</div>
			<a id="y_arama_select" href="javascript:;" class="sola"></a>
			<div id="y_arama_ara" class="sola"><input type="image" src="<?php echo site_resim(); ?>btn_yan_ara_normal.png" onmouseover="this.src='<?php echo site_resim(); ?>btn_yan_ara_hover.png'" onmouseout="this.src='<?php echo site_resim(); ?>btn_yan_ara_normal.png'" /></div>
			<div class="clear"></div>
		</div>
	</form>
<?php } ?>
<?php
if($eklenti_baslik_goster)
{
	echo '</div>' . "\n";
	echo '<div class="modul_alt"></div>' . "\n";
}
?>
</div>