<?php
namespace Middleware;

use \Core\ApiRet;
use \Error\ErrInfo;
use \Auth\AuthSession;

class AuthWare {

    public $callback = null;

    public $redirect_url = '';

    public function __construct($options = []) {
        if (isset($options['redirect'])) {
            $this->redirect_url = $options['redirect'];
        }
    }

    public function authRedirect($req, $res, $next) {
        $pass = true;
        if (AuthSession::user() === false) {
            $pass = false;
        } else {
            $token = $req->getCookieParam('api_token', '');
            if (empty($token) || $token !== AuthSession::user()['token']) {
                $pass = false;
            }
        }

        if ($pass === false) {
            return $res->withRedirect($this->redirect_url, 301);
        }
        
        $res = $next($req, $res);
        return $res;
    }
    
    public function __invoke($req, $res, $next) {
        if (AuthSession::user() === false) {
            return ApiRet::send(
                $res,
                ErrInfo::RetErr('ERR_NOT_LOGIN')
            );
        } else {
            $token = $req->getCookieParam('api_token', '');
            if (empty($token) || $token !== AuthSession::user()['token']) {
                return ApiRet::send(
                    $res,
                    ErrInfo::RetErr('ERR_PERM_DENY')
                );
            
            }
        }

        $res = $next($req, $next);
        return $res;
    }

}

