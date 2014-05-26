<?php $uri = $this->uri->segment(2); ?>
<div id="sol" class="sola">
	<div id="alisveris_menu" style="margin-top: 10px;">
		<ul>
			<li><a href="javascript:;" rel="nofollow" <?php echo ($uri == 'adim_1') ? 'class="sm_aktif"':NULL; ?>><span class="sm_faturali">Fatura/Teslimat Bilgileri</span></a></li>
			<li><a href="javascript:;" rel="nofollow" <?php echo ($uri == 'adim_2') ? 'class="sm_aktif"':NULL; ?>><span class="sm_kargolu">Kargo Seçimi</span></a></li>
			<li><a href="javascript:;" rel="nofollow" <?php echo ($uri == 'adim_3') ? 'class="sm_aktif"':NULL; ?>><span class="sm_kredili">Ödeme Seçenekleri</span></a></li>
			<li><a href="javascript:;" rel="nofollow" <?php echo ($uri == 'adim_4') ? 'class="sm_aktif"':NULL; ?>><span class="sm_diger">Ödeme Detayları</span></a></li>
			<li><a href="javascript:;" rel="nofollow" <?php echo ($uri == 'adim_5') ? 'class="sm_aktif"':NULL; ?>><span class="sm_diger">Sipariş</span></a></li>
		</ul>
	</div>
</div>