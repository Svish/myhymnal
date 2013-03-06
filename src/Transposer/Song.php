<?php

/**
 * Parses a song into verses, lines and chords which can then be 
 * transposed and converted to a string of HTML.
 */
class Transposer_Song
{
	private $verses = array();
	private $key;

	/**
	 * Parses the song lyrics.
	 *
	 * @param song The chords and lyrics of the song as plain text.
	 * @param key The original key of the song.
	 */
	public function __construct($song, $key)
	{
		if( ! array_key_exists($key, Transposer::$SCALES))
			throw new Exception('Unknown key: '.$key);

		$this->key = $key;

		// Split song into verses
		foreach(preg_split('/(?:\r\n){2,}/', $song) as $verse)
			$this->verses[] = new Transposer_Verse($verse);
	}

	/**
	 * Transposes the song.
	 *
	 * @param key The new key of the song.
	 */
	public function transpose($key)
	{
		if($key === NULL OR $key == $this->key)
			return;
		
		$t = new Transposer($this->key, $key);
		$this->key = $key;

		foreach($this->verses as $verse)
			foreach($verse->lines as $line)
				if( ! is_string($line))
					foreach($line->chords as $chord)
						$chord->chord = $t->transpose($chord->chord);
	}

	/**
	 * Returns a simple key selector.
	 *
	 * @param url URL prefix for key links. For example 'song/5/' or 'song.php?key='
	 * @return HTML
	 */
	public function get_key_selector($url)
	{
		$keys = '';
		foreach(array_keys(Transposer::$SCALES) as $k)
			$keys .= sprintf('<a href="%s"%s>%s</a>',
				$url.urlencode($k),
				$k == $this->key ? ' class="key"' : '',
				$k
				);

		return '<div class="keys">'.$keys.'</div>'.PHP_EOL;
	}

	/**
	 * Returns HTML for a simple key selector.
	 *
	 * @param url URL prefix for key links. For example 'song/5/' or 'song.php?key='
	 */
	public function __toString()
	{
		return '<div class="lyrics">'.PHP_EOL.implode('', $this->verses).'</div>'.PHP_EOL;
	}
}

class Transposer_Verse
{
	public $lines = array();
	public function __construct($verse)
	{
		// Split verse into lines
		foreach(preg_split('%\r\n%', $verse) as $line)
			try
			{
				// Try create a key line
				$this->lines[] = new Transposer_ChordLine($line);
			}
			catch(Exception $e)
			{
				// Otherwise it's just a regular text line
				$this->lines[] = $line;
			}
	}
	public function __toString()
	{
		return '<pre class="verse">'.implode(PHP_EOL,$this->lines).'</pre>'.PHP_EOL;
	}
}

/**
 * Parses a line into chords (if possible).
 */
class Transposer_ChordLine
{
	public $chords;
	public function __construct($text)
	{
		// Find all chords
		preg_match_all(Transposer_Chord::$pattern, $text, $this->chords, PREG_SET_ORDER);

		// Create chords and count combined length of found chords
		$len = mb_strlen($text);
		foreach($this->chords as &$k)
		{
			$len -= mb_strlen($k[0]);
			$k = new Transposer_Chord($k);
		}

		// Assume this is not a chord line unless all text was eaten
		if($len > 0)
			throw new Exception('Not a chord line: '.$text);
	}
	public function __toString()
	{
		return implode('', $this->chords);
	}
}
class Transposer_Chord
{
	public static $pattern = '%(\s*+\/?)([A-H][♯♭b\#]?)((?:2|5|6|7|9|11|13|6\/9|7\-5|7\-9|7\#5|7\#9|7\+5|7\+9|7b5|7b9|7sus2|7sus4|add2|add4|add9|aug|dim|dim7|m\/maj7|m6|m7|m7b5|m9|m11|m13|maj7|maj9|maj11|maj13|mb5|m|sus4|sus2|sus)*)(\s*+)%u';

	private $text;
	private $pre;
	public $chord;
	private $fluff;
	public function __construct(array $parts)
	{
		list($this->text, 
			$this->pre, 
			$this->chord,
			$this->fluff) = $parts;

		$this->chord = preg_replace('%b%u', '♭', preg_replace('%#%u', '♯', $this->chord));
	}
	public function __toString()
	{
		$old = $this->text.'<span class="c">'.'</span>';
		$r = $this->pre.'<span class="c">'.$this->chord.$this->fluff.'</span>';
		return str_pad($r, mb_strlen($old, 'UTF-8') + (strlen($r)-mb_strlen($r, 'UTF-8')));
	}
}
