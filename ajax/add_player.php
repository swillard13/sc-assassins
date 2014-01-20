<?php
require_once('../lib/facebook/facebook.php');
require_once('../config.php');
require_once('../models/player.php');
require_once('../models/game.php');

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
if (array_key_exists('gameId', $_POST) && array_key_exists('playerId', $_POST)) {
	if ($game = getGameForUser($facebook->getUser(), $_POST['gameId'], true)) {
		$player = new Player();
		$player->user = $playerId;
		$player->game = $game->id;
		$player->pending = 1;
		if ($player->save()) {
			echo json_encode(array('success' => true));
		} else {
			echo json_encode(array('success' => false));	
		}
	} else {
		http_response_code(401);
	}
} else {
	http_response_code(400);
}
?>