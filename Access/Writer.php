<?php
namespace Access;

use \Core\DB;
use \Error\ErrInfo;
use \Model\UAPI;
use \Model\Resource;


/*
    继承自Reader，具备Reader的所有功能，除此以外，
    还有Writer特有的：创建/发布文章，删除文章等功能。
*/

class Writer extends Reader {

    public $time_day    = 86400;


    public function filterData($text) {
        $text = preg_replace('/< *script *>/im', '', $text);
        $text = preg_replace('/< *script *src.*>/im', '', $text);
        $text = preg_replace('/< */script *>/im', '', $text);

        return $text;
    }

    public function preGetData($filter = []) {

        if (empty($filter)) {
            $filter = [
                'rs_title',
                'rs_content',
                'rs_keywords',
                'description',
                'rs_group'
            ];
        }

        $data = auto_post_data($filter, true);
        foreach($data as $k=>$v) {
            $data[$k] = $this->filterData($v);
        }

        return $data;
    }


    public function add($req, $res, $publish = false) {
        $uid = $this->user['id'];

        $rs = new Resource;

        $tm = time();
        $count = $rs->stats([
            'AND'   => [
                'user_id'   => $uid,
                'add_time[>]'  => $tm - $this->time_day,
                'add_time[<]'  => $tm
            ]
        ]);
        
        if ($count >= RS_MAX_PUB) {
            return ApiRet::send($res, ErrInfo::DefErr('Out of limit: 50/day'));
        }

        $data = $this->preGetData();

        $data['is_publish'] = ($publish ? 1 : 0);

        if (!isset($data['rs_title']) || empty($data['rs_title'])) {
            return ApiRet::send($res, ErrInfo::DefErr('Bad-Data: title not be empty'));
        }
        
        if (strlen($data['rs_content']) > RS_CONTENT_LIMIT) {
            return ApiRet::send($res, ErrInfo::DefErr('Bad-Data: content too large'));
        }

        $r = $rs->add($uid, $data);
        if (!$r) {
            return ApiRet::send($res, ErrInfo::DefErr( get_sys_error() ));
        }
        
        return ApiRet::send($res, [
            'status'    => 0,
            'id'        => $r
        ]);

    }

    public function publish($req, $res) {
        $uid = $this->user['id'];
        $id = post_data('resource_id');

        $r = (new Resource)->update($uid, $id, [
            'is_publish' => 1
        ]);

        if (!$r) {
            return ApiRet::send($res, ErrInfo::DefErr('data not update'));
        }

        return ApiRet::send($res, [
            'status'    => 0,
            'info'      => 'ok'
        ]);
    }

    public function update($req, $res) {
    
        $uid = $this->user['id'];
        $id = post_data('id');

        $rs = new Resource;

        $tm = time();
        
        $filter = [
            'rs_title',
            'rs_content',
            'rs_keywords',
            'is_publish',
            'description',
            'rs_group'
        ];
        $data = $this->preGetData($filter);

        if (!isset($data['rs_title']) || empty($data['rs_title'])) {
            return ApiRet::send($res, ErrInfo::DefErr('Bad-Data: title not be empty'));
        }
        
        
        if (strlen($data['rs_content']) > RS_CONTENT_LIMIT) {
            return ApiRet::send($res, ErrInfo::DefErr('Bad-Data: content too large'));
        }

        $r = $rs->update($uid, $id, $data);
        if (!$r) {
            return ApiRet::send($res, ErrInfo::DefErr( get_sys_error() ));
        }
        
        return ApiRet::send($res, [
            'status'    => 0,
            'info'      => 'ok'
        ]);
    }

    public function addPublish($req, $res) {
        return $this->add($req, $res, true);
    }

    public function remove($req, $res) {
        
        $uid = $this->user['id'];
        $id = post_data('resource_id');
        $real = post_data('real');
        if (empty($real)) {
            $real = false;
        }

        $rs = new Resource;

        $r = $rs->remove($uid, $id, $real);
        if (!$r) {
            return ApiRet::send($res, ErrInfo::DefErr(get_sys_error()));
        }
        
        return ApiRet::send($res, [
            'status'    => 0,
            'info'      => 'ok'
        ]);

    }


}

