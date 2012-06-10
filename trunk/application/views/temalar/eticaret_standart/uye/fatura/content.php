<div id="orta" class="sola">
	<h1 id="sayfa_baslik"><?php echo lang('messages_member_billing_title'); ?></h1>
	<div style="margin: 20px auto;width:700px;" class="f_bilgi"><?php echo lang('messages_member_billing_message'); ?></div>
	<div id="fatura">
		<!-- Listele -->
		<?php 
		if($faturalarim->num_rows() > 0)
		{
		?>
			<!--header-->
			<div class="f_oge f_oge_baslik">
				<?php /*<span class="f_tablo01 sola">Varsayılan</span>*/ ?>
				<span class="f_tablo02 sola"><?php echo lang('messages_member_billing_name'); ?></span>
				<span class="f_tablo01 sola">&nbsp;</span>
				<span class="f_tablo03 sola"><?php echo lang('messages_member_billing_operation'); ?></span>
			</div>
			<!--header son-->
			
			<!--body-->
			<?php
			foreach($faturalarim->result() as $i => $r):
	
				$class = 'f_oge';
				if(($i+1) % 2 == 0)
				{
					$class = 'f_oge f_gri';
				}
				
				$default_link = ssl_url('uye/fatura/varsayilan/'.$r->inv_id);
				$default_img  = site_resim().'f_none.png';
				if($r->inv_flag == 2 || $r->inv_flag == 4)
				{
					$default_link = ssl_url('uye/fatura/varsayilan/'.$r->inv_id);
					$default_img  = site_resim().'f_yes.png';
				}
			?>
				<div class="<?php echo $class; ?>">
					<?php /*<span class="f_tablo01 sola">
						<a href="<?php echo $default_link; ?>">
							<img src="<?php echo $default_img; ?>" alt="normal" title="Varsayılan Fatura Bilgisi Yap" />
						</a>
					</span>*/ ?>
					<span class="f_tablo02 sola">
						<a class="sitelink2" href="<?php echo ssl_url('uye/fatura/goruntule/'. $r->inv_id); ?>" title="<?php echo lang('messages_member_billing_view'); ?>"><?php echo $r->inv_name;?></a>
					</span>

					<span class="f_tablo01 sola">
						&nbsp;
					</span>

					<span class="f_tablo03 sola">
						<a class="sitelink2" href="<?php echo ssl_url('uye/fatura/goruntule/'. $r->inv_id); ?>"><img src="<?php echo site_resim()?>f_detail.png" alt="göster" title="<?php echo lang('messages_member_billing_view'); ?>" /></a> &nbsp; 
						<?php 
						/*if($r->inv_flag == '1')
							{
						?>
						<a class="sitelink2" href="<?php echo ssl_url('uye/fatura/duzenle/'. $r->inv_id); ?>"><img src="<?php echo site_resim()?>f_edit.png" alt="düzenle" title="Düzenle" /></a>
						<?php
							} else {
						?>
							<a class="sitelink2" href="javascript:;"><img src="<?php echo site_resim()?>f_edit_p.png" alt="düzenle" title="Bu Fatura Bilgisi İşlem Gördüğü İçin Düzenlenemez" /></a>
						<?php
						}*/
						?>
					</span>
					
				</div>
			<?php
			endforeach;
			?>
			<!--body son-->
		<?php } else {?>
			<div class="f_oge f_oge_baslik" style="width:100%; height:30px;text-align:center; padding-top:5px;">
				<span class="f_tablo01">Kayıtlı Faturanız Bulunmamaktadır.</span>
			</div>
		<?php }?>

		<?php /*?>
		<p style="text-align:right;width:700px;margin:auto;margin-top:10px;">
			<a class="butonum" href="<?php echo ssl_url('uye/fatura/ekle'); ?>">
				<span class="butsol"></span>
				<span class="butor">Yeni Fatura Bilgisi Ekle</span>
				<span class="butsag"></span>
			</a>
		</p>
		<!-- Listele SON -->
		*/ ?>
</div>
</div>