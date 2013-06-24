(function($) {

	/*
		Plugin yazılış tarihi : 6 mayıs 2011
		Yazar : Serkan Koch / 
		Versiyon : 1.0.1
	*/

	/*
		Değişim Kaydı
		1.0.0
			Genel ajax post işlevleri eklendi.
			Çalışabilir konuma getirildi.
		1.0.1
			aksiyon_yapilirken_islem fonksiyonu eklendi.
			aksiyon_tamamlaninca_islem fonksiyonu eklendi.
			aksiyon_sonucu_islem fonksiyonu eklendi.
	*/

	$.fn.ajax_post = function(ayarlar) {

		/*
			aksiyon_adresi				: işlem adresi http yada https
			aksiyon_tipi				: POST yada GET
			aksiyon_data				: gönderilen değerler post ve get değerleri, örn: veri=deneme&degerler=deneme2, yada $('#form_id').serialize();
			aksiyon_data_tipi			: data tipi xml, json, script, or html
			aksiyon_data_sonuc_degeri	: işlem sonucunda alınacak return değeri
			aksiyon_sonucu_bekle		: async, işlem sonucunu beklemeden başka işlem yaptırtma açık mı kapalımı boolean
			aksiyon_yapilirken_islem	: function(){ islem yapılırken kullanılabilir orn yükleniyor vs tarzı }
			aksiyon_tamamlaninca_islem	: function(){ yukleniyor yapıyoruz diyelim onun divini sildirmek vs }
			aksiyon_sonucu_islem		: function(islem_sonucu_gelenler){ islem vsvs }
		*/

		/*
			Basit Örnek;
			<script type="text/javascript" src="http://www.siteadresi.com/ajax_post.js"></script>

			var deneme = $.fn.ajax_post({
				aksiyon_adresi					: 'http://www.siteadresi.com/json_deneme',
				aksiyon_tipi					: 'POST',
				aksiyon_data					: 'deneme=asdasd',
				aksiyon_data_tipi				: 'json',
				aksiyon_data_sonuc_degeri		: false,
				aksiyon_sonucu_bekle			: false,
				aksiyon_yapilirken_islem		: function() {
					$('#div_yukleniyor').html('<img src="http://www.siteadresi.com/yukleniyor.gif" alt="yükleniyor" />');
				},
				aksiyon_tamamlaninca_islem		: function() {
					$('#div_yukleniyor').remove();
				},
				aksiyon_sonucu_islem			: function(data) {
					var sonuc = 'deneme1';
					if(data.basarili == sonuc) {
						alert('başarılı sonucu '+ sonuc +'dir');
					} else {
						alert('başarılı sonucu '+ sonuc +' değildir');
					}
				}
			});
			alert(deneme);
		*/

		// standart ayarlar
		var standart_ayarlar = {
			'aksiyon_adresi'					: '',
			'aksiyon_tipi'						: 'POST',
			'aksiyon_data'						: '',
			'aksiyon_data_tipi'					: 'json',
			'aksiyon_data_sonuc_degeri'			: false,
			'aksiyon_sonucu_bekle'				: false,
			aksiyon_yapilirken_islem			: function() {},
			aksiyon_tamamlaninca_islem			: function() {},
			aksiyon_sonucu_islem				: function() {}
		};

		var ayarlar = $.extend(standart_ayarlar, ayarlar);

		$.extend({
			ajax_post_ajax_form_gonder_sonuc_al : function(ayarlar) {
				var result = ayarlar.aksiyon_data_sonuc_degeri;
				$.ajax({
					url: ayarlar.aksiyon_adresi,
					type: ayarlar.aksiyon_tipi,
					data: ayarlar.aksiyon_data,
					dataType: ayarlar.aksiyon_data_tipi,
					async: ayarlar.aksiyon_sonucu_bekle,
					beforeSend: function() {
						// Geri çağırım işlemleri
						ayarlar.aksiyon_yapilirken_islem();
					},
					complete: function() {
						// Geri çağırım işlemleri
						ayarlar.aksiyon_tamamlaninca_islem();
					},
					success: function(data) {
						// Geri çağırım işlemleri
						ayarlar.aksiyon_sonucu_islem(data);
						result = data;
					}
				});
				return result;
			}
		});

		var sonuc = $.ajax_post_ajax_form_gonder_sonuc_al(ayarlar);
		return sonuc;
	};

})(jQuery);