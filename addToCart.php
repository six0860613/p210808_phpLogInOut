<?php
include __DIR__. '/mod/initialization.php';
header('Content-Type: application/json');

$sid = isset($_GET['sid']) ? intval($_GET['sid']) : 0;
$qty = isset($_GET['qty']) ? intval($_GET['qty']) : 0;
$_SESSION['success'] = false;

if(! empty($sid)){
    //以商品數量判斷是新增修改還是刪除
    if(! empty($qty)){
        $_SESSION['cart'][$sid] = $qty;
        $_SESSION['success'] = true;
    } else {
        unset($_SESSION['cart'][$sid]);
    }
}

echo json_encode($_SESSION, JSON_UNESCAPED_UNICODE);