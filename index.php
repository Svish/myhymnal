<?php

# Constants
define('DOCROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);
define('WEBROOT', $_SERVER['BASE']);
define('EXT', '.php');

# Timezone
date_default_timezone_set('Europe/Oslo');

# AutoLoader
require 'vendor/autoload.php';

# Hooks
ToroHook::add("404", function() {
    echo "Not found";
});

# Go!
Toro::serve(array(
    "/" => "Controller_Browse",
    "/song/:number" => "Controller_Song",
));
