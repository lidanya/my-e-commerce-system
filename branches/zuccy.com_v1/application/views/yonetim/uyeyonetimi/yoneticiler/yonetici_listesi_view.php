<?php
	$this->load->view('yonetim/header_view');

	if ($this->uri->segment(5)){ $sort_lnk = $this->uri->segment(5); } else {$sort_lnk='username_asc';}
	if ($this->uri->segment(6)){ $filt_lnk = $this->uri->segment(6); } else {$filt_lnk='username|]';}
	if ($this->uri->segment(7)){ $page_lnk = $this->uri->segment(7); } else {$page_lnk='0';}
	if (($sort_lnk=='ok') or ($sort_lnk=='not')) {$sort_lnk='username_asc';}
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
	if ($this->uri->segment(4)=='ok'){
		?>
		<div class="success">İşlem Başarılı</div>
		<?php
	}
	if ($order){
		if ($order=='asc'){$order_lnk='desc';} else if ($order=='desc'){$order_lnk='asc';}
	} else{
		$order_lnk = 'asc';
		$order = 'desc';
	}
?>
<?php 
/*if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php }
*/ 
?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('<?php echo yonetim_resim(); ?>customer.png');">Yöneticiler</h1>
    <?php /*
    <div class="butons">
    	<a onclick="$('form').attr('action', '<?php echo yonetim_url('uye_yonetimi/yoneticiler/sil'); ?>'); $('form').submit();" class="buton" style="margin-left:10px;margin-top:5px;float:right;"><span>Sil</span></a>
	</div> */?>
  </div>
  <div class="content">
    <form action="" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
		<thead>
			<tr>
				<td class="left">
				<?php if ($sort == 'username') { ?>
					<a href="<?php echo yonetim_url('uye_yonetimi/yoneticiler/listele/username_'. $order_lnk.'/'.$filt_lnk.'/'.$page_lnk); ?>" class="<?php echo strtolower($order); ?>">Müşteri Adı</a>
				<?php } else { ?>
					<a href="<?php echo yonetim_url('uye_yonetimi/yoneticiler/listele/username_'. $order_lnk.'/'.$filt_lnk.'/'.$page_lnk); ?>">Müşteri Adı</a>
				<?php } ?></td>
				<td class="left">
				<?php if ($sort == 'email') { ?>
					<a href="<?php echo yonetim_url('uye_yonetimi/yoneticiler/listele/email_'. $order_lnk.'/'.$filt_lnk.'/'.$page_lnk); ?>" class="<?php echo strtolower($order); ?>">E-Posta</a>
				<?php } else { ?>
					<a href="<?php echo yonetim_url('uye_yonetimi/yoneticiler/listele/email_'. $order_lnk.'/'.$filt_lnk.'/'.$page_lnk); ?>">E-Posta</a>
				<?php } ?></td>
				<td class="left">
				<?php if ($sort == 'roleid') { ?>
					<a href="<?php echo yonetim_url('uye_yonetimi/yoneticiler/listele/roleid_'. $order_lnk.'/'.$filt_lnk.'/'.$page_lnk); ?>" class="<?php echo strtolower($order); ?>">Müşteri Grubu</a>
				<?php } else { ?>
					<a href="<?php echo yonetim_url('uye_yonetimi/yoneticiler/listele/roleid_'. $order_lnk.'/'.$filt_lnk.'/'.$page_lnk); ?>">Müşteri Grubu</a>
				<?php } ?></td>
				<td class="left">
				<?php if ($sort == 'created') { ?>
					<a href="<?php echo yonetim_url('uye_yonetimi/yoneticiler/listele/created_'. $order_lnk.'/'.$filt_lnk.'/'.$page_lnk); ?>" class="<?php echo strtolower($order); ?>">Ekleme Tarihi</a>
				<?php } else { ?>
					<a href="<?php echo yonetim_url('uye_yonetimi/yoneticiler/listele/created_'. $order_lnk.'/'.$filt_lnk.'/'.$page_lnk); ?>">Ekleme Tarihi</a>
				<?php } ?>
				</td>
				<td class="right">İşlemler</td>
			</tr>
		</thead>
        <tbody>
          <tr class="filter">
            <td><input type="text" name="filter_name" value="<?php echo (!empty($filter_username)) ? $filter_username:NULL; ?>" /></td>
            <td><input type="text" name="filter_email" value="<?php echo (!empty($filter_email)) ? $filter_email:NULL; ?>" /></td>
            <td>
            	<select name="filter_customer_group_id">
	                <option value=""></option>
	                <?php foreach ($yonetici_gruplari as $yonetici_grup) { ?>
	                <?php if ($yonetici_grup->id == $filter_role_id) { ?>
	                <option value="<?php echo $yonetici_grup->id; ?>" selected="selected"><?php echo $yonetici_grup->name; ?></option>
	                <?php } else { ?>
	                <option value="<?php echo $yonetici_grup->id; ?>"><?php echo $yonetici_grup->name; ?></option>
	                <?php } ?>
	                <?php } ?>
              	</select>
              	
          	</td>            
            <td><input type="text" name="filter_date_added" value="<?php echo (!empty($filter_created)) ? $filter_created:NULL; ?>" size="12" id="date_created" /></td>
            <td align="right"><a onclick="filter();" class="buton"><span>Filtre</span></a></td>
          </tr>
          <?php if ($yoneticiler) { ?>
          <?php foreach ($yoneticiler as $yonetici) { ?>
          <tr>
            <td class="left"><?php echo $yonetici['ide_adi'] . ' ' . $yonetici['ide_soy'] ; ?> </td>
            <td class="left"><?php echo $yonetici['email']; ?> </td>
            <td class="left"><?php echo $yonetici['role_name']; ?> </td>
            <td class="left"><?php echo $yonetici['adddate'];?> </td>
            <td class="right">
            	<?php 
            	foreach($yonetici['action'] as $action)
            	{
            	?>
            		[ <a href="<?php echo $action['href'];?>"><?php echo $action['text'];?></a> ]
            	<?php 
            	}
            	?>
        	</td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="5">Gösterilecek sonuç yok!</td>
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
<script type="text/javascript"><!--
function filter() 
{
	url = '<?php echo yonetim_url(); ?>/uye_yonetimi/yoneticiler/listele/<?php echo $sort_lnk; ?>/';
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += 'username|' + encodeURIComponent(filter_name) + ']';
	}
	
	var filter_email = $('input[name=\'filter_email\']').attr('value');
	
	if (filter_email) {
		url += 'email|' + encodeURIComponent(filter_email) + ']';
	}
	
	url += 'role_id|' + encodeURIComponent($('select[name=\'filter_customer_group_id\']').attr('value')) + ']';
	
	var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
	
	if (filter_date_added) {
		url += 'created|' + encodeURIComponent(filter_date_added) + ']';
	}	

	url +=  '/0';
	location = url;
}
//--></script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date_created').datepicker({dateFormat: 'yy-mm-dd'});
	$('#form').keydown(function(e) {
		if (e.keyCode == 13) {
			filter();
		}
	});
});
//--></script>
<?php
	$this->load->view('yonetim/footer_view');
?>