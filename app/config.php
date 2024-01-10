<?php

// session_start();
date_default_timezone_set('Asia/Tokyo');
define('TODAY', date('Ymd'));
define('DSN', '');
define('DB_USER', '');
define('DB_PASS', '');

require_once( dirname(__FILE__) . '/Utils.php');
require_once( dirname(__FILE__) . '/Date.php');
require_once( dirname(__FILE__) . '/Database.php');
require_once( dirname(__FILE__) . '/Schedule.php');
