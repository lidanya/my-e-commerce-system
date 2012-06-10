<?php 
$this->load->view('yonetim/header_view');  
	$val = $this->validation;
	
	$slider_link = array(
		'name' 		=> 'slider_link',
		'id' 		=> 'slider_link',
		'class' 	=> 'slider_link',
		'size'		=> '43',
		'value' 	=> ($val->slider_link) ? $val->slider_link:$slider_veri->slider_link
	);

	$slider_sira = array(
		'name' 		=> 'slider_sira',
		'id' 		=> 'slider_sira',
		'class' 	=> 'slider_sira',
		'size'		=> '43',
		'value' 	=> ($val->slider_sira) ? $val->slider_sira:$slider_veri->slider_sira
	);
	
	if ($val->error_string)
	{
	?>
		<div class="warning">Eklerken hata oluştu.</div>
	<?php 
	}
?>

<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('<?php echo yonetim_resim(); ?>category.png');">Slider</h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="buton"><span>Kaydet</span></a><a onclick="location = '<?php echo yonetim_url('moduller/slider'); ?>';" class="buton" style="margin-left:10px;"><span>İptal</span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo yonetim_url('moduller/slider/duzenle/' . $slider_veri->slider_id); ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
		<tr>
			<input type="hidden" name="slider_id" id="slider_id" value="<?php echo $slider_veri->slider_id; ?>">
			<td>
				Slider Resim<br />
				<span class="help">Slider eklemek için tıklayın. Slider'ın varsayılan boyutu genişlik 750px yükseklik 240px'dir. Yükleyeceğiniz resimler otomatik olarak bu boyutlardaki çerçeveye ortalanmış olarak sitede görüntülenecektir.</span>
			</td>
				<?php
				if($this->input->post('image'))
				{
					$preview			= $this->image_model->resize($this->input->post('image'), 100, 100);
					$preview_input 		= $this->input->post('image');
				} else {
					if($slider_veri->slider_img != '')
					{
						if(file_exists(DIR_IMAGE . $slider_veri->slider_img))
						{
							$preview			= $this->image_model->resize($slider_veri->slider_img, 100, 100);
							$preview_input 		= $slider_veri->slider_img;
						} else {
							$preview			= $this->image_model->resize('resim_ekle.jpg', 100, 100);
							$preview_input 		= 'resim_ekle.jpg';
						}
					} else {
						$preview			= $this->image_model->resize('resim_ekle.jpg', 100, 100);
						$preview_input 		= 'resim_ekle.jpg';
					}
				}

				?>
			<td>
				<input type="hidden" name="image" value="<?php echo $preview_input; ?>" id="image">
				<img src="<?php echo $preview; ?>" alt="" id="preview" onmouseover="$(this).attr('src','<?php echo $this->image_model->resize('resim_ekle_hover.jpg', 100, 100); ?>');" onmouseout="$(this).attr('src','<?php echo $preview; ?>');" title="Resim eklemek yada değiştirmek için tıklayınız." onclick="image_upload('image', 'preview');" style="cursor: pointer; border: 1px solid #EEEEEE;">
				<img src="<?php echo yonetim_resim(); ?>image.png" alt="" title="Resim eklemek yada değiştirmek için tıklayınız." style="cursor: pointer;" align="top" onclick="image_upload('image', 'preview');">
			</td>
		</tr>
        <tr>
          <td>Slider Linki<br /><span class="help">Slider üzerine tıklanınca gitmesini istediğiniz adres.</span></td>
          <td><?php echo form_input($slider_link); ?></td>
        </tr>
        <tr>
          <td>Slider Sırası<br /><span class="help">Slider gösterim sırası.</span></td>
          <td><?php echo form_input($slider_sira); ?></td>
        </tr>
        <tr>
          <td>Slider Durum<br /><span class="help">Slider kapatıp açmak için.</span></td>
          <td>
          	<?php
          		$slider_durum_array = array('0' => 'Kapalı', '1' => 'Açık');
          		echo form_dropdown('slider_flag', $slider_durum_array, $slider_veri->slider_flag);
          	?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript">
<!--
function image_upload(field, preview) {
	$('#dialog').remove();
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="<?php echo yonetim_url(); ?>/dosya_yonetici?field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	$('#dialog').dialog({
		title: 'Resim Yükle',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: '<?php echo yonetim_url("dosya_yonetici/image"); ?>',
					type: 'POST',
					data: 'image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" onmouseover="$(this).attr(\'src\', \'<?php echo $this->image_model->resize("resim_ekle_hover.jpg", 100, 100); ?>\');" onmouseout="$(this).attr(\'src\', \'' + data + '\');" title="Resim eklemek yada değiştirmek için tıklayınız." class="image" onclick="image_upload(\'' + field + '\', \'' + preview + '\', \'' + data + '\');" style="cursor: pointer; border: 1px solid #EEEEEE;" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 700,
		height: 400,
		resizable: false,
		modal: false
	});
};

function seo_aktar()
{
	aranan=$('#name').val();
	aranan=aranan.replace(/ğ/g, "g"); 
	aranan=aranan.replace(/Ğ/g, "g"); 
	aranan=aranan.replace(/ı/g, "i"); 
	aranan=aranan.replace(/İ/g, "i"); 
	aranan=aranan.replace(/Ç/g, "c"); 
	aranan=aranan.replace(/ç/g, "c"); 
	aranan=aranan.replace(/Ü/g, "u"); 
	aranan=aranan.replace(/ü/g, "u"); 
	aranan=aranan.replace(/Ö/g, "o"); 
	aranan=aranan.replace(/ö/g, "o"); 
	aranan=aranan.replace(/Ş/g, "s"); 
	aranan=aranan.replace(/ş/g, "s"); 
	aranan=aranan.replace(/ /g, "-");
	aranan=aranan.replace(/\./g, "-");
	aranan=aranan.replace(/"/g, "-");
	aranan=aranan.replace(/'/g, "-");
	aranan=aranan.replace(/&/g, "-amp-");
	aranan=aranan.replace(/,/g, "-");
	aranan=aranan.replace(/:/g, "-");
	aranan=aranan.replace(/%/g, "-");
	aranan=aranan.replace(/&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;+?;/, "");
	aranan=aranan.replace(/[^%a-zA-Z0-9 _-]/g, "");
	aranan=aranan.replace(/\s+/, "-");
	aranan=aranan.replace(/|-+|/, "-");
	aranan=aranan.replace(/-/g, "-");
	aranan=strtolower(aranan);
	aranan=trim(aranan, '-' );
	$('#seo_link').val(aranan);
}
//--></script>
<?php $this->load->view('yonetim/footer_view');  ?>