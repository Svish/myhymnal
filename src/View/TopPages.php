<?php

class View_TopPages extends View
{
	public function __construct()
	{
		Timer::start(__METHOD__);

		$this->title = 'Most viewed';
		$this->pages = Cache::get('clicky', 'top', 24*60*60);

		if( ! $this->pages)
		{
			$clicky = include CONFROOT.'clicky.'.ENV.'.php';
			$url = 'http://api.clicky.com/api/stats/4';
			$params = array(
				'site_id' => $clicky['id'],
				'sitekey' => $clicky['key'],
				'type' => 'pages',
				'date' => 'previous-30-days',
				'output' => 'json',
				'limit' => 5,
				'filter' => ') - My hymnal',
				);
			$result = json_decode(HTTP::get($url, $params), TRUE);
			
			$pages = array();
			foreach($result[0]['dates'][0]['items'] as $i)
				$pages[] = array
					(
						'href' => $i['url'],
						'text' => str_replace(' - My Hymnal', '', $i['title']),
					);
			$this->pages = Cache::set('clicky', 'top', $pages);
		}

		Timer::stop();
	}
}