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
if (array_key_exists('id', $_POST)) {
	if ($game = getGameForUser($facebook->getUser(), $_POST['id'], false)) {
		if (array_key_exists('title', $_POST)) {
			$game->title = $_POST['title'];
		}
		if (array_key_exists('description', $_POST)) {
			$game->description = $_POST['description'];
		}
		if (array_key_exists('startDate', $_POST)) {
			$game->startDate = $_POST['startDate'];
		}
		if ($game->save()) {
			echo json_encode(array('success' => true));
		} else {
			echo json_encode(array('success' => false));
		}
		}
	} else { 
		http_response_code(404);
	}
} else {
	http_response_code(400);
}