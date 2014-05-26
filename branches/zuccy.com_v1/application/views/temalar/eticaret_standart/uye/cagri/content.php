<div id="orta" class="sola">
	<h1 id="sayfa_baslik"><?php echo lang('messages_member_tickets_title'); ?></h1>
	<!--Listele -->
	<!-- Navigasyon -->
		<div id="t_navigasyon">
			<div class="sola">
				<a href="<?php echo site_url('site/iletisim'); ?>#iletisim_form" class="butonum">
					<span class="butsol"></span>
					<span class="butor"><?php echo lang('messages_member_tickets_send'); ?></span>
					<span class="butsag"></span>
				</a>	
			</div>
			<div class="liste_sag saga">
				<!--Sayfalama varsa baş-->
			    <?php
			    	echo $this->daynex_pagination->create_links(); 
			    ?>
			    <!--Sayfalama varsa son-->
			</div>
		</div>
	<!-- Navigasyon -->
		<?php if($cagrilar->num_rows()) { ?>
			<div class="t_oge f_oge_baslik" style="margin-top:10px;">
				<span class="t_tablo01 sola"><?php echo lang('messages_member_tickets_code'); ?></span>
				<span class="t_tablo02 sola"><?php echo lang('messages_member_tickets_subject_title'); ?></span>
				<span class="t_tablo03 sola" style="font-size:13px;"><?php echo lang('messages_member_tickets_date'); ?></span>
				<span class="t_tablo04 sola"><?php echo lang('messages_member_tickets_status'); ?></span>
			</div>
		<?php } else { ?>
			<span class="t_tablo01" style="display:block;width:100%;text-align:center;">Henüz çağrınız bulunmamaktadır.</span>
		<?php } ?>
<?php
	if($cagrilar->num_rows()) {
		foreach($cagrilar->result() as $i => $r) {
			if(($i+1)%2 == 0) {
				$class = "t_oge f_gri";
			} else {
				$class = "t_oge";
			}
?>
		<div class="<?php echo $class;?>">
			<span class="t_tablo01 sola"><a class="sitelink2" href="<?php echo ssl_url('uye/cagri/cagri_goster/'. $r->ticket_id); ?>"><?php echo $r->ticket_kodu;?></a></span>
			<span class="t_tablo02 sola"><a class="sitelink2" href="<?php echo ssl_url('uye/cagri/cagri_goster/'. $r->ticket_id); ?>"><?php echo $r->ticket_konu;?></a></span>
			<span class="t_tablo03 sola"><?php echo standard_date('DATE_TR', $r->ticket_tarih, get_language('code')); ?></span>
			<?php 
				if($r->ticket_flag == 2) {
					$this->db->where('ticket_prm_id',$r->ticket_id);
					$this->db->where('ticket_tip','cevap');
					$this->db->where('ticket_uye_durum',1);
					$this->db->where('user_id', $this->dx_auth->get_user_id());
					$tck = $this->db->get('ticket');
					if($tck->num_rows()) {
				?>
					<span class="t_tablo04 sola"><img src="<?php echo site_resim();?>ticket_kapali_var.png" alt="<?php echo lang('messages_member_tickets_new_posts_ticket_closed'); ?>" title="<?php echo lang('messages_member_tickets_new_posts_ticket_closed'); ?>"/></span>
				<?php } else { ?>
					<span class="t_tablo04 sola"><img src="<?php echo site_resim();?>ticket_kapali_yok.png" alt="<?php echo lang('messages_member_tickets_no_new_posts_ticket_closed'); ?>" title="<?php echo lang('messages_member_tickets_no_new_posts_ticket_closed'); ?>"/></span>
			<?php 
				}
			?>
	
			<?php
				} else {
					$this->db->where('ticket_prm_id',$r->ticket_id);
					$this->db->where('ticket_uye_durum',1);
					$this->db->where('ticket_tip','cevap');
					$this->db->where('user_id', $this->dx_auth->get_user_id());
					$tck = $this->db->get('ticket');
					if($tck->num_rows()) {
				?>
					<span class="t_tablo04 sola"><img src="<?php echo site_resim();?>ticket_acik_var.png" alt="<?php echo lang('messages_member_tickets_new_posts_ticket_open'); ?>" title="<?php echo lang('messages_member_tickets_new_posts_ticket_open'); ?>"/></span>
				<?php } else { ?>
					<span class="t_tablo04 sola"><img src="<?php echo site_resim();?>ticket_acik_yok.png" alt="<?php echo lang('messages_member_tickets_no_new_posts_ticket_open'); ?>" title="<?php echo lang('messages_member_tickets_no_new_posts_ticket_open'); ?>"/></span>
			<?php 
					}
				}
			?>
		</div>
<?php
		}
	}
?>
</div>