<?php
require ('vendor/autoload.php');

use \Core\DB;
use \Core\VCode;
use \Core\ApiRet;
use \Middleware\AuthWare;
use \Middleware\RoleWare;
use \Middleware\MediaWare;


$co = new \Slim\Container;

$co['APIWriter'] = function($co) {
    return (new \Access\Writer);
};

$co['APICreator'] = function($co) {
    return (new \Access\Creator);
};

$app = new \Slim\App($co);


/*
 * 上传素材，编辑/发布内容
 * 删除自己的素材和内容
 * CREATOR和WRITER用户具备此权限
 *
 * */
$app->group('/w', function() use ($app) {
    
    $app->post('/media/upload', function($req, $res) {
    
    });

    $app->post('/media/delete', function($req, $res) {
    
    });

    $app->post('/media/replace', function($req, $res) {
    
    });

    $app->get('/media/list', function($req, $res) {
    
    });

    $app->post('/media/settag', function($req, $res) {
    
    });


})->add(
    new MediaWare
)->add(
    new RoleWare([USER_CREATOR, USER_WRITER])
)->add(
    new AuthWare
);


$app->run();

