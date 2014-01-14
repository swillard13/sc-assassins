<?php
require_once('../lib/facebook/facebook.php');
require_once('../config.php');
require_once('../models/game.php');
require_once('../models/player.php');

$facebook = new Facebook(array(
	'appId' => FACEBOOK_APP_ID,
    'secret' => FACEBOOK_APP_SECRET,
    'fileUpload' => FACEBOOK_FILE_UPLOAD, 
    'allowSignedRequest' => FACEBOOK_ALLOW_SIGNED_REQUEST,
));
if (!$facebook->getUser()) {
	header("HTTP/1.1 401 Unauthorized");
	exit;
}
header('Content-type: application/json');
if (array_key_exists('title', $_POST) && array_key_exists('description', $_POST) && array_key_exists('startDate', $_POST)) {
	$game = new Game();
	if (array_key_exists('id', $_POST)) {
		$game->id = $_POST['id'];
	}
	$game->title = $_POST['title'];
	$game->description = $_POST['description'];
	list($y,$m,$d) = explode('-',$_POST['startDate']);
	if (checkdate($m, $d, $y)) {
		$game->startDate = $_POST['startDate'];	
		$game->admin = $facebook->getUser();
		$game->save();
		$player = new Player();
		$player->user = $game->admin;
		$player->game = $game->id;
		$player->pending = false;
		$player->save();
		echo json_encode(array('success' => true, 'game' => get_object_vars($game)));	
	} else {
		echo json_encode(array('success' => false, 'reason' => 'Invalid start date.'));
	}
}  else {
	echo json_encode(array('success' => false, 'reason' => 'Missing required parameters.'));
}
?>