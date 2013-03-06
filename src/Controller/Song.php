<?php

class Controller_Song
{
	function get($id, $key = NULL)
	{
		echo new View_Song($id, $key);
	}

	function get_xhr($id, $key = NULL)
	{
		$song = Model_Song::get($id);
		$html = Geekality\Transposer::parse($song->text, $song->key);

		if($key !== NULL)
			$html->transpose($key);
		else
			$key = $song->key;

		echo json_encode(array('key' => $key, 'html' => ''.$html));
	}
}