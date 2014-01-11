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
	header("HTTP/1.1 401 Unauthorized");
	exit;
}
$game = new Game();
$game->id = $_GET['id'];
if (!$game->load()) {
	header("HTTP/1.1 404 Not Found");
	exit;
}
header('Content-type: application/json');
echo $game->toJson();
?>