<?php

# Constants
define('DOCROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);
define('CONFROOT', DOCROOT.'config'.DIRECTORY_SEPARATOR);
define('WEBROOT', $_SERVER['BASE']);
define('ENV', $_SERVER['SITE_ENV']);
define('RID', uniqid());


# Timezone
date_default_timezone_set('Europe/Oslo');

# Locale
setlocale(LC_ALL, 'en_US.utf-8', 'eng');

# AutoLoader
require 'vendor/autoload.php';


$uri = isset($_GET['toro_uri'])
    ? $_GET['toro_uri']
    : NULL;
Timer::start('Request', array($uri));

View::engine()->setHelpers(include CONFROOT.'mustache_helpers.php');
Website::init(include CONFROOT.'routes.php', 'Controller_Error')
    ->serve($uri);

Cache::delete('rid', NULL, 30*60);
Cache::set('rid', RID, Timer::result());
