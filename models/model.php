<?php
require_once('../config.php');
require_once('../lib/helper.php');

abstract class Model {

	public $id;
	
	public function getTableName() {
		return strtolower(get_class($this));	
	}

	public function save() {
		$database = getDatabase();
		$insert = (isset($this->id)) ? false : $this->selectById();
		return $this->saveToDatabase($insert, $database);
	}

	private function saveToDatabase($insert, $database) {
		$members = get_object_vars($this);
		unset($members['id']);
		$types = array();
		$params = array();
		foreach ($members as $key => $value) {
			$type = '';
			switch (gettype($value)) {
				case 'integer':
					array_push($types, 'i');
					break;
				case 'double':
					array_push($types, 'd');
					break;
				default:
					array_push($types, 's');
					break;
			}
			array_push($params, '?');
		}
		if ($insert) {
			$statement = $database->prepare(sprintf('INSERT INTO %s (%s) VALUES (%s)', $this->getTableName(), implode(',', array_keys($members)), implode(',', $params)));
			call_user_func_array(array(&$statement, 'bind_param'), array_merge(array(implode('', $types)), getReferences(array_values($members))));
			$statement->execute();
			return $statement->get_result();
		} else {
			$updateFields = array();
			foreach ($members as $key => $value) {
				array_push($updateFields, $key . '=?');
			}
			$statement = $database->prepare(sprintf('UPDATE %s SET %s WHERE id = ?', $this->getTableName(), implode(',', $updateFields)));
			array_push($types, 'i');
			call_user_func_array(array(&$statement, 'bind_param'), array_merge(array(implode('', $types)), getReferences(array_merge(array_values($members), array($this->id)))));
			$statement->execute();
			return $statement->get_result();
		}
	}

	public function load() {
		$result = $this->selectById();
		if ($result) {
			$row = $result->fetch_assoc();
			foreach ($this as $key => $value) {
				$this->$key = $row[$key];
			}
			return true;
		}
		return false;
	}

	private function selectById() {
		$database = getDatabase();
		$statement = $database->prepare(sprintf('SELECT * FROM %s WHERE id = ?', $this->getTableName()));
		$statement->bind_param('i', $this->id);
		$statement->execute();
		return $statement->get_result();	
	}

	public function toJson() {
		return json_encode(get_object_vars($this));
	}
}

function getDatabase() {
	return new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
}
?>