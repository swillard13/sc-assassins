<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="./js/vendor/jquery-1.10.1.min.js"><\/script>')</script>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/chosen.min.css">
	<link rel="stylesheet" href="css/main.css">
	<script src="js/vendor/modernizr-2.6.2.min.js"></script>
	<script src="js/vendor/bootstrap.min.js"></script>
	<link type="text/css" href="plugins/friend-selector/friend-selector/jquery.friend.selector-1.2.1.css" rel="stylesheet" />
	<script type="text/javascript" src="plugins/friend-selector/friend-selector/jquery.friend.selector-1.2.1.js" ></script>
	<style>
		body{
			padding-top:50px;
			padding-bottom:20px;
			padding-left:20px;
		}
	</style>
</head>
<body>
	<div id="fb-root"></div>
	<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
		<a class="navbar-brand" href="./">SC-Assassins</a>
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<div class="collapse navbar-collapse" role="navigation">
			<div class="navbar-header">
			</div>
			<ul class="nav navbar-nav" id="navbar">
				<li class="active"><a href="#home" data-toggle="tab">Home</a></li>
				<li><a href="#games"data-toggle="tab">My Games</a></li>
				<li><a href="#create" data-toggle="tab">Create a Game</a></li>
				<li><a href="#leaders" data-toggle="tab">Leaderboards</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li id="welcome"><a style="display: none;cursor:default;"></a></li>
				<li><div class="fbbutton" id="logout" style="display: none;">Log Out</div></li>
			</ul>
		</div>
	</nav>

	<div class="tab-content">
		<div class ="tab-pane active" id="home">Home</div>
		<div class="tab-pane" id="games">
			<h3>My Games</h3>
			<ul id="games-list" class="active"></ul>
			<div id="current-game">
				<button class="game-back">Back</button>
				<button class="game-edit admin-control">Edit</button>
				<h3 class="game-title"></h3>
				<p class="game-description"></p>
				<p class="game-start-date"></p>
				<button class="game-start admin-control">Start Game</button>
			</div>
		</div>
		<div class="tab-pane" id="create">
			<h3>Create your Game</h3>
			<form id="createForm" role="form" method="post" action="ajax/create_game.php">
				<div class="form-group">
					<label for="name">Game Name</label>
					<input id="name" class="form-control" type="text" name="title" placeholder="Give your game a name" style="width:25%;min-width:150px;" required>
				</div>
				<div class="form-group">
					<label for="gameDescription">Game Description</label>
					<textarea id="description" class="form-control" rows="5" name="description" form="createForm" placeholder="Give rules or information about the game" style="width:25%;min-width:150px;"></textarea>
				</div>
				<div><button type="submit" class="btn btn-default">Create</button></div>
			</form>
		</div>
		<div class="tab-pane" id="leaders">Leaderboards</div>
	</div>

	<div data-backdrop='static' data-keyboard='false' class="modal fade" id="fblogin" tabindex="-1" role="dialog" aria-labelledby="fblogin" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="logintitle">Connect through Facebook</h4>
				</div>
				<div class="modal-body">
					<fb:login-button show-faces="false" width="200" max-rows="1"></fb:login-button>
				</div>
				<div class="modal-footer">
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</body>
<script src="js/main.js"></script>
</html>