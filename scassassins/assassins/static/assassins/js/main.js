$(document).ready(function($){
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
			else{
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
				//FB.login();
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

	$(".bt-fs-dialog").fSelector({
		closeOnSubmit: true,
		facebookInvite: false,
		showButtonSelectAll: false,
		onSubmit: function(response){
			if(response.length > 0){
				console.log(response);
				for(var i = 0;i<response.length;i++){
					var url = "https://graph.facebook.com/" + response[i] + "/";
					var user_name = "";
					$.ajax({
						type: "POST",
						url: url,
						dataType: 'json',
						success: function(data){
							alert(data);
						},
						error: function(){
							alert('failure')
						}
					});
					$('#inviteList').append($('<div>').append($('<img>').attr('src', url + "picture")));
				}
			}
		}
	});

	/*$('#create #friendpicker').click(function(){
		FB.ui({method: 'apprequests',
			message: 'Assassin Request'
		}, function(response){
			if(response && response.to){
				console.log(response);
				var request_ids = [];
				for(i=0; i<response.to.length; i++) {
					var temp = response.request + '_' + response.to[i];
					request_ids.push(temp);
				}
				var requests = request_ids.join(',');
				alert(requests);
			}
		});
		return false;
	});*/
	$("#createForm").submit(function(e){
		alert($(this).serialize());
		var url= "create_game";
		$.ajax({
			type: "POST",
			url: url,
			data: $(this).serialize(),
			success: function(data){
				alert(data);
			},
			error: function(){
				alert('failure');
			}
		});
		return false;
	});
	$('#navbar a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
}
function connectActions() {
	FB.api('/me', function(response) {
		$('.navbar-right li a').fadeIn("slow", function(){

		}).text('Welcome ' + response.first_name + '!');
		$('.navbar-right li div').fadeIn("slow", function(){

		}).click(function(){
			FB.logout(function(response){
				location.reload();
			});
		});
	});
}
