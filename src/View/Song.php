<?php

class View_Song extends View
{
	public function __construct($id, $key = NULL)
	{
		Timer::start(__METHOD__, func_get_args());
		$this->song = Model_Song::get($id);
		$this->title = $this->song->title;
		$this->text_html = Geekality\Transposer::parse($this->song->text, $this->song->key);

		if($key !== NULL)
		{
			$this->text_html->transpose($key);
			$this->keys = $this->text_html->get_key_selector('song/'.$this->song->id.'/');
		}
		else
			$this->keys = $this->text_html->get_key_selector('song/'.$this->song->id.'/');
		Timer::stop();
	}
}