<?php

abstract class CachedController extends Controller
{
	private $cache;

	public function before(array &$info)
	{
		parent::before($info);

		$this->cache = Cache::get('page_'.sha1($_SERVER['REQUEST_URI']));

		if($this->cache)
			$info['method'] = 'cached';
		else
			ob_start();
	}

	public function cached()
	{
		header('X-Cache-Hit: true');

		foreach($this->cache['headers'] as $h)
			header($h);

		echo $this->cache['content'];
	}

	public function after(array $info)
	{
		if( ! $this->cache)
		{
			$cache = array
				(
					'headers' => headers_list(),
					'content' => ob_get_flush(),
				);
			Cache::set('page_'.sha1($_SERVER['REQUEST_URI']), $cache);
		}

		parent::after();
	}
}