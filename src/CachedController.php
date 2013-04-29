<?php

abstract class CachedController extends Controller
{
	private $cache;
	private $etag;
	private $cid;

	public function before(array &$info)
	{
		parent::before($info);

		$this->etag = sha1($_SERVER['REQUEST_URI']);
		$this->cid = 'pc_'.$this->etag;

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
		$etag = trim(Util::get('HTTP_IF_NONE_MATCH', $_SERVER));
		if($this->etag == $etag)
		{
			header('Not modified', true, 304);
			return;
		}

		header('X-Cache: hit');
		foreach($this->cache['headers'] as $h)
			header($h);
		echo $this->cache['content'];
	}

	public function after(array $info)
	{
		if( ! $this->cache)
		{
			header('Etag: '.$this->etag);
			Cache::set($this->cid, array
				(
					'headers' => headers_list(),
					'content' => ob_get_flush(),
				));
		}

		parent::after();
	}
}