function initMenuTip02() {

	$('#kategori_menu02 ul li ul').hide();
	
	$('#kategori_menu02 ul li:first ul').show();
	$('#kategori_menu02 ul li a:first').attr("class","k_aktif");
	
	$('#kategori_menu02 ul li a').click(
		function() {
			var kontrol = $(this).next();
			$('#kategori_menu02 ul li a').attr("class","k_pasif");
			$(this).attr("class","k_aktif");
			if((kontrol.is('ul')) && (kontrol.is(':visible'))) {
				return false;
			}
			if((kontrol.is('ul')) && (!kontrol.is(':visible'))) {
				$('#kategori_menu02 ul li ul:visible').slideUp('normal');
				kontrol.slideDown('normal');
				return false;
			}
		}
	);
}
$(document).ready(function() {initMenuTip02();});