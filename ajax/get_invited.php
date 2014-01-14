<?php
	require_once('../lib/facebook/facebook.php');
	require_once('../config.php');
	require_once('../models/game.php');
	$game = new Game();
	$game->id = $_GET['id'];
	if($game->load()){
		header("HTTP/1.1 404 Not Found");
		exit;
	}
	else{
		
	}
?>