<?php

/**
 * Builds a map for transposing chords between two different keys.
 */
class Transposer
{
	/**
	 * Scales belonging to each key.
	 */
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

	/**
	 * Chords in order, grouped by value.
	 */
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

	/**
	 * Creates a new Transposer.
	 *
	 * @param original Key to transpose from.
	 * @param target Key to transpose to.
	 * @throws Exception If any of the keys are not known.
	 */
	public function __construct($original, $target)
	{
		if( ! array_key_exists($original, self::$SCALES))
			throw new Exception('Unknown key: '.$original);

		if( ! array_key_exists($target, self::$SCALES))
			throw new Exception('Unknown key: '.$target);

		// Make a copy of the chords starting with the original
		$old = self::$CHORDS;
		while( ! in_array($original, $old[0]))
			array_push($old, array_shift($old));

		// Make a copy of the chords starting with the target
		$new = $old;
		while( ! in_array($target, $new[0]))
			array_push($new, array_shift($new));

		// For each chord group
		foreach(array_keys($old) as $chord)
		{
			$left = $old[$chord];
			$right = $new[$chord];

			// If single option on the right side
			if(count($right) == 1)
			{
				// Use that for all chords on left
				foreach($left as $x)
					$this->map[$x] = $right[0];

				continue;
			}

			// If single option after removing those not in target scale
			$right_c = array_intersect($right, self::$SCALES[$target]);
			if(count($right_c) == 1)
			{
				// Use that for all chords on left
				$right_c = array_pop($right_c);
				foreach($left as $x)
					$this->map[$x] = $right_c;

				continue;
			}

			// If two options on both sides
			if(count($left) == 2 AND count($right) == 2)
			{
				// Match first with first and second with second
				foreach(array_keys($left) as $x)
					$this->map[$left[$x]] = $right[$x];

				continue;
			}

			// If two options and only one on the left
			if(count($left) == 1 AND count($right) == 2)
			{
				// Calculate distance between original and target chord
				$diff = ord($target) - ord($original);
				if($diff < 0)
					$diff += 7;

				// Pick right chord with same distance to left chord
				$d0 = ord($right[0]) - ord($left[0]);
				if($d0 < 0)
					$d0 += 7;
				$d1 = ord($right[1]) - ord($left[0]);
				if($d1 < 0)
					$d1 += 7;
				$this->map[$left[0]] = $d0 == $diff ? $right[0] : $right[1];

				continue;
			}

			// If we get here, we have an unhandled case.
			// Which we really shouldn't have...
			throw new Exception("Unhandled case.");
		}
	}

	/**
	 * Transposes a chord from the old key to the new key.
	 * @param chord A chord in the old key.
	 * @return The chord in the new key.
	 */
	public function transpose($chord)
	{
		return $this->map[$chord];
	}

	public function __toString()
	{
		return print_r($this->map, true);
	}
}
