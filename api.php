<?php
require ('vendor/autoload.php');

use \Core\DB;
use \Core\VCode;
use \Core\ApiRet;
use \Middleware\AuthWare;
use \Middleware\RoleWare;


$co = new \Slim\Container;

$co['APIUser'] = function($co) {
    return (new \First\First);
};

$co['APIReader'] = function($co) {
    return (new \Access\Reader);
};

$co['APIWriter'] = function($co) {
    return (new \Access\Writer);
};

$co['APICreator'] = function($co) {
    return (new \Access\Creator);
};

//404
$co['notFoundHandler'] = function() {
    return function($req, $res) use ($co) {
        return ApiRet::send($res, [
            'status'    => 404,
            'errinfo'   => '404 : Not found'
        ]);
    };
};

$app = new \Slim\App($co);

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

    $app->get('/rs/group', function($req, $res) {
        return $this->APIUser->groupList($req, $res);
    });

    $app->get('/rs/list', function($req, $res) {
        return (new \First\First)->rsList2($req, $res);
    });
    
    $app->get('/rs/page', function($req, $res) {
        return (new \First\First)->rsPageInfo($req, $res);
    });

    $app->get('/rs/get/{id}', function($req, $res, $args) {
        return $this->APIUser->get($req, $res, $args['id']);
    });

    $app->get('/host', function($req, $res) {
        return ApiRet::send($res, $_SERVER['SERVER_NAME']);
    });

});



$app->group('/r', function() use ($app) {

    $app->get('/logout', function($req, $res) {
        return ApiRet::send($res, $this->APIUser->logout($req, $res));
    });


})->add(new AuthWare);

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


})->add(
    new RoleWare([USER_CREATOR, USER_WRITER])
)->add(
    new AuthWare
);


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


})->add(
    new RoleWare(USER_CREATOR)
)->add(
    new AuthWare
);


$app->run();

