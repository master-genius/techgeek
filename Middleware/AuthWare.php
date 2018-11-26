<?php
namespace Middleware;

use \Core\ApiRet;
use \Error\ErrInfo;
use \Auth\AuthSession;

class AuthWare {
    
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

