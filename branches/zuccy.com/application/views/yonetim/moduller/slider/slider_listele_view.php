<?php $this->load->view('yonetim/header_view'); ?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
    <h1 style="background-image: url('<?php echo yonetim_resim(); ?>category.png');">Slider</h1>
		<div class="buttons"><a onclick="location = '<?php echo yonetim_url('moduller/slider/ekle'); ?>'" class="buton"><span>Ekle</span></a> <a onclick="$('form').submit();" class="buton"><span>Sil</span></a></div>
	</div>
	<div class="content">
    <form action="<?php echo yonetim_url('moduller/slider/sil'); ?>" mesaj="Seçili sliderları silmek istediğinizden emin misiniz?" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left">Slider</td>
            <td class="left">Slider Link</td>
            <td class="left">Sırası</td>
            <td class="left">Durum</td>
            <td class="right">İşlemler</td>
          </tr>
        </thead>
        <tbody>
		<?php if ($sliderlar) { ?>
			<?php foreach ($sliderlar as $slider) { ?>
			<tr>
				<td style="text-align: center;">
				<?php if ($slider['selected']) { ?>
					<input type="checkbox" name="selected[]" value="<?php echo $slider['slider_id']; ?>" checked="checked" />
				<?php } else { ?>
					<input type="checkbox" name="selected[]" value="<?php echo $slider['slider_id']; ?>" />
				<?php } ?>
				</td>
				<td class="left">
					<?php
						if($slider['slider_img'])
						{
							if(file_exists(DIR_IMAGE . $slider['slider_img']))
							{
								echo '<img src="'. $this->image_model->resize($slider['slider_img'], 200, 50) .'" alt="Slider" />';
							} else {
								echo 'Slider Resmi Yok';
							}
						} else {
							echo 'Slider Resmi Yok';
						}
					?>
				</td>
				<td class="left">
					<?php echo $slider['slider_link']; ?>
				</td>
				<td class="left">
					<?php echo $slider['slider_sira']; ?>
				</td>
				<td class="left">
					<?php echo ($slider['slider_flag'] == '1') ? 'Açık' : 'Kapalı'; ?>
				</td>
				<td class="right">
				<?php foreach ($slider['action'] as $action) { ?>
					[ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
				<?php } ?>
				</td>
			<?php } ?>
		<?php } else { ?>
			<tr>
				<td class="center" colspan="5">Gösterilecek sonuç yok!</td>
			</tr>
		<?php } ?>
        </tbody>
      </table>
    </form>
    Resim boyutları 200-500 KB aralığında olmalıdır.
    <?php
		echo $this->pagination->create_links(); 
	?>
  </div>
</div>

<?php $this->load->view('yonetim/footer_view');  ?>