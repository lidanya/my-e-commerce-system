<div id="sol" class="sola">
<?php
	$this->moduller->modul_cagir('sol');
?>
<?php
    $fiyat_filtre = array(
        "50 TL ve altı",
        "50 TL-100 TL",
        "100 TL-200 TL",
        "200 TL-300 TL",
        "300 TL-400 TL",
        "400 TL-500 TL",
        "500 TL-1000 TL",
        "1000 TL ve üzeri"
    );

    $teslimat_filtre = array(
        "Aynı gün kargo",
        "1-3 iş günü",
        "1 hafta ve üzeri"
    );

?>
    <form action="<?php echo current_url(); ?>" method="get" id="search_filtre">

        <?php if(isset($markalarimiz["query"]) && isset($markalarimiz)) { ?>
            <?php  $marka_get= $this->input->get("manufacturer"); ?>
            <div class="left_filter">
                <h3>Markalar</h3>
                <ul>
                    <?php foreach($markalarimiz["query"] as $marka) { ?>
                        <li><input <?php echo isset($marka_get) && is_array($marka_get) && in_array($marka->manufacturer_id,$marka_get) ? "checked" : ""; ?> onclick="$('#search_filtre').submit();" type="checkbox" id="manufacturer_<?php echo $marka->manufacturer_id; ?>" value="<?php echo $marka->manufacturer_id; ?>" name="manufacturer[]" /><span><?php echo $marka->name; ?></span></li>
                    <? } ?>
                </ul>
            </div>
        <? } ?>

        <?php if(isset($sub_category) && $sub_category) { ?>
            <?php  $cat_get= $this->input->get("sub_category"); ?>
            <div class="left_filter">
                <h3>Kategoriler</h3>
                <ul>
                    <?php foreach($sub_category as $category) { ?>
                    <li><input <?php echo is_array($cat_get) && in_array($category->category_id,$cat_get) ? "checked" : ""; ?> onclick="$('#search_filtre').submit();" type="checkbox" id="cat_<?php echo $category->category_id; ?>" value="<?php echo $category->category_id; ?>" name="sub_category[]" /><span><?php echo $category->name; ?></span></li>
                    <? } ?>
                </ul>
            </div>
        <? } ?>

        <?php if(isset($sub_category)) { ?>
            <?php $fiyat_get = (int)$this->input->get("fiyat"); ?>
            <div class="left_filter">
                <h3>Fiyat</h3>
                <ul>
                    <?php foreach($fiyat_filtre as $key =>$fiyat) { ?>
                        <li><input <?php echo isset($fiyat_get) && ($fiyat_get == $key+1) ? "checked" : "";?> onclick="$('#search_filtre').submit();" type="radio" id="fiyat_<?php echo $key+1; ?>" value="<?php echo $key+1; ?>" name="fiyat" /><span><?php echo $fiyat; ?></span></li>
                    <? } ?>
                </ul>
            </div>
        <? } ?>

        <?php if(isset($sub_category)) { ?>
            <?php $teslimat_get = (int)$this->input->get("teslimat"); ?>
            <div class="left_filter">
                <h3>Teslimat</h3>
                <ul>
                    <?php foreach($teslimat_filtre as $key =>$teslimat) { ?>
                        <li><input <?php echo isset($teslimat_get) && ($teslimat_get == $key+1) ? "checked" : "";?> onclick="$('#search_filtre').submit();" type="radio" id="teslimat_<?php echo $key+1; ?>" value="<?php echo $key+1; ?>" name="teslimat" /><span><?php echo $teslimat; ?></span></li>
                    <? } ?>
                </ul>
            </div>
        <? } ?>
     </form>
</div>

<style>
    .left_filter { margin-top: 10px;}
    .left_filter h3 { margin-bottom: 5px;}
    .left_filter input { border: 1px solid #ccc;}
    .left_filter li { padding: 5px 0;}
    .left_filter li span { padding-left: 5px; position: relative; bottom: 2px;}

</style>