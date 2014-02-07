<?php
require_once('model.php');
require_once('player.php');

class Game extends Model {

	public $title;
	public $description;
	public $startDate;
	public $admin;

	public function start() {
		$players = getPlayersForGame($this->id);
		shuffle($players);
		for ($i = 0; $i < count($players) - 1; $i++) {
			$players[$i]->target = $players[$i+1]->id;
			if (!$players[$i]->save()) {
				return false;
			}
		}
		$players[count($players) - 1]->target = $players[0]->id;
		if (!$players[count($players) - 1]->save()) {
			return false;
		}
		return true;
	}
}

function getGameForUser($userId, $gameId, $admin) {
	$database = getDatabase();
	$statement = $database->prepare(sprintf('SELECT game.* FROM game JOIN player ON game.id = player.game
									 WHERE %s = ? AND game.id = ?', ($admin) ? 'game.admin' : 'player.user'));
	$statement->bind_param('ii', $userId, $gameId);
	$statement->execute();
	if ($result = $statement->get_result()) {
		if ($row = $result->fetch_assoc()) {
			$game = new Game();
			$game->loadFromRow($row);
			return $game;
		}
	}
	return false;
}

function getGamesForUser($userId) {
	$database = getDatabase();
	$statement = $database->prepare('SELECT game.* FROM game JOIN player ON game.id = player.game
									 WHERE player.user = ?');
	$statement->bind_param('i', $userId);
	$statement->execute();
	if ($result = $statement->get_result()) {
		$games = array();
		while ($row = $result->fetch_assoc()) {
			$game = new Game();
			$game->loadFromRow($row);
			array_push($games, $game);
		}
		return $games;
	}
	return false;
}
?>