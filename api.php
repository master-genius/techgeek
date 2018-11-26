<?php
require ('vendor/autoload.php');

use \Core\DB;
use \Core\VCode;
use \Core\ApiRet;

$app = new \Slim\App;

/*
 *
 * 接口路由分组：/u  /w  /c /r
 *
 * 使用分组作为权限控制，/u是所有用户都允许的操作
 * /r 是Reader用户的权限
 * /w 是Writer用户的权限
 * /c 是Creator用户的权限
 * /c用户具备 /w  /r 用户的权限
 * /w 具备/r用户的权限
 * 
 * */

$app->group('/u', function() use ($app) {

    $app->get('/rslist', function($req, $res) {
        return (new \First\First)->rsList($req, $res);
    });
    
    $app->get('/rspage', function($req, $res) {
        return (new \First\First)->rsPageInfo($req, $res);
    });

    $app->get('/host', function($req, $res) {
        return ApiRet::send($res, $_SERVER['SERVER_NAME']);
    });

});

$app->group('/r', function() use ($app) {



})->add(function ($req, $res, $next){

});

/*
 * 上传素材，编辑/发布内容
 * 删除自己的素材和内容
 * CREATOR和WRITER用户具备此权限
 *
 * */
$app->group('/w', function() use ($app) {
    
    $app->post('/rs/add', function($req, $res) {
    
    });

    $app->post('/rs/update', function($req, $res) {
    
    });

    $app->post('/rs/publish', function($req, $res) {
    
    });

    $app->post('/rs/delete', function($req, $res) {
    
    });

    $app->post('/rs/delall', function($req, $res) {
    
    });

    $app->post('', function($req, $res) {
    
    });




})->add(function ($req, $res, $next){

});


/*
 * 
 * 设置Writer用户，CREATOR用户具备此权限
 *
 *
 * */
$app->group('/c', function() use ($app) {

    $app->post('/wr/set', function($req, $res) {
    
    });

    $app->post('/wr/unset', function($req, $res) {
    
    });

    $app->get('/wr/list', function($req, $res) {
    
    });


})->add(function ($req, $res, $next){

});


$app->run();

