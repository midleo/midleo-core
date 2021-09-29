<?php
require_once 'controller/vendor/autoload.php'; 

use PhpOffice\PhpWord\Settings;
define('CLI', (PHP_SAPI == 'cli') ? true : false);
Settings::loadConfig();

Settings::setOutputEscapingEnabled(true);
