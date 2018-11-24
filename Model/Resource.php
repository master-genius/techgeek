<?php
namespace Model;

use \Core\DB;

class Resource {
    private $table = 'resource';
    
    private $fields = [
                'id',
                'rs_title',
                'rs_keywords',
                'rs_group',
                'description',
                //'stars',
                'add_time',
                'update_time'
            ];

    public function stats($cond) {
        return DB::instance()->count($this->table, $cond);
    }

    public function pageInfo($cond) {

        $total = DB::instance()->count($this->table, $cond);
        $total_page = ($total % RS_PAGESIZE) 
                        ? ((int)($total/RS_PAGESIZE) + 1)
                        : ($total/RS_PAGESIZE);

        return [
            'total'     => $total,
            'total_page'=> $total_page
        ];
    }

    public function rsList($page = 1, $cond = [], $fields=[]) {
        if (empty($cond)) {
            $cond = [
                'AND'   => [
                    'is_delete'  => 0,
                    'is_publish' => 1,
                ],

                'LIMIT' => [
                   RS_PAGESIZE * ($page - 1) , RS_PAGESIZE
                ]

            ];
        }

        if (empty($fields)) {
            $fields = $this->fields;
        }

        $rl = DB::instance()->select($this->table, $fields, $cond);

        return $rl;
    }

    public function get($id, $fields = []) {
        if (empty($fields)) {
            $fields = $this->fields;
        }
        $r = DB::instance()->get($this->table, $fields, ['id' => $id]);
        return $r;
    }

    public function add($user_id, $data) {
        $data['user_id'] = $user_id;
        $pdo = DB::instance()->insert($this->table, $data);
        if ($pdo->rowCount() <= 0) {
            set_sys_error($pdo->errorInfo()[2]);
            return false;
        }
        return DB::instance()->id();
    }

    public function update($user_id, $id, $data) {
        $pdo = DB::instance()->update($this->table, $data, [
            'AND'   => [
                'id'      => $id,
                'user_id' => $user_id
            ]
        ]);
        if ($pdo->rowCount() <= 0) {
            set_sys_error($pdo->errorInfo()[2]);
            return false;
        }
        return true;
    }

    public function remove($user_id, $id, $real = false) {
        $m = ($real === false) ? 'update' : 'delete';
        $r = DB::instance()->$m($this->table, [
            'AND'   => [
                'id' => $id,
                'user_id' => $user_id
            ]
        ]);
        if ($r->rowCount() <= 0) {
            set_sys_error($r->errorInfo()[2]);
            return false;
        }
        return true;
    }

    public function removeBatch($user_id, $idlist, $real=false) {
        $m = ($real === false) ? 'update' : 'delete';

        $r = DB::instance()->$m($this->table, [
            'AND'   => [
                'id' => $idlist,
                'user_id' => $user_id
            ]
        ]);
        if ($r->rowCount() <= 0) {
            set_sys_error($r->errorInfo()[2]);
            return false;
        }
        return $r->rowCount();
    }

}

