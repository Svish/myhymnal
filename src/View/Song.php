<?php

class View_Song extends View
{
	public function __construct($id)
	{
		Timer::start(__METHOD__, func_get_args());
		
		$this->song = Model_Song::get($id);
		$this->title = $this->song->title;
		$this->canonical = $this->song->permalink;
		$this->text_html = Geekality\Transposer::parse($this->song->text, $this->song->key);

		$key = array_key_exists('key', $_GET)
			? $_GET['key']
			: NULL;

		if($key !== NULL)
		{
			$this->text_html->transpose($key);
			$this->title .= ' ('.$key.')';
		}
		elseif($this->song->key !== NULL)
		{
			$this->title .= ' ('.$this->song->key.')';
		}

		$this->keys = $this->text_html->get_key_selector($this->song->permalink.'?key=');
		
		Timer::stop();
	}
}