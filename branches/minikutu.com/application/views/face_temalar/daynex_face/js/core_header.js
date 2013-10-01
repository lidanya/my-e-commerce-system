/*
	filename : core_header.js
	author : www.eticaretsistemim.com
	description : Will be valid on the site contains all JavaScript codes.
*/

	function redirect(url) {
		top.location.href = url;
	}

	function site_url(url) {
		var new_url = daynex_site_url + url;
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

		/* Mega Menu */
		$('#menu ul > li > a + div').each(function(index, element) {
			// IE6 & IE7 Fixes
			if ($.browser.msie && ($.browser.version == 7 || $.browser.version == 6)) {
				var category = $(element).find('a');
				var columns = $(element).find('ul').length;
				
				$(element).css('width', (columns * 143) + 'px');
				$(element).find('ul').css('float', 'left');
			}		

			var menu = $('#menu').offset();
			var dropdown = $(this).parent().offset();

			i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());

			if (i > 0) {
				$(this).css('margin-left', '-' + (i + 5) + 'px');
			}
		});

		// IE6 & IE7 Fixes
		if ($.browser.msie) {
			if ($.browser.version <= 6) {
				$('#column-left + #column-right + #content, #column-left + #content').css('margin-left', '195px');
				
				$('#column-right + #content').css('margin-right', '195px');
			
				$('.box-category ul li a.active + ul').css('display', 'block');	
			}

			if ($.browser.version <= 7) {
				$('#menu > ul > li').bind('mouseover', function() {
					$(this).addClass('active');
				});
					
				$('#menu > ul > li').bind('mouseout', function() {
					$(this).removeClass('active');
				});	
			}
		}
	});