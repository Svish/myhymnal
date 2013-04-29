<?php

abstract class CachedController extends Controller
{
	private $cache;
	private $cid;

	public function before(array &$info)
	{
		parent::before($info);

		$this->cid = 'page_'.sha1($_SERVER['REQUEST_URI']);

		if($info['method'] == 'get')
			$this->cache = Cache::get($this->cid);
		else
			Cache::delete($this->cid);


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
			Cache::set($this->cid, $cache);
		}

		parent::after();
	}
}