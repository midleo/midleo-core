<?php
if(!isset($_SERVER['HTTP_USER_AGENT'])) {
include "controller/config.main.php";
include "controller/config.cron.php";
Cron::startCron();
}

?>