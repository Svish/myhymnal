<?php

$rows = DB::query('SELECT song_id, song_title FROM song')
	->execute()
	->fetchAll();

foreach($rows as $row)
{
	$slug = Util::toAscii($row->song_title);
	DB::prepare('UPDATE song SET song_slug = ? WHERE song_id = ?')
		->bindValue(1, $slug)
		->bindValue(2, $row->song_id)
		->execute();
}