<?php
require('vendor/autoload.php');

use \Core\DB;

/*
    命令：
        dbsync

*/
$pdo = DB::instance()->query("show tables");

$tables = $pdo->fetchAll(PDO::FETCH_COLUMN);

walk_arr($tables);

foreach($tables as $t) {
    $pdo = DB::instance()->query("show columns from $t");
    $cols = $pdo->fetchAll();
    echo json_encode($cols);
}

