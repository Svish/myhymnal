<?php

abstract class DynObj
{
	private $data = array();

	public function __set($name, $value)
	{
		$this->data[$name] = $value;
	}

	public function __get($name)
	{
		if (array_key_exists($name, $this->data))
			return $this->data[$name];

		$trace = debug_backtrace();
		trigger_error(
			'Undefined property via __get(): ' . $name .
			' in ' . $trace[0]['file'] .
			' on line ' . $trace[0]['line'],
			E_USER_NOTICE);
		return NULL;
	}

	protected function set(array $data)
	{
		$this->data = array_merge($this->data, $data);
	}

	public function __isset($name)
	{
		return array_key_exists($name, $this->data);
	}

	public function __unset($name)
	{
		unset($this->data[$name]);
	}
}