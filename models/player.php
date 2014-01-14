<?php
require_once('model.php');

class Player extends Model {
	
	public $user;
	public $game;
	public $pending;

}

function getPlayersForGame($gameId) {
	$database = getDatabase();
	$statement = $database->prepare('SELECT user FROM player JOIN game ON player.game = game.id
									 WHERE player.game = ?');
	$statement->bind_param('i', $gameId);
	$statement->execute();
	if ($result = $statement->get_result()) {
		$players = array();
		while ($row = $result->fetch_assoc()) {
			array_push($players, $row['user']);
		}
		return $players;
	}
	return false;
}
?>