<?php

class View_Song extends View
{
	public function init($id, $key = NULL)
	{
		$this->song = Model_Song::get($id);
		$this->title = $this->song->title;
		$this->text_html = new Transposer_Song($this->song->text, $this->song->key);

		if($key !== NULL)
		{
			$this->text_html->transpose($key);
			$this->keys = Transposer::get_keys('song/'.$this->song->id.'/', $key);
		}
		else
			$this->keys = Transposer::get_keys('song/'.$this->song->id.'/', $this->song->key);
	}
}