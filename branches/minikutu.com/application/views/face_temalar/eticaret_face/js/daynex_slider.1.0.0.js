/*
* daynex_Silier-1.0.0 jQuery Plug-In
* http://www.eticaretsistemim.com/
*/
(function($) {

	$.fn.dynSlider = function(options){
		//ayarlar
		var defaults = {
			gecisSuresi: 3000,
			sliderGecis: 'fade',
			ileriID: 'dyn_ileri',
			geriID: 'dyn_geri',
			ileriClass:'dyn_ileri',
			geriClass:'dyn_geri',
			ileriText:'ileri',
			geriText:'geri',
			kontroller: false
		}; 
		
		var options = $.extend(defaults, options);
		
		this.each(function(){
			//genel degiskenler
			var dynBannerSayi = 0;
			var dynBanner = 0;
			var dynSlider = null;
			var Slider = $(this);
			
			//hazirlik
			dynBannerSayi = $("li", Slider).length;
			Slider.css("overflow","hidden");
			Slider.css("position","relative");
			$("ul > li", Slider).hide();
			$("ul > li:first", Slider).show();
			
			//kontroller varsa
			if (options.kontroller) {
				var ileri = "#"+options.ileriID;
				var geri = "#"+options.geriID;
				Slider.append('<a href="javascript:;" class="'+options.ileriClass+'" id="'+options.ileriID+'">'+options.ileriText+'</a>');
				Slider.append('<a href="javascript:;" class="'+options.geriClass+'" id="'+options.geriID+'">'+options.geriText+'</a>');
				$(ileri).css("position","absolute");
				$(ileri).css("overflow","hidden");
				$(ileri).css("z-index","999");
				$(geri).css("position","absolute");
				$(geri).css("overflow","hidden");
				$(geri).css("z-index","998");
				$(ileri).click(function(){dynDegistir("ileri");});
				$(geri).click(function(){dynDegistir("geri");});
			}
			//degistir
			function dynDegistir (z) {
				if (!z || z=="") {z="ileri";}
				if (z=="ileri") {dynBanner++;} else {dynBanner--;}
				if(dynBanner==dynBannerSayi) {dynBanner=0;}	
				if(dynBanner<0){dynBanner=(dynBannerSayi-1);}
				var hepsi = $("ul > li", Slider);
				var simdiki = $("ul > li:eq("+dynBanner+")", Slider);
				if (options.sliderGecis=="dikey") {
					hepsi.stop(true,true).slideUp("slow");
					simdiki.stop(true,true).slideDown("slow");
				} else if (options.sliderGecis=="fade") {
					hepsi.stop(true,true).fadeOut("fast");
					simdiki.stop(true,true).fadeIn("slow");
				}
			};
			
			//hover durus
			Slider.hover(function(){
				clearInterval(dynSlider);
			},function(){
				dynSlider = setInterval(function () {dynDegistir();}, options.gecisSuresi);
			});
			
			//init
			dynSlider = setInterval(function () {dynDegistir();}, options.gecisSuresi);
			
		});
		
	};
		
})(jQuery);