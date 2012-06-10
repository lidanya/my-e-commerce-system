function initMenuTip03() {
	var k03_x = $('#kategori_menu03 ul li ul').width();
	$('#kategori_menu03 ul li ul').hide();
	$('#kategori_menu03 ul li').css("position","relative");
	$('#kategori_menu03 ul li ul').css("position","absolute");
	$('#kategori_menu03 ul li ul').css("z-index","9999");
  	$('#kategori_menu03 ul li ul').css("left", k03_x+"px");
 	$('#kategori_menu03 ul li ul').css("top", "0px");
  
	$('#kategori_menu03 ul li').hover(
	 	function(){
	 		$(this).find("ul").stop(true,true).show("fast");
	 		$(this).attr("class", "k_aktif");
	 	}, function(){
	 		$(this).find("ul").stop(true,true);
	 		$(this).find("ul").hide();
	 		$(this).attr("class", "k_pasif");
	 	}
	);
	$('#kategori_menu03 ul li ul').hover (
		function() {
			
		}, function() {
		$('#kategori_menu03 ul li ul').hide();	
		} 	
 	);
}
$(document).ready(function() {initMenuTip03();});