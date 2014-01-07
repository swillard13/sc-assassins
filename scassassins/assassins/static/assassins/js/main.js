$(document).ready(function(){
	bindEvents();
});

function bindEvents(){
	window.fbAsyncInit = function() {
		FB.init({
			appId      : '1408319899413345',
    		status     : true, // check login status
    		cookie     : true, // enable cookies to allow the server to access the session
    		xfbml      : true  // parse XFBML
});
		FB.getLoginStatus(function(response){
			if(response.status !== 'connected'){
				$('#fblogin').modal('show');
			}
			else if(response.status === 'connected'){
				connectActions();
			}
		});
		FB.Event.subscribe('auth.authResponseChange', function(response) { 
			if (response.status === 'connected') {
				$('#fblogin').modal('hide');
				connectActions();
			} else if (response.status === 'not_authorized') {
				FB.login();
			} else {
				FB.login();
			}
		});
	};
	(function(d){
		var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
		if (d.getElementById(id)) {return;}
		js = d.createElement('script'); js.id = id; js.async = true;
		js.src = "//connect.facebook.net/en_US/all.js";
		ref.parentNode.insertBefore(js, ref);
	}(document));

	function connectActions() {
		FB.api('/me', function(response) {
			$('.navbar-right li a').show("fast", function(){
				
			}).text('Welcome ' + response.first_name + '!');
			$('.navbar-right li div').show("fast", function(){

			}).click(function(){
				FB.logout(function(response){
					location.reload();
				});
			});
		});
	}

	$('#navbar a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
}
