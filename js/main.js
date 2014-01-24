$(document).ready(function($){
	bindEvents();
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
				FB.Event.subscribe('auth.authResponseChange', function(response) { 
					if (response.status === 'connected') {
						$('#fblogin').modal('hide');
						connectActions();
					} else if (response.status === 'not_authorized') {
						//FB.login();
					} else {

					}
				});
			}
			else{
				connectActions();
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
				$('#navbar a[href$="#games"]').tab('show');
				loadGames();
				viewGame(data.game.id);
				resetForm();
			},
			error: function(){
				
			}
		});
		e.preventDefault();
	});

	$('[required]').siblings('label').append($('<span style="color: #FF0000;">*</span>'));

	(function(){
		var dateString = getDateString();
		$("#startDate").attr('min', dateString).attr('value',dateString);
	})();

	$('a[href$="#games"]').click(function() {
		loadGames();
	});

	$('#current-game .game-back').click(function(){
		$('#current-game').removeClass('active');
		$('#games-list').addClass('active');
		resetGameEdit(false);
		loadGames();
	});

	$('#current-game .game-edit').click(function(){
		var title = $('#current-game .game-title').text();
		var description = $('#current-game .game-description').text();
		var startDate = $('#current-game .game-start-date').text();
		$(this).after($('<button>').text('Cancel').addClass('game-cancel').click(function(){
			resetGameEdit(false, {
				title : title,
				description : description,
				startDate : startDate}
				)
		}));
		$(this).after($('<button>').text('Save').addClass('game-save').click(function(){resetGameEdit(true, null)}));
		$.editableFactory['text'].toEditable(title, $('#current-game .game-title').empty());
		$.editableFactory['textarea'].toEditable(description, $('#current-game .game-description').empty());
		$.editableFactory['date'].toEditable(startDate, $('#current-game .game-start-date').empty());
		$('#current-game').append($('<div>').addClass('inviteList').addClass('form-group'));
		$('#current-game').append($('<div>').addClass('form-group').append(($('<a>').addClass('bt-fs-dialog').addClass('fbbutton').text('Invite Friends'))));
		loadPlayersWithId($('#current-game').data('id'));
		initFriendSelector();
		$(this).hide();
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

function getPlayerList(players){
	var selected = $('.inviteList div[data-id]');
		$.each(selected, function(key, data){
			var j = $(data);
			players.push(parseInt(j.data('id')));
	});
}

function loadPlayersWithId(gameId){
	console.log($("#current-game").data('id'));
	$.get('ajax/get_players.php', {'id' : gameId}, function(data){
		if(data){
			$.each(data, function(key, playerId){
				getPlayerFbInfo(playerId, false);
			});
		}
	});
}

function getPlayerFbInfo(playerId, send){
	FB.api('/', 'POST', {
		batch: [
		{ method: 'GET', relative_url: playerId },
		]
	}, function (response) {
		var player = $.parseJSON(response[0]['body']);
		if(send){
			$.post('./ajax/add_player.php', {'gameId' : $('#current-game').data('id'), 'playerId' : playerId});
		}
		addPlayerToList(player);
	});
}

function addPlayerToList(data, accepted){
	var tile = createPlayerTile(data);
	$('.inviteList').append(tile);
}

function createPlayerTile(data){
	var tile = $('<div>').attr('data-id',data.id).attr('class', 'playerTile').append($('<div>').text(data.name)).append($('<span>').attr('title','Remove').attr('class','removePlayer').html('&times').click(function(){
		var playerId = $(this).parent().data('id');
		$.ajax({
			type: 'POST',
			url: './ajax/remove_player.php',
			data: {'gameId' : $('#current-game').data('id'), 'playerId' : playerId},
			success: function(){
				$('.playerTile[data-id=' + playerId + ']').remove();
			},
			error: function(){
				
			}
		});
	}));
	return tile;
}

function initFriendSelector(){
	var players = [];
	$(".bt-fs-dialog").fSelector({
		closeOnSubmit: true,
		showButtonSelectAll: false,
		onPreStart: function(){
			players.length = 0;
			getPlayerList(players);
		},
		excludeIds: players,
		onSubmit: function(response){
			if(response !== null && response.length > 0){
				for(var i = 0;i<response.length;i++){
					getPlayerFbInfo(response[i], true);
				}
			}
		},
	});
}

function createGameTile(data) {
	var game = $('<li>');
	game.append($('<h4>').text(data.title));
	game.attr('data-id', data.id);
	game.data('id', data.id);
	game.click(function() {
		viewGame($(this).data('id'));
	});
	return game;
}

function viewGame(id) {
	$('#current-game').addClass('active');
	loadGameWithId(id);
	$('#current-game').attr('data-id', id);
	$('#current-game').data('id', id);
	$('#games-list').removeClass('active');
}

function loadGames() {
	$('#games-list').empty();
	$.get('./ajax/get_games.php', function(data) {
		if (data) {
			$.each(data, function(key, entry){
				$('#games-list').append(createGameTile(entry));
			});
		}
	});
}

function loadGameWithId(id) {
	$.get('./ajax/get_games.php', {'id' : id}, function(data) { loadGame(data); });
}

function loadGame(data) {
	$('#current-game .game-title').text(data.title);
	$('#current-game .game-description').text(data.description);
	$('#current-game .game-start-date').text(data.startDate);
}

function resetGameEdit(save, data) {
	if (!data) {
		data = {
			title : $('#current-game .game-title input').val(),
			description : $('#current-game .game-description textarea').val(),
			startDate : $('#current-game .game-start-date input').val()
		};
	}
	if (save) {
		$.post('./ajax/update_game.php', $.extend({'id' : $('#current-game').data('id')}, data));
	}
	loadGame(data);
	$('#current-game .game-title').remove("input").html(data.title).show();
	$('#current-game .game-description').remove("input").html(data.description).show();
	$('#current-game .game-start-date').remove("input").html(data.startDate).show();
	$('#current-game .game-save').remove();
	$('#current-game .game-cancel').remove();
	$('#current-game .game-edit').show();
	$('.inviteList').remove();
	$('.bt-fs-dialog').remove();
}

function resetForm(){
	$('#name').val('');
	$('#description').val('');
	$('#startDate').val(getDateString());
}

$.editableFactory = {
	'text': {
		toEditable: function($value, $this, $maxLength){
			$('<input/>').addClass('form-control').attr('type', 'text').appendTo($this).val($value);
		},
		getValue: function($this){
			return $this.children().val();
		}
	},
	'textarea': {
		toEditable: function($value, $this, $maxLength){
			$('<textarea/>').addClass('form-control').appendTo($this)
			.val($value).attr('maxlength', $maxLength).resizeTextArea();
		},
		getValue: function($this){
			return $this.children().val();
		}
	},
	'date': {
		toEditable: function($value, $this, $maxLength){
			$('<input/>').addClass('form-control').attr('type', 'date').appendTo($this).val($value);
		},
		getValue: function($this){
			return $this.children().val();
		}
	},
}

$.fn.resizeTextArea = function() {
	var scrollHeight = $(this).prop('scrollHeight');
	var lineHeight = parseInt($(this).css('line-height').replace('px', ''));
	var rows = scrollHeight / lineHeight;
	$(this).attr('rows', rows);
}