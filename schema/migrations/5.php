<?php

$rows = DB::query('SELECT book_id, book_title FROM book')
	->execute()
	->fetchAll();

foreach($rows as $row)
{
	$slug = Util::toAscii($row->book_title);
	DB::prepare('UPDATE book SET book_slug = ? WHERE book_id = ?')
		->bindValue(1, $slug)
		->bindValue(2, $row->book_id)
		->execute();
}
