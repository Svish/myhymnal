<?php

return array
		(
			'rss' => function($date = NULL, $m = NULL)
				{
					$date = $m !== NULL
						? new DateTime($m->render($date))
						: new DateTime($date);
					return $date->format(DATE_RSS);
				},

			'atom' => function($date = NULL, $m = NULL)
				{
					$date = $m !== NULL
						? new DateTime($m->render($date))
						: new DateTime($date);
					return $date->format(DATE_ATOM);
				},
		);