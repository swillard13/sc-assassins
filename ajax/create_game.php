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
header('Content-type: application/json');
if (array_key_exists('title', $_POST) && array_key_exists('description', $_POST) && array_key_exists('startDate', $_POST)) {
	$game = new Game();
	$game->title = $_POST['title'];
	$game->description = $_POST['description'];
	list($y,$m,$d) = explode('-',$_POST['startDate']);
	if (checkdate($m, $d, $y)) {
		$game->startDate = $_POST['startDate'];	
		$game->admin = $facebook->getUser();
		echo json_encode(array('success' => true));	
		exit;
	}
} 
echo json_encode(array('success' => false));
?>