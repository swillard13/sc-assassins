<?php
require_once('model.php');

class Player extends Model {
	
	public $user;
	public $game;
	public $pending;
	public $target;
	public $killer;

}

function getPlayerForGame($gameId, $userId) {
	$database = getDatabase();
	$statement = $database->prepare('SELECT player.* FROM player JOIN game ON player.game = game.id
									 WHERE player.game = ? AND player.user = ?');
	$statement->bind_param('ii', $gameId, $userId);
	$statement->execute();
	if ($result = $statement->get_result()) {
		if ($row = $result->fetch_assoc()) {
			$player = new Player();
			$player->loadFromRow($row);
			return $player;
		}
	}
	return false;
}

function getPlayersForGame($gameId) {
	$database = getDatabase();
	$statement = $database->prepare('SELECT player.* FROM player JOIN game ON player.game = game.id
									 WHERE player.game = ?');
	$statement->bind_param('i', $gameId);
	$statement->execute();
	if ($result = $statement->get_result()) {
		$players = array();
		while ($row = $result->fetch_assoc()) {
			$player = new Player();
			$player->loadFromRow($row);
			array_push($players, $player);
		}
		return $players;
	}
	return false;
}
?>