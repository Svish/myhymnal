<?php

/**
 * Examples of songs on spotify.
 */
class Model_Spotify extends Model
{
	public function __construct()
	{
		if(empty($this->url) || empty($this->artists))
			$this->loadSpotifyInfo();
	}

	private function loadSpotifyInfo()
	{
		Timer::start(__METHOD__);

		$d = HTTP::get('http://ws.spotify.com/lookup/1/.json?uri=spotify:track:'.$this->id);
		$d = json_decode($d);

		$this->artists = implode(', ', Util::pluck('name', $d->track->artists));
		$this->url = 'http://open.spotify.com/track/'.$this->id;
		$this->save();

		Timer::stop();
	}

	public function save()
	{
		Timer::start(__METHOD__, array($this->id));
		DB::prepare('UPDATE spotify 
					SET spotify_artists=:artists, 
						spotify_url=:url 
					WHERE spotify_id=:id')
			->bindValue(':id', $this->id, PDO::PARAM_INT)
			->bindValue(':artists', $this->artists)
			->bindValue(':url', $this->url)
			->execute();
		Timer::stop();
	}

	public static function find_for_song($song_id)
	{
		Timer::start(__METHOD__, func_get_args());
		$r = DB::prepare('SELECT spotify_id "id", spotify_url "url", spotify_artists "artists"
						FROM spotify
						WHERE song_id = :id
						ORDER BY spotify_artists')
			->bindValue(':id', $song_id, PDO::PARAM_INT)
			->execute()
			->fetchAll(__CLASS__);
		Timer::stop();
		return $r;
	}

	public static function find_all()
	{
		Timer::start(__METHOD__);
		$r = DB::prepare('SELECT 
							spotify_id "id", 
							spotify_url "url", 
							spotify_artists "artists",
							song_title "title"
						FROM spotify
						INNER JOIN song ON song.song_id = spotify.song_id
						ORDER BY song.song_title, spotify.spotify_artists')
			->execute()
			->fetchAll(__CLASS__);
		Timer::stop();
		return $r;
	}
}