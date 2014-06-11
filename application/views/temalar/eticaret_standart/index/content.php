<div id="orta" class="saga" >
<?php
	$this->moduller->modul_cagir('slider');
?>
</div>
<div class="clear"></div>
<div style="margin-top: 10px;">
    <div class="sola" style="width: 657px; height: 508px; margin-right: 10px;">
        <div class="sola" style="margin-right: 10px;"><a href="<?php echo site_url('mutfak--category'); ?>"><img src="<?php echo site_resim();?>mutfak.png" alt="" width="323" height="240" /></a></div>
        <div class="sola"><a href="<?php echo site_url('banyo--category'); ?>"><img src="<?php echo site_resim();?>banyo.png" alt="" width="323" height="240" /></a></div>
        <div class="sola" style="margin-right: 10px; margin-top: 15px;"><a href="<?php echo site_url('salon--category'); ?>"><img src="<?php echo site_resim();?>salon.png" alt="" width="323" height="240" /></a></div>
        <div class="sola" style="margin-top: 15px;"><a href="<?php echo site_url('yatak-odasi--category'); ?>"><img src="<?php echo site_resim();?>yatak_odasi.png" alt="" width="323" height="240" /></a></div>
    </div>
    <div class="sola">
        <div class="sola"><a href=""><img src="<?php echo site_resim();?>orta_sag.png" alt="" width="323" height="498" /></a></div>

    </div>
</div>
<div class="clear"></div>

<div id="anasayfa_urun_tablar">
    <a href="javascript:;" tab="#tab_yeni_geldi" class="sola u_aktif">
        <span class="u_yeni_geldi">YENİ GELDİ!</span>
    </a>
    <a href="javascript:;" tab="#tab_editor" class="sola">
        <span class="u_editor">EDİTÖRÜN SEÇİMİ</span>
    </a>
    <a href="javascript:;" tab="#tab_cok_satan" class="sola" style="background: none;">
        <span class="u_cok">ÇOK SATANLAR</span>
    </a>
    <div class="u_bosluk sola"></div>
    <div class="clear"></div>
</div>
<script type="text/javascript">
    <!--
    $.tabs('#anasayfa_urun_tablar a', 'u_aktif');
    //-->
</script>
<div style="width: 990px;">
    <?php
    $this->moduller->modul_cagir('anasayfa');
    ?>
</div>