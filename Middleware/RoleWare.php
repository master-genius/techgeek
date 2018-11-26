<?php
namespace Middleware;


use \Core\ApiRet;
use \Error\ErrInfo;
use \Auth\AuthSession;

class RoleWare {

    public $roles = [];

    //roles是允许的用户
    public function __construct($roles = []) {
        if (!empty($roles)) {
            $this->roles = $roles;
        }
    }
    
    public function __invoke($req, $res, $next) {
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

        if ($pass === false) {
            return ApiRet::send(
                $res,
                ErrInfo::RetErr('ERR_PERM_DENY')
            );
        }

        $res = $next($req, $next);
        return $res;
    }

}

