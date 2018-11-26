<?php
require ('vendor/autoload.php');

use \Core\DB;
use \Core\VCode;
use \Core\ApiRet;
use \Core\View;

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
    $app->map(['GET', 'POST'], '/register', 
        function($req, $res) {
            if ($req->isGet()) {
                return (new \Access\User)->regPage($req, $res);
            } else {
                return (new \Access\User)->register($req, $res);
            }
        }
    );

    $app->get('/login', function($req, $res) {
        return ApiRet::raw($res, (new \Core\View)->page('user/login.html'));
    });

    $app->get('/forget/passwd', function($req, $res) {
        return (new \Access\User)->forgetPasswdPage($req, $res);
    });
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

    $app->post('/reset/passwd', function($req, $res) {
        return (new \Access\User)->findPasswd($req, $res);
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
        $page = $this->View->page('first/index.html', $this->Config);
        return ApiRet::raw($res, $page);
    });

    $app->post('/login', function($req, $res) {
        return (new \Access\User)->login($req, $res);
    });

    $app->get('/rslist', function($req, $res) {
        return (new \First\First)->rsList($req, $res);
    });
    
    $app->get('/rspage', function($req, $res) {
        return (new \First\First)->rsPageInfo($req, $res);
    });

});

$app->run();

