<?php 
$this->load->view('yonetim/header_view'); 
if ($this->uri->segment(5)){ $sort_lnk = $this->uri->segment(5); } else {$sort_lnk='ticket_desc';}
if ($this->uri->segment(6)){ $filt_lnk = $this->uri->segment(6); } else {$filt_lnk='durum|]';}
if ($this->uri->segment(7)){ $page_lnk = $this->uri->segment(7); } else {$page_lnk='0';}
if (($sort_lnk=='ok') or ($sort_lnk=='not')) {$sort_lnk='ticket_asc';}

$sort_lnk_e = explode('_',$sort_lnk);
$sort  = $sort_lnk_e[0];
$order = $sort_lnk_e[1];
$filter_e = explode(']',$filt_lnk);

for($i=0;$i<count($filter_e);$i++)
{
	if ($filter_e[$i])
	{
		$filter_d = explode('|',$filter_e[$i]);
		$filter_field  = $filter_d[0];
		$filter_data   = $filter_d[1];
		if (($filter_field) and ($filter_data))
		{
			${'filter_' . $filter_field} = $filter_data;
		}
	}
}

if ($this->uri->segment(5)=='ok'){
?>
<div class="success">Başarılı Bir</div>
<?php
} 
if ($order){
if ($order=='asc'){$order_lnk='desc';} else if ($order=='desc'){$order_lnk='asc';}

} else {
	$order_lnk = 'desc';		
	$order = 'asc';
}

$link = yonetim_url('cagri/cevapbekleyen/listele/'.$sort.'_'.$order_lnk.'/'.$filt_lnk.'/'.$page_lnk);
?>
<div class="box">
	<br>
	<div class="left"></div>	
  	<div class="right"></div>

  	<div class="heading">
    	<h1 style="background-image: url('<?php echo yonetim_resim(); ?>mail.png');">Cevap Bekleyen Çağrılar</h1>
    	<!--
    	<div class="buttons">
    		<a onclick="$('#form').submit();" class="buton"><span><?php echo lang('button_save'); ?></span></a>
    		<a onclick="location = '';" class="buton"><span><?php echo lang('button_cancel'); ?></span></a>
		</div>
		-->
  	</div>
	<div class="content">
<form method="post" enctype="multipart/form-data" id="form">
	<table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'select2\']').attr('checked', this.checked);"></td>
            <td class="center"><a href="<?php echo $link;?>" class="<?php echo $order;?>">ID</a></td>
            <td class="left">Kimden</td>
            <td class="left">Konu</td>
            <td class="left">Durum</td>
			<td class="right" width="100">Tarih</td>
            <td class="right">Eylem</td>
          </tr>
        </thead>
  		<tbody>
          <tr class="filter">
            <td></td>
            <td></td>
            <td><input type="text" value="<?php echo (!empty($filter_kimden)) ? $filter_kimden:NULL; ?>" name="filter_kimden"></td>
            <td><input type="text" value="<?php echo (!empty($filter_konu)) ? $filter_konu:NULL; ?>" name="filter_konu"></td>
            <td align="right">
            	<select name="filter_durum">
					<option value="T">*</option>
					<option value="2" <?php echo (!empty($filter_durum) && $filter_durum == 2) ? 'selected="selected"' : ''; ?>>Okunmuş Ticketlar</option>
					<option value="1" <?php echo (!empty($filter_durum) && $filter_durum == 1) ? 'selected="selected"' : ''; ?>>Okunmamış Ticketlar</option>
				</select>
            </td>
            <td class="center"><input type="text" value="<?php echo (!empty($filter_tarih)) ? $filter_tarih:NULL; ?>" name="filter_tarih" id="date_created" style="width:80px;" /></td>
            <td align="right"><a class="buton" onclick="filter('1');"><span>Filtre</span></a></td>
          </tr>
         <?php 
         foreach($cevaplanmis->result() as $i => $r):
         ?>
          <tr>
            <td style="text-align: center;"><input type="checkbox" value="<?php echo $r->ticket_id;?>" name="select2[]" id="select2"></td>
            <td class="center"  width="90"><?php echo $r->ticket_id;?></td>
            <td class="left"><?php echo user_ide_inf($r->user_id)->row()->ide_adi .' '. user_ide_inf($r->user_id)->row()->ide_soy;?></td>
            <td class="left"><a href="<?php echo yonetim_url('cagri/cevap_yaz/index/'. $r->ticket_id); ?>?geri_don=<?php echo yonetim_url('cagri/cevapbekleyen/listele'); ?>"><?php echo $r->ticket_konu;?></a></td>
			<td class="right"><?php echo ($r->ticket_adm_durum == 2) ? 'Okunmuş' : 'Okunmamış';?></td>
            <td class="center"><?php echo date('Y.m.d',$r->ticket_tarih);?></td>
            <td class="right"> [ <a href="<?php echo yonetim_url('cagri/cevap_yaz/index/'. $r->ticket_id); ?>?geri_don=<?php echo yonetim_url('cagri/cevapbekleyen/listele'); ?>">Cevap Yaz</a> ]</td>
          </tr>
          <?php 
          endforeach;
          ?>
         <tr class="filter">
         	<td align="right"><img width="28" src="<?php echo yonetim_resim();?>okokok.png"/></td>
         	<td colspan="6">  <a href="javascript:void(0);" onclick="$('input[name*=\'select2\']').attr('checked', true);">Tümünü Seç</a> &nbsp;/&nbsp; <a href="javascript:void(0);" onclick="$('input[name*=\'select2\']').attr('checked', false);">Tümünü Kaldır</a>
         		<select name="eylem2" onchange="coklu_islem('select2',this.value);">
         			<option value="0">*</option>
	     			<option value="1">Arşivden Ekle</option>	
         		</select>
         	</td>
         </tr>
         </tbody>
      </table>

</form>
    <?php
    echo $this->pagination->create_links(); 
    ?>
	</div>
</div>
<script type="text/javascript">
<!--
function filter(islem) {

	if(islem == 1)
	{
		url = "<?php echo yonetim_url('cagri/cevapbekleyen/listele/'. $sort_lnk); ?>/";
	}
	var filter_kimden = $('input[name=\'filter_kimden\']').attr('value');

	if (filter_kimden) {
		url += 'kimden|' + encodeURIComponent(filter_kimden) + ']';
	}
	
	var filter_konu = $('input[name=\'filter_konu\']').attr('value');
	if (filter_konu) {
		url += 'konu|' + encodeURIComponent(filter_konu) + ']';
	}

	url += 'durum|' + encodeURIComponent($('select[name=\'filter_durum\']').attr('value')) + ']';
	var filter_tarih = $('input[name=\'filter_tarih\']').attr('value');
	if (filter_tarih) {
		url += 'tarih|' + encodeURIComponent(filter_tarih) + ']';
	}	
	url +=  '/0';
	location = url;
}

function coklu_islem(islem,val)
{
	var toplam =  $("#"+islem+":checked").length ;
	if(toplam > 0)
	{
		if(islem == 'select2' && val != 0)
		{
			$('#form').attr('action','<?php echo yonetim_url("cagri/cevapbekleyen/arsive_ekle"); ?>').submit();
		}
	}else{
		alert('İşlemi gerçekleştirmek için lütfen çağrı seçiniz');
	}
}

$('#tabs a').tabs();
-->
</script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date_created').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<?php $this->load->view('yonetim/footer_view'); ?>