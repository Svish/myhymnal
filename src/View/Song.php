<?php

class View_Song extends View
{
	public function __construct(Model_Song $song)
	{
		Timer::start(__METHOD__, array($song->id, $song->slug));
		
		$this->song = $song;
		$this->title = $this->song->title;
		$this->canonical = $this->song->url;
		$this->text_html = Geekality\Transposer::parse($this->song->text, $this->song->key);

		$this->description = 'Lyrics and chords for the song \''.$this->song->title.'\'. Clean and simple.';

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

		$this->keys = $this->text_html->get_key_selector($this->song->url.'?key=');
		
		Timer::stop();
	}
}