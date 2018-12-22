<?php
require ('vendor/autoload.php');

use \Core\DB;
use \Core\VCode;
use \Core\ApiRet;
use \Core\View;
use \Middleware\RoleWare;
use \Middleware\AuthWare;

$co = new \Slim\Container;

$co['View'] = function($co){
    return (new View);
};

$co['Config'] = function($co) {
    $cfg = include (CONFIG_PATH . '/config.php');
    return $cfg;
};

$app = new \Slim\App($co);

$app->group('/user', function() use ($app) {
    $app->get('/register', function($req, $res) {
        return (new \Access\User)->regPage($req, $res);
    });

    $app->get('/login', function($req, $res) {
        return ApiRet::raw($res, (new \Core\View)->page('user/login.html'));
    });

    $app->get('/forget/passwd', function($req, $res) {
        return (new \Access\User)->forgetPasswdPage($req, $res);
    });
})->add(function($req, $res, $next) {
    
    $res = $next($req, $res);
    return $res;
});


$app->group('/v', function() use ($app) {
    
    $app->get('/email-verify', function($req, $res) {
        return (new \Access\User)->verifyEmail($req, $res);
    });

    $app->post('/reply-find-passwd', function($req, $res) {
        return (new \Access\User)->replyFindPasswd($req, $res);
    });

    $app->get('/findpasswd', function($req, $res) {
        return (new \Access\User)->findPasswdPage($req, $res);
    });



});

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

$app->group('', function() use ($app) {

    $app->get('[/]', function($req, $res) {
        $page = $this->View->page('techgeek/index.html', $this->Config);
        return ApiRet::raw($res, $page);
    });

});

$app->group('/w', function() use ($app) {

    $app->get('[/]', function($req, $res) {
        $page = $this->View->page('techgeek/home.html');
        return ApiRet::raw($res, $page);
    });

    $app->get('/rs/add', function($req, $res) {
        $page = $this->View->page('techgeek/rsadd.html');
        return ApiRet::raw($res, $page);
    });

    $app->get('/rs/edit/{id}', function($req, $res) {
        $page = $this->View->page('techgeek/rsedit.html');
        return ApiRet::raw($res, $page);
    });

    $app->get('/rs/wlist', function($req, $res) {
        $page = $this->View->page('techgeek/rslist.html');
        return ApiRet::raw($res, $page);
    });

    $app->get('/media/list', function($req, $res) {
        $page = $this->View->page('techgeek/media.html');
        return ApiRet::raw($res, $page);
    });

    $app->get('/lecture', function($req, $res) {
        $page = $this->View->page('techgeek/lecture.html');
        return ApiRet::raw($res, $page);
    });

    $app->get('/rs/mdadd', function($req, $res) {
        $page = $this->View->page('techgeek/mdadd.html');
        return ApiRet::raw($res, $page);
    });
    
    $app->get('/rs/mdedit/{id}', function($req, $res) {
        $page = $this->View->page('techgeek/mdedit.html');
        return ApiRet::raw($res, $page);
    });

});

$app->group('/c', function() use ($app) {
    
    $app->get('/wrlist', function($req, $res) {
    
    });

});

$app->run();

