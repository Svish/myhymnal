<?php

class Transposer
{
	public static $SCALES = array
		(
			'A' => array('A','B','C♯','D','E','F♯','G♯'),
			'A♯' => array('A♯','B♯','D','D♯','E♯','G','A'), //*
			'B♭' => array('B♭','C','D','E♭','F','G','A'),
			'B' => array('B','C♯','D♯','E','F♯','G♯','A♯'),
			'C♭' => array('C♭','D♭','E♭','F♭','G♭','A♭','B♭'),
			'B♯' => array('B♯','D','E','E♯','G','A','B'), //*
			'C' => array('C','D','E','F','G','A','B'),
			'C♯' => array('C♯','D♯','E♯','F♯','G♯','A♯','B♯'),
			'D♭' => array('D♭','E♭','F','G♭','A♭','B♭','C'),
			'D' => array('D','E','F♯','G','A','B','C♯'),
			'D♯' => array('D♯','E♯','G','G♯','A♯','C','D'), //*
			'E♭' => array('E♭','F','G','A♭','B♭','C','D'),
			'E' => array('E','F♯','G♯','A','B','C♯','D♯'), //*
			'E♯' => array('E♯','G','A','A♯','B♯','D','E'), //*
			'F♭' => array('F♭','G♭','A♭','A','C♭','D♭','E♭'), //*
			'F' => array('F','G','A','B♭','C','D','E'),
			'F♯' => array('F♯','G♯','A♯','B','C♯','D♯','E♯'),
			'G♭' => array('G♭','A♭','B♭','C♭','D♭','E♭','F'),
			'G' => array('G','A','B','C','D','E','F♯'),
			'G♯' => array('G♯','A♯','B♯','C♯','D♯','E♯','G'), //*
			'A♭' => array('A♭','B♭','C','D♭','E♭','F','G'),
		);

	public static $CHORDS = array
		(
			array('A'),
			array('A♯', 'B♭'),
			array('B', 'C♭'),
			array('B♯', 'C'),
			array('C♯', 'D♭'),
			array('D'),
			array('D♯', 'E♭'),
			array('E', 'F♭'),
			array('E♯', 'F'),
			array('F♯', 'G♭'),
			array('G'),
			array('G♯', 'A♭'),
		);


	private $map;

	public function __construct($original, $target)
	{
		if( ! array_key_exists($original, self::$SCALES))
			throw new Exception('Unknown chord: '.$original);

		if( ! array_key_exists($target, self::$SCALES))
			throw new Exception('Unknown chord: '.$target);

		// Get the chords
		$old = self::$CHORDS;

		// Original first
		while( ! in_array($original, $old[0]))
			array_push($old, array_shift($old));	

		// Distance from original to target
		$delta = self::find($target, $old);

		// Copy with target first
		$new = $old;
		$delta;
		while(--$delta >= 0)
			array_push($new, array_shift($new));

		// Clean scales
		$this->old = array();
		$this->new = array();

		$ns = self::$SCALES[$target];

		foreach(array_keys($old) as $key)
		{
			$left = $old[$key];
			$right = $new[$key];
			$right_c = array_intersect($right, $ns);

			// Only one option
			if(count($right) == 1)
			{
				foreach($left as $x)
				{
					$this->map[$x] = $right[0];
				}
				continue;
			}

			// Only one option after clean
			if(count($right_c) == 1)
			{
				$right_c = array_pop($right_c);
				foreach($left as $x)
				{
					$this->map[$x] = $right_c;
				}
				continue;
			}

			// Two options, two src
			if(count($left) == 2 AND count($right) == 2)
			{
				foreach(array_keys($left) as $x)
				{
					$this->map[$left[$x]] = $right[$x];
				}
				continue;
			}

			// Two options, one src
			if(count($left) == 1 AND count($right) == 2)
			{
				$diff = ord($target) - ord($original);
				if($diff < 0)
					$diff += 7;
				$d0 = ord($right[0]) - ord($left[0]);
				if($d0 < 0)
					$d0 += 7;
				$d1 = ord($right[1]) - ord($left[0]);
				if($d1 < 0)
					$d1 += 7;

				$this->map[$left[0]] = $d0 == $diff ? $right[0] : $right[1];

				continue;
			}

			throw new Exception("Unhandled case.");
		}
	}

	public function transpose($chord)
	{
		return $this->map[$chord];
	}

	private static function find($c, array $a)
	{
		reset($a);
		$n = 0;
		do
		{
			if(in_array($c, current($a)))
				return $n;
			$n++;
		}
		while(next($a) !== FALSE);
		return FALSE;
	}

	public function __toString()
	{
		return print_r($this->map, true);
	}


	public static function get_keys($url, $key = NULL)
	{
		$keys = '';
		foreach(array_keys(self::$SCALES) as $k)
			$keys .= sprintf('<a href="%s"%s>%s</a>',
				$url.urlencode($k),
				$k == $key ? ' class="key"' : '',
				$k
				);

		return '<div class="transpose-keys">'.$keys.'</div>';
	}
}
