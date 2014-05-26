    <div id="orta" class="sola">
		<?php 
		if($cagri->num_rows()) {
			$cagrii = $cagri->row();
			$ticket_tipi = $cagrii->ticket_tip;
		?>

		<h1 id="sayfa_baslik"><?php echo lang('messages_member_tickets_title'); ?></h1>

		<div class="t_oge f_oge_baslik" style="padding:7px 0 0 20px;width:700px;height:23px;margin-top:10px;">
			<?php echo lang('messages_member_tickets_code'); ?> : <?php echo $cagrii->ticket_kodu;?>
		</div>
		<span class="ib_baslik sola"><?php echo lang('messages_member_tickets_subject_title'); ?> : </span>
		<span class="ib_yazi sola" style="width:500px;height:auto;"><?php echo $cagrii->ticket_konu;?></span>
		<div class="clear"></div>
		<span class="ib_baslik sola"><?php echo lang('messages_member_tickets_message'); ?> : </span>
		<span class="ib_yazi sola" style="width:500px;height:auto;text-align:justify;"><?php echo $cagrii->ticket_icerik;?></span>
		<div class="clear"></div>

		<?php 
			$this->db->order_by('ticket_id','asc');
			$yazismalar = $this->db->get_where('ticket',array('ticket_prm_id' => $cagrii->ticket_id));
			if($yazismalar->num_rows() > 0)
			{
		?>
			<div class="t_ara_baslik"><?php echo lang('messages_member_tickets_messages_history'); ?></div>
		<?php 
			} else {
		?>
			<div class="t_ara_baslik"><?php echo lang('messages_member_tickets_messages_no_history'); ?></div>
		<?php 
			}
		?>
		<?php
		if($yazismalar->num_rows() > 0)
		{
		?>
		<div id="t_yazismalar">
			<?php
				foreach($yazismalar->result() as $i => $r):
					if($r->ticket_tip == 'cevap' && $r->ticket_prm_id != 0) {
						$class = 't_oge f_gri';
						$resim = 'ticket_destek.png';
						$mesaj = lang('messages_member_tickets_answer');
					} else {
						$class = 't_oge';
						$resim = 'ticket_musteri.png';
						$mesaj = lang('messages_member_tickets_question');
					}
			?>
			<div class="<?php echo $class;?>">	
				<span class="ty_baslik sola"><img src="<?php echo site_resim(); ?><?php echo $resim;?>" alt="" /> <?php echo $mesaj;?> :</span>
				<span class="ty_mesaj sola"><?php echo nl2br($r->ticket_icerik); ?></span>
				<div class="clear"></div>
			</div>
			<?php 
			endforeach;
			?>
			<?php } ?>
			<div id="t_mesaj_yaz">
				<?php
					if($cagrii->ticket_flag != 2) {
				?>
				<form action="<?php echo ssl_url('uye/cagri/cagri_goster/' . $cagrii->ticket_id);?>" name="ticket_form" id="ticket_form" method="post">
					<input type="hidden" name="ticket_konu" id="ticket_konu" value="<?php echo $cagrii->ticket_konu; ?>"/>
					<input type="hidden" name="ticket_kodu" id="ticket_kodu" value="<?php echo $cagrii->ticket_kodu; ?>"/>
					<input type="hidden" name="kapat" id="kapat" value="1"/>
					<input type="hidden" name="ticket_tip" value="soru"/>
					<input type="hidden" name="ticket_prm" value="<?php echo $cagrii->ticket_id;?>"/>
				<!-- Ticket Aciksa -->	
				<span class="ib_baslik sola"><?php echo lang('messages_member_tickets_message'); ?> : </span>
				<span class="ib_yazi sola" style="width:500px;height:auto;"><textarea name="ticket_mesaj"></textarea></span>
				<div class="clear"></div>
				<span class="ib_baslik sola">&nbsp; </span>
				<span class="ib_yazi sola" style="width:500px;height:auto;">
					<a class="butonum sola" href="javascript:;" onclick="$('#ticket_form').submit();">
						<span class="butsol"></span>
						<span class="butor"><?php echo lang('messages_member_tickets_send_message_button'); ?></span>
						<span class="butsag"></span>
					</a>
					<a class="butonum saga" href="javascript:;" onclick="$('#kapat').attr('value','2'); $('#ticket_form').submit();">
						<span class="butsol"></span>
						<span class="butor"><?php echo lang('messages_member_tickets_close_ticket_button'); ?></span>
						<span class="butsag"></span>
					</a>
					<div class="clear"></div>
				</span>
				<div class="clear"></div>
				<?php
					if (form_error('ticket_mesaj')) {
						echo '<div style="line-height: 20px;color:red; font-size:12px; font-weight:normal; margin-left:160px;">'. form_error('ticket_mesaj') .'</div><div class="clear"></div>';
					}
				?>
				<!-- Ticket Aciksa SON-->
				<?php } else {?>
				<p align="center"><?php echo lang('messages_member_tickets_this_ticket_closed'); ?></p>
				<?php }?>
			</div>	
		</div>
		<?php } else {redirect('site/index');}?>
		<!-- Navigasyon -->
			<div id="t_navigasyon" class="saga">
				<a href="<?php echo site_url('site/iletisim#iletisim_form'); ?>" class="butonum">
					<span class="butsol"></span>
					<span class="butor"><?php echo lang('messages_member_tickets_send'); ?></span>
					<span class="butsag"></span>
				</a>
				<a onclick="history.back();" class="butonum">
					<span class="butsol"></span>
					<span class="butor"><?php echo lang('messages_button_back'); ?></span>
					<span class="butsag"></span>
				</a>	
			</div>
		<!-- Navigasyon -->
	</div>