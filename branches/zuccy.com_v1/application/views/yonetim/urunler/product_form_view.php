<?php 
	$this->load->view('yonetim/header_view');
?>
<?php if(isset($errors) AND $errors) { ?>
	<div class="warning" style="margin-top: 10px;">
		Hata oluştu lütfen kontrol edin!
	</div>
<?php } ?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('<?php echo yonetim_resim(); ?>product.png');"><?php echo $title; ?></h1>
		<div class="buttons">
			<a onclick="$('#form').submit();" class="buton"><span>Kaydet</span></a>
			<a onclick="location = '<?php echo yonetim_url($cancel_url); ?>';" class="buton" style="margin-left:10px;"><span>İptal</span></a>
		</div>
	</div>

	<div class="content">
		<form action="<?php echo yonetim_url($action_url); ?>" method="post" id="form">

			<div id="tabs" class="htabs">
				<a tab="#tab_general">
					Genel
					<?php
						$tab_general_error = FALSE;
						foreach ($languages as $_language) {
							if (
								form_error('product_description['. $_language['language_id'] .'][name]') OR 
								form_error('product_description['. $_language['language_id'] .'][meta_keywords]') OR 
								form_error('product_description['. $_language['language_id'] .'][meta_description]') OR 
								form_error('product_description['. $_language['language_id'] .'][seo]') OR 
								form_error('product_description['. $_language['language_id'] .'][description]') OR 
								form_error('product_description['. $_language['language_id'] .'][video]')
							) {
								$tab_general_error = TRUE;
								break;
							}
						}
						echo ($tab_general_error) ? '<img src="'. yonetim_resim() .'warning.png" width="13" />' : NULL;
					?>
				</a>
				<a tab="#tab_data">
					Detaylar
					<?php
						if (
							form_error('model') OR form_error('quantity') OR form_error('stock_type') OR form_error('tax') OR form_error('price') OR 
							form_error('price_type') OR form_error('subtract') OR form_error('status') OR form_error('feature_status') OR 
							form_error('date_available') OR form_error('sort_order') OR form_error('date_available') OR form_error('date_available') OR 
							form_error('show_homepage') OR form_error('new_product')
						) {
							echo '<img src="'. yonetim_resim() .'warning.png" width="13" />';
						}
					?>
				</a>
				<a tab="#tab_category">
					Bağlantı Seçimleri
					<?php
						if (
							form_error('manufacturer_id') OR form_error('product_category') OR form_error('product_related')
						) {
							echo '<img src="'. yonetim_resim() .'warning.png" width="13" />';
						}
					?>
				</a>
				<a tab="#tab_feature">Özellikler</a>
				<a tab="#tab_image">Resimler</a>
				<a tab="#tab_option">Seçenekler</a>
				<a tab="#tab_discount" class="tab_menu_pasif">İndirim</a>
				<a tab="#tab_special" class="tab_menu_pasif">Kampanya</a>
				<a tab="#tab_cargo" class="tab_menu_pasif">Kargo Bilgileri</a>
			</div>

			<div id="tab_general">
				<div id="languages" class="htabs">
					<?php foreach ($languages as $_language) { ?>
						<a tab="#language_<?php echo $_language['language_id']; ?>"><img src="<?php echo yonetim_resim(); ?>flags/<?php echo $_language['image']; ?>" title="<?php echo $_language['name']; ?>" />
							<?php echo $_language['name']; ?>
							<?php
								if (
									form_error('product_description['. $_language['language_id'] .'][name]') OR 
									form_error('product_description['. $_language['language_id'] .'][meta_keywords]') OR 
									form_error('product_description['. $_language['language_id'] .'][meta_description]') OR 
									form_error('product_description['. $_language['language_id'] .'][seo]') OR 
									form_error('product_description['. $_language['language_id'] .'][description]') OR 
									form_error('product_description['. $_language['language_id'] .'][video]')
								) {
							?>
								<img src="<?php echo yonetim_resim(); ?>warning.png" width="13" />
							<?php } ?>
						</a>
					<?php } ?>
				</div>
	
				<?php foreach ($languages as $language) { ?>
					<div id="language_<?php echo $language['language_id']; ?>">
						<table class="form">
							<tr>
								<td>
									<span class="required">*</span> <?php echo $language['name']; ?> Ürün Başlık
								</td>
								<td>
									<?php
										if(isset($product_description[$language['language_id']]['name'])) {
											$_product_name = $product_description[$language['language_id']]['name'];
										} else {
											$_product_name = NULL;
										}
									?>
									<input type="text" name="product_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo $_product_name; ?>" />
									<?php if (form_error('product_description['. $language['language_id'] .'][name]')) { ?>
										<span class="error"><?php echo form_error('product_description['. $language['language_id'] .'][name]'); ?></span>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									 <?php echo $language['name']; ?> Meta Keywords
								</td>
								<td>
									<?php
										if(isset($product_description[$language['language_id']]['meta_keywords'])) {
											$_product_meta_keywords = $product_description[$language['language_id']]['meta_keywords'];
										} else {
											$_product_meta_keywords = NULL;
										}
									?>
									<input type="text" name="product_description[<?php echo $language['language_id']; ?>][meta_keywords]" size="100" value="<?php echo $_product_meta_keywords; ?>" />
									<?php if (form_error('product_description['. $language['language_id'] .'][meta_keywords]')) { ?>
										<span class="error"><?php echo form_error('product_description['. $language['language_id'] .'][meta_keywords]'); ?></span>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									 <?php echo $language['name']; ?> Meta Description
								</td>
								<td>
									<?php
										if(isset($product_description[$language['language_id']]['meta_description'])) {
											$_product_meta_description = $product_description[$language['language_id']]['meta_description'];
										} else {
											$_product_meta_description = NULL;
										}
									?>
									<input type="text" name="product_description[<?php echo $language['language_id']; ?>][meta_description]" size="100" value="<?php echo $_product_meta_description; ?>" />
									<?php if (form_error('product_description['. $language['language_id'] .'][meta_description]')) { ?>
										<span class="error"><?php echo form_error('product_description['. $language['language_id'] .'][meta_description]'); ?></span>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									<?php echo $language['name']; ?> Seo Adresi<br /><span class="help">Seo adresi girilmesi zorunlu değildir, girilen seo adresi gereçli olur. Girilmez ise otomatik olarak Başlık kısmını referans alarak oluşturulur.</span>
								</td>
								<td>
									<?php
										if(isset($product_description[$language['language_id']]['seo'])) {
											$_product_seo = $product_description[$language['language_id']]['seo'];
										} else {
											$_product_seo = NULL;
										}
									?>
									<input type="text" name="product_description[<?php echo $language['language_id']; ?>][seo]" size="100" value="<?php echo $_product_seo; ?>" />
									<?php if (form_error('product_description['. $language['language_id'] .'][seo]')) { ?>
										<span class="error"><?php echo form_error('product_description['. $language['language_id'] .'][seo]'); ?></span>
									<?php } ?>
								</td>
							</tr>
							
							<tr>
								<td>
									<?php echo $language['name']; ?> Ürün notu (MetinBox-Info)
								</td>
								<td>
									<?php
										if(isset($product_description[$language['language_id']]['info'])) {
											$_product_info = $product_description[$language['language_id']]['info'];
										} else {
											$_product_info = NULL;
										}
									?>
									<textarea name="product_description[<?php echo $language['language_id']; ?>][info]" id="info_<?php echo $language['language_id']; ?>"><?php echo $_product_info; ?></textarea>
									<?php if (form_error('product_description['. $language['language_id'] .'][info]')) { ?>
										<span class="error"><?php echo form_error('product_description['. $language['language_id'] .'][info]'); ?></span>
									<?php } ?>
								</td>
							</tr>							
							
							<tr>
								<td>
									<?php echo $language['name']; ?> Ürün Açıklama
								</td>
								<td>
									<?php
										if(isset($product_description[$language['language_id']]['description'])) {
											$_product_description = $product_description[$language['language_id']]['description'];
										} else {
											$_product_description = NULL;
										}
									?>
									<textarea name="product_description[<?php echo $language['language_id']; ?>][description]" id="description_<?php echo $language['language_id']; ?>"><?php echo $_product_description; ?></textarea>
									<?php if (form_error('product_description['. $language['language_id'] .'][description]')) { ?>
										<span class="error"><?php echo form_error('product_description['. $language['language_id'] .'][description]'); ?></span>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									<?php echo $language['name']; ?> Video Embed Kodu<br /><span class="help">Vimeo - Google Video - Youtube tarzı video sitelerinin embed kodu.</span>
								</td>
								<td>
									<?php
										if(isset($product_description[$language['language_id']]['video'])) {
											$_product_video = $product_description[$language['language_id']]['video'];
										} else {
											$_product_video = NULL;
										}
									?>
									<textarea name="product_description[<?php echo $language['language_id']; ?>][video]" cols="80" rows="10"><?php echo $_product_video; ?></textarea>
									<?php if (form_error('product_description['. $language['language_id'] .'][video]')) { ?>
										<span class="error"><?php echo form_error('product_description['. $language['language_id'] .'][video]'); ?></span>
									<?php } ?>
								</td>
							</tr>
						</table>
					</div>
				<?php } ?>
			</div>

			<div id="tab_data">
				<table class="form">
					<tr>
						<td><span class="required">*</span> Ürün Kodu<br /><span class="help">Ürünün kodu.</span></td>
						<td>
							<input type="text" name="model" size="100" value="<?php echo $model; ?>" />
							<?php if (form_error('model')) { ?>
								<span class="error"><?php echo form_error('model'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							<span class="required">*</span> Miktar<br /><span class="help">Üründen kaç adet olacağını belirler. Bu miktar 0 olarak girilirse ürün sitede "stokta yok" ibareleriyle listelenecektir.</span>
						</td>
						<td>
							<input type="text" name="quantity" value="<?php echo $quantity; ?>" class="text" size="5">
							<?php
								echo form_dropdown('stock_type', $stock_types, $stock_type);
							?>
							<?php if (form_error('quantity')) { ?>
								<span class="error"><?php echo form_error('quantity'); ?></span>
							<?php } ?>
							<?php if (form_error('stock_type')) { ?>
								<span class="error"><?php echo form_error('stock_type'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							<span class="required">*</span> Vergi Oranı<br /><span class="help">Ürünün vergi oranı.</span>
						</td>
						<td>
							%
							<?php
								echo form_dropdown('tax', $taxes, $tax);
							?>
							<?php if (form_error('tax')) { ?>
								<span class="error"><?php echo form_error('tax'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							<span class="required">*</span> Satış Fiyatı<br /><span class="help">Ürünün satış fiyatı.</span>
						</td>
						<td>
							<input type="text" name="price" value="<?php echo $price; ?>" class="text" size="5">
							<?php
								$_price_type_array = array('1' => 'TL', '2' => '$', '3' => '€');
								echo form_dropdown('price_type', $_price_type_array, $price_type);
							?>
							<?php if (form_error('price')) { ?>
								<span class="error"><?php echo form_error('price'); ?></span>
							<?php } ?>
							<?php if (form_error('price_type')) { ?>
								<span class="error"><?php echo form_error('price_type'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td><span class="required">*</span> Stoktan Düş<br /><span class="help">Ürün satıldıktan sonra ürün miktarı eksilir.</span></td>
						<td>
							<?php
								$_subtract_array = array('0' => ' - Hayır - ', '1' => ' - Evet - ');
								echo form_dropdown('subtract', $_subtract_array, $subtract);
							?>
							<?php if (form_error('subtract')) { ?>
								<span class="error"><?php echo form_error('subtract'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td><span class="required">*</span>Hızlı Gönderi<br /><span class="help">Aynı gün kargoya verilir.</span></td>
						<td>
							<?php
								$_hizli_array = array('0' => ' - Hayır - ', '1' => ' - Evet - ');
								echo form_dropdown('hizli', $_hizli_array, $hizli);
							?>
							<?php if (form_error('hizli')) { ?>
								<span class="error"><?php echo form_error('hizli'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td><span class="required">*</span> Durum<br /><span class="help">Ürünleri aktif yada pasif edin.</span></td>
						<td>
							<?php
								$_status_array = array('0' => ' - Kapalı - ', '1' => ' - Açık - ');
								echo form_dropdown('status', $_status_array, $status);
							?>
							<?php if (form_error('status')) { ?>
								<span class="error"><?php echo form_error('status'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td><span class="required">*</span> Özellik Bölümü<br /><span class="help">Ürünlerin özellik tabını gösterin yada göstermeyin.</span></td>
						<td>
							<?php
								$_feature_status_array = array('0' => ' - Gösterme - ', '1' => ' - Göster - ');
								echo form_dropdown('feature_status', $_feature_status_array, $feature_status);
							?>
							<?php if (form_error('feature_status')) { ?>
								<span class="error"><?php echo form_error('feature_status'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td><span class="required">*</span> Geçerlilik Süresi</td>
						<td>
							<input type="text" class="date" name="date_available" value="<?php echo $date_available; ?>" />
							<?php if (form_error('date_available')) { ?>
								<span class="error"><?php echo form_error('date_available'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td><span class="required">*</span> Sıralama</td>
						<td>
							<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" />
							<?php if (form_error('sort_order')) { ?>
								<span class="error"><?php echo form_error('sort_order'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td><span class="required">*</span> Anasayfada Göster 
							<span class="help">Anasayfa sırasını ayarlamak için sayı giriniz! 0'dan büyük sayı girerseniz anasayfada gösterir ve o sırayı alır. 0 girerseniz anasayfada gözükmez.</span>
						</td>
						<td>
							<input type="text" name="show_homepage" value="<?php echo $show_homepage; ?>" />
							<?php if (form_error('show_homepage')) { ?>
								<span class="error"><?php echo form_error('show_homepage'); ?></span>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td><span class="required">*</span> Yeni Ürün</td>
						<td>
							<?php
								$_new_product_array = array('0' => ' - Hayır - ', '1' => ' - Evet - ');
								echo form_dropdown('new_product', $_new_product_array, $new_product);
							?>
							<?php if (form_error('new_product')) { ?>
								<span class="error"><?php echo form_error('new_product'); ?></span>
							<?php } ?>
						</td>
					</tr>
				</table>
			</div>

			<div id="tab_category">
				<table class="form">
					<tbody>
						<tr>
							<td>Marka</td>
							<td>
								<?php
									echo form_dropdown('manufacturer_id', $manufacturers, $manufacturer_id);
								?>
								<?php if (form_error('manufacturer_id')) { ?>
									<span class="error"><?php echo form_error('manufacturer_id'); ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td>Kategoriler</td>
							<td>
								<div class="scrollbox" id="scrollbox_0" style="width:725px;height:200px;">
								<?php $class = 'odd'; ?>
								<?php foreach ($categories as $category) {
//                                                                    $this->db->select('parent_id', FALSE);
//		                                                    $this->db->from('e_category');
//		                                                    $this->db->where('parent_id', $category['category_id']);
//	                                                            $query = $this->db->get();
//		                                                    if(!$query->num_rows()){

                                                                    ?>
									<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
									<div class="<?php echo $class; ?>">
									<?php if (in_array($category['category_id'], $product_category)) { ?>
										<input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
										<?php echo $category['name']; ?>
									<?php } else { ?>
										<input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" />
										<?php echo $category['name']; ?>
									<?php } ?>
									</div>
								<?php //}

                                                                }
                                                                ?>
								</div>
								<span>
									<a onclick="$('#scrollbox_0 :checkbox').attr('checked', 'checked');"><u>Hepsini Seç</u></a> / <a onclick="$('#scrollbox_0 :checkbox').attr('checked', '');"><u>Seçimi Kaldır</u></a>
								</span>
								<?php if (form_error('product_category')) { ?>
									<span class="error"><?php echo form_error('product_category'); ?></span>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td>Benzer Ürünler</td>
							<td>
								<table>
									<tbody>
										<tr>
											<td style="padding: 0;" colspan="3">
												<?php if($categories) {	?>
                                                	<script type="text/javascript">
													$(document).ready(function(){ get_products(); });
													</script>
													<select id="category" style="margin-bottom: 5px;" onchange="get_products();">
													<?php foreach ($categories as $category) { ?>
													<option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
													<?php } ?>
													</select>
												<?php } ?>
											</td>
										</tr>
										<tr>
											<td style="padding: 0;">
												<select multiple="multiple" id="product" size="10" style="width: 350px;">
												</select>
												<div style="clear:both;"></div>
												<span>
													<a onclick="$('#product').find('option').attr('selected', 'selected');"><u>Hepsini Seç</u></a> / <a onclick="$('#product').find('option').attr('selected', '');"><u>Seçimi Kaldır</u></a>
												</span>
											</td>
											<td style="vertical-align: middle;">
												<input type="button" value="--&gt;" onclick="add_related();">
												<br>
												<input type="button" value="&lt;--" onclick="remove_related();">
											</td>
											<td style="padding: 0;">
												<?PHP
												// Benzer ürünleri gösteren bölüm
												$query = @$this->db->select('product_related.related_id')
												->from('product_description')
												->join('product_related','product_related.product_id = product_description.product_id','left')
												->where('product_related.product_id',$product_id)
												->get();
												
												$que = $query->result();
												
												?>
												<select multiple="multiple" id="related" size="10" style="width: 350px;">
														<?php 
                                                        if($query->num_rows() > 0)
														{
															foreach($que as $row)
															{
																$query2 = $this->db->get_where('product_description',array('product_id' => $row->related_id));
																$que2 = $query2->row();
																if($que2 != "")
																{
																	echo '<option value="'.$que2->product_id.'">'.$que2->name.'</option>';
																}
															}
														}
                                                        ?>
												</select>
                                                <?PHP // Benzer ürünleri gösteren bölüm son ?>
												<div style="clear:both;"></div>
												<span>
													<a onclick="$('#related').find('option').attr('selected', 'selected');"><u>Hepsini Seç</u></a> / <a onclick="$('#related').find('option').attr('selected', '');"><u>Seçimi Kaldır</u></a>
												</span>
											</td>
										</tr>
									</tbody>
								</table>
								<div id="product_related">
									<?php foreach ($product_related as $related_id) { ?>
										<input type="hidden" name="product_related[]" value="<?php echo $related_id; ?>" />
									<?php } ?>
								</div>
							</td>
						</tr>
					<tbody>
				</table>
			</div>

			<div id="tab_feature">
				<?php if (isset($product_id)) { ?>
				<div id="tab_feature_product_id">
					<input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
				</div>
				<?php } ?>
				<table class="list" id="feature_list">

				</table>
			</div>

			<div id="tab_image">
				<table class="form">
					<tr>
						<td>
							Ürün Ana Resmi<br />
							<span class="help">
								Ürüne ana resim eklemek için tıklayın.<br />
								Ürün resim eklerken maksimum resim boyutu 1MB ve genişlik 768px, yükseklik 1024px olmalıdır.
							</span>
						</td>
						<td>
							<input type="hidden" name="image" value="<?php echo $image; ?>" id="image">
							<img src="<?php echo $preview; ?>" alt="" id="preview" onmouseover="$(this).attr('src','<?php echo show_image('resim_ekle_hover.jpg', 100, 100); ?>');" onmouseout="$(this).attr('src','<?php echo $preview; ?>');" title="Resim eklemek yada değiştirmek için tıklayınız." onclick="image_upload('image', 'preview');" style="cursor: pointer; border: 1px solid #EEEEEE;">
							<img src="<?php echo yonetim_resim(); ?>image.png" alt="" title="Resim eklemek yada değiştirmek için tıklayınız." style="cursor: pointer;" align="top" onclick="image_upload('image', 'preview');">
						</td>
					</tr>
				</table>

				<table id="images" class="list">
					<thead>
						<tr>
							<td class="left">Resimler</td>
							<td></td>
						</tr>
					</thead>
					<?php $image_row = 0; ?>
					<?php foreach ($product_images as $product_image) { ?>
						<tbody id="image_row<?php echo $image_row; ?>">
							<tr style="background-color:#fff !important;">
								<td class="left">
									<input type="hidden" name="product_images[<?php echo $image_row; ?>]" value="<?php echo $product_image['file']; ?>" id="image<?php echo $image_row; ?>" />
									<img src="<?php echo $product_image['preview']; ?>" alt="" id="preview<?php echo $image_row; ?>" class="image" onclick="image_upload('image<?php echo $image_row; ?>', 'preview<?php echo $image_row; ?>');" onmouseout="$(this).attr('src','<?php echo $product_image['preview']; ?>');" onmouseover="$(this).attr('src','<?php echo show_image('resim_ekle_hover.jpg', 100, 100); ?>');" style="cursor: pointer; border: 1px solid #EEEEEE;" />
									<img src="<?php echo yonetim_resim(); ?>image.png" alt="" title="Resim eklemek yada değiştirmek için tıklayınız." style="cursor: pointer;" align="top" onclick="image_upload('image<?php echo $image_row; ?>', 'preview<?php echo $image_row; ?>');">
								</td>
								<td class="left">
									<a onclick="$('#image_row<?php echo $image_row; ?>').remove();" class="buton">
										<span>Kaldır</span>
									</a>
								</td>
							</tr>
						</tbody>

					<?php $image_row++; ?>
					<?php } ?>
					<tfoot>
						<tr>
							<td></td>
							<td class="left">
								<a onclick="add_image();" class="buton"><span>Ekle</span></a>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>

			<div id="tab_discount">
				<table id="discount" class="list">
					<thead>
						<tr>
							<td class="left">Müşteri Grubu</td>
							<td class="left">Öncelik</td>
							<td class="left">Fiyatı</td>
							<td class="left">Başlangıç Tarihi</td>
							<td class="left">Bitiş Tarihi</td>
							<td></td>
						</tr>
					</thead>
					<?php $discount_row = 0; ?>
					<?php foreach ($product_discounts as $product_discount) { ?>
						<tbody id="discount_row<?php echo $discount_row; ?>">
							<tr>
								<td class="left">
									<select name="product_discount[<?php echo $discount_row; ?>][user_group_id]">
									<?php foreach ($customer_groups as $customer_group) { ?>
										<?php if ($customer_group->id == $product_discount['user_group_id']) { ?>
											<option value="<?php echo $customer_group->id; ?>" selected="selected"><?php echo $customer_group->name; ?></option>
										<?php } else { ?>
											<option value="<?php echo $customer_group->id; ?>"><?php echo $customer_group->name; ?></option>
										<?php } ?>
									<?php } ?>
									</select>
								</td>
								<td class="left"><input type="text" name="product_discount[<?php echo $discount_row; ?>][priority]" value="<?php echo $product_discount['priority']; ?>" size="2"></td>
								<td class="left"><input type="text" name="product_discount[<?php echo $discount_row; ?>][price]" value="<?php echo $product_discount['price']; ?>" /></td>
								<td class="left"><input type="text" name="product_discount[<?php echo $discount_row; ?>][date_start]" value="<?php echo is_numeric($product_discount['date_start']) ? date('Y-m-d H:i:s', $product_discount['date_start']) : $product_discount['date_start']; ?>" class="date" /></td>
								<td class="left"><input type="text" name="product_discount[<?php echo $discount_row; ?>][date_end]" value="<?php echo is_numeric($product_discount['date_end']) ? date('Y-m-d H:i:s', $product_discount['date_end']) : $product_discount['date_end']; ?>" class="date" /></td>
								<td class="left"><a onclick="$('#discount_row<?php echo $discount_row; ?>').remove();" class="buton"><span>Kaldır</span></a></td>
							</tr>
						</tbody>
						<?php $discount_row++; ?>
					<?php } ?>
					<tfoot>
						<tr>
							<td colspan="5"></td>
							<td class="left"><a onclick="add_discount();" class="buton"><span>İndirim Ekle</span></a></td>
						</tr>
					</tfoot>
				</table>
			</div>

			<div id="tab_special">
				<table id="special" class="list">
					<thead>
						<tr>
							<td class="left">Müşteri Grubu</td>
							<td class="left">Miktarı</td>
							<td class="left">Öncelik</td>
							<td class="left">Fiyatı</td>
							<td class="left">Başlangıç Tarihi</td>
							<td class="left">Bitiş Tarihi</td>
							<td></td>
						</tr>
					</thead>
					<?php $special_row = 0; ?>
					<?php foreach ($product_specials as $product_special) { ?>
						<tbody id="special_row<?php echo $special_row; ?>">
							<tr>
								<td class="left">
									<select name="product_special[<?php echo $special_row; ?>][user_group_id]">
										<?php foreach ($customer_groups as $customer_group) { ?>
											<?php if ($customer_group->id == $product_special['user_group_id']) { ?>
												<option value="<?php echo $customer_group->id; ?>" selected="selected"><?php echo $customer_group->name; ?></option>
											<?php } else { ?>
												<option value="<?php echo $customer_group->id; ?>"><?php echo $customer_group->name; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</td>
								<td class="left"><input type="text" name="product_special[<?php echo $special_row; ?>][quantity]" value="<?php echo $product_special['quantity']; ?>" size="2" /></td>
								<td class="left"><input type="text" name="product_special[<?php echo $special_row; ?>][priority]" value="<?php echo $product_special['priority']; ?>" size="2" /></td>
								<td class="left"><input type="text" name="product_special[<?php echo $special_row; ?>][price]" value="<?php echo $product_special['price']; ?>" /></td>
								<td class="left"><input type="text" name="product_special[<?php echo $special_row; ?>][date_start]" value="<?php echo is_numeric($product_special['date_start']) ? date('Y-m-d H:i:s', $product_special['date_start']) : $product_special['date_start']; ?>" class="date" /></td>
								<td class="left"><input type="text" name="product_special[<?php echo $special_row; ?>][date_end]" value="<?php echo is_numeric($product_special['date_end']) ? date('Y-m-d H:i:s', $product_special['date_end']) : $product_special['date_end']; ?>" class="date" /></td>
								<td class="left"><a onclick="$('#special_row<?php echo $special_row; ?>').remove();" class="buton"><span>Kaldır</span></a></td>
							</tr>
						</tbody>
					<?php $special_row++; ?>
					<?php } ?>
					<tfoot>
						<tr>
							<td colspan="6"></td>
							<td class="left"><a onclick="add_special();" class="buton"><span>Kampanya Ekle</span></a></td>
						</tr>
					</tfoot>
				</table>
			</div>

			<div id="tab_cargo">
				<table class="form">
					<tr>
						<td>Kargo Gerekli</td>
						<td>
							<?php
								//$_cargo_array = array('0' => 'Hayır', '1' => 'Evet');
								//echo form_dropdown('cargo_required', $_cargo_array, $cargo_required);							
							?>
                            <select name="cargo_required">
                            	<option value="1">Evet</option>
                                <option value="0">Hayır</option>
                            </select>
						</td>
					</tr>
					<tr>
						<td>Kargo Ücreti Adet Çarpımı<br /><span class="help">Ürün alındığı zaman kargo fiyatı alınan ürün miktarı ile çarpılsın mı ?</span></td>
						<td>
							<?php
								$_cargo_multiply_array = array('0' => 'Hayır', '1' => 'Evet');
								echo form_dropdown('cargo_multiply_required', $_cargo_multiply_array, $cargo_multiply_required);
							?>
						</td>
					</tr>
					<tr>
						<td><span class="required">*</span> Önemli Uyarı</td>
						<td>
							Ürün <span style="font-weight:bold;">Ağırlığı</span> <span style="font-weight:bold;">Desi</span> değerinden büyükse, hesaplanacak değer <span style="font-weight:bold;">Ağırlık</span> olacaktır.
						</td>
					</tr>
					<tr>
						<td>
							Boyutları (U x G x Y)<span class="help">U = Uzunluk,<br />G = Genişlik,<br />Y = Yükseklik</span>
						</td>
						<td>
							<input type="text" name="length" id="length" value="<?php echo $length; ?>" onkeyup="desi_hesapla();" maxlength="10" size="4" />
							<input type="text" name="width" id="width" value="<?php echo $width; ?>" onkeyup="desi_hesapla();" maxlength="10" size="4" />
							<input type="text" name="height" id="height" value="<?php echo $height; ?>" onkeyup="desi_hesapla();" maxlength="10" size="4" />
							<?php echo form_dropdown('length_class_id', $length_class, $length_class_id); ?> Desi : <span id="desi_oran" style="font-weight:bold;"></span>
						</td>
					</tr>
					<tr>
						<td>Ağırlık</td>
						<td>
							<input type="text" name="weight" id="weight" value="<?php echo $weight; ?>" />
							<?php echo form_dropdown('weight_class_id', $weight_class, $weight_class_id); ?>
						</td>
					</tr>
				</table>
			</div>

			<div id="tab_option">
				<div id="vtab_option" class="vtabs">
					<?php $option_row = 0; ?>
					<?php foreach ($product_options as $product_option) { ?>
						<a tab="#tab_option-<?php echo $option_row; ?>" id="option-<?php echo $option_row; ?>"><?php echo $product_option['name']; ?>&nbsp;<img src="<?php echo yonetim_resim(); ?>delete.png" alt="" onclick="$('#vtabs a:first').trigger('click'); $('#option-<?php echo $option_row; ?>').remove(); $('#tab_option-<?php echo $option_row; ?>').remove(); return false;" /></a>
						<?php $option_row++; ?>
					<?php } ?>
					<span id="option-add"><input name="option" value="" />&nbsp;<img src="<?php echo yonetim_resim(); ?>add.png" alt="Seçenek Ekle" title="Seçenek Ekle" /></span>
				</div>
				<?php $option_row = 0; ?>
				<?php $option_value_row = 0; ?>
				<?php foreach ($product_options as $product_option) { ?>
					<div id="tab_option-<?php echo $option_row; ?>" class="vtabs-content">
						<input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_id]" value="<?php echo $product_option['product_option_id']; ?>" />
						<input type="hidden" name="product_option[<?php echo $option_row; ?>][name]" value="<?php echo $product_option['name']; ?>" />
						<input type="hidden" name="product_option[<?php echo $option_row; ?>][option_id]" value="<?php echo $product_option['option_id']; ?>" />
						<input type="hidden" name="product_option[<?php echo $option_row; ?>][type]" value="<?php echo $product_option['type']; ?>" />
						<table class="form">
							<tr>
								<td>Gerekli</td>
								<td>
									<select name="product_option[<?php echo $option_row; ?>][required]">
										<?php if ($product_option['required']) { ?>
											<option value="1" selected="selected">Evet</option>
											<option value="0">Hayır</option>
										<?php } else { ?>
											<option value="1">Evet</option>
											<option value="0" selected="selected">Hayır</option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<?php if ($product_option['type'] == 'text') { ?>
								<tr>
									<td>Seçenek Değeri</td>
									<td><input type="text" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>" /></td>
								</tr>
								<tr>
									<td>Maksimum Karakter Sayısı</td>
									<td><input type="text" name="product_option[<?php echo $option_row; ?>][character_limit]" maxlength="2" value="<?php echo $product_option['character_limit']; ?>" /></td>
								</tr>
							<?php } ?>
							<?php if ($product_option['type'] == 'textarea') { ?>
								<tr>
									<td>Seçenek Değeri</td>
									<td><textarea name="product_option[<?php echo $option_row; ?>][option_value]" cols="40" rows="5"><?php echo $product_option['option_value']; ?></textarea></td>
								</tr>
							<?php } ?>
							<?php if ($product_option['type'] == 'file') { ?>
								<tr style="display: none;">
									<td>Seçenek Değeri</td>
									<td><input type="text" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>" /></td>
								</tr>
							<?php } ?>
						</table>
						<?php if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox') { ?>
							<table id="option-value<?php echo $option_row; ?>" class="list">
								<thead>
									<tr>
										<td class="left">Seçenek Değeri</td>
										<td class="right">Miktar</td>
										<td class="left">Stokdan Düş</td>
										<td class="right">Fiyat</td>
										<?php /* ?>
										<td class="right">Puan</td>
										<td class="right">Ağırlık</td>
										<?php */ ?>
										<td></td>
									</tr>
								</thead>
								<?php foreach ($product_option['product_option_value'] as $product_option_value) { ?>
									<tbody id="option-value-row<?php echo $option_value_row; ?>">
										<tr>
											<td class="left">
												<select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][option_value_id]"></select>
												<input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][product_option_value_id]" value="<?php echo $product_option_value['product_option_value_id']; ?>" />
											</td>
											<td class="right">
												<input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][quantity]" value="<?php echo $product_option_value['quantity']; ?>" size="3" />
											</td>
											<td class="left">
												<select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][subtract]">
													<?php if ($product_option_value['subtract']) { ?>
														<option value="1" selected="selected">Evet</option>
														<option value="0">Hayır</option>
													<?php } else { ?>
														<option value="1">Evet</option>
														<option value="0" selected="selected">Hayır</option>
													<?php } ?>
												</select>
											</td>
											<td class="right">
												<select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price_prefix]">
													<?php if ($product_option_value['price_prefix'] == '+') { ?>
														<option value="+" selected="selected">+</option>
													<?php } else { ?>
														<option value="+">+</option>
													<?php } ?>
													<?php if ($product_option_value['price_prefix'] == '-') { ?>
														<option value="-" selected="selected">-</option>
													<?php } else { ?>
														<option value="-">-</option>
													<?php } ?>
												</select>
												<input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price]" value="<?php echo $product_option_value['price']; ?>" size="5" />
											</td>
											<?php /* ?>
											<td class="right">
												<select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points_prefix]">
													<?php if ($product_option_value['points_prefix'] == '+') { ?>
														<option value="+" selected="selected">+</option>
													<?php } else { ?>
														<option value="+">+</option>
													<?php } ?>
													<?php if ($product_option_value['points_prefix'] == '-') { ?>
														<option value="-" selected="selected">-</option>
													<?php } else { ?>
														<option value="-">-</option>
													<?php } ?>
												</select>
												<input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points]" value="<?php echo $product_option_value['points']; ?>" size="5" />
											</td>
											<td class="right">
												<select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight_prefix]">
													<?php if ($product_option_value['weight_prefix'] == '+') { ?>
														<option value="+" selected="selected">+</option>
													<?php } else { ?>
														<option value="+">+</option>
													<?php } ?>
													<?php if ($product_option_value['price_prefix'] == '-') { ?>
														<option value="-" selected="selected">-</option>
													<?php } else { ?>
														<option value="-">-</option>
													<?php } ?>
												</select>
												<input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight]" value="<?php echo $product_option_value['weight']; ?>" size="5" />
											</td>
											<?php */ ?>
											<td class="left">
												<a onclick="$('#option-value-row<?php echo $option_value_row; ?>').remove();" class="buton"><span>Kaldır</span></a>
											</td>
										</tr>
									</tbody>
									<?php $option_value_row++; ?>
								<?php } ?>
								<tfoot>
									<tr>
										<td colspan="4"></td>
										<td class="left"><a onclick="add_option_value('<?php echo $option_row; ?>');" class="buton"><span>Seçenek Değeri Ekle</span></a></td>
									</tr>
								</tfoot>
							</table>
						<?php } ?>
					</div>
					<?php $option_row++; ?>
				<?php } ?>
				<?php if($product_options) { ?>
				<script type="text/javascript" charset="utf-8">
					<?php $option_row = 0; ?>
					<?php $option_value_row = 0; ?>
					<?php foreach ($product_options as $product_option) { ?>
						<?php if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox') { ?>
							<?php foreach ($product_option['product_option_value'] as $product_option_value) { ?>
								$('select[name=\'product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][option_value_id]\']').load('<?php echo yonetim_url(); ?>/urunler/product/option?option_id=<?php echo $product_option['option_id']; ?>&option_value_id=<?php echo $product_option_value['option_value_id']; ?>');
								<?php $option_value_row++; ?>
							<?php } ?>
						<?php } ?>
						<?php $option_row++; ?>
					<?php } ?>
				</script>
				<?php } ?>
			</div>

		</form>
	</div>
</div>

<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('.date').datetimepicker({dateFormat: 'yy-mm-dd',timeFormat:'hh:mm:ss'});
		get_features();
		desi_hesapla();
		$('#scrollbox_0 input[type=\'checkbox\']').click(function() {
			get_features();
		});
	});
	$('#tabs a').tabs();
	$('#languages a').tabs();
	$('#vtab_option a').tabs();

	function desi_hesapla () {
		/* Desi Oran Hesaplama */
		var desi_length	= $('#length').attr('value');
		var desi_width	= $('#width').attr('value');
		var desi_height	= $('#height').attr('value');
		var desi_oran	= ((desi_length * desi_width * desi_height) / 3000);
		desi_oran		= Math.round (desi_oran*100) / 100;
		$('#desi_oran').html('<img src="<?php echo yonetim_resim(); ?>ajax-loader.gif" height="13" alt="Hesaplanıyor..." /> Hesaplanıyor...');
		setTimeout("$('#desi_oran').html(" + desi_oran + ")", 1000);
		/* Desi Oran Hesaplama */
	}

	<?php foreach ($languages as $language) { ?>
	CKEDITOR.replace('description_<?php echo $language['language_id']; ?>', {
		filebrowserBrowseUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
		filebrowserImageBrowseUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
		filebrowserFlashBrowseUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
		filebrowserUploadUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
		filebrowserImageUploadUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
		filebrowserFlashUploadUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>'
	});
	CKEDITOR.replace('info_<?php echo $language['language_id']; ?>', {
		filebrowserBrowseUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
		filebrowserImageBrowseUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
		filebrowserFlashBrowseUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
		filebrowserUploadUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
		filebrowserImageUploadUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>',
		filebrowserFlashUploadUrl: '<?php echo yonetim_url("dosya_yonetici"); ?>'
	});	
	<?php } ?>
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
							$('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" onmouseover="$(this).attr(\'src\', \'<?php echo show_image("resim_ekle_hover.jpg", 100, 100); ?>\');" onmouseout="$(this).attr(\'src\', \'' + data + '\');" title="Resim eklemek yada değiştirmek için tıklayınız." class="image" onclick="image_upload(\'' + field + '\', \'' + preview + '\', \'' + data + '\');" style="cursor: pointer; border: 1px solid #EEEEEE;" />');
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

	function get_features () {
		$.ajax({
			type: "POST",
			url: "<?php echo yonetim_url('urunler/product/get_features'); ?>",
			data: $('#scrollbox_0 input[type=\'checkbox\']:checked, #tab_feature_product_id input[type=\'hidden\']'),
			dataType: 'json',
			beforeSend: function() {},
			complete: function() {},
			success: function(response) {
				$('#feature_list').html('');

				if(response.error == '') {

					var sheme = '';
					sheme += '<table class="list">';
					sheme += '<thead><tr><td class="left" style="width:350px;">Özellik Adı</td><td class="left">Özellik Değeri</td></tr></thead>';

					for (i in response.data) {
						sheme += '<tbody>';

						var element_count = 0;
						for (e in response['data'][i]['data']) {element_count++;}

						if(element_count > 0) {
							sheme += '<tr>';
							sheme += '<td class="left" colspan="2">';
							sheme += 'Kategori : ' + response['data'][i]['name'];
							sheme += '</td>';
							sheme += '</tr>';

							sheme += '<tr>';
							sheme += '<td class="left">';
							var count = 0;
							for (ii in response['data'][i]['data']) {
								var div_style = '';
								if(count == 0) {
									div_style = '';
								} else {
									div_style = 'border-top:1px #DDD solid;padding-top:5px;';
								}
								sheme += '<div style="margin-top:5px;'+ div_style +'">';
								sheme += '<img src="<?php echo yonetim_resim(); ?>flags/'+ response['data'][i]['data'][ii]['lang_data']['image'] +'" />&nbsp;&nbsp;';
								sheme += response['data'][i]['data'][ii]['name'] + '<br />';
								sheme += '</div>';
								count++;
							}
							sheme += '</td>';

							sheme += '<td class="left">';
							var count = 0;
							for (ii in response['data'][i]['data']) {
								sheme += '<div style="margin-top:5px;">';
								sheme += '<img src="<?php echo yonetim_resim(); ?>flags/'+ response['data'][i]['data'][ii]['lang_data']['image'] +'" />&nbsp;&nbsp;';
								sheme += '<input type="text" style="width:400px;" name="product_features['+ i +']['+ ii +'][name]" value="'+ response['data'][i]['data'][ii]['value'] +'">';
								sheme += '</div>';
								count++;
							}
							sheme += '</td>';
							sheme += '</tr>';
						}
					}

					sheme += '</table>';

					$('#feature_list').html(sheme);
				}
			}
		});
	}

	function add_related() {
		$('#product :selected').each(function() {
			$(this).remove();
			$('#related option[value=\'' + $(this).attr('value') + '\']').remove();
			$('#related').append('<option value="' + $(this).attr('value') + '">' + $(this).text() + '</option>');
			$('#product_related input[value=\'' + $(this).attr('value') + '\']').remove();
			$('#product_related').append('<input type="hidden" name="product_related[]" value="' + $(this).attr('value') + '" />');
		});
	}

	function remove_related() {
		$('#related :selected').each(function() {
			$(this).remove();
			$('#product_related input[value=\'' + $(this).attr('value') + '\']').remove();
		});
	}

	function get_products() {
		$('#product option').remove();
		<?php if (isset($product_id)) { ?>
		var product_id = '<?php echo $product_id; ?>';
		<?php } else { ?>
		var product_id = 0;
		<?php } ?>
		$.ajax({
			url: '<?php echo yonetim_url(); ?>/urunler/product/category/' + $('#category').attr('value'),
			dataType: 'json',
			success: function(data) {
				for (i = 0; i < data.length; i++) {
					if (data[i]['product_id'] == product_id) { continue; }
					$('#product').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + ' (' + data[i]['model'] + ') </option>');
				}
			}
		});
	}

	$.widget('custom.catcomplete', $.ui.autocomplete, {
		_renderMenu: function(ul, items) {
			var self = this, currentCategory = '';
			
			$.each(items, function(index, item) {
				if (item.category != currentCategory) {
					ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
					
					currentCategory = item.category;
				}
				
				self._renderItem(ul, item);
			});
		}
	});

	/* Seçenekler */
	var option_row = <?php echo $option_row; ?>;
	$('input[name=\'option\']').catcomplete({
		delay: 0,
		source: function(request, response) {
			$.ajax({
				url: '<?php echo yonetim_url(); ?>/urunler/option/autocomplete',
				type: 'POST',
				dataType: 'json',
				data: 'filter_name=' +  encodeURIComponent(request.term),
				success: function(data) {
					response($.map(data, function(item) {
						return {
							category: item.category,
							label: item.name,
							value: item.option_id,
							type: item.type
						}
					}));
				}
			});
		},
		select: function(event, ui) {
			html  = '<div id="tab_option-' + option_row + '" class="vtabs-content">';
			html += '	<input type="hidden" name="product_option[' + option_row + '][product_option_id]" value="" />';
			html += '	<input type="hidden" name="product_option[' + option_row + '][name]" value="' + ui.item.label + '" />';
			html += '	<input type="hidden" name="product_option[' + option_row + '][option_id]" value="' + ui.item.value + '" />';
			html += '	<input type="hidden" name="product_option[' + option_row + '][type]" value="' + ui.item.type + '" />';
			html += '	<table class="form">';
			html += '	  <tr>';
			html += '		<td>Gerekli</td>';
			html += '       <td><select name="product_option[' + option_row + '][required]">';
			html += '	      <option value="1">Evet</option>';
			html += '	      <option value="0">Hayır</option>';
			html += '	    </select></td>';
			html += '     </tr>';
		
			if (ui.item.type == 'text') {
				html += '     <tr>';
				html += '       <td>Seçenek Değeri</td>';
				html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" /></td>';
				html += '     </tr>';
				html += '     <tr>';
				html += '       <td>Maksimum Karakter Sayısı</td>';
				html += '       <td><input type="text" name="product_option[' + option_row + '][character_limit]" maxlength="2" value="" /></td>';
				html += '     </tr>';
			}

			if (ui.item.type == 'textarea') {
				html += '     <tr>';
				html += '       <td>Seçenek Değeri</td>';
				html += '       <td><textarea name="product_option[' + option_row + '][option_value]" cols="40" rows="5"></textarea></td>';
				html += '     </tr>';						
			}

			if (ui.item.type == 'file') {
				html += '     <tr style="display: none;">';
				html += '       <td>Seçenek Değeri</td>';
				html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" /></td>';
				html += '     </tr>';			
			}

			html += '  </table>';

			if (ui.item.type == 'select' || ui.item.type == 'radio' || ui.item.type == 'checkbox') {
				html += '  <table id="option-value' + option_row + '" class="list">';
				html += '  	 <thead>'; 
				html += '      <tr>';
				html += '        <td class="left">Seçenek Değeri</td>';
				html += '        <td class="right">Miktar</td>';
				html += '        <td class="left">Stokdan Düş</td>';
				html += '        <td class="right">Fiyat</td>';
				/*html += '        <td class="right">Puan</td>';
				html += '        <td class="right">Ağırlık</td>';*/
				html += '        <td></td>';
				html += '      </tr>';
				html += '  	 </thead>';
				html += '    <tfoot>';
				html += '      <tr>';
				html += '        <td colspan="4"></td>';
				html += '        <td class="left"><a onclick="add_option_value(' + option_row + ');" class="buton"><span>Seçenek Değeri Ekle</span></a></td>';
				html += '      </tr>';
				html += '    </tfoot>';
				html += '  </table>';
				html += '</div>';	
			}

			$('#tab_option').append(html);

			$('#option-add').before('<a tab="#tab_option-' + option_row + '" id="option-' + option_row + '">' + ui.item.label + '&nbsp;<img src="<?php echo yonetim_resim(); ?>delete.png" alt="" onclick="$(\'#vtab_option a:first\').trigger(\'click\'); $(\'#option-' + option_row + '\').remove(); $(\'#tab_option-' + option_row + '\').remove(); return false;" /></a>');

			$('#vtab_option a').tabs();

			$('#option-' + option_row).trigger('click');		
		
			$('.date').datetimepicker({dateFormat: 'yy-mm-dd',timeFormat:'hh:mm:ss'});
			/*$('.datetime').datetimepicker({
				dateFormat: 'yy-mm-dd',
				timeFormat: 'h:m'
			});*/

			/*$('.time').timepicker({timeFormat: 'h:m'});*/

			option_row++;

			return false;
		}
	});
//--></script> 
<script type="text/javascript"><!--		
	var option_value_row = <?php echo $option_value_row; ?>;
	function add_option_value(option_row) {	
		html  = '<tbody id="option-value-row' + option_value_row + '">';
		html += '  <tr>';
		html += '    <td class="left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value_id]"></select><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][product_option_value_id]" value="" /></td>';
		html += '    <td class="right"><input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][quantity]" value="" size="3" /></td>'; 
		html += '    <td class="left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][subtract]">';
		html += '      <option value="1">Evet</option>';
		html += '      <option value="0">Hayır</option>';
		html += '    </select></td>';
		html += '    <td class="right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price_prefix]">';
		html += '      <option value="+">+</option>';
		html += '      <option value="-">-</option>';
		html += '    </select>';
		html += '    <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price]" value="" size="5" /></td>';
		/*
		html += '    <td class="right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points_prefix]">';
		html += '      <option value="+">+</option>';
		html += '      <option value="-">-</option>';
		html += '    </select>';
		html += '    <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points]" value="" size="5" /></td>';	
		html += '    <td class="right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight_prefix]">';
		html += '      <option value="+">+</option>';
		html += '      <option value="-">-</option>';
		html += '    </select>';
		html += '    <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight]" value="" size="5" /></td>';
		*/
		html += '    <td class="left"><a onclick="$(\'#option-value-row' + option_value_row + '\').remove();" class="buton"><span>Kaldır</span></a></td>';
		html += '  </tr>';
		html += '</tbody>';

		$('#option-value' + option_row + ' tfoot').before(html);

		$('select[name=\'product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value_id]\']').load('<?php echo yonetim_url(); ?>/urunler/product/option?option_id=' + $('input[name=\'product_option[' + option_row + '][option_id]\']').attr('value'));

		option_value_row++;
	}
	/* Seçenekler */

	/* İndirim */
	var discount_row = <?php echo $discount_row; ?>;
	var special_row = <?php echo $special_row; ?>; // eğer kampanya varsa indirim ekleyemesin için eklendi.
	function add_discount() {
	
	if(special_row>0)
	{
		alert('Eklenmiş olan kampanyanız vardır. Önce onu kaldırmanız ve kaydetmeniz gerekmektedir.');
		return false;
	
	}	
		html  = '<tbody id="discount_row' + discount_row + '">';
		html += '<tr>'; 
		html += '<td class="left"><select name="product_discount[' + discount_row + '][user_group_id]" style="margin-top: 3px;">';
		<?php foreach ($customer_groups as $customer_group) { ?>
		html += '<option value="<?php echo $customer_group->id; ?>"><?php echo $customer_group->name; ?></option>';
		<?php } ?>
		html += '</select></td>';
		html += '<td class="left"><input type="text" name="product_discount[' + discount_row + '][priority]" value="" size="2" /></td>';
		html += '<td class="left"><input type="text" name="product_discount[' + discount_row + '][price]" value="" /></td>';
		html += '<td class="left"><input type="text" name="product_discount[' + discount_row + '][date_start]" value="" class="date" /></td>';
		html += '<td class="left"><input type="text" name="product_discount[' + discount_row + '][date_end]" value="" class="date" /></td>';
		html += '<td class="left"><a onclick="$(\'#discount_row' + discount_row + '\').remove();" class="buton"><span>Kaldır</span></a></td>';
		html += '</tr>';	
		html += '</tbody>';
		$('#discount tfoot').before(html);
		$('#discount_row' + discount_row + ' .date').datetimepicker({dateFormat: 'yy-mm-dd',timeFormat:'hh:mm:ss'});
		discount_row++;
	}
	/* İndirim */

	/* Kampanyalar */
	var special_row = <?php echo $special_row; ?>;
	var discount_row = <?php echo $discount_row; ?>;
	function add_special() {
	
		if(discount_row>0)
		{
			alert('Eklenmiş olan indirim kampanyanız vardır. Önce onu kaldırmanız ve kaydetmeniz gerekmektedir.');
			return false;
	
		}	
	
		html  = '<tbody id="special_row' + special_row + '">';
		html += '<tr>'; 
		html += '<td class="left"><select name="product_special[' + special_row + '][user_group_id]" style="margin-top: 3px;">';
		<?php foreach ($customer_groups as $customer_group) { ?>
		html += '<option value="<?php echo $customer_group->id; ?>"><?php echo $customer_group->name; ?></option>';
		<?php } ?>
		html += '</select></td>';
		html += '<td class="left"><input type="text" name="product_special[' + special_row + '][quantity]" value="" size="2" /></td>';
		html += '<td class="left"><input type="text" name="product_special[' + special_row + '][priority]" value="" size="2" /></td>';
		html += '<td class="left"><input type="text" name="product_special[' + special_row + '][price]" value="" /></td>';
		html += '<td class="left"><input type="text" name="product_special[' + special_row + '][date_start]" value="" class="date" /></td>';
		html += '<td class="left"><input type="text" name="product_special[' + special_row + '][date_end]" value="" class="date" /></td>';
		html += '<td class="left"><a onclick="$(\'#special_row' + special_row + '\').remove();" class="buton"><span>Kaldır</span></a></td>';
		html += '</tr>';
		html += '</tbody>';
		$('#special tfoot').before(html);
		$('#special_row' + special_row + ' .date').datetimepicker({dateFormat: 'yy-mm-dd',timeFormat:'hh:mm:ss'});
		special_row++;
	}
	/* Kampanyalar */

	var image_row = <?php echo $image_row; ?>;
	function add_image() {
		html  = '<tbody id="image_row' + image_row + '">';
		html += '<tr>';
		html += '<td class="left"><input type="hidden" name="product_images[' + image_row + ']" value="" id="image' + image_row + '" /><img src="<?php echo show_image('resim_ekle.jpg', 100, 100); ?>" onmouseover="$(this).attr(\'src\', \'<?php echo show_image("resim_ekle_hover.jpg", 100, 100); ?>\');" onmouseout="$(this).attr(\'src\', \'<?php echo show_image('resim_ekle.jpg', 100, 100); ?>\');" alt="" id="preview' + image_row + '" class="image" onclick="image_upload(\'image' + image_row + '\', \'preview' + image_row + '\');" style="cursor: pointer; border: 1px solid #EEEEEE;" />';
		html += ' <img src="<?php echo yonetim_resim(); ?>image.png" alt="" title="Resim eklemek yada değiştirmek için tıklayınız." style="cursor: pointer;" align="top" onclick="image_upload(\'image' + image_row + '\', \'preview' + image_row + '\');"></td>';
		html += '<td class="left"><a onclick="$(\'#image_row' + image_row  + '\').remove();" class="buton"><span>Kaldır</span></a></td>';
		html += '</tr>';
		html += '</tbody>';
		$('#images tfoot').before(html);
		image_row++;
	}
</script>

<?php $this->load->view('yonetim/footer_view');   ?>