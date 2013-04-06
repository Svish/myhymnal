<?php

class Query
{
	private $s;

	public function __construct(PDOStatement $statement)
	{
		$this->s = $statement;
	}


	public function bindParam($parameter, &$variable, $data_type = PDO::PARAM_STR)
	{
		$this->s->bindParam($parameter, $variable, $data_type);
		return $this;
	}

	public function bindValue($parameter, $value, $data_type = PDO::PARAM_STR)
	{
		$this->s->bindParam($parameter, $value, $data_type);
		return $this;
	}

	public function execute()
	{
		$this->s->execute();
		return $this;
	}

	public function fetch($class_name)
	{
		return $this->s->fetchObject($class_name);
	}

	public function fetchAll($fetch_argument = NULL, $ctor_arguments = NULL, $fetch_style = PDO::FETCH_CLASS)
	{
		$r = $this->s->fetchAll($fetch_style, $fetch_argument, $ctor_arguments);
		return empty($r) ? FALSE : $r;
	}
}