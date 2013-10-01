$(document).ready(function(){
	//uye kontrol tablar
	$("#uk_baslik a").click(function(){
		var tabID= "#"+$(this).attr("id")+"_tab";
		$("#uk_baslik a").removeClass("uk_aktif");
		$(this).addClass("uk_aktif");
		$(".uk_tab").stop(true,true).fadeOut("fast");
		$(tabID).stop(true,true).fadeIn("fast");
	});
	//tooltipler
	$("#ft_secenekler a span").saTip();
	$(".info").saTip();
	//fatura tablar
	$("#ft_fatura_tab").attr("dyn",$("#ft_fatura_tab").outerHeight(true)).css({"height":0,"border-top":"solid 1px #dedede","padding-top":"10px;","margin-top":"30px"}).hide();
	$("#ft_not_tab").attr("dyn",$("#ft_not_tab").outerHeight(true)).css({"height":0,"border-top":"solid 1px #dedede","padding-top":"10px;","margin-top":"30px"}).hide();
	$("#ft_teslimat_tab").attr("dyn",$("#ft_teslimat_tab").outerHeight(true)).css({"height":0,"border-top":"solid 1px #dedede","padding-top":"10px;","margin-top":"30px"}).hide();
	$("#ft_secenekler a").click(function(){
		var id="#"+$(this).attr("id")+"_tab";
		if ($(id).is(":visible")) {
			$(id).stop(true).animate({height: 0}, 400, function(){$(id).hide();});
			$(this).removeClass("ft_aktif");
		} else {
			$(id).show().stop(true).animate({height: parseInt($(id).attr("dyn"))}, 400);
			$(this).addClass("ft_aktif");
		}
	});
	//Kredi Karti Tablar
	$("#os_kredi_linkler a").click(function(){
		$("#os_kredi_linkler a").removeClass("k_aktif");
		$("#k_tek_cekim_tab, #k_taksitli_cekim_tab").hide();
		$("#"+$(this).attr("id")+"_tab").stop(true, true).fadeIn("fast");
		$('.k_tip_secim').attr('checked','');
		$('#'+ $(this).attr("id") +'_tip').attr('checked','checked');
		$(this).addClass("k_aktif");
	});
	
});