<?php

/**
 * Examples of songs on spotify.
 */
class Model_Spotify extends Model
{
	public function __construct()
	{
		Timer::start(__METHOD__);
		if(empty($this->url) || empty($this->artists))
			$this->loadSpotifyInfo();
		Timer::stop();
	}

	private function loadSpotifyInfo()
	{
		Timer::start(__METHOD__);

		$d = HTTP::get('http://ws.spotify.com/lookup/1/.json?uri=spotify:track:'.$this->spotify_id);
		$d = json_decode($d);

		$this->artists = implode(', ', Util::pluck('name', $d->track->artists));
		$this->url = 'http://open.spotify.com/track/'.$this->spotify_id;
		$this->save();

		Timer::stop();
	}

	public function save()
	{
		Timer::start(__METHOD__, array($this->spotify_id));
		DB::query('UPDATE spotify 
					SET artists=:artists, 
						url=:url 
					WHERE spotify_id=:id')
			->bindValue(':id', $this->spotify_id, PDO::PARAM_INT)
			->bindValue(':artists', $this->artists)
			->bindValue(':url', $this->url)
			->execute();
		Timer::stop();
	}

	public static function find_for_song($song_id)
	{
		Timer::start(__METHOD__, func_get_args());
		$r = DB::query('SELECT spotify_id, url, artists
						FROM spotify
						WHERE song_id = :id
						ORDER BY artists')
			->bindValue(':id', $song_id, PDO::PARAM_INT)
			->execute()
			->fetchAll(__CLASS__);
		Timer::stop();
		return $r;
	}
}