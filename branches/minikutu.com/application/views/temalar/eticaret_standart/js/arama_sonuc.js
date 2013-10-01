/*
* sa_Goster-custom jQuery Plug-In (http://www.serdarakkilic.com)
*/
(function($) {

	$.fn.saGosterC = function(options){
		var defaults = {h: 60}; 
		var options = $.extend(defaults, options);
		
		this.each(function(){
			var obj = $(this);
			var obj2 = $("a.ara_tumu",obj);
			var orjHeight = obj.height() + "px";
			var h2 = options.h+"px";
			obj.css("height",h2);
			obj.css("cursor","pointer");
			obj.css("overflow","hidden");
			obj2.click(function(){
				if ($(this).attr("sa")=="acik") {
					obj.stop(true).animate({height: options.h}, 400);
					$(this).attr("sa","kapali");
				} else {
					obj.stop(true).animate({height: orjHeight}, 400);
					$(this).attr("sa","acik");
				}
			});
		});
		
	};

})(jQuery);

/*
 sayfascriptleri
*/
$(document).ready(function(){
	//tab
	var goster = "#ara_" + $(".ara_tab_aktif").attr("id");
	$(goster).show();
	$("#ara_tab_cont a").click(function(){
		$("#ara_tab_cont a").removeClass("ara_tab_aktif");
		$(this).addClass("ara_tab_aktif");
		$("#ara_marka, #ara_kategori").hide();
		goster = "#ara_" + $(this).attr("id");
		$(goster).show();
	});
	//tumu
	$("#ara_marka, #ara_kategori").saGosterC({h:20});
	
	//fiyat araligi
	var stepLeft = parseInt($("#af_left").attr("step"))-400;
	var stepRight = parseInt($("#af_right").attr("step"));
	$("#af_left").css("left",stepLeft);
	$("#af_right").css("left",stepRight);
	stepLeft = parseInt($("#af_left").attr("step"));

	var drag = null;
	var full = 400;
	var step = 10;
	var yon = null;
	var min = parseFloat($("#af_min").attr("fiyat"));
	var max = parseFloat($("#af_max").attr("fiyat"));
	var aralik = max- min;
	var steporan = (aralik*2.5)/100;
	min = min + ((stepLeft-10)/10)*steporan;
	max = max - ((400-stepRight)/10)*steporan;
	$("#af_min_fiyat").html(format(min));
	$("#af_max_fiyat").html(format(max));

	// ekledi
	/*$("input#min_fiyat").val(format(min));
	$("input#max_fiyat").val(format(max));*/
	
	function format(n){
		return (Math.round(n * 100) / 100).toLocaleString();
	}
	
	function atla(m,obj){
		var kontrol = parseInt($("#af_right").attr("step")) - parseInt($("#af_left").attr("step"));
		var v =  parseInt(obj.css("left"));
		var n =  parseInt(obj.attr("step"));
		var h = null;
		if (obj.attr("id")=="af_left") {
			if (yon == "saga") {
				if (kontrol>=20) {
					h = v + m;
					if (h > (v+n)) {
						obj.css("left",(v+step));
						n = n + step;
						min = min + steporan;
						$("#af_min_fiyat").html(format(min));
						$("input#min_fiyat").val(format(min));
						//input'a veri girilecek yer
					}
				}
			} else {
				if (v>=-380) {
					h = v + m;
					if (h < v+n) {
						obj.css("left",(v-step));
						n = n-step;
						min = min - steporan;
						$("#af_min_fiyat").html(format(min));
						$("input#max_fiyat").val(format(max));
						//input'a veri girilecek yer
					}
				}
			}
		} else {
			if (yon == "saga") {
				if (v<400){
					h = v + m;
					if (h > (v+n)) {
						obj.css("left",(v+step));
						n = n + step;
						max = max + steporan;
						$("#af_max_fiyat").html(format(max));
						//input'a veri girilecek yer
					}
				}
			} else {
				if (kontrol>=20) {
					h = v - m;
					if (h > step) {
						obj.css("left",(v-step));
						n = n-step;
						max = max - steporan;
						$("#af_max_fiyat").html(format(max));
						//input'a veri girilecek yer
					}
				}
			}
		}
		obj.attr("step",n);
	}

	$("#af_sola, #af_saga").mousedown(function(e){
		drag = true;
		var click = e.pageX - $("#af_bar").offset().left;
		var obj = null;
		if ($(this).attr("id")=="af_sola") {obj=$("#af_left");} else {obj=$("#af_right");}
		
		var simdiki = click;
		$("#af_bar").bind("mousemove",function(m){
			if (simdiki > (m.pageX-$(this).offset().left)) {yon = "sola";} else {yon = "saga";}
			simdiki = m.pageX-$(this).offset().left;
			atla(simdiki,obj);
		});
	});
	$(document).mouseup(function(){
		if (drag) {
			$("#af_bar").unbind("mousemove");
		}
	});
});