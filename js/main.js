$(document).ready(function($){
	bindEvents();
	$('a[href$="#games"]').click(function() {
		$('#games-list').empty();
		$.get('./ajax/get_games.php', function(data) {
			if (data) {
				$.each(data, function(key, entry){
					$('#games-list').append(createGameTile(entry));
				});
			}
		});
	});
});

function bindEvents(){
	(function(d){
		var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
		if (d.getElementById(id)) {return;}
		js = d.createElement('script'); js.id = id; js.async = true;
		js.src = "//connect.facebook.net/en_US/all.js";
		ref.parentNode.insertBefore(js, ref);
	}(document));
	
	$('#navbar a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});

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
				//FB.login();
			} else {

			}
		});
	};

	$("#createForm").submit(function(e){
		var url = $(this).attr('action');
		var type = $(this).attr('method');
		$.ajax({
			type: type,
			url: url,
			data: $(this).serialize(),
			success: function(data){
				
			},
			error: function(){
				alert('failure');
			}
		});
		e.preventDefault();
	});

	$('[required]').siblings('label').append($('<span style="color: #FF0000;">*</span>'));

	(function(){
		var dateString = getDateString();
		$("#startDate").attr('min',dateString).attr('value',dateString);
	})();

	$(".bt-fs-dialog").fSelector({
		closeOnSubmit: true,
		facebookInvite: false,
		showButtonSelectAll: false,
		onSubmit: function(response){
			if(response.length > 0){
				for(var i = 0;i<response.length;i++){
					var id = response[i];
					var url = "https://graph.facebook.com/" + id + "/";
					FB.api('/', 'POST', {
						batch: [
						{ method: 'GET', relative_url: id },
						]
					}, function (response) {
						var data = $.parseJSON(response[0]['body']);
						$('#inviteList').append($('<div>').attr('id',data.id).append($('<img>').attr('src', "https://graph.facebook.com/" + data.id + "/picture")).append($('<div>').text('name: ' + data.name)));
					});
				}
			}
		}
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

function getDateString(){
	Date.prototype.yyyymmdd = function() {
		var yyyy = this.getFullYear().toString();
		var mm = (this.getMonth()+1).toString();
		var dd  = this.getDate().toString();
		return yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]);
	};
	d = new Date();
	return d.yyyymmdd();
}

function createGameTile(data) {
	var game = $('<li>');
	game.append($('<h4>').text(data.title));
	var content = $('<div>').addClass('game-content');
	content.append($('<p>').text(data.description));
	content.append($('<p>').text(data.startDate));
	game.append(content);
	game.attr('data-game-id', data.id);
	game.click(function() {
		if ($(this).hasClass('active')) {
			$(this).removeClass('active');
		} else {
			$(this).addClass('active');
		}
	});
	return game;
}