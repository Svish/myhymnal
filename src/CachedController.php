<?php

abstract class CachedController extends Controller
{
	const CS = 'pc';

	private $etag;
	private $cache;

	public function before(array &$info)
	{
		parent::before($info);

		$this->etag = sha1($_SERVER['REQUEST_URI']);

		if($info['method'] == 'get')
			$this->cache = Cache::get(self::CS, $this->etag);
		else
			Cache::delete(self::CS, $this->etag);

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

		foreach($this->cache['headers'] as $h)
			header($h);
		echo $this->cache['content'];
	}

	public function after(array $info)
	{
		if( ! $this->cache)
		{
			header('Etag: '.$this->etag);
			Cache::set(self::CS, $this->etag, array
				(
					'headers' => headers_list(),
					'content' => ob_get_flush(),
				));
		}

		parent::after();
	}
}