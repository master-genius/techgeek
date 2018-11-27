<?php
namespace Middleware;


use \Core\ApiRet;
use \Error\ErrInfo;
use \Auth\AuthSession;

class RoleWare {

    public $roles = [];

    public $redirect_url = '';

    //roles是允许的用户
    public function __construct($options = []) {
        if (isset($options['roles'])) {
            $this->roles = $options['roles'];
        }

        if (isset($options['redirect'])) {
            $this->redirect_url = $options['redirect'];
        }
    }

    public function rolePass() {

        $user = AuthSession::user();
        $pass = false;
        if (is_array($this->roles)) {
            if (array_search(
                $user['info']['user_role'],
                $this->roles) !== false)
            {
                $pass = true;
            }
        } else if(is_string($this->roles)
            || is_numeric($this->roles) )
        {
            if ($this->roles == $user['info']['user_role']) {
                $pass = true;
            }
        }

        return $pass;
    }

    public function roleRedirect($req, $res, $next) {

        if ($this->rolePass() === false) {
            return $res->withRedirect($this->redirect_url, 301);
        }

        $res = $next($req, $res);
        return $res;
    }


    public function __invoke($req, $res, $next) {

        if ($this->rolePass() === false) {
            return ApiRet::send(
                $res,
                ErrInfo::RetErr('ERR_PERM_DENY')
            );
        }

        $res = $next($req, $next);
        return $res;
    }

}

