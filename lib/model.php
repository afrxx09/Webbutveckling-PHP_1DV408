<?php

class Model{
	protected $connection;
	protected $tabelName;
	protected $columns;
	
	protected function connection() {
		if ($this->connection === NULL){
			$this->connection = new PDO(Config::$DB_CONNECTION_STRING, Config::$DB_USERNAME, Config::$DB_PASSWORD);
		}
		$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $this->connection;
	}
	
	public function find($id){
		return $this->findBy('id', $id);
	}
	
	/**
	*	@return DBO-object|null
	*/
	public function findBy($column, $value){
		try{
			$con = $this->connection();
			$sql = "
				SELECT " . $this->tabelName . ".*
				FROM " . $this->tabelName . "
				WHERE " . $this->tabelName . "." . $column . " = ?
			";
			$params = array($value);
			
			$query = $con->prepare($sql);
			$query->execute($params);
			$res = $query->fetch(PDO::FETCH_ASSOC);
			
			if($res === false){
				throw new Exception("Could not find " .  $this->tabelName . " where " . $column . " is " . $value);
			}
			if(!class_exists($this->tabelName)){
				throw new Exception("Could not find class " . $this->tabelName);
			}
			return new $this->tabelName($res);
		}
		catch(Exception $e){
			return null;
		}	
	}
}
?>