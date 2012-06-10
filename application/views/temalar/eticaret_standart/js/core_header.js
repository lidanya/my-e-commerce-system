

	function redirect(url) {
		document.location.href = url;
	}

	function site_url(url) {
		var new_url = eticaret_site_url + url;
		return new_url;
	}

	$(document).ready(function() {
		//uye paneli
		$("#h_uye_panel").hover(function(){
			$(this).addClass("hd_aktif");
			$("ul",$(this)).stop(true,true).slideDown("fast");
		},
		function(){
			$(this).removeClass("hd_aktif");
			$("ul",$(this)).stop(true,true).slideUp(50);
		});
		//dil secimi ve para birimi secimi
		$("#h_dil, #h_para").hover(function(){
			$(this).addClass("hd_aktif");
			$("ul",$(this)).stop(true,true).slideDown("fast");
		},
		function(){
			$(this).removeClass("hd_aktif");
			$("ul",$(this)).stop(true,true).slideUp(50);
		});
		//header arama modulu

		$("#h_a_kat").hover(function(){
			$(this).addClass("h_a_aktif");
			$("div",$(this)).stop(true,true).slideDown("fast");
		},
		function(){
			$(this).removeClass("h_a_aktif");
			$("div",$(this)).stop(true,true).slideUp(50);
		});
		$("#h_a_kat div a").click(function(){
			$("#h_a_kat_txt").text($(this).text());
			$("#kategori_kriter").val($(this).attr("dyn"));
			$("#h_a_kat div").stop(true,true).slideUp(50);
		});

		//yan arama modulu
		$('#kategori_box2').focus(function(){
			kategori_goster2();
		});
		$('#kategori_box2').blur(function(){
			kategori_gizle2();
		});
		$('#y_arama_select').click(function(){
			$('#kategori_box2').focus();
		});
		$('#y_arama_kategoriler ul > li > a').click(function(){
			$('#kategori_kriter2').attr('value',$(this).attr("kategori"));
			$('#kategori_box2').attr('value',$(this).text());
		});

		function kategori_goster2() {
			$("#y_arama_kategoriler").fadeIn("fast");
			$("#y_arama_select").attr("class","sola y_a_aktif");
		}

		function kategori_gizle2() {
			$("#y_arama_kategoriler").fadeOut("fast");
			$("#y_arama_select").attr("class","sola");
		}

		$('.switcher').bind('click', function() {$(this).find('.option').slideToggle('fast');});
		$('.switcher').bind('mouseleave', function() {$(this).find('.option').slideUp('fast');});

		/*$("#h_banner").dynSlider({
			gecisSuresi: 3000,
			sliderGecis: 'fade'
		});*/
	});