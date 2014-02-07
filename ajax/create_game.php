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
	http_response_code(401);
	exit;
}
header('Content-type: application/json');
if (array_key_exists('title', $_POST) && array_key_exists('description', $_POST)) {
	$game = new Game();
	$game->title = $_POST['title'];
	$game->description = $_POST['description'];
	$game->admin = $facebook->getUser();
	if ($game->save()) {
		$player = new Player();
		$player->user = $game->admin;
		$player->game = $game->id;
		$player->pending = 0;
		if ($player->save()) {
			echo json_encode(array('success' => true, 'game' => get_object_vars($game)));
		} else {
			echo json_encode(array('success' => false, 'reason' => 'Unable to save player.'));	
		}
	} else {
		echo json_encode(array('success' => false, 'reason' => 'Unable to save game.'));	
	}
}  else {
	http_response_code(400);
	echo json_encode(array('success' => false, 'reason' => 'Missing required parameters.'));
}
?>