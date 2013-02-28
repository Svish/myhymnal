<?php

class View_Song extends View
{
	public function init($id)
	{
		$this->song = Model_Song::get($id);
		$this->title = $this->song->title;
		$this->text_html = $this->song->transpose(NULL);
	}
}