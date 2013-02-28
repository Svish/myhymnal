<?php

class Transposer
{
	public static function transpose($song, $original_key, $key = NULL)
	{
		return new Transposer_Song($song);
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
class Transposer_Song
{
	private $verses = array();

	public function __construct($song)
	{
		foreach(preg_split('/(?:\r\n){2,}/', $song) as $verse)
			$this->verses[] = new Transposer_Verse($verse);
	}

	public function __toString()
	{
		return '<div class="song">'.implode('', $this->verses).'</div>';
	}
}
class Transposer_Verse
{
	private $lines = array();
	public function __construct($verse)
	{
		foreach(preg_split('%\r\n%', $verse) as $line)
			try
			{
				$this->lines[] = new Transposer_KeyLine($line);
			}
			catch(Exception $e)
			{
				$this->lines[] = new Transposer_TextLine($line);
			}
	}
	public function factory($verse)
	{
		return new Transposer_Verse($verse);
	}
	public function __toString()
	{
		return '<pre class="verse">'.implode(PHP_EOL,$this->lines).'</pre>';
	}
}
class Transposer_TextLine
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
class Transposer_KeyLine
{
	public function __construct($text)
	{
		preg_match_all(Transposer_Key::$pattern, $text, $this->keys, PREG_SET_ORDER);

		$len = mb_strlen($text);
		foreach($this->keys as &$k)
		{
			$len -= mb_strlen($k[0]);
			$k = new Transposer_Key($k);
		}

		if($len > 0)
			throw new Exception('Not a key line: '.$text);
	}
	public function __toString()
	{
		return implode('', $this->keys);
	}
}
class Transposer_Key
{
	public static $pattern = '%(\s*+)(\/?[A-H][b\#]?)((?:2|5|6|7|9|11|13|6\/9|7\-5|7\-9|7\#5|7\#9|7\+5|7\+9|7b5|7b9|7sus2|7sus4|add2|add4|add9|aug|dim|dim7|m\/maj7|m6|m7|m7b5|m9|m11|m13|maj7|maj9|maj11|maj13|mb5|m|sus4|sus2|sus)*)(\s*+)%';

	private $length;
	private $pre;
	private $chord;
	private $fluff;

	public function __construct(array $parts)
	{
		$this->length = mb_strlen($parts[0]);
		$this->pre = $parts[1];
		$this->chord = $parts[2];
		$this->fluff = $parts[3];
	}

	public function __toString()
	{
		return '<span class="c">'.str_pad($this->pre.$this->chord.$this->fluff, $this->length).'</span>';
	}
}
