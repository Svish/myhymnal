<?php

/**
 * Examples of songs on spotify.
 */
class Model_Example extends Model
{
	public function __construct()
	{
		$d = HTTP::get('http://ws.spotify.com/lookup/1/.json?uri=spotify:track:'.$this->spotify_track_id);
		$this->set((array)json_decode($d));
	}

	public function __get($var)
	{
		switch($var)
		{
			case 'url':
				return 'http://open.spotify.com/track/'.$this->spotify_track_id;

			case 'artists':
				return implode(',', Arr::get('name', $this->track->artists));

			default:
				return parent::__get($var);
		}
	}

	public function __isset($var)
	{
		switch($var)
		{
			case 'url':
			case 'artists':
				return TRUE;

			default:
				return parent::__isset($var);
		}
	}

	public static function find_for_song($song_id)
	{
		return DB::query('SELECT spotify_track_id
							FROM example
							WHERE song_id = :id')
			->bindValue(':id', $song_id, PDO::PARAM_INT)
			->execute()
			->fetchAll('Model_Example');
	}
}