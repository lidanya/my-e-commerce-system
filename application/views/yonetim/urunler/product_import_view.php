<?php 
	$this->load->view('yonetim/header_view');
?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
    	<h1 style="background-image: url('<?php echo yonetim_resim();?>category.png');">Ürün İçeri Aktar</h1>
		<div class="buttons"><a onclick="$('#form').submit();" class="buton"><span>Kaydet</span></a><a onclick="location = '<?php echo yonetim_url('urunler/product/lists'); ?>';" class="buton" style="margin-left:10px;"><span>İptal</span></a></div>
	</div>
	<div class="content">
    	<form action="<?php echo yonetim_url('urunler/product_import'); ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="form">
				<?php
					$files = glob('./upload/csv/*.gz');
					if($files) {
						echo '<tr>';
						echo '<td>Yedekleriniz <span class="help">.csv aktarımı yapmadan önceki otomatik alınan yedekler.</span></td>';
						echo '<td>';
						foreach($files as $gz_files) {
							if(is_file($gz_files)) {
								$file_info = get_file_info($gz_files);
								echo debug($file_info);

								/*$filename = basename($gz_files);
								$filetime_ = explode('_', $filename);
								$filetime = $filetime_[0];
								$deletetime = $filetime + (86400 * 30);
								$filesize = filesize($gz_files);
								$filesize_ = byte_format($filesize);

								if(time() > $deletetime)
								{
									@unlink('./upload/csv/' . $filename);
								} else {
									echo '<div style="width:100px;height:110px;text-align:center;margin:5px 5px 5px 10px;float:left;">';
									echo '<div style="width:48px;height:48px;text-align:center;margin:auto;"><a href="'. site_url('upload/csv/' . $filename) .'" title="Yedeği İndir"><img src="'. yonetim_resim() .'download-database.png" alt="Veritabanı Yedeği" /></a></div>';
									echo '<div style="padding-top:3px;font-size:10px;color:green;">' . $filesize_ . '</div>';
									echo '<div style="font-size:10px;color:green;">Yedekleme Tarihi<br />' . date('d/m/Y H:i:s', $filetime) . '</div>';
									echo '<div style="padding-top:2px;font-size:10px;color:red;">Silineceği Tarih<br />' . date('d/m/Y H:i:s', $deletetime) . '</div>';
									echo '</div>';
								}*/
							}
						}
						echo '</td>';
						echo '</tr>';
					}
				?>
	            <tr>
	            	<td>
	            		<span class="required">*</span> Veriler
	            		<span class="help">.csv uzantılı dosya seçiniz</span>
            		</td>
	              	<td>
	              		<input type="file" name="csv_file" />
              		</td>
	            </tr>
			</table>
		</form>
	</div>
</div>
<?php $this->load->view('yonetim/footer_view');  ?>