<?php 
	$this->load->view('yonetim/header_view');
?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('<?php echo yonetim_resim(); ?>customer.png');"><?php echo $title; ?></h1>
	</div>
	<div class="content">
		<form method="post" enctype="multipart/form-data" id="form">
		<table class="list">
			<thead>
				<tr>
					<td class="left">
						<?php $_namesurname_url = yonetim_url('customer_management/customer/lists/namesurname-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_namesurname_url; ?>"<?php echo ($sort == 'namesurname') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Müşteri Adı</a>
					</td>
					<td class="left">
						<?php $_username_url = yonetim_url('customer_management/customer/lists/u.username-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_username_url; ?>"<?php echo ($sort == 'u.username') ? ' class="'. strtolower($order) .'"' : NULL; ?>>E-Posta</a>
					</td>
					<td class="center">
						<?php $_role_id_url = yonetim_url('customer_management/customer/lists/u.role_id-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_role_id_url; ?>"<?php echo ($sort == 'u.role_id') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Müşteri Grubu</a>
					</td>
					<td class="center">
						<?php $_last_ip_url = yonetim_url('customer_management/customer/lists/u.last_ip-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_last_ip_url; ?>"<?php echo ($sort == 'u.last_ip') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Son Giriş IP</a>
					</td>
					<td class="right">
						<?php $_last_login_url = yonetim_url('customer_management/customer/lists/u.last_login-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_last_login_url; ?>"<?php echo ($sort == 'u.last_login') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Son Giriş Tarihi</a>
					</td>
					<td class="right">
						<?php $_created_url = yonetim_url('customer_management/customer/lists/u.created-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_created_url; ?>"<?php echo ($sort == 'u.created') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Üyelik Tarihi</a>
					</td>
					<td class="right">
						<?php $_modified_url = yonetim_url('customer_management/customer/lists/u.modified-' . $order_link . '/' . $filt_link . '/' . $page_link); ?>
						<a href="<?php echo $_modified_url; ?>"<?php echo ($sort == 'u.modified') ? ' class="'. strtolower($order) .'"' : NULL; ?>>Güncelleme Tarihi</a>
					</td>
					<td class="right">İşlemler</td>
				</tr>
			</thead>
			<tbody>
				<tr class="filter">
					<td>
						<input type="text" name="filter_namesurname" value="<?php echo isset($filter_uii_namesurname) ? $filter_uii_namesurname : NULL; ?>" />
					</td>
					<td>
						<input type="text" name="filter_username" value="<?php echo isset($filter_u_username) ? $filter_u_username : NULL; ?>" />
					</td>
					<td class="center">
						<?php
							$_filter_customer_group = isset($filter_u_role_id) ? $filter_u_role_id : '';
							echo form_dropdown('filter_customer_group', $customer_groups, $_filter_customer_group);
						?>
					</td>
					<td class="center">
						<input type="text" name="filter_last_ip" value="<?php echo isset($filter_u_last_ip) ? $filter_u_last_ip : NULL; ?>" />
					</td>
					<td align="right">
						<input type="text" name="filter_last_login" class="date" value="<?php echo isset($filter_u_last_login) ? $filter_u_last_login : NULL; ?>" />
					</td>
					<td align="right">
						<input type="text" name="filter_created" class="date" value="<?php echo isset($filter_u_created) ? $filter_u_created : NULL; ?>" />
					</td>
					<td align="right">
						<input type="text" name="filter_modified" class="date" value="<?php echo isset($filter_u_modified) ? $filter_u_modified : NULL; ?>" />
					</td>
					<td align="right" style="width: 90px;">
						<a onclick="filter();" class="buton"><span>Filtre</span></a>
					</td>
				</tr>

				<?php 
				if ($customers) {
					foreach ($customers as $customer) 
					{ ?>
						<tr>
							<td class="left"><?php echo $customer['namesurname']; ?></td>
							<td class="left"><?php echo $customer['username']; ?></td>
							<td class="center"><?php echo $customer['role_name']; ?></td>
							<td class="center"><?php echo $customer['last_ip']; ?></td>
							<td class="right"><?php echo standard_date('DATE_TR', mysql_to_unix($customer['last_login']), 'tr'); ?></td>
							<td class="right"><?php echo standard_date('DATE_TR', mysql_to_unix($customer['last_login']), 'tr'); ?></td>
							<td class="right"><?php echo standard_date('DATE_TR', mysql_to_unix($customer['modified']), 'tr'); ?></td>
							<td class="right">
								<?php foreach ($customer['action'] as $action) { ?>
								[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
								<?php } ?>
							</td>
						</tr>
						<?php 
					}
				} else { ?>
				<tr>
					<td class="center" colspan="8"><?php echo $title; ?> Bulunamadı</td>
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
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});

	function filter() {
		url = '<?php echo yonetim_url(); ?>/customer_management/customer/lists/<?php echo $sort_link; ?>/';
		
		var filter_namesurname = $('input[name=\'filter_namesurname\']').attr('value');
		if (filter_namesurname) {
			url += 'uii.namesurname|' + encodeURIComponent(filter_namesurname) + ']';
		}

		var filter_username = $('input[name=\'filter_username\']').attr('value');
		if (filter_username) {
			url += 'u.username|' + encodeURIComponent(filter_username) + ']';
		}

		var filter_customer_group = $('select[name=\'filter_customer_group\']').attr('value');
		if (filter_customer_group) {
			url += 'u.role_id|' + encodeURIComponent(filter_customer_group) + ']';
		}

		var filter_last_ip = $('input[name=\'filter_last_ip\']').attr('value');
		if (filter_last_ip) {
			url += 'u.last_ip|' + encodeURIComponent(filter_last_ip) + ']';
		}

		var filter_last_login = $('input[name=\'filter_last_login\']').attr('value');
		if (filter_last_login) {
			url += 'u.last_login|' + encodeURIComponent(filter_last_login) + ']';
		}
	
		var filter_created = $('input[name=\'filter_created\']').attr('value');
		if (filter_created) {
			url += 'u.created|' + encodeURIComponent(filter_created) + ']';
		}
	
		var filter_modified = $('input[name=\'filter_modified\']').attr('value');
		if (filter_modified) {
			url += 'u.modified|' + encodeURIComponent(filter_modified) + ']';
		}

		url +=  '/0';
		document.location.href = url;
	}

	$('#form').keydown(function(e) {
		if (e.keyCode == 13) {
			filter();
		}
	});

//--></script>
<?php $this->load->view('yonetim/footer_view');   ?>