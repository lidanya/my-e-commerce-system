$.fn.tabs = function() {
	var selector = this;
	
	this.each(function() {
		var obj = $(this); 
		
		$(obj.attr('tab')).hide();
		
		$(obj).click(function() {
			$(selector).removeClass('selected');
			
			$(selector).each(function(i, element) {
				$($(element).attr('tab')).hide();
			});
			
			$(this).addClass('selected');
			
			$($(this).attr('tab')).show();
			
			return false;
		});
	});

	$(this).first().click();
};