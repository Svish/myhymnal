<?php


class Transposer
{
	public static function transpose($song, $original_key, $key = NULL)
	{
		$song = Song::factory($song, $original_key);
		$song->transpose($key);
		return self::get_keys($key ? $key : $original_key).PHP_EOL
			. $song;
	}


	public static function get_keys($selected)
	{
		$keys = '';
		foreach(Model_Key::find_all() as $key)
			$keys .= sprintf('<a onclick="return false" href="#"%s>%s</a>',
				$selected == $key->key ? ' class="selected"' : '',
				$key->key);

		return '<div class="transpose-keys">'.$keys.'</div>';
	}
}

class Song
{
	private $verses = array();
	private $key;

	private function __construct($song, $key)
	{
		$this->key = $key;
		foreach(preg_split('/(?:\r\n){2,}/', $song) as $verse)
			$this->verses[] = Verse::factory($verse);
	}

	public function factory($song, $key)
	{
		return new Song($song, $key);
	}

	public function transpose($key)
	{
		// TODO
	}

	public function __toString()
	{
		return '<div class="song">'.implode('', $this->verses).'</div>';
	}
}

class Verse
{
	private $lines = array();
	private function __construct($verse)
	{
		foreach(preg_split('%\r\n%', $verse) as $line)
			$this->lines[] = Line::factory($line);
	}
	public function factory($verse)
	{
		return new Verse($verse);
	}
	public function __toString()
	{
		return '<pre class="verse">'.implode(PHP_EOL,$this->lines).'</pre>';
	}
}

class Line
{
	public function factory($line)
	{
		if(KeyLine::is_keys($line))
			return new KeyLine($line);
		return new TextLine($line);
	}
}
class KeyLine
{
	private static $key_pattern = '%(/?[A-H][b#]?(2|5|6|7|9|11|13|6/9|7-5|7-9|7#5|7#9|7\+5|7\+9|7b5|7b9|7sus2|7sus4|add2|add4|add9|aug|dim|dim7|m/maj7|m6|m7|m7b5|m9|m11|m13|maj7|maj9|maj11|maj13|mb5|m|sus4|sus2|sus)*+)%';
	private $text;
	public function __construct($text)
	{
		$this->text = $text;
	}
	public function __toString()
	{
		$text = preg_replace(self::$key_pattern, '<span class=\'c\'>$1</span>', $this->text);
		return $text;
	}

	public static function is_keys($line)
	{
		foreach(preg_split('%\s++%', $line) as $token)
			if( ! preg_match(self::$key_pattern, $token) AND ! empty($token))
				return false;
		return true;
	}
}
class TextLine
{
	private $text;
	public function __construct($text)
	{
		$this->text = $text;
	}
	public function __toString()
	{
		return $this->text;
	}
}