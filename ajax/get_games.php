<?php
require_once('../lib/facebook/facebook.php');
require_once('../config.php');
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
$game = new Game();
if (array_key_exists('gameId', $_GET)) {
	$game->id = $_GET['gameId'];
	if (!$game->load()) {
		http_response_code(404);
		exit;
	}
	header('Content-type: application/json');
	echo $game->toJson();
} else {
	if ($games = getGamesForUser($facebook->getUser())) {
		header('Content-type: application/json');
		echo json_encode($games);
	} 
}
?>