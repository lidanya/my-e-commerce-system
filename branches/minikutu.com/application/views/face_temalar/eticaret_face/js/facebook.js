window.fbAsyncInit = function() {
	FB.init({
		appId   : facebook_app_id,
		status  : true, // check login status
		cookie  : true, // enable cookies to allow the server to access the session
		xfbml   : true // parse XFBML
	});

	/* IE DE HATA VERDIGINDEN SORUN COZULENEDEK PASIFE ALINDI
	// whenever the user logs in, we refresh the page
	FB.Event.subscribe('auth.login', function() {
		window.location.reload();
	});
	*/

	//FB.Canvas.setSize();
	FB.Canvas.setAutoResize();
};

/**
 * Arkadaşlarını uygulamaya davet et.
 */
function invite_friends(title, message) {
	if (title == '') {
		title = 'Uygulama Davet Başlığı';
	};

	if (message == '') {
		message = 'Uygulama davet mesaj icerigi';
	};

	FB.ui({
		method: 'apprequests',
		title:  title,
		message: message
	});
}

/**
 * Duvarıma gönder
 */
function send_wall(options) {
	FB.ui({
		app_id: facebook_app_id,
		method: 'feed',
		name: options.name,
		caption: options.caption,
		description: options.description,
		message: '',
		link: options.link,
		picture: options.picture
	});
}

// Facebook oturum kapatma.
function fb_logout() {
	FB.logout(function(response){
		alert(response);
	});
}

// izin kontrolü
function check_perms() {
	FB.ui({
		method: 'oauth',
		scope: 'offline_access',
		cilent_id: facebook_app_id,
		redirect_uri: facebook_app_url,
		response_type: 'token'
	});
}