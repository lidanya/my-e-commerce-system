<?php
if($eklenti_baslik_goster)
{
	echo '<div class="baslik" >'. $eklenti_baslik .'</div>' . "\n";
}
?>
<?php if(eklenti_ayar('anasayfa_vitrini', 'tip') == '1' || eklenti_ayar('anasayfa_vitrini', 'tip') == NULL) { ?>
	<?php
		$anasayfa_vitrini_sorgu = $this->eklentiler_anasayfa_vitrini_model->anasayfa_vitrin_listele();
		$anasayfa_vitrini_editor_sorgu = $this->eklentiler_anasayfa_vitrini_model->anasayfa_vitrin_listele_editorun_secimi();
        $anasayfa_vitrini_cok_satan_sorgu = $this->eklentiler_anasayfa_vitrini_model->anasayfa_vitrin_listele_cok_satan();
		if($anasayfa_vitrini_sorgu['toplam'] > 0) {
			$i = 0;
	?>
		<!--Ürün Listele-->
       <div id="tab_yeni_geldi">
		<div class="liste_container" style="margin-top:0px;">
		<?php
			$sablon_gonder = new stdClass();
			foreach($anasayfa_vitrini_sorgu['sorgu']->result() as $anasayfa_vitrini)
			{
				$i = $i+1;
				$sablon_gonder->product_id		= $anasayfa_vitrini->product_id;
				$sablon_gonder->model			= $anasayfa_vitrini->model;
				$sablon_gonder->name			= $anasayfa_vitrini->name;
				$sablon_gonder->new_product		= $anasayfa_vitrini->new_product;
				$sablon_gonder->quantity		= $anasayfa_vitrini->quantity;
				$sablon_gonder->seo				= $anasayfa_vitrini->seo;
				$sablon_gonder->image			= $anasayfa_vitrini->image;

				$this->product_model->stock_shema($sablon_gonder, 'normal_liste');
			}

			if($i == '6')
			{
				$i = 0;
				echo '<div class="clear"></div>';
			}
		?>
		</div>
       </div>
		<!--Ürün Listele Son-->
	<?php } ?>
    <?php
    if($anasayfa_vitrini_editor_sorgu['toplam'] > 0) {
    $i = 0;
    ?>
    <!--Ürün Listele-->
    <div id="tab_editor" style="display: none;">
    <div class="liste_container" style="margin-top:0px;">
        <?php
        $sablon_gonder = new stdClass();
        foreach($anasayfa_vitrini_editor_sorgu['sorgu']->result() as $anasayfa_vitrini)
        {
            $i = $i+1;
            $sablon_gonder->product_id		= $anasayfa_vitrini->product_id;
            $sablon_gonder->model			= $anasayfa_vitrini->model;
            $sablon_gonder->name			= $anasayfa_vitrini->name;
            $sablon_gonder->new_product		= $anasayfa_vitrini->new_product;
            $sablon_gonder->quantity		= $anasayfa_vitrini->quantity;
            $sablon_gonder->seo				= $anasayfa_vitrini->seo;
            $sablon_gonder->image			= $anasayfa_vitrini->image;

            $this->product_model->stock_shema($sablon_gonder, 'normal_liste');
        }

        if($i == '6')
        {
            $i = 0;
            echo '<div class="clear"></div>';
        }
        ?>
    </div>
        </div>
    <!--Ürün Listele Son-->
<?php } ?>

    <?php
    if($anasayfa_vitrini_cok_satan_sorgu['toplam'] > 0) {
        $i = 0;
        ?>
        <!--Ürün Listele-->
        <div id="tab_cok_satan" style="display: none;">
            <div class="liste_container" style="margin-top:0px;">
                <?php
                $sablon_gonder = new stdClass();
                foreach($anasayfa_vitrini_cok_satan_sorgu['sorgu']->result() as $anasayfa_vitrini)
                {
                    $i = $i+1;
                    $sablon_gonder->product_id		= $anasayfa_vitrini->product_id;
                    $sablon_gonder->model			= $anasayfa_vitrini->model;
                    $sablon_gonder->name			= $anasayfa_vitrini->name;
                    $sablon_gonder->new_product		= $anasayfa_vitrini->new_product;
                    $sablon_gonder->quantity		= $anasayfa_vitrini->quantity;
                    $sablon_gonder->seo				= $anasayfa_vitrini->seo;
                    $sablon_gonder->image			= $anasayfa_vitrini->image;

                    $this->product_model->stock_shema($sablon_gonder, 'normal_liste');
                }

                if($i == '6')
                {
                    $i = 0;
                    echo '<div class="clear"></div>';
                }
                ?>
            </div>
        </div>
        <!--Ürün Listele Son-->
    <?php } ?>



<?php } ?>