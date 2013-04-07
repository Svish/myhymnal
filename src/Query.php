<?php

/**
 * A simple PDOStatement wrapper for method chaining 
 * and streamlining of common defaults.
 */
class Query
{
	private $pdo;

	public function __construct(PDOStatement $statement)
	{
		$this->pdo = $statement;
	}

	public function bindParam($parameter, &$variable, $data_type = PDO::PARAM_STR)
	{
		$this->pdo->bindParam($parameter, $variable, $data_type);
		return $this;
	}

	public function bindValue($parameter, $value, $data_type = PDO::PARAM_STR)
	{
		$this->pdo->bindParam($parameter, $value, $data_type);
		return $this;
	}

	public function execute($input_parameters = NULL)
	{
		$this->pdo->execute($input_parameters);
		return $this;
	}

	public function fetch($class_name, $ctor_arguments = NULL)
	{
		return $this->pdo->fetchObject($class_name, $ctor_arguments);
	}

	public function fetchAll($fetch_argument = 'stdClass', $ctor_arguments = NULL, $fetch_style = PDO::FETCH_CLASS)
	{
		return $this->pdo->fetchAll($fetch_style, $fetch_argument, $ctor_arguments);
	}

	public function fetchColumn($column = 0)
	{
		return $this->pdo->fetchColumn($column);
	}

	public function debug()
	{
		$this->pdo->debugDumpParams();
		return $this;
	}
}