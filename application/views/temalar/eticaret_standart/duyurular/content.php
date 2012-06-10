	<!--orta -->
	<div id="orta" class="sola">
		<h1 id="sayfa_baslik"><?php echo $duyuru->title; ?></h1>
		<?php
			$bul = array( 
				'{Daynex_Resim_Url}',
				'{Daynex_Js_Url}',
				'{Daynex_Site_Url}',
				'{Daynex_Flash_Url}',
			);
			$degis = array(
				site_resim(),
				site_js(),
				site_url(),
				site_flash(),
			);
			echo str_replace($bul, $degis, $duyuru->description);
		?>
		<br>
	</div>
	<!-- orta son-->