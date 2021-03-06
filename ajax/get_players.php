<?php
require_once('../lib/facebook/facebook.php');
require_once('../config.php');
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
if (array_key_exists('id', $_GET)) {
	if ($players = getPlayersForGame($_GET['id'])) {
		echo json_encode($players);
	} else {
		http_response_code(404);
	}
} else {
	http_response_code(400);
}
?>