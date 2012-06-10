<?php $this->load->view('yonetim/header_view'); ?>
<div class="box">
	<br>
	<div class="left"></div>	
  	<div class="right"></div>
  	<div class="heading">
    	<h1 style="background-image: url('<?php echo yonetim_resim(); ?>mail.png');">Çağrı Detayı</h1>
    	<!--
    	<div class="buttons">
    		<a onclick="$('#form').submit();" class="buton"><span><?php echo lang('button_save'); ?></span></a>
    		<a onclick="location = '';" class="buton"><span><?php echo lang('button_cancel'); ?></span></a>
		</div>
		-->
  	</div>
<div class="content">
	<table class="form">
 		  <tr>
			<td>Mesaj Durum</td>
			<td><?php echo ($soru->ticket_flag == 2) ? 'Ticket Kapalı' : 'Ticket Açık';?> </td>
          </tr>  
		  <tr>
			<td>Gönderen Kullanıcı</td>
			<td><?php echo user_ide_inf($soru->user_id)->row()->ide_adi . ' ' . user_ide_inf($soru->user_id)->row()->ide_soy;?></td>
          </tr>
          <tr>
			<td>Gönderim Tarihi</td>
			<td><?php echo date('m.d.Y H:i',$soru->ticket_tarih);?></td>
          </tr>
          <tr>
			<td>Konu</td>
			<td><?php echo $soru->ticket_konu;?></td>
          </tr>
          <tr>
			<td>Mesaj</td>
			<td><?php echo $soru->ticket_icerik ;?></td>
          </tr>
          <tr>
          	<td align="center" colspan="2"><strong>Ticket Geçmişi</strong></td>
          </tr>
    </table>
	<table class="list">
		<thead>
		<tr>
			<td  width="200" height="30">İlgili Kişi</td>
			<td>Mesajı</td>
			<td width="150" >Tarih - Saat</td>
		</tr>
		</thead>
		<tbody>
		<?php 
		foreach($yazismalar->result() as $i => $r)
		{
			if($r->ticket_tip == 'soru')
			{
				$style = 'style="background:#fff"';
			}else{
				$style = 'style="background:#f6f6f6"';
			}
		?>
		<tr <?php echo $style;?> >
			<td height="50">
				<?php echo user_ide_inf($r->user_id)->row()->ide_adi .' '.user_ide_inf($r->user_id)->row()->ide_soy;?>
			</td>
			<td><?php echo $r->ticket_icerik;?></td>
			<td><?php echo date('m.d.Y - H:i',$r->ticket_tarih);?></td>
		</tr>
		<?php }?>
		</tbody>
	</table>

	<table class="form">
		<tr>
 			<td align="left">
 			<div style="display:none;" id="mesaj_kutu">
 				<form action="<?php echo current_url(); ?>" method="post">
					<input type="hidden" name="id" id="id" value="<?php echo $this->uri->segment(5);?>">
					<textarea name="txt_mesaj" style="width:100%; height:200px; margin-bottom:5px;" id="txt_mesaj"></textarea>
						<div class="buttons">
							<a class="buton" onclick="$('form').submit();"><span>Gönder</span></a>
						</div>
				</form>
			</div>
			</td>
		</tr>
		<tr>
			<td align="right" style="float:right;">
				<div>
					<?php 
					if($soru->ticket_flag != 2){
					?>
						<div style="float:right;">[ <a href="<?php echo yonetim_url('cagri/cevap_yaz/arsive_ekle/'. $soru->ticket_id); ?>?geri_don=<?php echo $this->input->get("geri_don");?>">Arşive Kaldır</a> ]</div>
					<?php 
					} else {
					?>
						<div style="float:right;">[ <a href="<?php echo yonetim_url('cagri/cevap_yaz/arsivden_cikart/'. $soru->ticket_id); ?>?geri_don=<?php echo $this->input->get("geri_don");?>">Arşiveden Çıkart</a> ]</div>
					<?php 	
					}
					?>

					<?php 
					if($soru->ticket_flag != 2){
					?>
					<div <?php echo ($this->uri->segment(3) == 'ticket_oku') ? 'style="display:none;"' : '';?> id="cevap_yaz">[ <a href="javascript:void(0);" onclick="$('#mesaj_kutu, #cevap_kapat').css('display','block'); $('#cevap_yaz').css('display','none');">Cevap Yaz</a> ]</div> 
					<div id="cevap_kapat" style="display:none;">[ <a href="javascript:void(0);" onclick="$('#mesaj_kutu, #cevap_kapat').css('display','none'); $('#cevap_yaz').css('display','block'); $('#txt_mesaj').attr('value','');">Cevap Kapat</a> ]</div> 
					<?php }?>
				</div>
			</td>
        </tr>
	</table>
</div>
<script type="text/javascript">
<!--
$('#tabs a').tabs();
$('#languages a').tabs();
-->
</script>
<?php $this->load->view('yonetim/footer_view'); ?>