<?php

// session_start();
date_default_timezone_set('Asia/Tokyo');
define('TODAY', date('Ymd'));
define('DSN', 'sqlsrv:server=SUKAGAWASRV14;database=sukagawadb');
define('DB_USER', 'viewer');
define('DB_PASS', '');

require_once( dirname(__FILE__) . '/Utils.php');
require_once( dirname(__FILE__) . '/Date.php');
require_once( dirname(__FILE__) . '/Database.php');
require_once( dirname(__FILE__) . '/Schedule.php');