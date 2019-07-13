<?php
/**
 * Created by PhpStorm.
 * User: du
 * Date: 2019/2/14
 * Time: 19:01
 */
if (isset($state)) {
    if ($state) {
        echo "<h2>处理成功</h2>";
    } else {
        echo "<h2>处理失败</h2>";
    }
}

if (isset($data)){
    echo "<div class='alert alert-warning'>$data</div>";
}