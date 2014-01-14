<?php
require('model.php');

class Game extends Model {

	public $title;
	public $description;
	public $startDate;
	public $admin;

}

function getGamesForUser($userId) {
	$database = getDatabase();
	$statement = $database->prepare('SELECT * FROM game JOIN player ON game.id = player.game
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