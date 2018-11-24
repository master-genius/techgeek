<?php

define('APP_PATH', __DIR__ . '/..');

define('CONFIG_PATH' , APP_PATH . '/config');

define('VIEW_PATH', APP_PATH . '/View');

define('USER_READER', 0);
define('USER_WRITER', 1);
define('USER_CREATOR', 2);

define('USER_PAGESIZE', 12);

define('RS_PAGESIZE', 12);

define('RS_MAX_PUB', 25);

define('RS_WR_MAX', 5000);

define('RS_MAX_IMAGE', 30);

//in bytes
define('RS_CONTENT_LIMIT', 409600);

define('USER_IMAGE_LIMIT', 2000);

define('IMAGE_SIZE_LIMIT', 2000000);

//Creator用户授权Writer用户最大限制
define('WR_AUTH_LIMIT', 15);

define('SERV_HOST', 'http://127.0.0.1:8192');

