<?php
namespace First;

use \Model\Resource;
use \Model\Group;
use \Model\Users;
use \Error\ErrInfo;
use \First\UserSession;
use \Core\ApiRet;
//use \Auth\AuthSession;


class First {

    protected $role_map = [
        USER_CREATOR    => 'Creator',
        USER_READER     => 'Reader',
        USER_WRITE      => 'Writer'
    ];

    protected $user = [

        //Creator , Writer
        'role'          => 'Reader',
        
        'is_login'      => false,

        'id'            => 0,

        'email'         => '',

        'username'      => '',


    ];

    public $pagesize    = 18;


    public function __construct() {
        /*
        $user = AuthSession::user();
        if (false !== $user) {
            $this->user['is_login'] = true;
            $this->user['username'] = $user['info']['username'];
            $this->user['email']    = $user['info']['email'];
            $this->user['id']       = $user['id'];
            if (isset( $this->role_map[ $user['info']['user_role'] ] ) ) {
                $this->user['role']     = $this->role_map[$user['info']['user_role']];
            }
        }
         */
        $user = UserSession::get();
        if (false !== $user) {
            $this->user['is_login'] = true;
            $this->user['username'] = $user['username'];
            $this->user['email']    = $user['email'];
            $this->user['id']       = $user['id'];
            if (isset( $this->role_map[ $user['user_role'] ] ) ) {
                $this->user['role']     = $this->role_map[$user['user_role']];
            }
        }

    }

    /*
     *
     * Basic API
     *
     * */
    
    public function get($req, $res, $id) {
        $rs = (new Resource)->get($id);
        if (empty($rs)) {
            return ApiRet::send($res, ErrInfo::RetErr('ERR_BAD_DATA'));
        }

        return ApiRet::send($res, [
            'status'    => 0,
            'resource'  => $rs
        ]);
    }


    /*
        通过get参数控制列表：
            
            kwd     关键字
            page    页码
            group   组
            orderby 排序方式：
                default
                timedesc
                click_count desc
                mix1  ->  time desc click_count asc
                mix2  ->  click_count desc time asc

        尽管可以实现复杂的查询，但是最开始并不提供，仅仅提供最简单的搜索：
            关键字      空格分割关键字进行精确查询
            组


    */

    public function preRsListCond($req, $res) {
    
        $kwd = get_data('kwd');
        $group = get_data('group');
        $kwd_list = [];

        $cond = [
            'AND'   => [
                'is_delete'     => 0,
                'is_publish'    => 1,
            ],

        ];

        if (!empty($kwd)) {
            $ktmp = explode(' ', $kwd);
            $tmp = '';
            foreach($ktmp as $k) {
                $tmp = trim($k);
                if ( !empty($tmp) ) {
                    $kwd_list[] = $tmp;
                }
            }

            if (count($kwd_list) > 0) {
                $cond['AND']['OR'] = [
                    'rs_title[~]'    => $kwd_list,
                    'rs_keywords[~]' => $kwd_list
                ];   
            }
        }
        
        if (is_numeric($group) || $group > 0) {
            $cond['AND']['rs_group'] = $group;
        }

        return $cond;
    }

    public function rsList($req, $res) {
        $page = get_data('page');
        if (!is_numeric($page) || $page <= 0) {
            $page = 1;
        }

        $cond = $this->preRsListCond($req, $res);
        $cond['LIMIT'] = [
            RS_PAGESIZE * ($page-1), RS_PAGESIZE
        ];

        return ApiRet::send($res, [
            'status'    => 0,
            'rs_list'    => (new Resource)->rsList($page, $cond)
        ]);

    }

    public function rsList2($req, $res) {
        $page = get_data('page');
        if (!is_numeric($page) || $page <= 0) {
            $page = 1;
        }

        $rsobj = new Resource;

        $cond = $this->preRsListCond($req, $res);

        $pi = $rsobj->pageInfo($cond);

        $cond['LIMIT'] = [
            RS_PAGESIZE * ($page-1), RS_PAGESIZE
        ];



        return ApiRet::send($res, [
            'status'    => 0,
            'rs_list'    => $rsobj->rsList($page, $cond),

            'total_page' => $pi['total_page'],
            'total'      => $pi['total'],
            'cur_page'   => $page
        ]);

    }


    public function groupList($req, $res) {
        return ApiRet::send($res, [
            'status'    => 0,
            'group_list'=> (new Group)->groupList()
        ]);
    }

    public function rsPageInfo($req, $res) {

        $cond = $this->preRsListCond($req, $res);

        $pi = (new Resource)->pageInfo($cond);

        return ApiRet::send($res, [
            'status'      => 0,
            'total_page'  => $pi['total_page'],
            'pagesize'    => RS_PAGESIZE,
            'total'       => $pi['total']
        ]);

    }


}

